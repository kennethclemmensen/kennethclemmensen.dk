<?php
namespace KC\Core\Images;

use KC\Core\Security\SecurityService;

/**
 * The ImageService class contains image methods
 */
final class ImageService {

	/**
	 * Get an image url
	 * 
	 * @param int $imageID the id of the image
	 * @param ImageSize $size the size of the image
	 * @return string the image url
	 */
	public static function getImageUrl(int $imageID, ImageSize $size = ImageSize::PostThumbnail) : string {
		return SecurityService::escapeUrl(get_the_post_thumbnail_url($imageID, $size->value));
	}
}