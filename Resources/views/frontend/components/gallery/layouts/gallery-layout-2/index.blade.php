@if(count($gallery) > 0)
  <div class="gallery-layout-2">
    <section id="galeria">
      @foreach($gallery as $item)
        <article>
          <figure>
            <x-media::single-image :isMedia="true" :mediaFiles="$item" :dataFancybox="$dataFancybox"
                                   :autoplayVideo="$autoplayVideo" :loopVideo="$loopVideo" :mutedVideo="$mutedVideo"/>
          </figure>
        </article>
      @endforeach
    </section>
  </div>
@endif

<style>
    #galeria {
        column-count: {{$columnMasonry}};
    }
</style>
