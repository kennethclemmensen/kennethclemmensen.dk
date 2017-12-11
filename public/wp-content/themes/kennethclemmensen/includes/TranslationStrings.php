<?php
class TranslationStrings {

    const FRONT_PAGE = 'Front page';
    const YOU_ARE_HERE = 'You are here:';
    const SEARCH = 'Search';

    public function __construct() {
        $context = 'Theme';
        pll_register_string('Breadcrumb title', self::YOU_ARE_HERE, $context);
        pll_register_string(self::FRONT_PAGE, self::FRONT_PAGE, $context);
        pll_register_string(self::SEARCH, self::SEARCH, $context);
    }

    public static function getFrontPageText() : string {
        return pll__(self::FRONT_PAGE);
    }

    public static function getYouAreHereText() : string {
        return pll__(self::YOU_ARE_HERE);
    }

    public static function getSearchText() : string {
        return pll__(self::SEARCH);
    }
}