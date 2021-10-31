<?php
namespace KC\Slider\Settings;

use KC\Core\Action;
use KC\Core\Capability;
use KC\Core\ImageSize;
use KC\Core\ISettings;
use KC\Core\PostType;
use KC\Core\TranslationString;
use KC\Security\Security;
use KC\Utils\PluginHelper;

/**
 * The SliderSettings class contains methods to handle the slider settings
 */
class SliderSettings implements ISettings {

	private string $settingOptionsName;
	private array | bool $settingsOption;
	private string $settingsPageSlug;
	private string $slideWidth;
	private string $slideHeight;

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
			$title = PluginHelper::getTranslatedString(TranslationString::SETTINGS);
			add_submenu_page('edit.php?post_type='.PostType::SLIDES, $title, $title, Capability::ADMINISTRATOR, $this->settingsPageSlug, function() : void {
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
			add_settings_section($sectionID, '', null, $this->settingsPageSlug);
			add_settings_field($prefix.'slideWidth', PluginHelper::getTranslatedString(TranslationString::IMAGE_WIDTH), function() : void {
				echo '<input type="number" name="'.$this->settingOptionsName.'['.$this->slideWidth.']" value="'.$this->getSlideWidth().'" min="1">';
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($prefix.'slideHeight', PluginHelper::getTranslatedString(TranslationString::IMAGE_HEIGHT), function() : void {
				echo '<input type="number" name="'.$this->settingOptionsName.'['.$this->slideHeight.']" value="'.$this->getSlideHeight().'" min="1">';
			}, $this->settingsPageSlug, $sectionID);
			register_setting($this->settingOptionsName, $this->settingOptionsName, function(array $input) : array {
				return Security::validateSettingInputs($input);
			});
		});
	}

	/**
	 * Use the init action to add an image size
	 */
	private function addImageSize() : void {
		add_action(Action::INIT, function() : void {
			$width = $this->getSlideWidth();
			$height = $this->getSlideHeight();
			add_image_size(ImageSize::KC_SLIDES, $width, $height, true);
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