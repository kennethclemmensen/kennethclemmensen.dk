<?php
namespace KC\Security;

/**
 * The Security class contains security methods to use in the plugin
 */
class Security {

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
     * Check if the user has access to the API
     * 
     * @return bool true if the user has access to the API. False if the user doesn't have access to the API
     */
    public static function hasApiAccess() : bool {
        return !is_user_logged_in();
    }
}