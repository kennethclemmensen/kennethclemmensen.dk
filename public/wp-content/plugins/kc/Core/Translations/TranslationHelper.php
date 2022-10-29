<?php
namespace KC\Core\Translations;

/**
 * The TranslationHelper class contains translation methods
 */
final class TranslationHelper {

	/**
	 * Get a translated string
	 * 
	 * @param TranslationString $str the string to translate
	 * @return string the translated string
	 */
	public static function getTranslatedString(TranslationString $str) : string {
		return __($str->value, 'kc');
	}
}