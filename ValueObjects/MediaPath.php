<?php

namespace Modules\Media\ValueObjects;

use Illuminate\Support\Facades\Storage;

class MediaPath
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $disk;

    public function __construct($path, $disk = null)
    {
        if (! is_string($path)) {
            throw new \InvalidArgumentException('The path must be a string');
        }
        $this->path = $path;

        $this->disk = $disk;
    }

    /**
     * Get the URL depending on configured disk
     * @param  string  $disk
     * @return string
     */
    public function getUrl($disk = null)
    {
        $path = ltrim($this->path, '/');
        $disk = is_null($disk)? is_null($this->disk)? config('asgard.media.config.filesystem') : $this->disk : $disk;
   // dd( Storage::disk($disk));
        return Storage::disk($disk)->url($path);
    }

    /**
     * @return string
     */
    public function getRelativeUrl()
    {
        return $this->path;
    }

    public function __toString()
    {
        try {
            return $this->getUrl();
        } catch (\Exception $e) {
            return '';
        }
    }
}
