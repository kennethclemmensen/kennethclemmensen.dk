<?php
namespace KC\Data;

use KC\Core\Images\ImageService;
use KC\Core\Images\ImageSize;
use KC\Core\PostTypes\FieldName;
use KC\Core\PostTypes\PostType;
use KC\Core\PostTypes\PostTypeFeature;
use KC\Core\PostTypes\PostTypeService;
use KC\Core\PostTypes\SortingOrder;
use KC\Core\Security\SecurityService;
use KC\Core\Taxonomies\TaxonomyName;
use \WP_Query;

/**
 * The DataManager class contains functionality to manage data
 */
final readonly class DataManager {

	private readonly PostTypeService $postTypeService;
	private readonly SecurityService $securityService;
	private readonly ImageService $imageService;

	/**
	 * DataManager constructor
	 *
	 * @param PostTypeService $postTypeService the post type service
	 * @param SecurityService $securityService the security service
	 * @param ImageService $imageService the image service
	 */
	public function __construct(PostTypeService $postTypeService, SecurityService $securityService, ImageService $imageService) {
		$this->postTypeService = $postTypeService;
		$this->securityService = $securityService;
		$this->imageService = $imageService;
	}

	/**
	 * Get the pages
	 * 
	 * @return array the pages
	 */
	public function getPages() : array {
		$pages = [];
		$args = [
			'order' => SortingOrder::Ascending->value,
			'orderby' => 'menu_order',
			'posts_per_page' => -1,
			'post_type' => [PostType::Page->value]
		];
		$wpQuery = new WP_Query($args);
		while($wpQuery->have_posts()) {
			$wpQuery->the_post();
			$relativeLink = wp_make_link_relative(get_permalink(get_the_ID()));
			$url = $this->removeLastCharacter($relativeLink);
			$pages[$url] = get_the_title();
		}
		return $pages;
	}

	/**
	 * Get the pages by title
	 *
	 * @param string $title the title to get the pages from
	 * @return array the pages
	 */
	public function getPagesByTitle(string $title) : array {
		$pages = [];
		$args = [
			'order' => SortingOrder::Ascending->value,
			'orderby' => 'menu_order',
			'posts_per_page' => -1,
			'post_type' => [PostType::Page->value],
			's' => $title
		];
		$wpQuery = new WP_Query($args);
		while($wpQuery->have_posts()) {
			$wpQuery->the_post();
			$pages[] = [
				'title' => get_the_title(),
				'link' => get_permalink(get_the_ID()),
				'excerpt' => html_entity_decode(get_the_excerpt())
			];
		}
		return $pages;
	}

	/**
	 * Get the slides
	 * 
	 * @return array the slides
	 */
	public function getSlides() : array {
		$slides = [];
		$args = [
			'post_type' => PostType::Slides->value,
			'posts_per_page' => -1,
			'order' => SortingOrder::Ascending->value,
			'orderby' => 'menu_order'
		];
		$wpQuery = new WP_Query($args);
		while($wpQuery->have_posts()) {
			$wpQuery->the_post();
			$slides[] = ['image' => $this->imageService->getImageUrl(get_the_ID(), ImageSize::Slides)];
		}
		return $slides;
	}

	/**
	 * Get the galleries
	 *
	 * @return array the galleries
	 */
	public function getGalleries() : array {
		$galleries = [];
		$args = [
			'post_type' => PostType::Gallery->value,
			'posts_per_page' => -1,
			'order' => SortingOrder::Ascending->value
		];
		$wpQuery = new WP_Query($args);
		while($wpQuery->have_posts()) {
			$wpQuery->the_post();
			$galleries[] = [
				'title' => get_the_title(),
				'link' => get_permalink(get_the_ID()),
				'image' => $this->imageService->getImageUrl(get_the_ID(), ImageSize::GalleryImage)
			];
		}
		return $galleries;
	}

	/**
	 * Get the images from a gallery
	 * 
	 * @param int $galleryId the gallery id
	 * @return array the images
	 */
	public function getImages(int $galleryId) : array {
		$images = [];
		$args = [
			'post_type' => PostType::Image->value,
			'posts_per_page' => -1,
			'orderby' => PostTypeFeature::Title->value,
			'order' => SortingOrder::Ascending->value,
			'meta_key' => FieldName::ImageGallery->value,
			'meta_value' => $galleryId
		];
		$wpQuery = new WP_Query($args);
		while($wpQuery->have_posts()) {
			$wpQuery->the_post();
			$id = get_the_ID();
			$url = $this->imageService->getImageUrl($id);
			$imageInfo = wp_get_attachment_image_src(attachment_url_to_postid($url));
			$images[] = [
				'title' => get_the_title(),
				'url' => $this->imageService->getImageUrl($id, ImageSize::Large),
				'thumbnail' => $this->imageService->getImageUrl($id, ImageSize::Thumbnail),
				'gallery' => $galleryId,
				'width' => $imageInfo[1].'px',
				'height' => $imageInfo[2].'px'
			];
		}
		return $images;
	}

	/**
	 * Update the download counter for a file
	 *
	 * @param int $fileID the id of the file
	 */
	public function updateFileDownloadCounter(int $fileID) : void {
		$downloads = $this->getFileDownloads($fileID);
		$downloads++;
		$this->postTypeService->setFieldValue($downloads, FieldName::FileDownloads, $fileID);
	}

	/**
	 * Get the files based on the file types
	 * 
	 * @param array $fileTypes the file types
	 * @return array the files
	 */
	public function getFiles(array $fileTypes) : array {
		$files = [];
		$args = [
			'post_type' => PostType::File->value,
			'posts_per_page' => -1,
			'order' => SortingOrder::Ascending->value,
			'tax_query' => [
				[
					'taxonomy' => TaxonomyName::FileType->value,
					'terms' => $fileTypes
				]
			]
		];
		$wpQuery = new WP_Query($args);
		while($wpQuery->have_posts()) {
			$wpQuery->the_post();
			$id = get_the_ID();
			$files[] = [
				'id' => $id,
				'fileName' => $this->getFileName($id), 
				'url' => $this->getFileUrl($id),
				'description' => $this->getFileDescription($id),
				'downloads' => $this->getFileDownloads($id)
			];
		}
		return $files;
	}

	/**
	 * Get the shortcuts
	 * 
	 * @return array the shortcuts
	 */
	public function getShortcuts() : array {
		$shortcuts = [];
		$args = [
			'post_type' => PostType::Page->value,
			'posts_per_page' => -1
		];
		$wpQuery = new WP_Query($args);
		while($wpQuery->have_posts()) {
			$wpQuery->the_post();
			$id = get_the_ID();
			$key = $this->postTypeService->getFieldValue(FieldName::Key, $id);
			$altKey = $this->postTypeService->getFieldValue(FieldName::AltKey, $id) === '1';
			$ctrlKey = $this->postTypeService->getFieldValue(FieldName::CtrlKey, $id) === '1';
			$shiftKey = $this->postTypeService->getFieldValue(FieldName::ShiftKey, $id) === '1';
			if($key && ($altKey === true || $ctrlKey === true || $shiftKey === true)) {
				$relativeLink = wp_make_link_relative(get_permalink($id));
				$url = $this->removeLastCharacter($relativeLink);
				$shortcuts[] = [
					'altKey' => $altKey,
					'ctrlKey' => $ctrlKey,
					'shiftKey' => $shiftKey,
					'key' => $key,
					'url' => $url
				];
			}
		}
		return $shortcuts;
	}

	/**
	 * Get the file url
	 *
	 * @param int $fileID the id of the file
	 * @return string the file url
	 */
	private function getFileUrl(int $fileID) : string {
		$attachmentID = $this->postTypeService->getFieldValue(FieldName::File, $fileID);
		return $this->securityService->escapeUrl(wp_get_attachment_url($attachmentID));
	}

	/**
	 * Get the file name
	 *
	 * @param int $fileID the id of the file
	 * @return string the file name
	 */
	private function getFileName(int $fileID) : string {
		$attachmentID = $this->postTypeService->getFieldValue(FieldName::File, $fileID);
		return basename(get_attached_file($attachmentID));
	}

	/**
	 * Get the file description
	 *
	 * @param int $fileID the id of the file
	 * @return string the file description
	 */
	private function getFileDescription(int $fileID) : string {
		return $this->postTypeService->getFieldValue(FieldName::FileDescription, $fileID);
	}

	/**
	 * Get the number of file downloads for a file
	 *
	 * @param int $fileID the id of the file
	 * @return int the number of file downloads
	 */
	private function getFileDownloads(int $fileID) : int {
		return $this->postTypeService->getFieldValue(FieldName::FileDownloads, $fileID);
	}

	/**
	 * Remove the last character from a string
	 * 
	 * @param string $str the string to remove the last character from
	 * @return string the string without the last character
	 */
	private function removeLastCharacter(string $str) : string {
		return (strlen($str) <= 1) ? $str : substr_replace($str, '', -1);
	}
}