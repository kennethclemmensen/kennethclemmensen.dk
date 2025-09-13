<?php
/**
 * The ContactSettings class contains functionality to handle the contact settings
 */
final class ContactSettings extends BaseSettings {
	
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
		parent::__construct('kc-theme-settings', 'kc-theme-settings-contact-options');
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
		return $this->settingsPage;
	}

	/**
	 * Show the fields
	 */
	public function showFields() : void {
		settings_fields($this->settingsName);
		do_settings_sections($this->settingsPage);
	}

	/**
	 * Create the fields
	 */
	public function createFields() : void {
		$sectionID = $this->settingsPage.'-section-contact';
		$prefix = $this->settingsPage;
		add_settings_section($sectionID, '', function() {}, $this->settingsPage);
		add_settings_field($prefix.'email', $this->translationStrings->getTranslatedString(TranslationStrings::EMAIL), function() : void {
			echo '<input type="email" name="'.$this->settingsName.'['.$this->email.']" value="'.$this->getEmail().'" class="regular-text" required> ';
			echo '['.$this->emailShortcode.']';
		}, $this->settingsPage, $sectionID);
		add_settings_field($prefix.'linkedin', $this->translationStrings->getTranslatedString(TranslationStrings::LINKEDIN), function() : void {
			echo '<input type="url" name="'.$this->settingsName.'['.$this->linkedIn.']" value="'.$this->getLinkedInUrl().'" class="regular-text" required> ';
			echo '['.$this->linkedInShortcode.']';
		}, $this->settingsPage, $sectionID);
		add_settings_field($prefix.'github', $this->translationStrings->getTranslatedString(TranslationStrings::GITHUB), function() : void {
			echo '<input type="url" name="'.$this->settingsName.'['.$this->gitHub.']" value="'.$this->getGitHubUrl().'" class="regular-text" required> ';
			echo '['.$this->gitHubShortcode.']';
		}, $this->settingsPage, $sectionID);
		$this->registerSetting($this->settingsName);
	}

	/**
	 * Get the email
	 *
	 * @return string the email
	 */
	private function getEmail() : string {
		$string = $this->settings[$this->email] ?? '';
		return stripslashes($string);
	}

	/**
	 * Get the LinkedIn url
	 *
	 * @return string the LinkedIn url
	 */
	private function getLinkedInUrl() : string {
		$url = $this->settings[$this->linkedIn] ?? '';
		return esc_url($url);
	}

	/**
	 * Get the GitHub url
	 *
	 * @return string the GitHub url
	 */
	private function getGitHubUrl() : string {
		$url = $this->settings[$this->gitHub] ?? '';
		return esc_url($url);
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