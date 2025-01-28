<?php

namespace Modules\Media\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class DynamicImage extends Component
{

  protected $listeners = ['updateSingleImage'];

  public $src;
  public $zone;
  public $alt;
  public $title;
  public $showDescription;
  public $fallbackExtension;
  public $url;
  public $extraLargeSrc;
  public $fallback;
  public $largeSrc;
  public $mediumSrc;
  public $smallSrc;
  public $imgClasses;
  public $linkClasses;
  public $linkRel;
  public $defaultLinkClasses;
  public $imgStyles;
  public $width;
  public $dataFancybox;
  public $dataCaption;
  public $target;
  public $isVideo;
  protected $mediaFiles;
  public $uid;
  public $dataTarget;
  public $dataSlideTo;
  public $autoplayVideo;
  public $mutedVideo;
  public $loopVideo;
  public $withVideoControls;
  public $fetchPriority;
  public $isSVG;
  public $productId;
  protected $images;
  public $updateOnlyThisComponent;
  protected $newMediaFiles;
  public $componentId;


  public function mount($src = '', $alt = '', $title = null, $showDescription = false, $url = null, $isMedia = false, $mediaFiles = null,
                        $zone = 'mainimage', $extraLargeSrc = null, $largeSrc = null, $mediumSrc = null,
                        $smallSrc = null, $fallback = null, $imgClasses = '', $linkClasses = '', $linkRel = '',
                        $defaultLinkClasses = 'image-link w-100', $imgStyles = '', $width = '300px',
                        $dataFancybox = null, $dataTarget = null, $dataSlideTo = null, $dataCaption = null,
                        $target = '_self', $setting = '', $autoplayVideo = false, $loopVideo = true,
                        $mutedVideo = true, $central = false, $withVideoControls = false, $fetchPriority = 'low', $productId = null)
  {
    $this->componentId = 'livewireImage-' . $productId;
    $this->src = $src;
    $this->alt = !empty($alt) ? $alt : $mediaFiles->{$zone}->alt ?? $mediaFiles->alt ?? '';
    $this->title = $title;
    $this->showDescription = $showDescription;
    $this->url = $url;
    $this->imgClasses = $imgClasses;
    $this->linkClasses = $linkClasses;
    $this->defaultLinkClasses = $defaultLinkClasses;
    $this->imgStyles = $imgStyles;
    $this->linkRel = $linkRel;
    $this->width = $width;
    $this->dataFancybox = $dataFancybox;
    $this->dataCaption = $dataCaption;
    $this->dataTarget = $dataTarget;
    $this->dataSlideTo = $dataSlideTo;
    $this->target = $target;
    $this->autoplayVideo = $autoplayVideo;
    $this->loopVideo = $loopVideo;
    $this->mutedVideo = $mutedVideo;
    $this->withVideoControls = $withVideoControls;
    $this->isMedia = $isMedia;
    $this->extraLargeSrc = $extraLargeSrc;
    $this->largeSrc = $largeSrc;
    $this->mediumSrc = $mediumSrc;
    $this->smallSrc = $smallSrc;
    $this->fallback = $fallback;
    $this->setting = $setting;
    $this->central = $central;
    $this->fetchPriority = $fetchPriority;
    $this->images = $mediaFiles;
    $this->newMediaFiles = null;
    $this->updateOnlyThisComponent = null;
    cache()->put($this->componentId, $mediaFiles);

  }

  public function updateSingleImage($productOptionValueSelected, $productId)
  {
    $this->newMediaFiles = json_decode(json_encode($productOptionValueSelected));
    $this->updateOnlyThisComponent = 'livewireImage-' . $productId;

    if ($this->componentId == $this->updateOnlyThisComponent) {
      $this->images = $this->newMediaFiles;
    } else {
      $cachedMediaFiles = cache()->get($this->componentId);
      $this->images = $cachedMediaFiles;
    }
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\View\View|string
   */
  public function render()
  {
    return view("media::frontend.livewire.dynamic-image.index", ['images' => $this->images]);
  }
}
