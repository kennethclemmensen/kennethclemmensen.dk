<?php
/**
 * The TranslationStrings class contains methods to get translation strings
 */
class TranslationStrings {

    private const FRONT_PAGE = 'Front page';
    private const YOU_ARE_HERE = 'You are here:';
    private const SEARCH = 'Search';
    private const SEARCH_RESULTS = 'Search results';
    private const NO_RESULTS = 'Your search returned no results';
    private const IMAGE = 'Image';
    private const OF = 'of';
    private const EMAIL = 'Email';
    private const LINKEDIN = 'LinkedIn';
    private const GITHUB = 'GitHub';

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
        pll_register_string(self::IMAGE, self::IMAGE, $context);
        pll_register_string(self::OF, self::OF, $context);
        pll_register_string(self::EMAIL, self::EMAIL, $context);
        pll_register_string(self::LINKEDIN, self::LINKEDIN, $context);
        pll_register_string(self::GITHUB, self::GITHUB, $context);
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

    /**
     * Get the image text
     *
     * @return string the image text
     */
    public static function getImageText() : string {
        return pll__(self::IMAGE);
    }

    /**
     * Get the of text
     *
     * @return string the of text
     */
    public static function getOfText() : string {
        return pll__(self::OF);
    }

    /**
     * Get the email text
     *
     * @return string the email text
     */
    public static function getEmailText() : string {
        return pll__(self::EMAIL);
    }

    /**
     * Get the LinkedIn text
     *
     * @return string the LinkedIn text
     */
    public static function getLinkedInText() : string {
        return pll__(self::LINKEDIN);
    }

    /**
     * Get the GitHub text
     *
     * @return string the GitHub text
     */
    public static function getGitHubText() : string {
        return pll__(self::GITHUB);
    }
}