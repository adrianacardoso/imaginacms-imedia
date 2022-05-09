<div class="gallery-layout-6">
  <div class="row">
    <div class="items-gallery">
      @if(count($gallery) > 0)
        @foreach($gallery as $item)
          <div class="item">
            <x-media::single-image :isMedia="true" :mediaFiles="$item" :dataFancybox="$dataFancybox"
                                   :autoplayVideo="$autoplayVideo" :loopVideo="$loopVideo" :mutedVideo="$mutedVideo"/>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>

<style>
    .items-gallery {
        grid-template-columns: repeat({{$columnMasonry}}, 1fr);
        grid-gap: {{$marginItems}};
    }
</style>
