<?php
namespace KC\Core\Security;

/**
 * The SecurityService class contains security methods.
 * The class cannot be inherited.
 */
final class SecurityService {

	/**
	 * Escape an url
	 * 
	 * @param string $url the url to escape
	 * @return string the escaped url
	 */
	public function escapeUrl(string $url) : string {
		return esc_url($url);
	}

	/**
	 * Check if the user has access to the Api
	 * 
	 * @return bool true if the user has access to the Api. False if the user doesn't has access to the Api
	 */
	public function hasApiAccess() : bool {
		return !is_user_logged_in();
	}

	/**
	 * Sanitize a string
	 * 
	 * @param string $str the string to sanitize
	 * @return string the sanitized string
	 */
	public function sanitizeString(string $str) : string {
		return sanitize_text_field($str);
	}

	/**
	 * Check if the value is valid
	 * 
	 * @param string $value the value to check
	 * @return bool true if the value is valid. False if the value isn't valid
	 */
	public function isValid(string $value) : bool {
		return !empty($value);
	}

	/**
	 * Validate setting inputs
	 *
	 * @param array $inputs the inputs to validate
	 * @return array the validated inputs
	 */
	public function validateSettingInputs(array $inputs) : array {
		$output = [];
		foreach($inputs as $key => $value) {
			$output[$key] = strip_tags(addslashes($inputs[$key]));
		}
		return $output;
	}

	/**
	 * Encrypt a message with a nonce and a key
	 * 
	 * @param string $message the message to encrypt
	 * @param string $nonce the nonce
	 * @param string $key the key
	 * @return string the encrypted message
	 */
	public function encryptMessage(string $message, string $nonce, string $key) : string {
		return sodium_crypto_aead_aes256gcm_encrypt($message, '', $nonce, $key);
	}

	/**
	 * Decrypt a message with a nonce and a key
	 * 
	 * @param string $message the message to decrypt
	 * @param string $nonce the nonce
	 * @param string $key the key
	 * @return string the decrypted message
	 */
	public function decryptMessage(string $message, string $nonce, string $key) : string {
		return sodium_crypto_aead_aes256gcm_decrypt($message, '', $nonce, $key);
	}

	/**
	 * Generate an encryption key based on the password
	 * 
	 * @param string $password the password
	 * @return string the encryption key
	 */
	public function generateEncryptionKey(string $password) : string {
		$length = SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES;
		$salt = random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES);
		$opslimit = SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE;
		$memlimit = SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE;
		return sodium_crypto_pwhash($length, $password, $salt, $opslimit, $memlimit);
	}

	/**
	 * Generate a nonce
	 * 
	 * @return string the nonce
	 */
	public function generateNonce() : string {
		return random_bytes(12);
	}

	/**
	 * Generate a password
	 * 
	 * @return string the password
	 */
	public function generatePassword() : string {
		$password = '';
		$passwordLength = 32;
		$letters = 'abcdefghijklmnopqrstuvwxyz';
		$digits = '0123456789';
		$specialCharacters = '!@#$%^&*()_+-={}[]|:;"<>,.?/';
		$characters = $letters.mb_strtoupper($letters).$digits.$specialCharacters;
		for($i = 0; $i < $passwordLength; $i++) {
			$index = random_int(0, mb_strlen($characters) - 1);
			$password .= $characters[$index];
		}
		return $password;
	}
}