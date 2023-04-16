<?php
namespace KC\Gallery;

use KC\Core\Action;
use KC\Core\Filter;
use KC\Core\Images\ImageService;
use KC\Core\Images\ImageSize;
use KC\Core\Modules\IModule;
use KC\Core\PostTypes\FieldName;
use KC\Core\PostTypes\FieldType;
use KC\Core\PostTypes\Icon;
use KC\Core\PostTypes\PostType;
use KC\Core\PostTypes\PostTypeFeature;
use KC\Core\PostTypes\PostTypeService;
use KC\Core\Security\SecurityService;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use KC\Data\Database\DatabaseManager;
use KC\Data\Database\DataManager;
use KC\Gallery\Settings\GallerySettings;

/**
 * The GalleryModule class contains functionality to handle galleries
 */
final readonly class GalleryModule implements IModule {

	private GallerySettings $gallerySettings;
	private string $fieldParentPage;
	private TranslationService $translationService;
	private PostTypeService $postTypeService;
	private ImageService $imageService;
	private DataManager $dataManager;

	/**
	 * Initialize a new instance of the GalleryModule class
	 */
	public function __construct() {
		$this->translationService = new TranslationService();
		$this->gallerySettings = new GallerySettings($this->translationService);
		$this->fieldParentPage = FieldName::ParentPage->value;
		$this->postTypeService = new PostTypeService();
		$this->imageService = new ImageService();
		$this->dataManager = new DataManager($this->postTypeService, new SecurityService(), $this->imageService);
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
					'name' => $this->translationService->getTranslatedString(TranslationString::Galleries),
					'singular_name' => $this->translationService->getTranslatedString(TranslationString::Gallery)
				],
				'public' => true,
				'has_archive' => true,
				'supports' => [PostTypeFeature::Title->value, PostTypeFeature::Editor->value, PostTypeFeature::Thumbnail->value],
				'menu_icon' => Icon::Gallery->value,
				'rewrite' => ['slug' => $this->gallerySettings->getParentPagePath(), 'with_front' => false]
			]);
			register_post_type(PostType::Image->value, [
				'labels' => [
					'name' => $this->translationService->getTranslatedString(TranslationString::Images),
					'singular_name' => $this->translationService->getTranslatedString(TranslationString::Image)
				],
				'public' => false,
				'has_archive' => false,
				'supports' => [PostTypeFeature::Title->value, PostTypeFeature::Thumbnail->value],
				'menu_icon' => Icon::Image->value,
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
			$this->postTypeService->setFieldValue($_REQUEST[$this->fieldParentPage], FieldName::ParentPage, $postID);
			$parentPage = $this->postTypeService->getFieldValue(FieldName::ParentPage, $postID);
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
				'title' => $this->translationService->getTranslatedString(TranslationString::GalleryInformations),
				'post_types' => [PostType::Gallery->value],
				'fields' => [
					[
						'name' => $this->translationService->getTranslatedString(TranslationString::ParentPage),
						'id' => $this->fieldParentPage,
						'type' => FieldType::Select->value,
						'options' => $this->dataManager->getAllPosts(PostType::Page)
					]
				]
			];
			$metaBoxes[] = [
				'id' => 'image_informations',
				'title' => $this->translationService->getTranslatedString(TranslationString::ImageInformations),
				'post_types' => [PostType::Image->value],
				'fields' => [
					[
						'name' => $this->translationService->getTranslatedString(TranslationString::Gallery),
						'id' => FieldName::ImageGallery->value,
						'type' => FieldType::Select->value,
						'options' => $this->dataManager->getAllPosts(PostType::Gallery)
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
			$columns[$columnGalleryKey] = $this->translationService->getTranslatedString(TranslationString::Gallery);
			$columns[$columnImageKey] = $this->translationService->getTranslatedString(TranslationString::Image);
			return $columns;
		});
		add_action(Action::getManagePostsCustomColumn(PostType::Image), function(string $columnName) use ($columnGalleryKey, $columnImageKey) : void {
			if($columnName === $columnGalleryKey) {
				$galleryID = $this->postTypeService->getFieldValue(FieldName::ImageGallery, get_the_ID());
				echo get_the_title($galleryID);
			} else if($columnName === $columnImageKey) {
				echo '<img src="'.$this->imageService->getImageUrl(get_the_ID(), ImageSize::Thumbnail).'" alt="'.get_the_title().'">';
			}
		});
	}
}