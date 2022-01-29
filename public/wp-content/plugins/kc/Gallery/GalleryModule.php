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

	private GallerySettings $gallerySettings;
	private string $fieldParentPage;

	/**
	 * Initialize a new instance of the GalleryModule class
	 */
	public function __construct() {
		$this->gallerySettings = new GallerySettings();
		$this->fieldParentPage = FieldName::PARENT_PAGE;
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
			register_post_type(PostType::GALLERY, [
				'labels' => [
					'name' => PluginHelper::getTranslatedString(TranslationString::GALLERIES),
					'singular_name' => PluginHelper::getTranslatedString(TranslationString::GALLERY)
				],
				'public' => true,
				'has_archive' => true,
				'supports' => [Constant::TITLE, Constant::EDITOR, Constant::THUMBNAIL],
				'menu_icon' => 'dashicons-format-gallery',
				'rewrite' => ['slug' => $this->gallerySettings->getParentPagePath(), 'with_front' => false]
			]);
			register_post_type(PostType::IMAGE, [
				'labels' => [
					'name' => PluginHelper::getTranslatedString(TranslationString::IMAGES),
					'singular_name' => PluginHelper::getTranslatedString(TranslationString::IMAGE)
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
		add_action(Action::getSavePostAction(PostType::GALLERY), function(int $postID) : void {
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
				'title' => PluginHelper::getTranslatedString(TranslationString::GALLERY_INFORMATIONS),
				'post_types' => [PostType::GALLERY],
				'fields' => [
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::PARENT_PAGE),
						'id' => $this->fieldParentPage,
						'type' => 'select',
						'options' => $this->getAllPosts(PostType::PAGE)
					]
				]
			];
			$metaBoxes[] = [
				'id' => 'image_informations',
				'title' => PluginHelper::getTranslatedString(TranslationString::IMAGE_INFORMATIONS),
				'post_types' => [PostType::IMAGE],
				'fields' => [
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::GALLERY),
						'id' => FieldName::IMAGE_GALLERY,
						'type' => 'select',
						'options' => $this->getAllPosts(PostType::GALLERY)
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
		add_filter(Filter::getManagePostsColumnsFilter(PostType::IMAGE), function(array $columns) use ($columnGalleryKey, $columnImageKey) : array {
			$columns[$columnGalleryKey] = PluginHelper::getTranslatedString(TranslationString::GALLERY);
			$columns[$columnImageKey] = PluginHelper::getTranslatedString(TranslationString::IMAGE);
			return $columns;
		});
		add_action(Action::getManagePostsCustomColumn(PostType::IMAGE), function(string $columnName) use ($columnGalleryKey, $columnImageKey) : void {
			if($columnName === $columnGalleryKey) {
				$galleryID = PluginHelper::getFieldValue(FieldName::IMAGE_GALLERY, get_the_ID());
				echo get_the_title($galleryID);
			} else if($columnName === $columnImageKey) {
				echo '<img src="'.PluginHelper::getImageUrl(get_the_ID(), ImageSize::THUMBNAIL).'" alt="'.get_the_title().'">';
			}
		});
	}
}