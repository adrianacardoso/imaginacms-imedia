<style>
  	#galleryWithHorizontalThumbs .primary-gallery {
      margin-bottom: 15px;
  	}
		#galleryWithHorizontalThumbs .primary-gallery .carousel-item img,
    #galleryWithHorizontalThumbs .thumbs-gallery .item img{
				aspect-ratio: {{ str_replace("-","/", $aspectRatio)}};
        object-fit: {{$objectFit}};
		}
</style>
