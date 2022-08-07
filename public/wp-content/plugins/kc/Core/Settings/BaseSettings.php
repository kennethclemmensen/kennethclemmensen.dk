<?php
namespace KC\Core\Settings;

use KC\Security\Security;

/**
 * The BaseSettings class contains basic functionality to handle settings
 */
class BaseSettings {

	/**
	 * Register a setting with a name
	 * 
	 * @param string $name the name of the setting
	 */
	protected function registerSetting(string $name) : void {
		register_setting($name, $name, [
			'sanitize_callback' => function(array $input) : array {
				return Security::validateSettingInputs($input);
			}
		]);
	}
}