<?php
namespace KC\Slider\Settings;

use KC\Core\Action;
use KC\Core\Images\ImageSize;
use KC\Core\PostTypes\PostType;
use KC\Core\Settings\BaseSettings;
use KC\Core\Settings\ISettings;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use KC\Core\Users\UserRole;

/**
 * The SliderSettings class contains methods to handle the slider settings
 */
final class SliderSettings extends BaseSettings implements ISettings {

	private readonly string $settingOptionsName;
	private readonly array | bool $settingsOption;
	private readonly string $settingsPageSlug;
	private readonly string $slideWidth;
	private readonly string $slideHeight;

	/**
	 * SliderSettings constructor
	 */
	public function __construct() {
		$this->settingOptionsName = 'kc-slides-settings-options';
		$this->settingsOption = get_option($this->settingOptionsName);
		$this->settingsPageSlug = 'kc-slides-settings';
		$prefix = 'slide_';
		$this->slideWidth = $prefix.'width';
		$this->slideHeight = $prefix.'height';
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		add_action(Action::ADMIN_MENU, function() : void {
			$title = TranslationService::getTranslatedString(TranslationString::Settings);
			add_submenu_page('edit.php?post_type='.PostType::Slides->value, $title, $title, UserRole::Administrator->value, $this->settingsPageSlug, function() : void {
				settings_errors();
				?>
				<form action="options.php" method="post">
					<?php
					settings_fields($this->settingOptionsName);
					do_settings_sections($this->settingsPageSlug);
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
		add_action(Action::ADMIN_INIT, function() : void {
			$sectionID = $this->settingsPageSlug.'-section-slider';
			$prefix = $this->settingsPageSlug;
			add_settings_section($sectionID, '', function() : void {}, $this->settingsPageSlug);
			add_settings_field($prefix.'slideWidth', TranslationService::getTranslatedString(TranslationString::ImageWidth), function() : void {
				echo '<input type="number" name="'.$this->settingOptionsName.'['.$this->slideWidth.']" value="'.$this->getSlideWidth().'" min="1">';
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($prefix.'slideHeight', TranslationService::getTranslatedString(TranslationString::ImageHeight), function() : void {
				echo '<input type="number" name="'.$this->settingOptionsName.'['.$this->slideHeight.']" value="'.$this->getSlideHeight().'" min="1">';
			}, $this->settingsPageSlug, $sectionID);
			$this->registerSetting($this->settingOptionsName);
		});
	}

	/**
	 * Use the init action to add an image size
	 */
	private function addImageSize() : void {
		add_action(Action::INIT, function() : void {
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
		return $this->settingsOption[$this->slideWidth] ?? 1;
	}

	/**
	 * Get the slide height
	 * 
	 * @return int the slide height
	 */
	private function getSlideHeight() : int {
		return $this->settingsOption[$this->slideHeight] ?? 1;
	}
}