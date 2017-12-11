<?php
/**
 * The TranslationStrings class contains methods to get translation strings
 */
class TranslationStrings {

    const FRONT_PAGE = 'Front page';
    const YOU_ARE_HERE = 'You are here:';
    const SEARCH = 'Search';
    const SEARCH_RESULTS = 'Search results';
    const NO_RESULTS = 'Your search returned no results';

    /**
     * TranslationStrings constructor
     */
    public function __construct() {
        $context = 'Theme';
        pll_register_string('Breadcrumb title', self::YOU_ARE_HERE, $context);
        pll_register_string(self::FRONT_PAGE, self::FRONT_PAGE, $context);
        pll_register_string(self::SEARCH, self::SEARCH, $context);
        pll_register_string(self::SEARCH_RESULTS, self::SEARCH_RESULTS, $context);
        pll_register_string('No results', self::NO_RESULTS, $context);
    }

    /**
     * Get the front page text
     *
     * @return string the front page text
     */
    public static function getFrontPageText() : string {
        return pll__(self::FRONT_PAGE);
    }

    /**
     * Get the You are here text
     *
     * @return string the You are here text
     */
    public static function getYouAreHereText() : string {
        return pll__(self::YOU_ARE_HERE);
    }

    /**
     * Get the search text
     *
     * @return string the search text
     */
    public static function getSearchText() : string {
        return pll__(self::SEARCH);
    }

    /**
     * Get the search results text
     *
     * @return string the search results text
     */
    public static function getSearchResultsText() : string {
        return pll__(self::SEARCH_RESULTS);
    }

    /**
     * Get the no results text
     *
     * @return string the no results text
     */
    public static function getNoResultsText() : string {
        return pll__(self::NO_RESULTS);
    }
}