<?php
/**
 * The SliderSettings class contains functionality to handle the slider settings
 */
final class SliderSettings extends BaseSettings {

	private readonly string $sliderPageSlug;
	private readonly string $sliderOptionsName;
	private readonly array | bool $sliderOptions;
	private readonly string $sliderDelay;
	private readonly string $sliderDuration;
	private readonly string $sliderAnimation;

	/**
	 * SliderSettings constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->sliderPageSlug = $this->prefix.'slider';
		$this->sliderOptionsName = $this->sliderPageSlug.$this->postfix;
		$this->sliderOptions = get_option($this->sliderOptionsName);
		$prefix = 'slider_';
		$this->sliderDelay = $prefix.'delay';
		$this->sliderDuration = $prefix.'duration';
		$this->sliderAnimation = $prefix.'animation';
	}

	/**
	 * Show the fields
	 */
	public function showFields() : void {
		settings_fields($this->sliderOptionsName);
		do_settings_sections($this->sliderPageSlug);
	}

	/**
	 * Create the fields
	 */
	public function createFields() : void {
		$sectionID = $this->sliderPageSlug.'-section-slider';
		$prefix = $this->sliderPageSlug;
		add_settings_section($sectionID, '', function() {}, $this->sliderPageSlug);
		add_settings_field($prefix.'sliderDelay', $this->translationStrings->getTranslatedString(TranslationStrings::DELAY), function() : void {
			echo '<input type="number" name="'.$this->sliderOptionsName.'['.$this->sliderDelay.']" value="'.$this->getSliderDelay().'" min="1" max="10000">';
		}, $this->sliderPageSlug, $sectionID);
		add_settings_field($prefix.'sliderDuration', $this->translationStrings->getTranslatedString(TranslationStrings::DURATION), function() : void {
			echo '<input type="number" name="'.$this->sliderOptionsName.'['.$this->sliderDuration.']" value="'.$this->getSliderDuration().'" min="1" max="10000">';
		}, $this->sliderPageSlug, $sectionID);
		add_settings_field($prefix.'sliderAnimation', $this->translationStrings->getTranslatedString(TranslationStrings::ANIMATION), function() : void {
			?>
			<select name="<?php echo $this->sliderOptionsName.'['.$this->sliderAnimation.']'; ?>">
				<?php
				$animations = $this->getSliderAnimations();
				foreach($animations as $key => $value) {
					echo '<option value="'.$key.'" '.selected($this->getSliderAnimation(), $key).'>'.$value.'</option>';
				}
				?>
			</select>
			<?php
		}, $this->sliderPageSlug, $sectionID);
		$this->registerSetting($this->sliderOptionsName);
	}

	/**
	 * Get the slider sliderDelay
	 * 
	 * @return int the slider sliderDelay
	 */
	public function getSliderDelay() : int {
		return intval($this->sliderOptions[$this->sliderDelay]);
	}

	/**
	 * Get the slider sliderDuration
	 * 
	 * @return int the slider sliderDuration
	 */
	public function getSliderDuration() : int {
		return intval($this->sliderOptions[$this->sliderDuration]);
	}

	/**
	 * Get the slider sliderAnimation
	 * 
	 * @return string the slider sliderAnimation
	 */
	public function getSliderAnimation() : string {
		return ($this->sliderOptions) ? stripslashes($this->sliderOptions[$this->sliderAnimation]) : '';
	}

	/**
	 * Get the slider animations
	 * 
	 * @return array the slider animations
	 */
	private function getSliderAnimations() : array {
		return [
			'fade' => $this->translationStrings->getTranslatedString(TranslationStrings::FADE),
			'slide_down' => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_DOWN),
			'slide_left' => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_LEFT),
			'slide_right' => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_RIGHT),
			'slide_up' => $this->translationStrings->getTranslatedString(TranslationStrings::SLIDE_UP)
		];
	}
}