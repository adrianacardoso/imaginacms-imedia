@if(!empty($url) || $dataFancybox)
    <a href="{{ $dataFancybox ? $src : $url }}" title="{{$title}}" class="{{$defaultLinkClasses}} {{$linkClasses}}"
            {{$dataFancybox ? "data-fancybox=$dataFancybox" : ''}}
            {{$dataCaption ? "data-caption=$dataCaption" : ''}} >
    @endif
    <!--Use data-srcset, data-src and specify lazyload class for images -->
        <picture>
            @if(!empty($smallSrc))
                <source data-srcset='{{$smallSrc}}' type="image/webp" media="(max-width: 300px)">
            @endif
            @if(!empty($mediumSrc))
                <source data-srcset='{{$mediumSrc}}' type="image/webp" media="(max-width: 600px)">
            @endif
            @if(!empty($largeSrc))
                <source data-srcset='{{$largeSrc}}' type="image/webp" media="(min-width: 900px)">
            @endif
            @if(!empty($extraLargeSrc))
                <source data-srcset='{{$extraLargeSrc}}' type="image/webp" media="(min-width: 1200px)">
            @endif
            @if(!empty($fallback))
                <source data-srcset='{{$fallback}}' type="image/{{$fallbackExtension}}" media="(min-width: 900px)">
            @endif

            <img data-src="{{$fallback}}" class="img-fluid lazyload {{$imgClasses}}"
                 alt="{{$alt}}" style="{{$imgStyles}}" data-sizes="auto" width="{{$width}}"/>
        </picture>

        @if(!empty($url)|| $dataFancybox)
    </a>
@endif


