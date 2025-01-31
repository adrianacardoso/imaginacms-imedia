<?php

namespace Modules\Media\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class DynamicGallery extends Component
{
  public $idGallery;
  public $zones;
  public $mediaFiles;
  public $margin;
  public $responsiveClass;
  public $autoplay;
  public $autoplayHoverPause;
  public $loopGallery;
  public $dots;
  public $nav;
  public $responsive;
  public $gallery;
  public $dataFancybox;
  public $view;
  public $columnMasonry;
  public $navText;
  public $showNavs;
  public $maxImages;
  public $autoplayVideo;
  public $mutedVideo;
  public $loopVideo;
  public $stagePadding;
  public $autoplayTimeout;
  public $aspectRatio;
  public $objectFit;
  public $showDescription;
  public $marginItems;
  public $heightItems;
  public $componentId;
  protected $images;
  public $itemId;

  public function mount($mediaFiles, $idGallery = "gallery", $zones = ["gallery"], $margin = 10, $responsiveClass = true,
                        $autoplay = true, $autoplayHoverPause = true, $loopGallery = true, $dots = true, $nav = true,
                        $showNavs = true, $responsive = null, $dataFancybox = 'gallery', $layout = "gallery-layout-1",
                        $columnMasonry = 3, $navText = "", $maxImages = null, $onlyVideos = false,
                        $onlyImages = false, $autoplayVideo = false, $mutedVideo = false, $loopVideo = false,
                        $stagePadding = 0, $autoplayTimeout = 5000, $aspectRatio = "1-1", $objectFit = 'contain',
                        $showDescription = false, $marginItems = 0, $heightItems = 350, $itemId = null)
  {
    $this->componentId = 'livewireGallery' . rand(0, 99);
    $this->idGallery = $idGallery;
    $this->layout = $layout;
    $this->view = "media::frontend.components.gallery.layouts.$layout.index";
    $this->zones = $zones;
    $this->margin = $margin;
    $this->responsiveClass = $responsiveClass;
    $this->autoplay = $autoplay;
    $this->autoplayHoverPause = $autoplayHoverPause;
    $this->loopGallery = $loopGallery;
    $this->dots = $dots;
    $this->nav = $nav;
    $this->showNavs = $showNavs;
    $this->responsive = json_encode($responsive ?? [0 => ["items" => 1], 640 => ["items" => 2], 992 => ["items" => 4]]);
    if (gettype($responsive) === 'string') $this->responsive = $responsive;
    $this->dataFancybox = $dataFancybox ? $dataFancybox . Str::uuid() : null;
    $this->gallery = [];
    $this->columnMasonry = $columnMasonry;
    $this->navText = json_encode($navText);
    $this->maxImages = $maxImages;
    $this->autoplayVideo = $autoplayVideo;
    $this->mutedVideo = $mutedVideo;
    $this->loopVideo = $loopVideo;
    $this->stagePadding = $stagePadding;
    $this->autoplayTimeout = $autoplayTimeout;
    $this->aspectRatio = $aspectRatio;
    $this->objectFit = $objectFit;
    $this->showDescription = $showDescription;
    $this->marginItems = $marginItems;
    $this->heightItems = $heightItems;
    $this->onlyVideos = $onlyVideos;
    $this->onlyImages = $onlyImages;
    $this->images = $mediaFiles;
    $this->mediaFiles = json_encode($mediaFiles); // save like this because is needed as primitive
    $this->itemId = $itemId;
  }

  protected function getListeners()
  {
    return ["updateMediaFilesItem-$this->itemId" => "updateGallery"];
  }

  public function updateGallery($mediaFiles = null)
  {
    $this->images = $mediaFiles ? json_decode(json_encode($mediaFiles)) : json_decode($this->mediaFiles);
    $this->emit('refreshOwlCarousel' . $this->componentId);
  }

  public function render()
  {
    return view("media::frontend.livewire.dynamic-gallery.index", ['images' => $this->images]);
  }
}
