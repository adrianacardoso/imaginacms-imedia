<?php

namespace Modules\Media\Events\Handlers;

use Illuminate\Support\Arr;
use Modules\Media\Contracts\StoringMedia;
use Modules\Media\Entities\File;
use Modules\Media\Entities\Zone;

class HandleMediaStorage
{

  private $fileService;

  public function handle($event = null, $data = [])
  {

    $this->fileService = app("Modules\Media\Services\FileService");
    if ($event instanceof StoringMedia) {
      $this->handleMultiMedia($event);

      $this->handleSingleMedia($event);
    }
  }

  /**
   * Handle the request for the multi media partial
   * @param StoringMedia $event
   */
  private function handleMultiMedia(StoringMedia $event)
  {
    $entity = $event->getEntity();
    $postMedias = Arr::get($event->getSubmissionData(), 'medias_multi', []);

    foreach ($postMedias as $zone => $attributes) {
      $syncList = [];
      $orders = $this->getOrdersFrom($attributes);

      //getting Zone with custom features to this file
      $entityZone = Zone::where("entity_type", get_class($entity))->where("name",$zone)->first();

      foreach (Arr::get($attributes, 'files', []) as $fileId) {

        if(isset($entityZone->id) and $entityZone->name == $zone){
          //Add watermark from the Zone
          $this->addWatermark($fileId,$entityZone);
        }

        //Check type file id
        $fileId = $this->getFileIdByDataType($fileId);

        $syncList[$fileId] = [];
        $syncList[$fileId]['imageable_type'] = get_class($entity);
        $syncList[$fileId]['zone'] = $zone;
        $syncList[$fileId]['order'] = (int)array_search($fileId, $orders);
      }


      $entity->filesByZone($zone)->sync($syncList);
    }
  }

  /**
   * Handle the request to parse single media partials
   * @param StoringMedia $event
   */
  private function handleSingleMedia(StoringMedia $event)
  {

    $entity = $event->getEntity();
    $postMedia = Arr::get($event->getSubmissionData(), 'medias_single', []);

    //dd($postMedia);

    foreach ($postMedia as $zone => $fileId) {

      //getting Zone with custom features to this file
      $entityZone = Zone::where("entity_type", get_class($entity))->where("name",$zone)->first();

      if(isset($entityZone->id) and $entityZone->name == $zone){
        //Add watermark from the Zone
        $this->addWatermark($fileId,$entityZone);
      }

      if (!empty($fileId)) {

        //Check type file id
        $fileId = $this->getFileIdByDataType($fileId);
        //Sync Data
        $entity->filesByZone($zone)->sync([$fileId => ['imageable_type' => get_class($entity), 'zone' => $zone, 'order' => null]]);
      } else {
        $entity->filesByZone($zone)->sync([]);
      }
    }
  }

  /**
   * Parse the orders input and return an array of file ids, in order
   * @param array $attributes
   * @return array
   */
  private function getOrdersFrom(array $attributes)
  {
    $orderString = Arr::get($attributes, 'orders');

    if ($orderString === null) {
      return [];
    }

    $orders = explode(',', $orderString);

    return array_filter($orders);
  }

  private function addWatermark($fileId, $entityZone)
  {
    //if isset a zone
    if (isset($entityZone->id)) {
      //finding file from DB
      $file = File::find($fileId);
      //if the file doesn't has a watermark all ready
      if (!$file->has_watermark) {
        $this->fileService->addWatermark($file, $entityZone);
      }
    }
  }

  /**
   * Check if fileId is an url and save information
   */
  private function getFileIdByDataType($fileId)
  {
    $urlParts = parse_url($fileId);

    // Check if it is a URL
    if (isset($urlParts['scheme']) && isset($urlParts['host']) && isset($urlParts['path'])) {

      // Reconstruct base URL without query parameters
      $basePath = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'];

      // Try to find a file by the base path
      $fileExist = File::where('path', 'like', $basePath . '%')->first();

      if (is_null($fileExist)) {
        // Not found, create new
        $fileCreated = $this->fileService->storeHotLinked($fileId, "external");
        $fileId = $fileCreated->id;
      } else {
        // Found — update to latest query version if needed
        if ($fileExist->path !== $fileId) {
          $fileExist->path = $fileId; // Save the latest version with query params
          $fileExist->save();
        }
        $fileId = $fileExist->id;
      }

    }

    return $fileId;

  }

}
