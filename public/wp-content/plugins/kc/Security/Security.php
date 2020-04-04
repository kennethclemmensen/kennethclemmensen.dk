<?php
namespace KC\Security;

class Security {

    /**
     * Validate the inputs
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