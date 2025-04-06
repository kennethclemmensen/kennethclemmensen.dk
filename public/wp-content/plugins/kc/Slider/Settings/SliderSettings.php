<?php
namespace KC\Slider\Settings;

use KC\Core\Action;
use KC\Core\PluginService;
use KC\Core\Images\ImageSize;
use KC\Core\PostTypes\PostType;
use KC\Core\Settings\BaseSettings;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use KC\Core\Users\UserRole;

/**
 * The SliderSettings class contains methods to handle the slider settings.
 * The class cannot be inherited.
 */
final class SliderSettings extends BaseSettings {

	private readonly string $slideWidth;
	private readonly string $slideHeight;

	/**
	 * SliderSettings constructor
	 * 
	 * @param TranslationService $translationService the translation service
	 * @param PluginService $pluginService the plugin service
	 */
	public function __construct(private readonly TranslationService $translationService, private readonly PluginService $pluginService) {
		parent::__construct('kc-slides-settings', 'kc-slides-settings-options');
		$prefix = 'slide_';
		$this->slideWidth = $prefix.'width';
		$this->slideHeight = $prefix.'height';
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		$this->pluginService->addAction(Action::ADMIN_MENU, function() : void {
			$title = $this->translationService->getTranslatedString(TranslationString::Settings);
			$this->addSubmenuPage(PostType::Slides->value, $title, UserRole::Administrator->value, $this->settingsPage, function() : void {
				settings_errors();
				?>
				<form action="options.php" method="post">
					<?php
					settings_fields($this->settingsName);
					do_settings_sections($this->settingsPage);
					submit_button();
					?>
				</form>
				<?php
			});
		});
		$this->registerSettingInputs();
		$this->addImageSize();
	}

	/**
	 * Use the admin_init action to register the setting inputs
	 */
	private function registerSettingInputs() : void {
		$this->pluginService->addAction(Action::ADMIN_INIT, function() : void {
			$sectionID = $this->settingsPage.'-section-slider';
			$prefix = $this->settingsPage;
			add_settings_section($sectionID, '', function() : void {}, $this->settingsPage);
			add_settings_field($prefix.'slideWidth', $this->translationService->getTranslatedString(TranslationString::ImageWidth), function() : void {
				echo '<input type="number" name="'.$this->settingsName.'['.$this->slideWidth.']" value="'.$this->getSlideWidth().'" min="1">';
			}, $this->settingsPage, $sectionID);
			add_settings_field($prefix.'slideHeight', $this->translationService->getTranslatedString(TranslationString::ImageHeight), function() : void {
				echo '<input type="number" name="'.$this->settingsName.'['.$this->slideHeight.']" value="'.$this->getSlideHeight().'" min="1">';
			}, $this->settingsPage, $sectionID);
			$this->registerSetting($this->settingsName);
		});
	}

	/**
	 * Use the init action to add an image size
	 */
	private function addImageSize() : void {
		$this->pluginService->addAction(Action::INIT, function() : void {
			$width = $this->getSlideWidth();
			$height = $this->getSlideHeight();
			add_image_size(ImageSize::Slides->value, $width, $height, true);
		});
	}

	/**
	 * Get the slide width
	 * 
	 * @return int the slide width
	 */
	private function getSlideWidth() : int {
		return $this->settings[$this->slideWidth] ?? 1;
	}

	/**
	 * Get the slide height
	 * 
	 * @return int the slide height
	 */
	private function getSlideHeight() : int {
		return $this->settings[$this->slideHeight] ?? 1;
	}
}