<?php
namespace KC\Gallery;

use KC\Core\Action;
use KC\Core\Modules\BaseModule;
use KC\Core\Constant;
use KC\Core\FieldName;
use KC\Core\Filter;
use KC\Core\Images\ImageSize;
use KC\Core\Modules\IModule;
use KC\Core\PostType;
use KC\Core\TranslationString;
use KC\Data\DatabaseManager;
use KC\Gallery\Settings\GallerySettings;
use KC\Utils\PluginHelper;

/**
 * The GalleryModule class contains functionality to handle galleries
 */
class GalleryModule extends BaseModule implements IModule {

	private readonly GallerySettings $gallerySettings;
	private readonly string $fieldParentPage;

	/**
	 * Initialize a new instance of the GalleryModule class
	 */
	public function __construct() {
		$this->gallerySettings = new GallerySettings();
		$this->fieldParentPage = FieldName::ParentPage->value;
	}

	/**
	 * Setup the gallery module
	 */
	public function setupModule() : void {
		$this->gallerySettings->createSettingsPage();
		$this->registerPostTypes();
		$this->updatePostParent();
		$this->addMetaBoxes();
		$this->addAdminColumns();
	}

	/**
	 * Register the gallery and the image custom post types
	 */
	private function registerPostTypes() : void {
		add_action(Action::INIT, function() : void {
			register_post_type(PostType::Gallery->value, [
				'labels' => [
					'name' => PluginHelper::getTranslatedString(TranslationString::Galleries),
					'singular_name' => PluginHelper::getTranslatedString(TranslationString::Gallery)
				],
				'public' => true,
				'has_archive' => true,
				'supports' => [Constant::TITLE, Constant::EDITOR, Constant::THUMBNAIL],
				'menu_icon' => 'dashicons-format-gallery',
				'rewrite' => ['slug' => $this->gallerySettings->getParentPagePath(), 'with_front' => false]
			]);
			register_post_type(PostType::Image->value, [
				'labels' => [
					'name' => PluginHelper::getTranslatedString(TranslationString::Images),
					'singular_name' => PluginHelper::getTranslatedString(TranslationString::Image)
				],
				'public' => false,
				'has_archive' => false,
				'supports' => [Constant::TITLE, Constant::THUMBNAIL],
				'menu_icon' => 'dashicons-format-image',
				'publicly_queryable' => true,
				'show_ui' => true,
				'exclude_from_search' => true,
				'show_in_nav_menus' => false,
				'rewrite' => false
			]);
		});
	}

	/**
	 * Update the post_parent column in the database when saving a gallery
	 */
	private function updatePostParent() : void {
		add_action(Action::getSavePostAction(PostType::Gallery), function(int $postID) : void {
			PluginHelper::setFieldValue($_REQUEST[$this->fieldParentPage], $this->fieldParentPage, $postID);
			$parentPage = PluginHelper::getFieldValue($this->fieldParentPage, $postID);
			$dbManager = new DatabaseManager();
			$dbManager->updatePostParent($postID, $parentPage);
		});
	}

	/**
	 * Add meta boxes to the gallery and the image custom post types
	 */
	private function addMetaBoxes() : void {
		add_filter(Filter::META_BOXES, function(array $metaBoxes) : array {
			$metaBoxes[] = [
				'id' => 'gallery_informations',
				'title' => PluginHelper::getTranslatedString(TranslationString::GalleryInformations),
				'post_types' => [PostType::Gallery->value],
				'fields' => [
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::ParentPage),
						'id' => $this->fieldParentPage,
						'type' => 'select',
						'options' => $this->getAllPosts(PostType::Page)
					]
				]
			];
			$metaBoxes[] = [
				'id' => 'image_informations',
				'title' => PluginHelper::getTranslatedString(TranslationString::ImageInformations),
				'post_types' => [PostType::Image->value],
				'fields' => [
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::Gallery),
						'id' => FieldName::ImageGallery->value,
						'type' => 'select',
						'options' => $this->getAllPosts(PostType::Gallery)
					]
				]
			];
			return $metaBoxes;
		});
	}

	/**
	 * Add admin columns to the image custom post type
	 */
	private function addAdminColumns() : void {
		$columnGalleryKey = 'gallery';
		$columnImageKey = 'image';
		add_filter(Filter::getManagePostsColumnsFilter(PostType::Image), function(array $columns) use ($columnGalleryKey, $columnImageKey) : array {
			$columns[$columnGalleryKey] = PluginHelper::getTranslatedString(TranslationString::Gallery);
			$columns[$columnImageKey] = PluginHelper::getTranslatedString(TranslationString::Image);
			return $columns;
		});
		add_action(Action::getManagePostsCustomColumn(PostType::Image), function(string $columnName) use ($columnGalleryKey, $columnImageKey) : void {
			if($columnName === $columnGalleryKey) {
				$galleryID = PluginHelper::getFieldValue(FieldName::ImageGallery, get_the_ID());
				echo get_the_title($galleryID);
			} else if($columnName === $columnImageKey) {
				echo '<img src="'.PluginHelper::getImageUrl(get_the_ID(), ImageSize::Thumbnail).'" alt="'.get_the_title().'">';
			}
		});
	}
}