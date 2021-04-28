<?php

namespace Modules\Media\Services;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Media\Entities\File;
use Modules\Media\Jobs\CreateThumbnails;
use Modules\Media\Repositories\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function __construct(FileRepository $file, Factory $filesystem)
    {
        $this->file = $file;
        $this->filesystem = $filesystem;
    }

    /**
     * @param  UploadedFile  $file
     * @param  int  $parentId
     * @param  string  $disk
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileExistsException
     */
    public function store(UploadedFile $file, $parentId = 0, $disk = null)
    {
        $disk = is_null($disk)? $this->getConfiguredFilesystem() : $disk;

        $savedFile = $this->file->createFromFile($file, $parentId, $disk);


        $path = $this->getDestinationPath($savedFile->getRawOriginal('path'));
        $stream = fopen($file->getRealPath(), 'r+');
        $this->filesystem->disk($disk)->writeStream($path, $stream, [
            'visibility' => 'public',
            'mimetype' => $savedFile->mimetype,
        ]);

        $this->createThumbnails($savedFile);

        return $savedFile;
    }

    /**
     * Create the necessary thumbnails for the given file
     * @param $savedFile
     */
    private function createThumbnails(File $savedFile)
    {
        $this->dispatch(new CreateThumbnails($savedFile->path,$savedFile->disk));
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
    private function getConfiguredFilesystem()
    {
        return config('asgard.media.config.filesystem');
    }
}
