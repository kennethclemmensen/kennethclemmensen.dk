<?php
/**
 * The ScriptSettings class contains functionality to handle the script settings
 */
final class ScriptSettings extends BaseSettings {

	private readonly string $scriptPageSlug;
	private readonly string $scriptOptionsName;
	private readonly array | bool $scriptOptions;
	private readonly string $scriptHeader;
	private readonly string $scriptStartBody;
	private readonly string $scriptFooter;
	private readonly string $removeVersionQueryString;

	/**
	 * ScriptSettings constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->scriptPageSlug = $this->prefix.'scripts';
		$this->scriptOptionsName = $this->scriptPageSlug.$this->postfix;
		$this->scriptOptions = get_option($this->scriptOptionsName);
		$prefix = 'scripts_';
		$this->scriptHeader = $prefix.'header';
		$this->scriptStartBody = $prefix.'start_body';
		$this->scriptFooter = $prefix.'footer';
		$this->removeVersionQueryString = $prefix.'remove_version_query_string';
	}

	/**
	 * Show the fields
	 */
	public function showFields() : void {
		settings_fields($this->scriptOptionsName);
		do_settings_sections($this->scriptPageSlug);
	}

	/**
	 * Create the fields
	 */
	public function createFields() : void {
		$sectionID = $this->scriptPageSlug.'-section-scripts';
		$prefix = $this->scriptPageSlug;
		add_settings_section($sectionID, '', function() {}, $this->scriptPageSlug);
		add_settings_field($prefix.'header', $this->translationStrings->getTranslatedString(TranslationStrings::HEADER), function() : void {
			echo '<textarea name="'.$this->scriptOptionsName.'['.$this->scriptHeader.']" cols="80" rows="10">'.$this->getHeaderScripts().'</textarea>';
		}, $this->scriptPageSlug, $sectionID);
		add_settings_field($prefix.'start-body', $this->translationStrings->getTranslatedString(TranslationStrings::START_BODY), function() : void {
			echo '<textarea name="'.$this->scriptOptionsName.'['.$this->scriptStartBody.']" cols="80" rows="10">'.$this->getStartBodyScripts().'</textarea>';
		}, $this->scriptPageSlug, $sectionID);
		add_settings_field($prefix.'footer', $this->translationStrings->getTranslatedString(TranslationStrings::FOOTER), function() : void {
			echo '<textarea name="'.$this->scriptOptionsName.'['.$this->scriptFooter.']" cols="80" rows="10">'.$this->getFooterScripts().'</textarea>';
		}, $this->scriptPageSlug, $sectionID);
		add_settings_field($prefix.'remove-version-query-string', $this->translationStrings->getTranslatedString(TranslationStrings::REMOVE_VERSION_QUERY_STRING), function() : void {
			$checked = (isset($this->scriptOptions[$this->removeVersionQueryString])) ? $this->scriptOptions[$this->removeVersionQueryString] : '';
			echo '<input type="checkbox" name="'.$this->scriptOptionsName.'['.$this->removeVersionQueryString.']" '.checked($checked, $this->checkboxCheckedValue, false).' >';
		}, $this->scriptPageSlug, $sectionID);
		$this->registerSetting($this->scriptOptionsName);
	}

	/**
	 * Get the header scripts
	 *
	 * @return string the header scripts
	 */
	public function getHeaderScripts() : string {
		return ($this->scriptOptions) ? stripslashes($this->scriptOptions[$this->scriptHeader]) : '';
	}

	/**
	 * Get the start body scripts
	 *
	 * @return string the start body scripts
	 */
	public function getStartBodyScripts() : string {
		return ($this->scriptOptions) ? stripslashes($this->scriptOptions[$this->scriptStartBody]) : '';
	}

	/**
	 * Get the footer scripts
	 *
	 * @return string the footer scripts
	 */
	public function getFooterScripts() : string {
		return ($this->scriptOptions) ? stripslashes($this->scriptOptions[$this->scriptFooter]) : '';
	}

	/**
	 * Check whether the version query string must be removed
	 *
	 * @return bool true if the version query string must be removed. False if it must not be removed
	 */
	public function mustVersionQueryStringBeRemoved() : bool {
		return isset($this->scriptOptions[$this->removeVersionQueryString]) && $this->scriptOptions[$this->removeVersionQueryString] === $this->checkboxCheckedValue;
	}
}