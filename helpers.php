<?php

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

if (!function_exists('mediaMimesAvailableRule')) {
  function mediaMimesAvailableRule()
  {
    return 'mimes:' . implode(',', json_decode(setting('media::allowedImageTypes', null, config('asgard.media.config.allowedImageTypes'))))
      . ',' . implode(',', json_decode(setting('media::allowedFileTypes', null, config('asgard.media.config.allowedFileTypes'))))
      . ',' . implode(',', json_decode(setting('media::allowedVideoTypes', null, config('asgard.media.config.allowedVideoTypes'))))
      . ',' . implode(',', json_decode(setting('media::allowedAudioTypes', null, config('asgard.media.config.allowedAudioTypes'))));
  }
}
if (!function_exists('mediaExtensionsAvailable')) {
  function mediaExtensionsAvailable()
  {
    return array_merge(json_decode(setting('media::allowedImageTypes', null, config('asgard.media.config.allowedImageTypes'))),
      json_decode(setting('media::allowedFileTypes', null, config('asgard.media.config.allowedFileTypes'))),
      json_decode(setting('media::allowedVideoTypes', null, config('asgard.media.config.allowedVideoTypes'))),
      json_decode(setting('media::allowedAudioTypes', null, config('asgard.media.config.allowedAudioTypes')))
    );
  }
}
if (!function_exists('mediaOrganizationPrefix')) {
  function mediaOrganizationPrefix($file = null, $prefix = '', $suffix = '', $organizationId = null, $forced = false)
  {
    $organizationId = tenant()->id ?? $file->organization_id ?? $organizationId ?? null;
    $isSingleDataBase = config("tenancy.mode", null) == "singleDatabase";//Check the tenant mode
    $isDefaultImage = $file
      && is_object($file->path)
      && str_contains($file->path->getRelativeUrl(), 'default.jpg');//check if file is default image
    $isGlobalFile = true;
    //Check if file has a organizationId
    if (isset($file->organization_id)) {
      $isGlobalFile = false;
    }
    if (!$organizationId || $isDefaultImage || $isGlobalFile) return "";
    if ($isSingleDataBase || $forced) return $prefix . config("tenancy.filesystem.suffix_base") . $organizationId . $suffix;

//    if (
//      (isset($file->id) && !empty($file->organization_id)) &&
//      (isset(tenant()->id) || !empty($organizationId)) ||
//      $tenancyMode == "singleDatabase"
//    ) {
//      $organizationId = tenant()->id ?? $file->organization_id ?? $organizationId ?? "";
//      if (isset($file->id) && empty($file->organization_id)) return "";
//      if ((!($tenancyMode == "multiDatabase") || $forced) && !empty($organizationId)) {
//        return $prefix . config("tenancy.filesystem.suffix_base") . $organizationId . $suffix;
//      }
//    }

    return '';
  }
}

if (!function_exists('mediaPrivatePath')) {
  function mediaPrivatePath($file)
  {
    $path = '';
    $argv = explode('/', $file->path->getRelativeUrl());
    $fileName = end($argv);
    foreach ($argv as $key => $str) {
      if ($key == 0) {
        $path .= "$str";
      } elseif ($str != $fileName) {
        $path .= "/$str";
      }
    }
    $path .= '/' . $file->filename;

    return $path;
  }
}
if (!function_exists('getUploadedFileFromBase64')) {
  function getUploadedFileFromBase64(string $base64File): UploadedFile
  {
    // Get file data base64 string
    $fileData = base64_decode(Arr::last(explode(',', $base64File)));

    // Create temp file and get its absolute path
    $tempFile = tmpfile();
    $tempFilePath = stream_get_meta_data($tempFile)['uri'];

    // Save file data in file
    file_put_contents($tempFilePath, $fileData);

    $tempFileObject = new File($tempFilePath);

    $file = new UploadedFile(
      $tempFileObject->getPathname(),
      $tempFileObject->getFilename(),
      $tempFileObject->getMimeType(),
      0,
      true // Mark it as test, since the file isn't from real HTTP POST.
    );

    // Close this file after response is sent.
    // Closing the file will cause to remove it from temp director!
    app()->terminating(function () use ($tempFile) {
      fclose($tempFile);
    });

    // return UploadedFile object
    return $file;
  }
}

if (!function_exists('getUploadedFileFromUrl')) {
  function getUploadedFileFromUrl(string $url, array $context = [], array $params = []): UploadedFile
  {
    $path = parse_url($url, PHP_URL_PATH);
    $basename = $params["file_name"] ?? basename($path);
    $tmpRootPath = "/tmp/" . config("app.name");
    //Validate app folder
    if (!file_exists($tmpRootPath)) {
      mkdir($tmpRootPath, 0777, true);
    }
    //Instance the tmp location
    $tmpLocation = $tmpRootPath . "/" . $basename;
    //Instance request context
    $requestContext = ["http" => array_merge_recursive(['method' => 'GET'], $context)];
    //Get File and save as tmp
    $result = copy($url, $tmpLocation, stream_context_create($requestContext));
    //Instance uplaodedFile
    $tmpFileObject = new File($tmpLocation);
    return new UploadedFile(
      $tmpFileObject->getPathname(),
      $tmpFileObject->getFilename(),
      $tmpFileObject->getMimeType(),
      0,
      true // Mark it as test, since the file isn't from real HTTP POST.
    );
  }
}
if (!function_exists('validateMediaDefaultUrl')) {
  function validateMediaDefaultPath($path)
  {
    //If path include word ad replace by media default path to prevent issues with ad blockers
    if (str_contains(strtolower($path), 'ad')) $path = "modules/media/img/file/default.jpg";
    //Response
    return $path;
  }
}
