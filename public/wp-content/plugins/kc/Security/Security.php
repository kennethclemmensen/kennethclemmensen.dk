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
}