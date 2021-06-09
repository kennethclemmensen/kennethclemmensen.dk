<?php
namespace KC\Utils;

use KC\Core\Constant;
use KC\Core\ImageSize;
use KC\Security\Security;

/**
 * The PluginHelper class contains utility methods to use in the plugin
 */
class PluginHelper {

    /**
     * Get an image url
     * 
     * @param int $imageID the id of the image
     * @param string $size the size of the image
     * @return string the image url
     */
    public static function getImageUrl(int $imageID, string $size = ImageSize::POST_THUMBNAIL) : string {
        return Security::escapeUrl(get_the_post_thumbnail_url($imageID, $size));
    }

    /**
     * Get a field value from a post
     * 
     * @param string $fieldName the name of the field
     * @param int $postId the id of the post
     * @return string the field value
     */
    public static function getFieldValue(string $fieldName, int $postId) : string {
        return get_post_meta($postId, $fieldName, true);
    }

    /**
     * Set a field value on a post
     * 
     * @param mixed $value the value
     * @param string $fieldName the name of the field
     * @param int $postId the id of the post
     */
    public static function setFieldValue(string | int $value, string $fieldName, int $postId) : void {
        update_post_meta($postId, $fieldName, $value);
    }

    /**
     * Get a translated string
     * 
     * @param string $str the string to translate
     * @return string the translated string
     */
    public static function getTranslatedString(string $str) : string {
        return __($str, Constant::TEXT_DOMAIN);
    }

    /**
     * Append a slash to a string
     * 
     * @param string $str the string to append the slash to
     */
    public static function appendSlash(string &$str) : void {
        $str .= '/';
    }

    /**
     * Remove the last character from a string
     * 
     * @param string $str the string to remove the last character from
     * @return string the string without the last character
     */
    public static function removeLastCharacter(string $str) : string {
        return (strlen($str) <= 1) ? $str : substr_replace($str, '', -1);
    }
}