<?php
namespace KC\Core\Images;

/**
 * The ImageSize enum defines the image sizes
 */
enum ImageSize: string {
	case GalleryImage = 'kc-gallery-image';
	case Large = 'large';
	case PostThumbnail = 'post-thumbnail';
	case Slides = 'kc-slides';
	case Thumbnail = 'thumbnail';
}