<?php
namespace KC\Slider;

use KC\Core\Action;
use KC\Core\Constant;
use KC\Core\Filter;
use KC\Core\IModule;
use KC\Core\PostType;
use KC\Core\TranslationString;
use KC\Slider\Settings\SliderSettings;
use KC\Utils\PluginHelper;

/**
 * The SliderModule class contains functionality to handle the slides
 */
class SliderModule implements IModule {

	/**
	 * Setup the slider module
	 */
	public function setupModule() : void {
		require_once 'Settings/SliderSettings.php';
		$sliderSettings = new SliderSettings();
		$sliderSettings->createSettingsPage();
		$this->registerPostType();
		$this->addAdminColumns();        
	}

	/**
	 * Register the slides custom post type
	 */
	private function registerPostType() : void {
		add_action(Action::INIT, function() : void {
			register_post_type(PostType::SLIDES, [
				'labels' => [
					'name' => PluginHelper::getTranslatedString(TranslationString::SLIDES),
					'singular_name' => PluginHelper::getTranslatedString(TranslationString::SLIDE)
				],
				'public' => false,
				'has_archive' => false,
				'supports' => [Constant::TITLE, Constant::THUMBNAIL],
				'menu_icon' => 'dashicons-images-alt',
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
		add_filter(Filter::getManagePostsColumnsFilter(PostType::SLIDES), function(array $columns) use ($imageColumnKey) : array {
			$columns[$imageColumnKey] = PluginHelper::getTranslatedString(TranslationString::IMAGE);
			return $columns;
		});
		add_action(Action::getManagePostsCustomColumn(PostType::SLIDES), function(string $columnName) use ($imageColumnKey) : void {
			if($columnName === $imageColumnKey) echo '<img src="'.PluginHelper::getImageUrl(get_the_ID()).'" alt="'.get_the_title().'" style="height: 60px">';
		});
	}
}