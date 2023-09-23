<?php
/**
 * The SliderSettings class contains functionality to handle the slider settings
 */
final class SliderSettings extends BaseSettings {

	private readonly string $sliderDelay;
	private readonly string $sliderDuration;
	private readonly string $sliderAnimation;

	/**
	 * SliderSettings constructor
	 */
	public function __construct() {
		parent::__construct('kc-theme-settings-slider', 'kc-theme-settings-slider-options');
		$prefix = 'slider_';
		$this->sliderDelay = $prefix.'delay';
		$this->sliderDuration = $prefix.'duration';
		$this->sliderAnimation = $prefix.'animation';
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
		$sectionID = $this->settingsPage.'-section-slider';
		$prefix = $this->settingsPage;
		add_settings_section($sectionID, '', function() {}, $this->settingsPage);
		add_settings_field($prefix.'sliderDelay', $this->translationStrings->getTranslatedString(TranslationStrings::DELAY), function() : void {
			echo '<input type="number" name="'.$this->settingsName.'['.$this->sliderDelay.']" value="'.$this->getSliderDelay().'" min="1" max="10000">';
		}, $this->settingsPage, $sectionID);
		add_settings_field($prefix.'sliderDuration', $this->translationStrings->getTranslatedString(TranslationStrings::DURATION), function() : void {
			echo '<input type="number" name="'.$this->settingsName.'['.$this->sliderDuration.']" value="'.$this->getSliderDuration().'" min="1" max="10000">';
		}, $this->settingsPage, $sectionID);
		add_settings_field($prefix.'sliderAnimation', $this->translationStrings->getTranslatedString(TranslationStrings::ANIMATION), function() : void {
			?>
			<select name="<?php echo $this->settingsName.'['.$this->sliderAnimation.']'; ?>">
				<?php
				$animations = $this->getSliderAnimations();
				foreach($animations as $key => $value) {
					echo '<option value="'.$key.'" '.selected($this->getSliderAnimation(), $key).'>'.$value.'</option>';
				}
				?>
			</select>
			<?php
		}, $this->settingsPage, $sectionID);
		$this->registerSetting($this->settingsName);
	}

	/**
	 * Get the slider delay
	 * 
	 * @return int the slider delay
	 */
	public function getSliderDelay() : int {
		$defaultDelay = 500;
		$value = $this->settings[$this->sliderDelay] ?? $defaultDelay;
		return intval($value);
	}

	/**
	 * Get the slider duration
	 * 
	 * @return int the slider duration
	 */
	public function getSliderDuration() : int {
		$defaultDuration = 8000;
		$value = $this->settings[$this->sliderDuration] ?? $defaultDuration;
		return intval($value);
	}

	/**
	 * Get the slider animation
	 * 
	 * @return string the slider animation
	 */
	public function getSliderAnimation() : string {
		$string = $this->settings[$this->sliderAnimation] ?? SliderAnimation::Fade->value;
		return stripslashes($string);
	}

	/**
	 * Get the slider animations
	 * 
	 * @return array the slider animations
	 */
	private function getSliderAnimations() : array {
		return [
			SliderAnimation::Fade->value => $this->translationStrings->getTranslatedString(TranslationStrings::FADE),
			SliderAnimation::SlideDown->value => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_DOWN),
			SliderAnimation::SlideLeft->value => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_LEFT),
			SliderAnimation::SlideRight->value => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_RIGHT),
			SliderAnimation::SlideUp->value => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_UP)
		];
	}
}