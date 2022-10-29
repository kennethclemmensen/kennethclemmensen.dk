<?php
namespace KC\Core\Images;

use KC\Core\Security\SecurityHelper;

/**
 * The ImageHelper class contains image methods
 */
final class ImageHelper {

	/**
	 * Get an image url
	 * 
	 * @param int $imageID the id of the image
	 * @param ImageSize $size the size of the image
	 * @return string the image url
	 */
	public static function getImageUrl(int $imageID, ImageSize $size = ImageSize::PostThumbnail) : string {
		return SecurityHelper::escapeUrl(get_the_post_thumbnail_url($imageID, $size->value));
	}
}