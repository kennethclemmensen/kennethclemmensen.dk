<?php
/**
 * The MobileMenuSettings class contains functionality to handle the mobile menu settings
 */
final class MobileMenuSettings extends BaseSettings {

	private readonly string $mobileMenuAnimation;

	/**
	 * MobileMenuSettings constructor.
	 */
	public function __construct() {
		parent::__construct('kc-theme-settings-mobile-menu', 'mobile-menu-options');
		$this->mobileMenuAnimation = 'mobile_menu_animation';
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
		$sectionID = $this->settingsPage.'-section-mobile-menu';
		$prefix = $this->settingsPage;
		add_settings_section($sectionID, '', function() {}, $this->settingsPage);
		add_settings_field($prefix.'mobileMenuAnimation', $this->translationStrings->getTranslatedString(TranslationStrings::ANIMATION), function() : void {
			?>
			<select name="<?php echo $this->settingsName.'['.$this->mobileMenuAnimation.']'; ?>">
				<?php
				$animations = $this->getMobileMenuAnimations();
				foreach($animations as $key => $value) {
					echo '<option value="'.$key.'" '.selected($this->getMobileMenuAnimation(), $key).'>'.$value.'</option>';
				}
				?>
			</select>
			<?php
		}, $this->settingsPage, $sectionID);
		$this->registerSetting($this->settingsName);
	}

	/**
	 * Get the mobile menu animation
	 * 
	 * @return string the mobile menu animation
	 */
	public function getMobileMenuAnimation() : string {
		return ($this->settings) ? stripslashes($this->settings[$this->mobileMenuAnimation]) : MobileMenuAnimation::SlideRight->value;
	}

	/**
	 * Get the mobile menu animations
	 * 
	 * @return array the mobile menu animations
	 */
	private function getMobileMenuAnimations() : array {
		return [
			MobileMenuAnimation::SlideLeft->value => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_LEFT),
			MobileMenuAnimation::SlideRight->value => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_RIGHT)
		];
	}
}