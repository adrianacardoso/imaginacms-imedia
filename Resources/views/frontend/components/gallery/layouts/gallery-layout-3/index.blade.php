@if(count($gallery) > 0)
  <div class="gallery-layout-3">
    <main>
      @foreach($gallery as $item)
        <div class="item">
          <x-media::single-image :isMedia="true" :mediaFiles="$item" :dataFancybox="$dataFancybox"
                                 :autoplayVideo="$autoplayVideo" :loopVideo="$loopVideo" :mutedVideo="$mutedVideo"/>
        </div>
      @endforeach
    </main>
  </div>
@endif