<?php
namespace KC\Core\PostTypes;

/**
 * The PostType enum defines the post types
 */
enum PostType: string {
	case File = 'kc_file';
	case Gallery = 'kc_gallery';
	case Image = 'kc_image';
	case Page = 'page';
	case Slides = 'kc_slides';
}