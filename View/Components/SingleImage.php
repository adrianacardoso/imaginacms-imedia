<?php

namespace Modules\Media\View\Components;

use Illuminate\View\Component;
use Browser;

class SingleImage extends Component
{
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public $src;
  public $alt;
  public $title;
  public $extension;
  public $url;
  public $extraLargeSrc;
  public $fallback;
  public $largeSrc;
  public $mediumSrc;
  public $smallSrc;
  public $imgClasses;
  public $linkClasses;
  public $defaultLinkClasses;
  public $imgStyles;
  public $width;
  public $dataFancybox;
  public $dataCaption;
  public $isOldMacVersion;
  
  public function __construct($src = '', $alt = '', $title = null, $url = null, $isMedia = false, $mediaFiles = null,
                              $zone = 'mainimage', $extraLargeSrc = null, $largeSrc = null, $mediumSrc = null,
                              $smallSrc = null, $fallback = null, $imgClasses = '', $linkClasses = '', $defaultLinkClasses = 'image-link w-100',
                              $imgStyles = '', $width = "300px", $dataFancybox = null, $dataCaption = null)
  {
    $this->src = $src;
    $this->alt = $alt;
    $this->title = $title;
    $this->url = $url;
    $this->imgClasses = $imgClasses;
    $this->linkClasses = $linkClasses;
    $this->defaultLinkClasses = $defaultLinkClasses;
    $this->imgStyles = $imgStyles;
    $this->width = $width;
    $this->dataFancybox = $dataFancybox;
    $this->dataCaption = $dataCaption;
    $this->isOldMacVersion = false;
    
    if (!empty($fallback)) {
      $this->extension = pathinfo($fallback, PATHINFO_EXTENSION);
      if ($this->extension == "jpg") $this->extension = "jpeg";
    }
    
   
    if($isMedia && !empty($mediaFiles)){
      $this->src = $mediaFiles->{$zone}->extraLargeThumb;
      $this->fallback = $mediaFiles->{$zone}->path;
      $this->extraLargeSrc = $mediaFiles->{$zone}->extraLargeThumb;
      $this->largeSrc = $mediaFiles->{$zone}->largeThumb;
      $this->mediumSrc = $mediaFiles->{$zone}->mediumThumb;
      $this->smallSrc = $mediaFiles->{$zone}->smallThumb;
      
    }else{
      $this->extraLargeSrc = $extraLargeSrc;
      $this->largeSrc = $largeSrc;
      $this->mediumSrc = $mediumSrc;
      $this->smallSrc = $smallSrc;
      $this->fallback = $fallback ?? $src;
    }
    
    //Fix Safari version 14, does not support the webp images format
    if(Browser::isSafari() && Browser::browserVersion()<=14){
      $this->isOldMacVersion = true;
  
    }
 
  }
  
  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\View\View|string
   */
  public function render()
  {
    return view('media::frontend.components.singleimage');
  }
}
