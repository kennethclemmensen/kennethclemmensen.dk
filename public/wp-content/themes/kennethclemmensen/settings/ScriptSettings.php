<?php
/**
 * The ScriptSettings class contains functionality to handle the script settings
 */
final class ScriptSettings extends BaseSettings {

	private readonly string $scriptHeader;
	private readonly string $scriptStartBody;
	private readonly string $scriptFooter;
	private readonly string $removeVersionQueryString;

	/**
	 * ScriptSettings constructor
	 */
	public function __construct() {
		parent::__construct('kc-theme-settings-scripts', 'kc-theme-settings-scripts-options');
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
		settings_fields($this->settingsName);
		do_settings_sections($this->settingsPage);
	}

	/**
	 * Create the fields
	 */
	public function createFields() : void {
		$sectionID = $this->settingsPage.'-section-scripts';
		$prefix = $this->settingsPage;
		add_settings_section($sectionID, '', function() {}, $this->settingsPage);
		add_settings_field($prefix.'header', $this->translationStrings->getTranslatedString(TranslationStrings::HEADER), function() : void {
			echo '<textarea name="'.$this->settingsName.'['.$this->scriptHeader.']" cols="80" rows="10">'.$this->getHeaderScripts().'</textarea>';
		}, $this->settingsPage, $sectionID);
		add_settings_field($prefix.'start-body', $this->translationStrings->getTranslatedString(TranslationStrings::START_BODY), function() : void {
			echo '<textarea name="'.$this->settingsName.'['.$this->scriptStartBody.']" cols="80" rows="10">'.$this->getStartBodyScripts().'</textarea>';
		}, $this->settingsPage, $sectionID);
		add_settings_field($prefix.'footer', $this->translationStrings->getTranslatedString(TranslationStrings::FOOTER), function() : void {
			echo '<textarea name="'.$this->settingsName.'['.$this->scriptFooter.']" cols="80" rows="10">'.$this->getFooterScripts().'</textarea>';
		}, $this->settingsPage, $sectionID);
		add_settings_field($prefix.'remove-version-query-string', $this->translationStrings->getTranslatedString(TranslationStrings::REMOVE_VERSION_QUERY_STRING), function() : void {
			$checked = (isset($this->settings[$this->removeVersionQueryString])) ? $this->settings[$this->removeVersionQueryString] : '';
			echo '<input type="checkbox" name="'.$this->settingsName.'['.$this->removeVersionQueryString.']" '.checked($checked, $this->checkboxCheckedValue, false).' >';
		}, $this->settingsPage, $sectionID);
		$this->registerSetting($this->settingsName);
	}

	/**
	 * Get the header scripts
	 *
	 * @return string the header scripts
	 */
	public function getHeaderScripts() : string {
		$string = $this->settings[$this->scriptHeader] ?? '';
		return stripslashes($string);
	}

	/**
	 * Get the start body scripts
	 *
	 * @return string the start body scripts
	 */
	public function getStartBodyScripts() : string {
		$string = $this->settings[$this->scriptStartBody] ?? '';
		return stripslashes($string);
	}

	/**
	 * Get the footer scripts
	 *
	 * @return string the footer scripts
	 */
	public function getFooterScripts() : string {
		$string = $this->settings[$this->scriptFooter] ?? '';
		return stripslashes($string);
	}

	/**
	 * Check whether the version query string must be removed
	 *
	 * @return bool true if the version query string must be removed. False if it must not be removed
	 */
	public function mustVersionQueryStringBeRemoved() : bool {
		return isset($this->settings[$this->removeVersionQueryString]) && $this->settings[$this->removeVersionQueryString] === $this->checkboxCheckedValue;
	}
}