<?php

namespace Modules\Media\Transformers;

use Modules\Core\Icrud\Transformers\CrudResource;
use Modules\Media\Helpers\FileHelper;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\ThumbnailManager;

class MediaTransformer extends CrudResource
{
    /**
     * Method to merge values with response
     */
    public function modelAttributes($request)
    {
        foreach (app(ThumbnailManager::class)->all() as $thumbnail) {
            $thumbnailName = $thumbnail->name();

            $data['thumbnails'][] = [
                'name' => $thumbnailName,
                'path' => app(Imagy::class)->getThumbnail($this->resource->path, $thumbnailName, $this->resource->disk),
                'size' => $thumbnail->size(),
            ];
        }
        return [
            'path' => $this->getPath(),
            'is_image' => $this->resource->isImage(),
            'is_folder' => $this->resource->isFolder(),
            'fa_icon' => FileHelper::getFaIcon($this->resource->media_type),
            'created_at' => $this->resource->created_at,
            'folder_id' => $this->resource->folder_id,
            'small_thumb' => app(Imagy::class)->getThumbnail($this->resource->path, 'smallThumb', $this->resource->disk),
            'medium_thumb' => app(Imagy::class)->getThumbnail($this->resource->path, 'mediumThumb', $this->resource->disk),
            'urls' => [
                'delete_url' => $this->getDeleteUrl(),
            ],
            'thumbnails' => $data['thumbnails'],
        ];
    }
    private function getPath()
    {
        if ($this->resource->isFolder()) {
            return $this->resource->path->getRelativeUrl();
        }

        return (string) $this->resource->path;
    }

    private function getDeleteUrl()
    {
        if ($this->resource->isImage()) {
            return route('api.media.media.destroy', $this->resource->id);
        }

        return route('api.media.folders.destroy', $this->resource->id);
    }
}