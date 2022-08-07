<?php
namespace KC\Gallery\Settings;

use KC\Core\Action;
use KC\Core\Images\ImageSize;
use KC\Core\PostTypes\PostType;
use KC\Core\Settings\BaseSettings;
use KC\Core\Settings\ISettings;
use KC\Core\Translations\TranslationString;
use KC\Core\Users\UserRole;
use KC\Data\DataManager;
use KC\Utils\PluginHelper;

/**
 * The GallerySettings class contains methods to handle the gallery settings
 */
class GallerySettings extends BaseSettings implements ISettings {

	private readonly string $settingOptionsName;
	private readonly array | bool $settingsOption;
	private readonly string $settingsPageSlug;
	private readonly string $galleryImageWidth;
	private readonly string $galleryImageHeight;
	private readonly string $galleryParentPage;

	/**
	 * GallerySettings constructor
	 */
	public function __construct() {
		$this->settingOptionsName = 'kc-gallery-settings-options';
		$this->settingsOption = get_option($this->settingOptionsName);
		$this->settingsPageSlug = 'kc-gallery-settings';
		$prefix = 'gallery_';
		$this->galleryImageWidth = $prefix.'image_width';
		$this->galleryImageHeight = $prefix.'image_height';
		$this->galleryParentPage = $prefix.'parent_page';
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		add_action(Action::ADMIN_MENU, function() : void {
			$title = PluginHelper::getTranslatedString(TranslationString::Settings);
			add_submenu_page('edit.php?post_type='.PostType::Gallery->value, $title, $title, UserRole::Administrator->value, $this->settingsPageSlug, function() : void {
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
	 * Get the parent page path
	 * 
	 * @return string the parent page path
	 */
	public function getParentPagePath() : string {
		return $this->settingsOption[$this->galleryParentPage] ?? '/';
	}

	/**
	 * Use the admin_init action to register the setting inputs
	 */
	private function registerSettingInputs() : void {
		add_action(Action::ADMIN_INIT, function() : void {
			$sectionID = $this->settingsPageSlug.'-section-gallery';
			$prefix = $this->settingsPageSlug.'galleryImage';
			add_settings_section($sectionID, '', null, $this->settingsPageSlug);
			add_settings_field($prefix.'Width', PluginHelper::getTranslatedString(TranslationString::ImageWidth), function() : void {
				echo '<input type="number" name="'.$this->settingOptionsName.'['.$this->galleryImageWidth.']" value="'.$this->getGalleryImageWidth().'" min="1">';
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($prefix.'Height', PluginHelper::getTranslatedString(TranslationString::ImageHeight), function() : void {
				echo '<input type="number" name="'.$this->settingOptionsName.'['.$this->galleryImageHeight.']" value="'.$this->getGalleryImageHeight().'" min="1">';
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($this->settingsPageSlug.'parentPage', PluginHelper::getTranslatedString(TranslationString::ParentPage), function() : void {
				?>
				<select name="<?php echo $this->settingOptionsName.'['.$this->galleryParentPage.']'; ?>">
					<?php
					$dataManager = new DataManager();
					$pages = $dataManager->getPages();
					foreach($pages as $key => $value) {
						echo '<option value="'.$key.'" '.selected($this->getParentPagePath(), $key).'>'.$value.'</option>';
					}
					?>
				</select>
				<?php
			}, $this->settingsPageSlug, $sectionID);
			$this->registerSetting($this->settingOptionsName);
		});
	}

	/**
	 * Use the init action to add an image size
	 */
	private function addImageSize() : void {
		add_action(Action::INIT, function() : void {
			$width = $this->getGalleryImageWidth();
			$height = $this->getGalleryImageHeight();
			add_image_size(ImageSize::GalleryImage->value, $width, $height, true);
		});
	}

	/**
	 * Get the gallery image width
	 * 
	 * @return int the gallery image width
	 */
	private function getGalleryImageWidth() : int {
		return $this->settingsOption[$this->galleryImageWidth] ?? 1;
	}

	/**
	 * Get the gallery image height
	 * 
	 * @return int the gallery image height
	 */
	private function getGalleryImageHeight() : int {
		return $this->settingsOption[$this->galleryImageHeight] ?? 1;
	}
}