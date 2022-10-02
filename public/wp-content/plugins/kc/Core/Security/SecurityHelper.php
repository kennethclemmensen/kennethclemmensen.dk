<?php
namespace KC\Core\Security;

/**
 * The SecurityHelper class contains security methods
 */
class SecurityHelper {

	/**
	 * Escape an url
	 * 
	 * @param string $url the url to escape
	 * @return string the escaped url
	 */
	public static function escapeUrl(string $url) : string {
		return esc_url($url);
	}

	/**
	 * Check if the user has access to the Api
	 * 
	 * @return bool true if the user has access to the Api. False if the user doesn't has access to the Api
	 */
	public static function hasApiAccess() : bool {
		return !is_user_logged_in();
	}

	/**
	 * Sanitize a string
	 * 
	 * @param string $str the string to sanitize
	 * @return string the sanitized string
	 */
	public static function sanitizeString(string $str) : string {
		return sanitize_text_field($str);
	}

	/**
	 * Check if the value is valid
	 * 
	 * @param string $value the value to check
	 * @return bool true if the value is valid. False if the value isn't valid
	 */
	public static function isValid(string $value) : bool {
		return !empty($value);
	}

	/**
	 * Validate setting inputs
	 *
	 * @param array $inputs the inputs to validate
	 * @return array the validated inputs
	 */
	public static function validateSettingInputs(array $inputs) : array {
		$output = [];
		foreach($inputs as $key => $value) {
			$output[$key] = strip_tags(addslashes($inputs[$key]));
		}
		return $output;
	}
}