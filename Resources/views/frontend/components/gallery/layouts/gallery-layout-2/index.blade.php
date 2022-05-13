@if(count($gallery) > 0)
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
@endif

<style>
    #galeria {
        margin: 1rem auto;
        width: 100%;
        column-count: {{$columnMasonry}};
        -webkit-column-span: all;
        column-span: all;
        break-inside: avoid;
        page-break-inside: avoid;
        /*-moz-column-rule: 1px solid #bbb;*/
        /*-webkit-column-rule: 1px solid #bbb;*/
        /*column-rule: 1px solid #bbb;*/
    }

    @media (max-width: 767px) {
        #galeria {
            columns: 2;
        }

    }

    @media (max-width: 480px) {
        #galeria {
            columns: 1;
        }
    }
</style>
