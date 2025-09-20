<?php
/**
 * The TranslationStrings class contains methods to get translation strings
 */
final class TranslationStrings {

	public const string ADD_AN_ICON = 'Add an icon';
	public const string ANIMATION = 'Animation';
	public const string CONTACT = 'Contact';
	public const string DELAY = 'Delay';
	public const string DURATION = 'Duration';
	public const string EMAIL = 'Email';
	public const string FADE = 'Fade';
	public const string FOOTER = 'Footer';
	public const string FRONT_PAGE = 'Front page';
	public const string GALLERY = 'Gallery';
	public const string GITHUB = 'GitHub';
	public const string HEADER = 'Header';
	public const string ICON = 'Icon';
	public const string IMAGE = 'Image';
	public const string IMAGES_PER_PAGE = 'Images per page';
	public const string LINK = 'Link';
	public const string LINKEDIN = 'LinkedIn';
	public const string MOBILE_MENU = 'Mobile menu';
	public const string NEXT = 'Next';
	public const string NO_RESULTS = 'Your search returned no results';
	public const string NUMBER_OF_DOWNLOADS = 'Number of downloads:';
	public const string OF = 'of';
	public const string OPEN_IN_A_NEW_TAB = 'Open in a new tab';
	public const string PREVIOUS = 'Previous';
	public const string REMOVE_VERSION_QUERY_STRING = 'Remove version query string';
	public const string SCRIPTS = 'Scripts';
	public const string SEARCH = 'Search';
	public const string SEARCH_RESULTS = 'Search results';
	public const string SETTINGS = 'Settings';
	public const string SLIDER = 'Slider';
	public const string SLIDE_DOWN = 'Slide down';
	public const string SLIDE_LEFT = 'Slide left';
	public const string SLIDE_RIGHT = 'Slide right';
	public const string SLIDE_UP = 'Slide up';
	public const string START_BODY = 'Start body';
	public const string TITLE = 'Title';
	public const string YOU_ARE_HERE = 'You are here:';

	/**
	 * The TranslationStrings constructor register the strings that should be translated
	 */
	public function __construct() {
		if($this->isPolylangActivated()) {
			$context = 'Theme';
			pll_register_string(self::ADD_AN_ICON, self::ADD_AN_ICON, $context);
			pll_register_string(self::ANIMATION, self::ANIMATION, $context);
			pll_register_string(self::CONTACT, self::CONTACT, $context);
			pll_register_string(self::DELAY, self::DELAY, $context);
			pll_register_string(self::DURATION, self::DURATION, $context);
			pll_register_string(self::EMAIL, self::EMAIL, $context);
			pll_register_string(self::FADE, self::FADE, $context);
			pll_register_string(self::FOOTER, self::FOOTER, $context);
			pll_register_string(self::FRONT_PAGE, self::FRONT_PAGE, $context);
			pll_register_string(self::GALLERY, self::GALLERY, $context);
			pll_register_string(self::GITHUB, self::GITHUB, $context);
			pll_register_string(self::HEADER, self::HEADER, $context);
			pll_register_string(self::ICON, self::ICON, $context);
			pll_register_string(self::IMAGE, self::IMAGE, $context);
			pll_register_string(self::IMAGES_PER_PAGE, self::IMAGES_PER_PAGE, $context);
			pll_register_string(self::LINK, self::LINK, $context);
			pll_register_string(self::LINKEDIN, self::LINKEDIN, $context);
			pll_register_string(self::MOBILE_MENU, self::MOBILE_MENU, $context);
			pll_register_string(self::NEXT, self::NEXT, $context);
			pll_register_string(self::NO_RESULTS, self::NO_RESULTS, $context);
			pll_register_string(self::NUMBER_OF_DOWNLOADS, self::NUMBER_OF_DOWNLOADS, $context);
			pll_register_string(self::OF, self::OF, $context);
			pll_register_string(self::OPEN_IN_A_NEW_TAB, self::OPEN_IN_A_NEW_TAB, $context);
			pll_register_string(self::PREVIOUS, self::PREVIOUS, $context);
			pll_register_string(self::REMOVE_VERSION_QUERY_STRING, self::REMOVE_VERSION_QUERY_STRING, $context);
			pll_register_string(self::SCRIPTS, self::SCRIPTS, $context);
			pll_register_string(self::SEARCH, self::SEARCH, $context);
			pll_register_string(self::SEARCH_RESULTS, self::SEARCH_RESULTS, $context);
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
	 * Get a translated string
	 * 
	 * @param string $str the string to translate
	 * @return string the translated string
	 */
	public function getTranslatedString(string $str) : string {
		return ($this->isPolylangActivated()) ? pll__($str) : $str;
	}

	/**
	 * Check if the Polylang plugin is activated
	 *
	 * @return bool true if the Polylang plugin is activated. False if it isn't activated
	 */
	private function isPolylangActivated() : bool {
		return function_exists('pll_register_string');
	}
}