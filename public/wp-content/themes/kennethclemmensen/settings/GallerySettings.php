<?php
/**
 * The GallerySettings class contains functionality to handle the gallery settings
 */
final class GallerySettings extends BaseSettings {

	private readonly string $imagesPerPage;

	/**
	 * GallerySettings constructor
	 */
	public function __construct() {
		parent::__construct('kc-theme-settings-gallery', 'kc-theme-settings-gallery-options');
		$this->imagesPerPage = 'images_per_page';
	}

	/**
	 * Show the fields
	 */
	public function showFields() : void {
		settings_fields($this->settingsName);
		do_settings_sections($this->settingsPage);
	}

	/**
	 * Create the fields
	 */
	public function createFields() : void {
		$sectionID = $this->settingsPage.'-section-gallery';
		$prefix = $this->settingsPage;
		add_settings_section($sectionID, '', function() {}, $this->settingsPage);
		add_settings_field($prefix.'images-per-page', $this->translationStrings->getTranslatedString(TranslationStrings::IMAGES_PER_PAGE), function() : void {
			echo '<input type="number" name="'.$this->settingsName.'['.$this->imagesPerPage.']" value="'.$this->getImagesPerPage().'" min="1" max="50">';
		}, $this->settingsPage, $sectionID);
		$this->registerSetting($this->settingsName);
	}

	/**
	 * Get the number of images per page
	 *
	 * @return int the number of images per page
	 */
	public function getImagesPerPage() : int {
		$defaultImagesPerPage = 10;
		$value = $this->settings[$this->imagesPerPage] ?? $defaultImagesPerPage;
		return intval($value);
	}
}