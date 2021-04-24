<?php

namespace Modules\Media\View\Components;

use Illuminate\View\Component;


class Gallery extends Component
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
  public $imgStyles;
  public $width;
  
  public function __construct($item, $title = null, $url = null, $isMedia = false, $mediaFiles = null,
                              $zone = 'mainimage', $extraLargeSrc = null, $largeSrc = null, $mediumSrc = null,
                              $smallSrc = null, $fallback = null, $imgClasses = '',  $imgStyles = '', $width = "300px")
  {
    $this->src = $src;
    $this->alt = $alt;
    $this->title = $title;
    $this->url = $url;
    $this->imgClasses = $imgClasses;
    $this->imgStyles = $imgStyles;
    $this->width = $width;
  
    if (!empty($fallback)) {
      $this->extension = pathinfo($fallback, PATHINFO_EXTENSION);
      if ($this->extension == "jpg") $this->extension = "jpeg";
    }
  
   
    if($isMedia && !empty($mediaFiles)){
      $this->src = $mediaFiles->{$zone}->extraLargeThumb;
      $this->fallback = $mediaFiles->{$zone}->relativePath;
      $this->extraLargeSrc = $mediaFiles->{$zone}->extraLargeThumb;
      $this->largeSrc = $mediaFiles->{$zone}->largeThumb;
      $this->mediumSrc = $mediaFiles->{$zone}->mediumThumb;
      $this->smallSrc = $mediaFiles->{$zone}->smallThumb;
    }else{
      $this->extraLargeSrc = $extraLargeSrc;
      $this->largeSrc = $largeSrc;
      $this->mediumSrc = $mediumSrc;
      $this->smallSrc = $smallSrc;
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
