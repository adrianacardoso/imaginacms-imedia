  <script>
    $(document).ready(function () {
      var owl = $('#{{$id}}Carousel');

      owl.owlCarousel({
        loop: {!! $loop ? 'true' : 'false' !!},
        lazyLoad: true,
        margin: {!! $margin !!},
        {!! (isset($items) && !empty($items)) ? 'items: '.$items."," : "" !!}
        {!! !empty($navText) ? 'navText: '.$navText."," : "" !!}
        dots: {!! $dots ? 'true' : 'false' !!},
        responsiveClass: {!! $responsiveClass ? 'true' : 'false' !!},
        autoplay: {!! $autoplay ? 'true' : 'false' !!},
        autoplayHoverPause: {!! $autoplayHoverPause ? 'true' : 'false' !!},
        nav: {!! $nav ? 'true' : 'false' !!},
      {!! !empty($responsive) ? 'responsive: '.$responsive : "" !!}
      });

    });
  </script>

 