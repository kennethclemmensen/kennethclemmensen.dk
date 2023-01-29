<?php
/**
 * The ThemeSettings class contains methods to handle the theme settings
 */
final class ThemeSettings {

	private static ?ThemeSettings $instance = null;
	private readonly string $contactPageSlug;
	private readonly string $scriptPageSlug;
	private readonly string $sliderPageSlug;
	private readonly string $otherPageSlug;
	private readonly string $contactOptionsName;
	private readonly string $scriptOptionsName;
	private readonly string $sliderOptionsName;
	private readonly string $otherOptionsName;
	private readonly array | bool $contactOptions;
	private readonly array | bool $scriptOptions;
	private readonly array | bool $sliderOptions;
	private readonly array | bool $otherOptions;
	private readonly string $email;
	private readonly string $linkedIn;
	private readonly string $gitHub;
	private readonly string $imagesPerPage;
	private readonly string $filesPerPage;
	private readonly string $searchResultsPerPage;
	private readonly string $allowFileEditing;
	private readonly string $scriptHeader;
	private readonly string $scriptStartBody;
	private readonly string $scriptFooter;
	private readonly string $removeVersionQueryString;
	private readonly string $emailShortcode;
	private readonly string $linkedInShortcode;
	private readonly string $gitHubShortcode;
	private readonly string $sliderDelay;
	private readonly string $sliderDuration;
	private readonly string $sliderAnimation;
	private readonly string $checkboxCheckedValue;
	private readonly TranslationStrings $translationStrings;

	/**
	 * ThemeSettings constructor
	 */
	private function __construct() {
		$prefix = 'kc-theme-settings-';
		$postfix = '-options';
		$this->contactPageSlug = substr($prefix, 0, strlen($prefix) - 1);
		$this->scriptPageSlug = $prefix.'scripts';
		$this->sliderPageSlug = $prefix.'slider';
		$this->otherPageSlug = $prefix.'other';
		$this->contactOptionsName = $prefix.'contact'.$postfix;
		$this->scriptOptionsName = $this->scriptPageSlug.$postfix;
		$this->sliderOptionsName = $this->sliderPageSlug.$postfix;
		$this->otherOptionsName = $this->otherPageSlug.$postfix;
		$this->contactOptions = get_option($this->contactOptionsName);
		$this->scriptOptions = get_option($this->scriptOptionsName);
		$this->sliderOptions = get_option($this->sliderOptionsName);
		$this->otherOptions = get_option($this->otherOptionsName);
		$this->email = 'email';
		$this->linkedIn = 'linkedin';
		$this->gitHub = 'github';
		$this->imagesPerPage = 'photos_per_page';
		$this->filesPerPage = 'files_per_page';
		$this->searchResultsPerPage = 'search_results_per_page';
		$this->allowFileEditing = 'allow_file_editing';
		$prefix = 'scripts_';
		$this->scriptHeader = $prefix.'header';
		$this->scriptStartBody = $prefix.'start_body';
		$this->scriptFooter = $prefix.'footer';
		$this->removeVersionQueryString = $prefix.'remove_version_query_string';
		$prefix = 'kc-';
		$this->emailShortcode = $prefix.$this->email;
		$this->linkedInShortcode = $prefix.$this->linkedIn;
		$this->gitHubShortcode = $prefix.$this->gitHub;
		$prefix = 'slider_';
		$this->sliderDelay = $prefix.'delay';
		$this->sliderDuration = $prefix.'duration';
		$this->sliderAnimation = $prefix.'animation';
		$this->checkboxCheckedValue = 'on';
		$this->translationStrings = new TranslationStrings();
		$this->createSettingsPage();
		$this->registerSettingInputs();
		$this->addShortcodes();
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
	 * Use the admin_menu action to create a settings page
	 */
	private function createSettingsPage() : void {
		add_action('admin_menu', function() : void {
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
						$otherTab = 'other';
						$activeTab = (isset($_GET['tab'])) ? $_GET['tab'] : $contactTab;
						$currentTab = 'nav-tab-active';
						?>
						<a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $contactTab; ?>"
						   class="nav-tab <?php echo (!in_array($activeTab, [$scriptsTab, $sliderTab, $otherTab])) ? $currentTab : ''; ?>">
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
						<a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $otherTab; ?>"
						   class="nav-tab <?php echo ($activeTab === $otherTab) ? $currentTab : ''; ?>">
						   <?php echo $this->translationStrings->getTranslatedString(TranslationStrings::OTHER); ?>
						</a>
					</h2>
					<form action="options.php" method="post">
						<?php
						switch($activeTab) {
							case $scriptsTab:
								settings_fields($this->scriptOptionsName);
								do_settings_sections($this->scriptPageSlug);
								break;
							case $sliderTab:
								settings_fields($this->sliderOptionsName);
								do_settings_sections($this->sliderPageSlug);
								break;
							case $otherTab:
								settings_fields($this->otherOptionsName);
								do_settings_sections($this->otherPageSlug);
								break;
							default:
								settings_fields($this->contactOptionsName);
								do_settings_sections($this->contactPageSlug);                            
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
		add_action('admin_init', function() : void {
			$this->createContactInputs();
			$this->createScriptInputs();
			$this->createSliderInputs();
			$this->createOtherInputs();
			define('DISALLOW_FILE_EDIT', !$this->isFileEditingAllowed());
		});
	}

	/**
	 * Create the contact inputs
	 */
	private function createContactInputs() : void {
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
		$this->registerSetting($this->contactOptionsName);
	}

	/**
	 * Create the script inputs
	 */
	private function createScriptInputs() : void {
		$sectionID = $this->scriptPageSlug.'-section-scripts';
		$prefix = $this->scriptPageSlug;
		add_settings_section($sectionID, '', function() {}, $this->scriptPageSlug);
		add_settings_field($prefix.'header', $this->translationStrings->getTranslatedString(TranslationStrings::HEADER), function() : void {
			echo '<textarea name="'.$this->scriptOptionsName.'['.$this->scriptHeader.']" cols="80" rows="10">'.$this->getHeaderScripts().'</textarea>';
		}, $this->scriptPageSlug, $sectionID);
		add_settings_field($prefix.'start-body', $this->translationStrings->getTranslatedString(TranslationStrings::START_BODY), function() : void {
			echo '<textarea name="'.$this->scriptOptionsName.'['.$this->scriptStartBody.']" cols="80" rows="10">'.$this->getStartBodyScripts().'</textarea>';
		}, $this->scriptPageSlug, $sectionID);
		add_settings_field($prefix.'footer', $this->translationStrings->getTranslatedString(TranslationStrings::FOOTER), function() : void {
			echo '<textarea name="'.$this->scriptOptionsName.'['.$this->scriptFooter.']" cols="80" rows="10">'.$this->getFooterScripts().'</textarea>';
		}, $this->scriptPageSlug, $sectionID);
		add_settings_field($prefix.'remove-version-query-string', $this->translationStrings->getTranslatedString(TranslationStrings::REMOVE_VERSION_QUERY_STRING), function() : void {
			$checked = (isset($this->scriptOptions[$this->removeVersionQueryString])) ? $this->scriptOptions[$this->removeVersionQueryString] : '';
			echo '<input type="checkbox" name="'.$this->scriptOptionsName.'['.$this->removeVersionQueryString.']" '.checked($checked, $this->checkboxCheckedValue, false).' >';
		}, $this->scriptPageSlug, $sectionID);
		$this->registerSetting($this->scriptOptionsName);
	}

	/**
	 * Create the slider inputs
	 */
	private function createSliderInputs() : void {
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
	 * Create other inputs
	 */
	private function createOtherInputs() : void {
		$sectionID = $this->otherPageSlug.'-section-other';
		$prefix = $this->otherPageSlug;
		add_settings_section($sectionID, '', function() {}, $this->otherPageSlug);
		add_settings_field($prefix.'github', $this->translationStrings->getTranslatedString(TranslationStrings::GITHUB), function() : void {
			echo '<input type="url" name="'.$this->otherOptionsName.'['.$this->gitHub.']" value="'.$this->getGitHubUrl().'" class="regular-text" required> ';
			echo '['.$this->gitHubShortcode.']';
		}, $this->otherPageSlug, $sectionID);
		add_settings_field($prefix.'images-per-page', $this->translationStrings->getTranslatedString(TranslationStrings::IMAGES_PER_PAGE), function() : void {
			echo '<input type="number" name="'.$this->otherOptionsName.'['.$this->imagesPerPage.']" value="'.$this->getImagesPerPage().'" min="1" max="50">';
		}, $this->otherPageSlug, $sectionID);
		add_settings_field($prefix.'files-per-page', $this->translationStrings->getTranslatedString(TranslationStrings::FILES_PER_PAGE), function() : void {
			echo '<input type="number" name="'.$this->otherOptionsName.'['.$this->filesPerPage.']" value="'.$this->getFilesPerPage().'" min="1" max="50">';
		}, $this->otherPageSlug, $sectionID);
		add_settings_field($prefix.'search-results-per-page', $this->translationStrings->getTranslatedString(TranslationStrings::SEARCH_RESULTS_PER_PAGE), function() : void {
			echo '<input type="number" name="'.$this->otherOptionsName.'['.$this->searchResultsPerPage.']" value="'.$this->getSearchResultsPerPage().'" min="1" max="50">';
		}, $this->otherPageSlug, $sectionID);
		add_settings_field($prefix.'allow-file-editing', $this->translationStrings->getTranslatedString(TranslationStrings::ALLOW_FILE_EDITING), function() : void {            
			$checked = (isset($this->otherOptions[$this->allowFileEditing])) ? $this->otherOptions[$this->allowFileEditing] : '';
			echo '<input type="checkbox" name="'.$this->otherOptionsName.'['.$this->allowFileEditing.']" '.checked($checked, $this->checkboxCheckedValue, false).' >';
		}, $this->otherPageSlug, $sectionID);
		$this->registerSetting($this->otherOptionsName);
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

	/**
	 * Use the wp_head action to add scripts to the header
	 */
	private function addHeaderScripts() : void {
		$priority = 0;
		add_action('wp_head', function() : void {
			echo $this->getHeaderScripts();
		}, $priority);
	}

	/**
	 * Use the wp_body_open action to add scripts after the start body tag
	 */
	private function addAfterStartBodyScripts() : void {
		add_action('wp_body_open', function() : void {
			echo $this->getStartBodyScripts();
		});
	}

	/**
	 * Use the wp_footer action to add scripts to the footer
	 */
	private function addFooterScripts() : void {
		$priority = 100;
		add_action('wp_footer', function() : void {
			echo $this->getFooterScripts();
		}, $priority);
	}

	/**
	 * Use the script_loader_src filter to remove the version query string from scripts
	 */
	private function scriptLoaderSrc() : void {
		add_filter('script_loader_src', function(string $src) : string {
			return ($this->mustVersionQueryStringBeRemoved()) ? $this->removeVersionQueryString($src) : $src;
		});
	}
	
	/**
	 * Use the style_loader_src filter to remove the version query string from stylesheets
	 */
	private function styleLoaderSrc() : void {
		add_filter('style_loader_src', function(string $src) : string {
			return ($this->mustVersionQueryStringBeRemoved()) ? $this->removeVersionQueryString($src) : $src;
		});
	}

	/**
	 * Register a setting with a name
	 * 
	 * @param string $name the name of the setting
	 */
	private function registerSetting(string $name) : void {
		register_setting($name, $name, [
			'sanitize_callback' => function(array $input) : array {
				return $this->validateSettingInputs($input);
			}
		]);
	}

	/**
	 * Validate the setting inputs
	 *
	 * @param array $inputs the inputs to validate
	 * @return array the validated inputs
	 */
	private function validateSettingInputs(array $inputs) : array {
		$output = [];
		foreach($inputs as $key => $value) {
			$output[$key] = strip_tags(addslashes($inputs[$key]), '<script>');
		}
		return $output;
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
		return esc_url($this->otherOptions[$this->gitHub]);
	}

	/**
	 * Get the header scripts
	 *
	 * @return string the header scripts
	 */
	private function getHeaderScripts() : string {
		return ($this->scriptOptions) ? stripslashes($this->scriptOptions[$this->scriptHeader]) : '';
	}

	/**
	 * Get the start body scripts
	 *
	 * @return string the start body scripts
	 */
	private function getStartBodyScripts() : string {
		return ($this->scriptOptions) ? stripslashes($this->scriptOptions[$this->scriptStartBody]) : '';
	}

	/**
	 * Get the footer scripts
	 *
	 * @return string the footer scripts
	 */
	private function getFooterScripts() : string {
		return ($this->scriptOptions) ? stripslashes($this->scriptOptions[$this->scriptFooter]) : '';
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

	/**
	 * Check whether file editing is allowed
	 *
	 * @return bool true if file editing is allowed. False if it isn't allowed
	 */
	private function isFileEditingAllowed() : bool {
		return isset($this->otherOptions[$this->allowFileEditing]) && $this->otherOptions[$this->allowFileEditing] === $this->checkboxCheckedValue;
	}

	/**
	 * Check whether the version query string must be removed
	 *
	 * @return bool true if the version query string must be removed. False if it must not be removed
	 */
	private function mustVersionQueryStringBeRemoved() : bool {
		return isset($this->scriptOptions[$this->removeVersionQueryString]) && $this->scriptOptions[$this->removeVersionQueryString] === $this->checkboxCheckedValue;
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

	/**
	 * Get the number of images per page
	 *
	 * @return int the number of images per page
	 */
	public function getImagesPerPage() : int {
		return intval($this->otherOptions[$this->imagesPerPage]);
	}
	
	/**
	 * Get the number of files per page
	 *
	 * @return int the number of files per page
	 */
	public function getFilesPerPage() : int {
		return intval($this->otherOptions[$this->filesPerPage]);
	}
	
	/**
	 * Get the number of search results per page
	 *
	 * @return int the number of search results per page
	 */
	public function getSearchResultsPerPage() : int {
		return intval($this->otherOptions[$this->searchResultsPerPage]);
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
}