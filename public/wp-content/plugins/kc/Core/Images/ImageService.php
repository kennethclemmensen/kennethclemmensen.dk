<?php
namespace KC\Core\Images;

use KC\Core\Security\SecurityService;

/**
 * The ImageService class contains image methods
 */
final readonly class ImageService {

	/**
	 * Get an image url
	 * 
	 * @param int $imageID the id of the image
	 * @param ImageSize $size the size of the image
	 * @return string the image url
	 */
	public function getImageUrl(int $imageID, ImageSize $size = ImageSize::PostThumbnail) : string {
		$securityService = new SecurityService();
		return $securityService->escapeUrl(get_the_post_thumbnail_url($imageID, $size->value));
	}
}