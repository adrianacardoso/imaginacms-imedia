@if(!empty($url) || $dataFancybox)
    <a href="{{$dataFancybox ? $src : $url}}" title="{{$title}}" class="{{$defaultLinkClasses}} {{$linkClasses}}"
            {{$dataFancybox ? "data-fancybox=$dataFancybox" : ''}}
            {{$dataCaption ? "data-caption=$dataCaption" : ''}} >
        @endif

        @if($isOldMacVersion)
            <img
                    data-sizes="auto"
                    width="{{$width}}"
                    src="{{$fallback}}"
                    alt="{{$alt}}"
                    class="img-fluid {{$imgClasses}}"
                    style="{{$imgStyles}}"/>
        @else
            <img
                    data-sizes="auto"
                    width="{{$width}}"
                    data-src="{{$fallback}}"
                    alt="{{$alt}}"
                    data-srcset=" @php echo (!empty($smallSrc) ? $smallSrc." 300w,": '') @endphp
                    @php echo (!empty($mediumSrc) ? $mediumSrc." 600w," : '') @endphp
                    @php echo (!empty($largeSrc) ? $largeSrc." 900w," : '') @endphp
                    @php echo (!empty($extraLargeSrc) ? $extraLargeSrc." 1200w," : '') @endphp
                            "
                    class="img-fluid lazyload {{$imgClasses}}"
                    style="{{$imgStyles}}"/>
        @endif
        @if(!empty($url)|| $dataFancybox)
    </a>
@endif


