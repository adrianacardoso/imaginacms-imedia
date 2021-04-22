@if(!empty($url))
  <a href="{{$url}}" title="{{$title}}" class="image-link w-100">
    @endif
  
        <img
        data-sizes="auto"
        width="{{$width}}"
        data-src="{{$src}}"
        alt="{{$alt}}"
        data-srcset=" @php echo (!empty($smallSrc) ? $smallSrc." 300w,": '') @endphp
        @php echo (!empty($mediumSrc) ? $mediumSrc." 600w," : '') @endphp
        @php echo (!empty($largeSrc) ? $largeSrc." 900w," : '') @endphp
        @php echo (!empty($extraLargeSrc) ? $extraLargeSrc." 1200w," : '') @endphp
          "
      class="img-fluid lazyload {{$imgClasses}}"
        style="{{$imgStyles}}"/>
  
    @if(!empty($url))
  </a>
@endif


