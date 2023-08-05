<?php
/**
 * The GallerySettings class contains functionality to handle the gallery settings
 */
final class GallerySettings extends BaseSettings {

	private readonly string $galleryPageSlug;
	private readonly string $galleryOptionsName;
	private readonly array | bool $galleryOptions;
	private readonly string $imagesPerPage;

	/**
	 * GallerySettings constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->galleryPageSlug = $this->prefix.'gallery';
		$this->galleryOptionsName = $this->galleryPageSlug.$this->postfix;
		$this->galleryOptions = get_option($this->galleryOptionsName);
		$this->imagesPerPage = 'images_per_page';
	}

	/**
	 * Show the fields
	 */
	public function showFields() : void {
		settings_fields($this->galleryOptionsName);
		do_settings_sections($this->galleryPageSlug);
	}

	/**
	 * Create the fields
	 */
	public function createFields() : void {
		$sectionID = $this->galleryPageSlug.'-section-other';
		$prefix = $this->galleryPageSlug;
		add_settings_section($sectionID, '', function() {}, $this->galleryPageSlug);
		add_settings_field($prefix.'images-per-page', $this->translationStrings->getTranslatedString(TranslationStrings::IMAGES_PER_PAGE), function() : void {
			echo '<input type="number" name="'.$this->galleryOptionsName.'['.$this->imagesPerPage.']" value="'.$this->getImagesPerPage().'" min="1" max="50">';
		}, $this->galleryPageSlug, $sectionID);
		$this->registerSetting($this->galleryOptionsName);
	}

	/**
	 * Get the number of images per page
	 *
	 * @return int the number of images per page
	 */
	public function getImagesPerPage() : int {
		return intval($this->galleryOptions[$this->imagesPerPage]);
	}
}