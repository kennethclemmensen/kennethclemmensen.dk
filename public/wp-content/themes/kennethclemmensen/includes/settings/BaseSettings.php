<?php
/**
 * The BaseSettings class contains basic functionality to handle settings
 */
class BaseSettings {

	protected readonly string $settingsPage;
	protected readonly string $settingsName;
	protected readonly array | bool $settings;
	protected readonly TranslationStrings $translationStrings;
	protected readonly string $checkboxCheckedValue;

	/**
	 * BaseSettings constructor
	 * 
	 * @param string $settingsPage the settings page
	 * @param string $settingsName the settings name
	 */
	protected function __construct(string $settingsPage, string $settingsName) {
		$this->settingsPage = $settingsPage;
		$this->settingsName = $settingsName;
		$this->settings = get_option($this->settingsName);
		$this->translationStrings = new TranslationStrings();
		$this->checkboxCheckedValue = 'on';
	}

	/**
	 * Register a setting with a name
	 * 
	 * @param string $name the name of the setting
	 */
	protected function registerSetting(string $name) : void {
		register_setting($name, $name, [
			'sanitize_callback' => function(array $input) : array {
				return $this->validateSettingInputs($input);
			}
		]);
	}

	/**
	 * Validate the setting inputs
	 *
	 * @param array $inputs the inputs to validate
	 * @return array the validated inputs
	 */
	private function validateSettingInputs(array $inputs) : array {
		$output = [];
		foreach($inputs as $key => $value) {
			$output[$key] = strip_tags(addslashes($inputs[$key]), '<script>');
		}
		return $output;
	}
}