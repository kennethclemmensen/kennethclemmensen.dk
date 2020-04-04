<?php
/**
 * The TranslationStrings class contains methods to get translation strings
 */
final class TranslationStrings {

    private const FRONT_PAGE = 'Front page';
    private const YOU_ARE_HERE = 'You are here:';
    private const SEARCH = 'Search';
    private const SEARCH_RESULTS = 'Search results';
    private const NO_RESULTS = 'Your search returned no results';
    private const IMAGE = 'Image';
    private const OF = 'of';
    private const PREVIOUS = 'Previous';
    private const NEXT = 'Next';
    private const NUMBER_OF_DOWNLOADS = 'Number of downloads:';

    /**
     * The TranslationStrings constructor register the strings that should be translated
     */
    public function __construct() {
        if(self::isPolylangActivated()) {
            $context = 'Theme';
            pll_register_string(self::YOU_ARE_HERE, self::YOU_ARE_HERE, $context);
            pll_register_string(self::FRONT_PAGE, self::FRONT_PAGE, $context);
            pll_register_string(self::SEARCH, self::SEARCH, $context);
            pll_register_string(self::SEARCH_RESULTS, self::SEARCH_RESULTS, $context);
            pll_register_string(self::NO_RESULTS, self::NO_RESULTS, $context);
            pll_register_string(self::IMAGE, self::IMAGE, $context);
            pll_register_string(self::OF, self::OF, $context);
            pll_register_string(self::PREVIOUS, self::PREVIOUS, $context);
            pll_register_string(self::NEXT, self::NEXT, $context);
            pll_register_string(self::NUMBER_OF_DOWNLOADS, self::NUMBER_OF_DOWNLOADS, $context);
        }
    }

    /**
     * Get the front page text
     *
     * @return string the front page text
     */
    public static function getFrontPageText() : string {
        return (self::isPolylangActivated()) ? pll__(self::FRONT_PAGE) : self::FRONT_PAGE;
    }

    /**
     * Get the You are here text
     *
     * @return string the You are here text
     */
    public static function getYouAreHereText() : string {
        return (self::isPolylangActivated()) ? pll__(self::YOU_ARE_HERE) : self::YOU_ARE_HERE;
    }

    /**
     * Get the search text
     *
     * @return string the search text
     */
    public static function getSearchText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SEARCH) : self::SEARCH;
    }

    /**
     * Get the search results text
     *
     * @return string the search results text
     */
    public static function getSearchResultsText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SEARCH_RESULTS) : self::SEARCH_RESULTS;
    }

    /**
     * Get the no results text
     *
     * @return string the no results text
     */
    public static function getNoResultsText() : string {
        return (self::isPolylangActivated()) ? pll__(self::NO_RESULTS) : self::NO_RESULTS;
    }

    /**
     * Get the image text
     *
     * @return string the image text
     */
    public static function getImageText() : string {
        return (self::isPolylangActivated()) ? pll__(self::IMAGE) : self::IMAGE;
    }

    /**
     * Get the of text
     *
     * @return string the of text
     */
    public static function getOfText() : string {
        return (self::isPolylangActivated()) ? pll__(self::OF) : self::OF;
    }

    /**
     * Get the previous text
     *
     * @return string the previous text
     */
    public static function getPreviousText() : string {
        return (self::isPolylangActivated()) ? pll__(self::PREVIOUS) : self::PREVIOUS;
    }

    /**
     * Get the next text
     *
     * @return string the next text
     */
    public static function getNextText() : string {
        return (self::isPolylangActivated()) ? pll__(self::NEXT) : self::NEXT;
    }

    /**
     * Get the number of downloads text
     * 
     * @return string the number of downloads text
     */
    public static function getNumberOfDownloadsText() : string {
        return (self::isPolylangActivated()) ? pll__(self::NUMBER_OF_DOWNLOADS) : self::NUMBER_OF_DOWNLOADS;
    }

    /**
     * Check if the Polylang plugin is activated
     *
     * @return bool true if the Polylang plugin is activated. False if it isn't activated
     */
    private static function isPolylangActivated() : bool {
        return function_exists('pll_register_string');
    }
}