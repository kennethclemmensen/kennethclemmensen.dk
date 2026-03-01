<?php
namespace KC\Gallery\Settings;

use KC\Core\Action;
use KC\Core\PluginService;
use KC\Core\Images\ImageService;
use KC\Core\Images\ImageSize;
use KC\Core\PostTypes\PostType;
use KC\Core\PostTypes\PostTypeService;
use KC\Core\Security\SecurityService;
use KC\Core\Settings\BaseSettings;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use KC\Core\Users\UserRole;
use KC\Data\Database\DataManager;

/**
 * The GallerySettings class contains methods to handle the gallery settings.
 * The class cannot be inherited.
 */
final class GallerySettings extends BaseSettings {

	private readonly string $galleryImageWidth;
	private readonly string $galleryImageHeight;
	private readonly string $galleryParentPage;
	private readonly PluginService $pluginService;

	/**
	 * GallerySettings constructor
	 * 
	 * @param TranslationService $translationService the translation service
	 */
	public function __construct(private readonly TranslationService $translationService) {
		parent::__construct('kc-gallery-settings', 'kc-gallery-settings-options');
		$prefix = 'gallery_';
		$this->galleryImageWidth = "{$prefix}image_width";
		$this->galleryImageHeight = "{$prefix}image_height";
		$this->galleryParentPage = "{$prefix}parent_page";
		$this->pluginService = new PluginService();
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		$this->pluginService->addAction(Action::ADMIN_MENU, function() : void {
			$title = $this->translationService->getTranslatedString(TranslationString::Settings);
			$this->addSubmenuPage(PostType::Gallery->value, $title, UserRole::Administrator->value, $this->settingsPage, function() : void {
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
	 * Get the parent page path
	 * 
	 * @return string the parent page path
	 */
	public function getParentPagePath() : string {
		return $this->settings[$this->galleryParentPage] ?? '/';
	}

	/**
	 * Use the admin_init action to register the setting inputs
	 */
	private function registerSettingInputs() : void {
		$this->pluginService->addAction(Action::ADMIN_INIT, function() : void {
			$sectionID = "{$this->settingsPage}-section-gallery";
			$prefix = "{$this->settingsPage}galleryImage";
			$this->addSettingsSection($sectionID, $this->settingsPage);
			$this->addSettingsField("{$prefix}Width", $this->translationService->getTranslatedString(TranslationString::ImageWidth), $this->settingsPage, $sectionID, [
				'type' => 'number',
				'name' => "{$this->settingsName}[{$this->galleryImageWidth}]",
				'value' => $this->getGalleryImageWidth(),
				'min' => '1'
			]);
			$this->addSettingsField("{$prefix}Height", $this->translationService->getTranslatedString(TranslationString::ImageHeight), $this->settingsPage, $sectionID, [
				'type' => 'number',
				'name' => "{$this->settingsName}[{$this->galleryImageHeight}]",
				'value' => $this->getGalleryImageHeight(),
				'min' => '1'
			]);
			$dataManager = new DataManager(new PostTypeService(), new SecurityService(), new ImageService());
			$pages = $dataManager->getPages();
			$this->addSettingsField("{$this->settingsPage}parentPage", $this->translationService->getTranslatedString(TranslationString::ParentPage), $this->settingsPage, $sectionID, [
				'type' => 'select',
				'name' => "{$this->settingsName}[{$this->galleryParentPage}]",
				'options' => $pages,
				'selected' => $this->getParentPagePath()
			]);
			$this->registerSetting($this->settingsName);
		});
	}

	/**
	 * Use the init action to add an image size
	 */
	private function addImageSize() : void {
		$this->pluginService->addAction(Action::INIT, function() : void {
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
		return $this->settings[$this->galleryImageWidth] ?? 1;
	}

	/**
	 * Get the gallery image height
	 * 
	 * @return int the gallery image height
	 */
	private function getGalleryImageHeight() : int {
		return $this->settings[$this->galleryImageHeight] ?? 1;
	}
}