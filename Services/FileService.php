<?php

namespace Modules\Media\Services;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Media\Entities\File;
use Modules\Media\Http\Requests\UploadMediaRequest;
use Modules\Media\Image\Imagy;
use Modules\Media\Jobs\CreateThumbnails;
use Modules\Media\Repositories\FileRepository;
use Modules\Media\ValueObjects\MediaPath;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;
use Illuminate\Support\Facades\Storage;

use Aws\S3\MultipartUploader;
use Aws\S3\Exception\MultipartUploadException;

class FileService
{
  use DispatchesJobs;

  /**
   * @var FileRepository
   */
  private $file;

  /**
   * @var Factory
   */
  private $filesystem;

  /**
   * @var Imagy
   */
  private $imagy;

  public function __construct(FileRepository $file, Factory $filesystem, Imagy $imagy)
  {
    $this->file = $file;
    $this->filesystem = $filesystem;
    $this->imagy = $imagy;
  }

  /**
   * @param UploadedFile $file
   * @param int $parentId
   * @param string $disk
   * @return mixed
   * @throws \Illuminate\Contracts\Filesystem\FileExistsException
   */
  public function store(UploadedFile $file, $parentId = 0, $disk = null, $createThumbnails = true)
  {
    $disk = $this->getConfiguredFilesystem($disk);
    $typesWithoutResizeImagesAndCreateThumbnails = config("asgard.media.config.typesWithoutResizeImagesAndCreateThumbnails");

    //validating avaiable extensions
    $request = new UploadMediaRequest(["file" => $file]);
    $validator = Validator::make($request->all(), $request->rules(), $request->messages());
    //if get errors, throw errors
    if ($validator->fails()) {
      throw new \Exception(json_encode($validator->errors()), 400);
    }

    $savedFile = $this->file->createFromFile($file, $parentId, $disk);

    if (!in_array($savedFile->extension, $typesWithoutResizeImagesAndCreateThumbnails)) {
      $this->resizeImages($file, $savedFile);
    }

    $organizationPrefix = mediaOrganizationPrefix($savedFile);
    $s3FilePath = ($organizationPrefix) . $savedFile->path->getRelativeUrl();

    \Log::info("Uploading file to {$disk}: $s3FilePath (size: " . $file->getSize() . " bytes)");

    $stream = fopen($file->getRealPath(), 'r+');

    // Check if the disk is 's3' to upload as multipart
    if ($disk === 's3') {
      $response = $this->multipartUploadToS3($stream, $s3FilePath);
    } else {
      // Use Laravel's Filesystem for normal uploads
      $response = Storage::disk($disk)->writeStream($s3FilePath, $stream, [
        'visibility' => 'public',
        'mimetype' => $savedFile->mimetype,
      ]);
    }

    fclose($stream);

    if (!in_array($savedFile->extension, $typesWithoutResizeImagesAndCreateThumbnails) && $createThumbnails) {
      $this->createThumbnails($savedFile);
    }

    return $savedFile;
  }

  /**
   * @param $path - Url from External
   * @param string $disk - External Name (splash)
   * @return mixed
   */
  public function storeHotLinked($path, $disk = null)
  {

    $data = app("Modules\Media\Services\\" . ucfirst($disk) . "Service")->getDataFromUrl($path, $disk);

    $data = [
      'filename' => $data['fileName'],
      'path' => $data['path'],
      'extension' => $data['extension'] ?? null,
      'folder_id' => 0,
      'is_folder' => 0,
      'disk' => $disk,
      'mimetype' => $data['mimetype'] ?? null
    ];

    $savedFile = $this->file->create($data);

    return $savedFile;
  }

  /**
   * Resize Images based in the setting defaultImageSize
   * @param UploadedFile $file
   * @param $savedFile
   */
  private function resizeImages(UploadedFile $file, $savedFile)
  {
    if ($savedFile->isImage()) {
      $image = \Image::make(fopen($file->getRealPath(), 'r+'));

      $imageSize = json_decode(setting("media::defaultImageSize", null, config('asgard.media.config.defaultImageSize')));

      $image->resize($imageSize->width, $imageSize->height, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });

      $filePath = $file->getPathName();
      \File::put($filePath, $image->stream($savedFile->extension, $imageSize->quality));
    }
  }

  /**
   * Create the necessary thumbnails for the given file
   * @param $savedFile
   */
  private function createThumbnails(File $savedFile)
  {
    $this->dispatch(new CreateThumbnails($savedFile));
  }

  /**
   * @param string $path
   * @return string
   */
  private function getDestinationPath($path)
  {
    if ($this->getConfiguredFilesystem() === 'local') {
      return basename(public_path()) . $path;
    }

    return $path;
  }

  /**
   * @return string
   */
  private function getConfiguredFilesystem($disk = "publicmedia")
  {
    $settingDisk = setting('media::filesystem', null, config("asgard.media.config.filesystem"));
    if ($disk == "publicmedia" && $settingDisk == "s3") return $settingDisk;
    return $disk ?? "publicmedia";
  }

  public function addWatermark($file, $zone)
  {

    //if the watermark zone exist in DB and if is image exclusively
    if (isset($zone->mediaFiles()->watermark->id) && $file->isImage()) {
      //getting watermark file from the DB
      $watermarkFile = File::find($zone->mediaFiles()->watermark->id);

      //if exist the watermark file in the DB
      if (isset($watermarkFile->id)) {
        //watermark file disk
        $watermarkDisk = is_null($watermarkFile->disk) ? $this->getConfiguredFilesystem() : $watermarkFile->disk;

        //file entity disk
        $disk = is_null($file->disk) ? $this->getConfiguredFilesystem() : $file->disk;

        $tenantPrefix = mediaOrganizationPrefix($file);
        //creating image in memory
        $image = \Image::make($this->filesystem->disk($disk)->get(($tenantPrefix) . $file->path->getRelativeUrl()));

        // insert watermark at center corner with 0px offset by default
        $image->insert(
        //file path from specific disk
          $this->filesystem->disk($watermarkDisk)->path(($tenantPrefix) . $watermarkFile->path->getRelativeUrl()),
          //position inside the base image
          $zone->options->watermarkPosition ?? "center",
          //X axis position
          $zone->options->watermarkXAxis ?? 0,
          //Y axis position
          $zone->options->watermarkYAxis ?? 0
        );

        //put the new file in the same location of the current entity file
        $this->filesystem->disk($disk)->put(($tenantPrefix) . $file->path->getRelativeUrl(), $image->stream($file->extension, 100));

        //regenerate thumbnails
        $this->createThumbnails($file);

        //updating entity has_watermark field
        $file->has_watermark = true;

        //saving has_watermark field
        $file->save();
      }
    }
  }

  /**
   * Perform multipart upload for large files using Storage::disk('s3').
   */
  protected function multipartUploadToS3($file, string $s3FilePath)
  {
    $s3FilePath = ltrim($s3FilePath, "/");
    $s3Client = Storage::disk('s3')->getClient();

    try {
      $uploader = new MultipartUploader($s3Client, $file, [
        'bucket' => config('filesystems.disks.s3.bucket'),
        'key' => $s3FilePath,
        'params' => [
          'ChecksumAlgorithm' => 'CRC32'
        ]
      ]);
      $result = $uploader->upload();
      \Log::info("Multipart Upload Success: " . json_encode($result));
      return $result;
    } catch (MultipartUploadException $e) {
      \Log::error("Multipart Upload Failed: " . $e->getMessage());
      throw new \Exception("Multipart Upload Failed: " . $e->getMessage());
    }
  }
}
