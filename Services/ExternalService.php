<?php

namespace Modules\Media\Services;

class ExternalService
{

    private $log = "Media: ExternalService|";
    private $mimetypes = array(
        'jpg'     => 'image/jpeg',
        'jpe'     => 'image/jpeg',
        'jpeg'    => 'image/jpeg',
        'png'     => 'image/png',
        'svg'     => 'image/svg+xml',
        'bmp'     => 'image/bmp',
        'ico'     => 'image/x-icon',
        'svgz'    => 'image/svg+xml',
        'tif'     => 'image/tiff',
        'tiff'    => 'image/tiff',
        'jfif'    => 'image/jpeg'
    );

    /**
     * Get data from url (needed to save to the database later)
     * It's a generic method
     */
    public function getDataFromUrl(string $url,string $disk = null)
    {

        //Get Parts
        $parts = parse_url($url);

        //Get Extension and Mimetype in path from Url
        foreach ($this->mimetypes as $key => $value) {
            if(strpos($parts['path'], $key) !== false){
                $data['extension'] = $key;
                $data['mimetype'] = $value;
                break;
            }
        }

        //Set filename
        $data['fileName'] = uniqid();

        //Set Path
        $data['path'] = $url;

        return $data;

    }

    /**
     * @param $name (Thumbnail Name)
     */
    public function getThumbnail($file,string $name)
    {

        //\Log::info($this->log."getThumbnail|".$name);

        $url = $file->path->getRelativeUrl();

        //Set params in final url
        $thumbnail = $url;

        return $thumbnail;


    }

}
