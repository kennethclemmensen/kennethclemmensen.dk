<?php
namespace KC\Slider;

use KC\Core\Action;
use KC\Core\Filter;
use KC\Core\Images\ImageService;
use KC\Core\Modules\IModule;
use KC\Core\PostTypes\Icon;
use KC\Core\PostTypes\PostType;
use KC\Core\PostTypes\PostTypeFeature;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use KC\Slider\Settings\SliderSettings;

/**
 * The SliderModule class contains functionality to handle the slides
 */
final readonly class SliderModule implements IModule {

	private TranslationService $translationService;

	/**
	 * SliderModule constructor
	 */
	public function __construct() {
		$this->translationService = new TranslationService();
	}

	/**
	 * Setup the slider module
	 */
	public function setupModule() : void {
		$sliderSettings = new SliderSettings($this->translationService);
		$sliderSettings->createSettingsPage();
		$this->registerPostType();
		$this->addAdminColumns();        
	}

	/**
	 * Register the slides custom post type
	 */
	private function registerPostType() : void {
		add_action(Action::INIT, function() : void {
			register_post_type(PostType::Slides->value, [
				'labels' => [
					'name' => $this->translationService->getTranslatedString(TranslationString::Slides),
					'singular_name' => $this->translationService->getTranslatedString(TranslationString::Slide)
				],
				'public' => false,
				'has_archive' => false,
				'supports' => [PostTypeFeature::Title->value, PostTypeFeature::Thumbnail->value],
				'menu_icon' => Icon::Images->value,
				'publicly_queryable' => true,
				'show_ui' => true,
				'exclude_from_search' => true,
				'show_in_nav_menus' => false,
				'rewrite' => false
			]);
		});
	}

	/**
	 * Add admin columns for the custom post type slides
	 */
	private function addAdminColumns() : void {
		$imageColumnKey = 'image';
		add_filter(Filter::getManagePostsColumnsFilter(PostType::Slides), function(array $columns) use ($imageColumnKey) : array {
			$columns[$imageColumnKey] = $this->translationService->getTranslatedString(TranslationString::Image);
			return $columns;
		});
		add_action(Action::getManagePostsCustomColumn(PostType::Slides), function(string $columnName) use ($imageColumnKey) : void {
			$imageService = new ImageService();
			if($columnName === $imageColumnKey) echo '<img src="'.$imageService->getImageUrl(get_the_ID()).'" alt="'.get_the_title().'" style="height: 60px">';
		});
	}
}