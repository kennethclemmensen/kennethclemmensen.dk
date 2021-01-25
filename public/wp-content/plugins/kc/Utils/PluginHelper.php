<?php
namespace KC\Utils;

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
    public static function getImageUrl(int $imageID, string $size = 'post-thumbnail') : string {
        return Security::escapeUrl(get_the_post_thumbnail_url($imageID, $size));
    }

    /**
     * Get a field value from a post
     * 
     * @param string $fieldId the id of the field
     * @param int $postId the id of the post
     * @return string the field value
     */
    public static function getFieldValue(string $fieldId, int $postId) : string {
        return get_post_meta($postId, $fieldId, true);
    }

    /**
     * Set a field value on a post
     * 
     * @param string $value the value
     * @param string $fieldId the id of the field
     * @param int $postId the id of the post
     */
    public static function setFieldValue(string $value, string $fieldId, int $postId) : void {
        update_post_meta($postId, $fieldId, $value);
    }

    /**
     * Get a translated string
     * 
     * @param string $str the string to translate
     * @return string the translated string
     */
    public static function getTranslatedString(string $str) : string {
        return __($str);
    }
}