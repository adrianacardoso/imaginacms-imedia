@if(!empty($url) || $dataFancybox)
    <a href="{{ $dataFancybox ? ($isVideo ? "#mediaVideo".$uid : $src ) : $url }}"
       data-caption="{{$dataFancybox ? ($mediaFiles->{$zone}->description ?? $mediaFiles->description ?? '') : ''}}"
       title="{{$title ?? $mediaFiles->{$zone}->description ?? $mediaFiles->description ?? ''}}"
       class="{{$defaultLinkClasses}} {{$linkClasses}}"
            {{$dataFancybox ? "data-fancybox=$dataFancybox" : ''}}
            {{$dataCaption ? "data-caption=$dataCaption" : ''}} target="{{$target}}" rel="{{!empty($linkRel) ? $linkRel : ""}}">
    @endif

        @if($isVideo && (isset($mediaFiles->{$zone}->path) || isset($mediaFiles->path)))
                <video class="d-block h-100 cover-img" width="100%" loop autoplay muted>
                    <source src="{{ $mediaFiles->{$zone}->path ?? $mediaFiles->path }}" type="video/mp4">
                    <source src="{{ $mediaFiles->{$zone}->path ?? $mediaFiles->path }}" type="video/webm">
                    <source src="{{ $mediaFiles->{$zone}->path ?? $mediaFiles->path }}" type="video/ogg">
                </video>
        @else

    <!--Use data-srcset, data-src and specify lazyload class for images -->
        <picture style="display: contents; width: 100%">
            @if(!empty($smallSrc))
                <source data-srcset='{{$smallSrc}} 300w' type="image/webp" media="(max-width: 300px)">
            @endif
            @if(!empty($mediumSrc))
                <source data-srcset='{{$mediumSrc}} 600w' type="image/webp" media="(max-width: 600px)">
            @endif
            @if(!empty($largeSrc))
                <source data-srcset='{{$largeSrc}} 900w' type="image/webp" media="(max-width: 900px)">
            @endif
            @if(!empty($extraLargeSrc))
                <source data-srcset='{{$extraLargeSrc}} 1920w' type="image/webp" media="(min-width: 900px)">
            @endif
            @if(!empty($fallback))
                <source data-srcset='{{$fallback}}' type="image/{{$fallbackExtension}}">
            @endif

            <img data-src="{{$fallback}}"
                 class="lazyload {{$imgClasses}}"
                 alt="{{$alt}}"
                 style="width: 100%; {{$imgStyles}}"
                 data-sizes="auto"
                 data-parent-fit="contain"
                 data-parent-container=".image-link"
                 
            />
        </picture>
        @endif
        @if(!empty($url)|| $dataFancybox)
    </a>
    
    @if($isVideo && $dataFancybox)
        <video controls id="mediaVideo{{$uid}}" style="display:none;">
            <source src="{{ $mediaFiles->{$zone}->path ?? $mediaFiles->path }}" type="video/mp4">
            <source src="{{ $mediaFiles->{$zone}->path ?? $mediaFiles->path }}" type="video/webm">
            <source src="{{ $mediaFiles->{$zone}->path ?? $mediaFiles->path }}" type="video/ogg">
            Your browser doesn't support HTML5 video tag.
        </video>
        @endif
@endif

