<?php
namespace KC\Core\Settings;

use KC\Core\Security\SecurityService;

/**
 * The BaseSettings class contains basic functionality to handle settings
 */
abstract class BaseSettings {

	/**
	 * Create a settings page
	 */
	public abstract function createSettingsPage() : void;

	/**
	 * Register a setting with a name
	 * 
	 * @param string $name the name of the setting
	 */
	protected function registerSetting(string $name) : void {
		register_setting($name, $name, [
			'sanitize_callback' => function(array $input) : array {
				return SecurityService::validateSettingInputs($input);
			}
		]);
	}

	/**
	 * Convert a binary string to a hexadecimal string
	 * 
	 * @param string $binaryString the binary string to convert
	 * @return string the hexadecimal string
	 */
	protected function convertToHexadecimal(string $binaryString) : string {
		return bin2hex($binaryString);
	}

	/**
	 * Convert a hexadecimal string to a binary string
	 * 
	 * @param string $hexadecimalString the hexadecimal string to convert
	 * @return string the binary string
	 */
	protected function convertToBinary(string $hexadecimalString) : string {
		return hex2bin($hexadecimalString);
	}
}