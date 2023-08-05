<?php
/**
 * The ContactSettings class contains functionality to handle the contact settings
 */
final class ContactSettings extends BaseSettings {
	
	private readonly string $contactPageSlug;
	private readonly string $contactOptionsName;
	private readonly array | bool $contactOptions;
	private readonly string $email;
	private readonly string $linkedIn;
	private readonly string $gitHub;
	private readonly string $emailShortcode;
	private readonly string $linkedInShortcode;
	private readonly string $gitHubShortcode;

	/**
	 * ContactSettings constructor
	 */
	public function __construct() {
		parent::__construct();
		$prefix = $this->prefix;
		$this->contactPageSlug = substr($prefix, 0, strlen($prefix) - 1);
		$this->contactOptionsName = $prefix.'contact'.$this->postfix;
		$this->contactOptions = get_option($this->contactOptionsName);
		$this->email = 'email';
		$this->linkedIn = 'linkedin';
		$this->gitHub = 'github';
		$prefix = 'kc-';
		$this->emailShortcode = $prefix.$this->email;
		$this->linkedInShortcode = $prefix.$this->linkedIn;
		$this->gitHubShortcode = $prefix.$this->gitHub;
		$this->addShortcodes();
	}

	/**
	 * Get the contact page slug
	 * 
	 * @return string the contact page slug
	 */
	public function getContactPageSlug() : string {
		return $this->contactPageSlug;
	}

	/**
	 * Show the fields
	 */
	public function showFields() : void {
		settings_fields($this->contactOptionsName);
		do_settings_sections($this->contactPageSlug);
	}

	/**
	 * Create the fields
	 */
	public function createFields() : void {
		$sectionID = $this->contactPageSlug.'-section-contact';
		$prefix = $this->contactPageSlug;
		add_settings_section($sectionID, '', function() {}, $this->contactPageSlug);
		add_settings_field($prefix.'email', $this->translationStrings->getTranslatedString(TranslationStrings::EMAIL), function() : void {
			echo '<input type="email" name="'.$this->contactOptionsName.'['.$this->email.']" value="'.$this->getEmail().'" class="regular-text" required> ';
			echo '['.$this->emailShortcode.']';
		}, $this->contactPageSlug, $sectionID);
		add_settings_field($prefix.'linkedin', $this->translationStrings->getTranslatedString(TranslationStrings::LINKEDIN), function() : void {
			echo '<input type="url" name="'.$this->contactOptionsName.'['.$this->linkedIn.']" value="'.$this->getLinkedInUrl().'" class="regular-text" required> ';
			echo '['.$this->linkedInShortcode.']';
		}, $this->contactPageSlug, $sectionID);
		add_settings_field($prefix.'github', $this->translationStrings->getTranslatedString(TranslationStrings::GITHUB), function() : void {
			echo '<input type="url" name="'.$this->contactOptionsName.'['.$this->gitHub.']" value="'.$this->getGitHubUrl().'" class="regular-text" required> ';
			echo '['.$this->gitHubShortcode.']';
		}, $this->contactPageSlug, $sectionID);
		$this->registerSetting($this->contactOptionsName);
	}

	/**
	 * Get the email
	 *
	 * @return string the email
	 */
	private function getEmail() : string {
		return stripslashes($this->contactOptions[$this->email]);
	}

	/**
	 * Get the LinkedIn url
	 *
	 * @return string the LinkedIn url
	 */
	private function getLinkedInUrl() : string {
		return esc_url($this->contactOptions[$this->linkedIn]);
	}

	/**
	 * Get the GitHub url
	 *
	 * @return string the GitHub url
	 */
	private function getGitHubUrl() : string {
		return esc_url($this->contactOptions[$this->gitHub]);
	}

	/**
	 * Add shortcodes
	 */
	private function addShortcodes() : void {
		add_shortcode($this->emailShortcode, function() : string {
			return $this->getEmail();
		});
		add_shortcode($this->linkedInShortcode, function() : string {
			return $this->getLinkedInUrl();
		});
		add_shortcode($this->gitHubShortcode, function() : string {
			return $this->getGitHubUrl();
		});
	}
}