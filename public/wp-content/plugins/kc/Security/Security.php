<?php
namespace KC\Security;

/**
 * The Security class contains security methods to use in the plugin
 */
class Security {

    /**
     * Validate the input
     *
     * @param array $input the input to validate
     * @return array the validated input
     */
    public static function validateInput(array $input) : array {
        $validatedInput = [];
        foreach($input as $key => $value) {
            $validatedInput[$key] = strip_tags(addslashes($input[$key]));
        }
        return $validatedInput;
    }

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