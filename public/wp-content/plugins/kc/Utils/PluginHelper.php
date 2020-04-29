<?php
namespace KC\Utils;

use KC\Security\Security;

/**
 * The PluginHelper class contains utility methods to use in the plugin
 */
class PluginHelper {

    /**
     * Get the image url
     * 
     * @param int $imageID the id of the image
     * @param string $size the size of the image
     * @return string the image url
     */
    public static function getImageUrl(int $imageID, string $size = 'post-thumbnail') : string {
        return Security::escapeUrl(get_the_post_thumbnail_url($imageID, $size));
    }
}