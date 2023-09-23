<?php
/**
 * The ThemeSettings class contains methods to handle the theme settings
 */
final class ThemeSettings {

	private readonly TranslationStrings $translationStrings;
	private readonly ContactSettings $contactSettings;
	private readonly SliderSettings $sliderSettings;
	private readonly ScriptSettings $scriptSettings;
	private readonly GallerySettings $gallerySettings;
	private readonly MobileMenuSettings $mobileMenuSettings;
	private readonly string $contactPageSlug;
	private static ?self $instance = null;

	/**
	 * ThemeSettings constructor
	 */
	private function __construct() {
		$this->translationStrings = new TranslationStrings();
		$this->contactSettings = new ContactSettings();
		$this->sliderSettings = new SliderSettings();
		$this->scriptSettings = new ScriptSettings();
		$this->gallerySettings = new GallerySettings();
		$this->mobileMenuSettings = new MobileMenuSettings();
		$this->contactPageSlug = $this->contactSettings->getContactPageSlug();
		$this->createSettingsPage();
		$this->registerSettingInputs();
		$this->addHeaderScripts();
		$this->addAfterStartBodyScripts();
		$this->addFooterScripts();
		$this->scriptLoaderSrc();
		$this->styleLoaderSrc();
	}

	/**
	 * Get the instance of the class
	 *
	 * @return ThemeSettings the instance of the class
	 */
	public static function getInstance() : self {
		if(self::$instance === null) self::$instance = new self();
		return self::$instance;
	}

	/**
	 * Get the number of images per page
	 *
	 * @return int the number of images per page
	 */
	public function getImagesPerPage() : int {
		return $this->gallerySettings->getImagesPerPage();
	}

	/**
	 * Get the slider delay
	 * 
	 * @return int the slider delay
	 */
	public function getSliderDelay() : int {
		return $this->sliderSettings->getSliderDelay();
	}

	/**
	 * Get the slider duration
	 * 
	 * @return int the slider duration
	 */
	public function getSliderDuration() : int {
		return $this->sliderSettings->getSliderDuration();
	}

	/**
	 * Get the slider animation
	 * 
	 * @return string the slider animation
	 */
	public function getSliderAnimation() : string {
		return $this->sliderSettings->getSliderAnimation();
	}

	/**
	 * Get the mobile menu animation
	 * 
	 * @return string the mobile menu animation
	 */
	public function getMobileMenuAnimation() : string {
		return $this->mobileMenuSettings->getMobileMenuAnimation();
	}

	/**
	 * Use the admin_menu action to create a settings page
	 */
	private function createSettingsPage() : void {
		add_action(Action::AdminMenu->value, function() : void {
			$title = $this->translationStrings->getTranslatedString(TranslationStrings::SETTINGS);
			add_theme_page($title, $title, 'administrator', $this->contactPageSlug, function() : void {
				settings_errors();
				?>
				<div class="wrap">
					<h2 class="nav-tab-wrapper">
						<?php
						$contactTab = 'contact';
						$scriptsTab = 'scripts';
						$sliderTab = 'slider';
						$galleryTab = 'gallery';
						$mobileMenuTab = 'mobile-menu';
						$activeTab = (isset($_GET['tab'])) ? $_GET['tab'] : $contactTab;
						$currentTab = 'nav-tab-active';
						?>
						<a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $contactTab; ?>"
						   class="nav-tab <?php echo (!in_array($activeTab, [$scriptsTab, $sliderTab, $galleryTab])) ? $currentTab : ''; ?>">
						   <?php echo $this->translationStrings->getTranslatedString(TranslationStrings::CONTACT); ?>
						</a>
						<a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $scriptsTab; ?>"
						   class="nav-tab <?php echo ($activeTab === $scriptsTab) ? $currentTab : ''; ?>">
						   <?php echo $this->translationStrings->getTranslatedString(TranslationStrings::SCRIPTS); ?>
						</a>
						<a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $sliderTab; ?>"
						   class="nav-tab <?php echo ($activeTab === $sliderTab) ? $currentTab : ''; ?>">
						   <?php echo $this->translationStrings->getTranslatedString(TranslationStrings::SLIDER); ?>
						</a>
						<a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $galleryTab; ?>"
						   class="nav-tab <?php echo ($activeTab === $galleryTab) ? $currentTab : ''; ?>">
						   <?php echo $this->translationStrings->getTranslatedString(TranslationStrings::GALLERY); ?>
						</a>
						<a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $mobileMenuTab; ?>"
						   class="nav-tab <?php echo ($activeTab === $mobileMenuTab) ? $currentTab : ''; ?>">
						   <?php echo $this->translationStrings->getTranslatedString(TranslationStrings::MOBILE_MENU); ?>
						</a>
					</h2>
					<form action="options.php" method="post">
						<?php
						switch($activeTab) {
							case $scriptsTab:
								$this->scriptSettings->showFields();
								break;
							case $sliderTab:
								$this->sliderSettings->showFields();
								break;
							case $galleryTab:
								$this->gallerySettings->showFields();
								break;
							case $mobileMenuTab:
								$this->mobileMenuSettings->showFields();
								break;
							default:
								$this->contactSettings->showFields();
								break;
						}
						submit_button();
						?>
					</form>
				</div>
				<?php
			});
		});
	}

	/**
	 * Use the admin_init action to register the setting inputs
	 */
	private function registerSettingInputs() : void {
		add_action(Action::AdminInit->value, function() : void {
			$this->contactSettings->createFields();
			$this->scriptSettings->createFields();
			$this->sliderSettings->createFields();
			$this->gallerySettings->createFields();
			$this->mobileMenuSettings->createFields();
		});
	}

	/**
	 * Use the wp_head action to add scripts to the header
	 */
	private function addHeaderScripts() : void {
		$priority = 0;
		add_action(Action::WpHead->value, function() : void {
			echo $this->getHeaderScripts();
		}, $priority);
	}

	/**
	 * Use the wp_body_open action to add scripts after the start body tag
	 */
	private function addAfterStartBodyScripts() : void {
		add_action(Action::WpBodyOpen->value, function() : void {
			echo $this->getStartBodyScripts();
		});
	}

	/**
	 * Use the wp_footer action to add scripts to the footer
	 */
	private function addFooterScripts() : void {
		$priority = 100;
		add_action(Action::WpFooter->value, function() : void {
			echo $this->getFooterScripts();
		}, $priority);
	}

	/**
	 * Use the script_loader_src filter to remove the version query string from scripts
	 */
	private function scriptLoaderSrc() : void {
		add_filter(Filter::ScriptLoaderSrc->value, function(string $src) : string {
			return ($this->mustVersionQueryStringBeRemoved()) ? $this->removeVersionQueryString($src) : $src;
		});
	}
	
	/**
	 * Use the style_loader_src filter to remove the version query string from stylesheets
	 */
	private function styleLoaderSrc() : void {
		add_filter(Filter::StyleLoaderSrc->value, function(string $src) : string {
			return ($this->mustVersionQueryStringBeRemoved()) ? $this->removeVersionQueryString($src) : $src;
		});
	}

	/**
	 * Get the header scripts
	 *
	 * @return string the header scripts
	 */
	private function getHeaderScripts() : string {
		return $this->scriptSettings->getHeaderScripts();
	}

	/**
	 * Get the start body scripts
	 *
	 * @return string the start body scripts
	 */
	private function getStartBodyScripts() : string {
		return $this->scriptSettings->getStartBodyScripts();
	}

	/**
	 * Get the footer scripts
	 *
	 * @return string the footer scripts
	 */
	private function getFooterScripts() : string {
		return $this->scriptSettings->getFooterScripts();
	}

	/**
	 * Check whether the version query string must be removed
	 *
	 * @return bool true if the version query string must be removed. False if it must not be removed
	 */
	private function mustVersionQueryStringBeRemoved() : bool {
		return $this->scriptSettings->mustVersionQueryStringBeRemoved();
	}

	/**
	 * Remove the version query string from the source
	 *
	 * @param string $src the source to remove the version query string from
	 * @return string the source without the version query string
	 */
	private function removeVersionQueryString(string $src) : string {
		return explode('?ver', $src)[0];
	}
}