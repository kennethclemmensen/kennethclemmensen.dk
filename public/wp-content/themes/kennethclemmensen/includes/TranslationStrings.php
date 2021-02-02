<?php
/**
 * The TranslationStrings class contains methods to get translation strings
 */
final class TranslationStrings {

    private const ANIMATION = 'Animation';
    private const CONTACT = 'Contact';
    private const DELAY = 'Delay';
    private const DURATION = 'Duration';
    private const EMAIL = 'Email';
    private const FADE = 'Fade';
    private const FILES_PER_PAGE = 'Files per page';
    private const FOOTER = 'Footer';
    private const FRONT_PAGE = 'Front page';
    private const GITHUB = 'GitHub';
    private const HEADER = 'Header';
    private const ICON = 'Icon';
    private const IMAGE = 'Image';
    private const IMAGES_PER_PAGE = 'Images per page';
    private const LINK = 'Link';
    private const LINKEDIN = 'LinkedIn';
    private const NEXT = 'Next';
    private const NO_RESULTS = 'Your search returned no results';
    private const NUMBER_OF_DOWNLOADS = 'Number of downloads:';
    private const OF = 'of';
    private const OPEN_IN_A_NEW_TAB = 'Open in a new tab';
    private const OTHER = 'Other';
    private const PREVIOUS = 'Previous';
    private const SCRIPTS = 'Scripts';
    private const SEARCH = 'Search';
    private const SEARCH_RESULTS = 'Search results';
    private const SEARCH_RESULTS_PER_PAGE = 'Search results per page';
    private const SETTINGS = 'Settings';
    private const SLIDER = 'Slider';
    private const SLIDE_DOWN = 'Slide down';
    private const SLIDE_LEFT = 'Slide left';
    private const SLIDE_RIGHT = 'Slide right';
    private const SLIDE_UP = 'Slide up';
    private const START_BODY = 'Start body';
    private const TITLE = 'Title';
    private const YOU_ARE_HERE = 'You are here:';

    /**
     * The TranslationStrings constructor register the strings that should be translated
     */
    public function __construct() {
        if(self::isPolylangActivated()) {
            $context = 'Theme';            
            pll_register_string(self::ANIMATION, self::ANIMATION, $context);
            pll_register_string(self::CONTACT, self::CONTACT, $context);
            pll_register_string(self::DELAY, self::DELAY, $context);
            pll_register_string(self::DURATION, self::DURATION, $context);
            pll_register_string(self::EMAIL, self::EMAIL, $context);
            pll_register_string(self::FADE, self::FADE, $context);
            pll_register_string(self::FILES_PER_PAGE, self::FILES_PER_PAGE, $context);
            pll_register_string(self::FOOTER, self::FOOTER, $context);
            pll_register_string(self::FRONT_PAGE, self::FRONT_PAGE, $context);
            pll_register_string(self::GITHUB, self::GITHUB, $context);
            pll_register_string(self::HEADER, self::HEADER, $context);
            pll_register_string(self::ICON, self::ICON, $context);
            pll_register_string(self::IMAGE, self::IMAGE, $context);
            pll_register_string(self::IMAGES_PER_PAGE, self::IMAGES_PER_PAGE, $context);
            pll_register_string(self::LINK, self::LINK, $context);
            pll_register_string(self::LINKEDIN, self::LINKEDIN, $context);
            pll_register_string(self::NEXT, self::NEXT, $context);
            pll_register_string(self::NO_RESULTS, self::NO_RESULTS, $context);
            pll_register_string(self::NUMBER_OF_DOWNLOADS, self::NUMBER_OF_DOWNLOADS, $context);
            pll_register_string(self::OF, self::OF, $context);
            pll_register_string(self::OPEN_IN_A_NEW_TAB, self::OPEN_IN_A_NEW_TAB, $context);
            pll_register_string(self::OTHER, self::OTHER, $context);
            pll_register_string(self::PREVIOUS, self::PREVIOUS, $context);
            pll_register_string(self::SCRIPTS, self::SCRIPTS, $context);
            pll_register_string(self::SEARCH, self::SEARCH, $context);
            pll_register_string(self::SEARCH_RESULTS, self::SEARCH_RESULTS, $context);
            pll_register_string(self::SEARCH_RESULTS_PER_PAGE, self::SEARCH_RESULTS_PER_PAGE, $context);
            pll_register_string(self::SETTINGS, self::SETTINGS, $context);
            pll_register_string(self::SLIDER, self::SLIDER, $context);
            pll_register_string(self::SLIDE_DOWN, self::SLIDE_DOWN, $context);
            pll_register_string(self::SLIDE_LEFT, self::SLIDE_LEFT, $context);
            pll_register_string(self::SLIDE_RIGHT, self::SLIDE_RIGHT, $context);
            pll_register_string(self::SLIDE_UP, self::SLIDE_UP, $context);
            pll_register_string(self::START_BODY, self::START_BODY, $context);
            pll_register_string(self::TITLE, self::TITLE, $context);
            pll_register_string(self::YOU_ARE_HERE, self::YOU_ARE_HERE, $context);
        }
    }

    /**
     * Get the animation text
     * 
     * @return string the animation text
     */
    public static function getAnimationText() : string {
        return (self::isPolylangActivated()) ? pll__(self::ANIMATION) : self::ANIMATION;
    }

    /**
     * Get the contact text
     * 
     * @return string the contact text
     */
    public static function getContactText() : string {
        return (self::isPolylangActivated()) ? pll__(self::CONTACT) : self::CONTACT;
    }

    /**
     * Get the delay text
     * 
     * @return string the delay text
     */
    public static function getDelayText() : string {
        return (self::isPolylangActivated()) ? pll__(self::DELAY) : self::DELAY;
    }

    /**
     * Get the duration text
     * 
     * @return string the duration text
     */
    public static function getDurationText() : string {
        return (self::isPolylangActivated()) ? pll__(self::DURATION) : self::DURATION;
    }

    /**
     * Get the email text
     * 
     * @return string the email text
     */
    public static function getEmailText() : string {
        return (self::isPolylangActivated()) ? pll__(self::EMAIL) : self::EMAIL;
    }

    /**
     * Get the fade text
     * 
     * @return string the fade text
     */
    public static function getFadeText() : string {
        return (self::isPolylangActivated()) ? pll__(self::FADE) : self::FADE;
    }

    /**
     * Get the files per page text
     * 
     * @return string the files per page text
     */
    public static function getFilesPerPageText() : string {
        return (self::isPolylangActivated()) ? pll__(self::FILES_PER_PAGE) : self::FILES_PER_PAGE;
    }

    /**
     * Get the footer text
     * 
     * @return string the footer text
     */
    public static function getFooterText() : string {
        return (self::isPolylangActivated()) ? pll__(self::FOOTER) : self::FOOTER;
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
     * Get the GitHub text
     * 
     * @return string the GitHub text
     */
    public static function getGitHubText() : string {
        return (self::isPolylangActivated()) ? pll__(self::GITHUB) : self::GITHUB;
    }

    /**
     * Get the header text
     * 
     * @return string the header text
     */
    public static function getHeaderText() : string {
        return (self::isPolylangActivated()) ? pll__(self::HEADER) : self::HEADER;
    }

    /**
     * Get the icon text
     * 
     * @return string the icon text
     */
    public static function getIconText() : string {
        return (self::isPolylangActivated()) ? pll__(self::ICON) : self::ICON;
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
     * Get the images per page text
     * 
     * @return string the images per page text
     */
    public static function getImagesPerPageText() : string {
        return (self::isPolylangActivated()) ? pll__(self::IMAGES_PER_PAGE) : self::IMAGES_PER_PAGE;
    }

    /**
     * Get the link text
     * 
     * @return string the link text
     */
    public static function getLinkText() : string {
        return (self::isPolylangActivated()) ? pll__(self::LINK) : self::LINK;
    }

    /**
     * Get the LinkedIn text
     * 
     * @return string the LinkedIn text
     */
    public static function getLinkedInText() : string {
        return (self::isPolylangActivated()) ? pll__(self::LINKEDIN) : self::LINKEDIN;
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
     * Get the no results text
     *
     * @return string the no results text
     */
    public static function getNoResultsText() : string {
        return (self::isPolylangActivated()) ? pll__(self::NO_RESULTS) : self::NO_RESULTS;
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
     * Get the of text
     *
     * @return string the of text
     */
    public static function getOfText() : string {
        return (self::isPolylangActivated()) ? pll__(self::OF) : self::OF;
    }

    /**
     * Get the open in a new tab text
     * 
     * @return string the open in a new tab text
     */
    public static function getOpenInANewTabText() : string {
        return (self::isPolylangActivated()) ? pll__(self::OPEN_IN_A_NEW_TAB) : self::OPEN_IN_A_NEW_TAB;
    }

    /**
     * Get the other text
     * 
     * @return string the other text
     */
    public static function getOtherText() : string {
        return (self::isPolylangActivated()) ? pll__(self::OTHER) : self::OTHER;
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
     * Get the scripts text
     * 
     * @return string the scripts text
     */
    public static function getScriptsText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SCRIPTS) : self::SCRIPTS;
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
     * Get the search results per page text
     * 
     * @return string the search results per page text
     */
    public static function getSearchResultsPerPageText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SEARCH_RESULTS_PER_PAGE) : self::SEARCH_RESULTS_PER_PAGE;
    }

    /**
     * Get the settings text
     * 
     * @return string the settings text
     */
    public static function getSettingsText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SETTINGS) : self::SETTINGS;
    }

    /**
     * Get the slider text
     * 
     * @return string the slider text
     */
    public static function getSliderText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SLIDER) : self::SLIDER;
    }

    /**
     * Get the slide down text
     * 
     * @return string the slide down text
     */
    public static function getSlideDownText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SLIDE_DOWN) : self::SLIDE_DOWN;
    }

    /**
     * Get the slide left text
     * 
     * @return string the slide left text
     */
    public static function getSlideLeftText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SLIDE_LEFT) : self::SLIDE_LEFT;
    }

    /**
     * Get the slide right text
     * 
     * @return string the slide right text
     */
    public static function getSlideRightText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SLIDE_RIGHT) : self::SLIDE_RIGHT;
    }

    /**
     * Get the slide up text
     * 
     * @return string the slide up text
     */
    public static function getSlideUpText() : string {
        return (self::isPolylangActivated()) ? pll__(self::SLIDE_UP) : self::SLIDE_UP;
    }

    /**
     * Get the start body text
     * 
     * @return string the start body text
     */
    public static function getStartBodyText() : string {
        return (self::isPolylangActivated()) ? pll__(self::START_BODY) : self::START_BODY;
    }

    /**
     * Get the title text
     * 
     * @return string the title text
     */
    public static function getTitleText() : string {
        return (self::isPolylangActivated()) ? pll__(self::TITLE) : self::TITLE;
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
     * Check if the Polylang plugin is activated
     *
     * @return bool true if the Polylang plugin is activated. False if it isn't activated
     */
    private static function isPolylangActivated() : bool {
        return function_exists('pll_register_string');
    }
}