<?php
/**
 * The BaseSettings class contains basic functionality to handle settings
 */
class BaseSettings {

	protected readonly TranslationStrings $translationStrings;
	protected readonly string $checkboxCheckedValue;
	protected readonly string $prefix;
	protected readonly string $postfix;

	/**
	 * BaseSettings constructor
	 */
	public function __construct() {
		$this->translationStrings = new TranslationStrings();
		$this->checkboxCheckedValue = 'on';
		$this->prefix = 'kc-theme-settings-';
		$this->postfix = '-options';
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