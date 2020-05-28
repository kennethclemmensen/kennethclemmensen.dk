<?php
/**
 * The ThemeSettings class contains methods to setup and retrieve the theme settings
 */
final class ThemeSettings {

    private static $instance = null;
    private $contactPageSlug;
    private $scriptPageSlug;
    private $sliderPageSlug;
    private $otherPageSlug;
    private $contactOptionsName;
    private $scriptOptionsName;
    private $sliderOptionsName;
    private $otherOptionsName;
    private $contactOptions;
    private $scriptOptions;
    private $sliderOptions;
    private $otherOptions;
    private $email;
    private $linkedIn;
    private $gitHub;
    private $imagesPerPage;
    private $filesPerPage;
    private $searchResultsPerPage;
    private $scriptHeader;
    private $scriptStartBody;
    private $scriptFooter;
    private $emailShortcode;
    private $linkedInShortcode;
    private $gitHubShortcode;
    private $delay;
    private $duration;
    private $animation;

    /**
     * ThemeSettings constructor
     */
    private function __construct() {
        $prefix = 'kc-theme-settings-';
        $postfix = '-options';
        $this->contactPageSlug = $prefix.'contact';
        $this->scriptPageSlug = $prefix.'scripts';
        $this->sliderPageSlug = $prefix.'slider';
        $this->otherPageSlug = $prefix.'other';
        $this->contactOptionsName = $this->contactPageSlug.$postfix;
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
        $prefix = 'scripts_';
        $this->scriptHeader = $prefix.'header';
        $this->scriptStartBody = $prefix.'start_body';
        $this->scriptFooter = $prefix.'footer';
        $prefix = 'kc-';
        $this->emailShortcode = $prefix.$this->email;
        $this->linkedInShortcode = $prefix.$this->linkedIn;
        $this->gitHubShortcode = $prefix.$this->gitHub;
        $prefix = 'slider_';
        $this->delay = $prefix.'delay';
        $this->duration = $prefix.'duration';
        $this->animation = $prefix.'animation';
        $this->adminMenu();
        $this->adminInit();
        $this->addShortcodes();
        $this->addHeaderScripts();
        $this->addAfterStartBodyScripts();
        $this->addFooterScripts();
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
    private function adminMenu() : void {
        add_action('admin_menu', function() : void {
            $title = __('Settings');
            add_theme_page($title, $title, 'administrator', $this->contactPageSlug, function() : void {
                settings_errors();
                ?>
                <div class="wrap">
                    <h2 class="nav-tab-wrapper">
                        <?php
                        $contactTab = 'contact_options';
                        $scriptsTab = 'scripts_options';
                        $sliderTab = 'slider_options';
                        $otherTab = 'other_options';
                        $activeTab = (isset($_GET['tab'])) ? $_GET['tab'] : $contactTab;
                        $currentTab = 'nav-tab-active';
                        ?>
                        <a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $contactTab; ?>"
                           class="nav-tab <?php echo ($activeTab === $contactTab) ? $currentTab : ''; ?>">Contact</a>
                        <a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $scriptsTab; ?>"
                           class="nav-tab <?php echo ($activeTab === $scriptsTab) ? $currentTab : ''; ?>">Scripts</a>
                        <a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $sliderTab; ?>"
                           class="nav-tab <?php echo ($activeTab === $sliderTab) ? $currentTab : ''; ?>">Slider</a>
                        <a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $otherTab; ?>"
                           class="nav-tab <?php echo ($activeTab === $otherTab) ? $currentTab : ''; ?>">Other</a>
                    </h2>
                    <form action="options.php" method="post">
                        <?php
                        if($activeTab === $contactTab) {
                            settings_fields($this->contactOptionsName);
                            do_settings_sections($this->contactPageSlug);
                        } else if($activeTab === $scriptsTab) {
                            settings_fields($this->scriptOptionsName);
                            do_settings_sections($this->scriptPageSlug);
                        } else if($activeTab === $sliderTab) {
                            settings_fields($this->sliderOptionsName);
                            do_settings_sections($this->sliderPageSlug);
                        } else {
                            settings_fields($this->otherOptionsName);
                            do_settings_sections($this->otherPageSlug);
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
     * Use the admin_init action to register the settings inputs
     */
    private function adminInit() : void {
        add_action('admin_init', function() : void {
            $this->createContactInputs();
            $this->createScriptInputs();
            $this->createSliderInputs();
            $this->createOtherInputs();
        });
    }

    /**
     * Create the contact inputs
     */
    private function createContactInputs() : void {
        $sectionID = $this->contactPageSlug.'-section-contact';
        $prefix = $this->contactPageSlug;
        add_settings_section($sectionID, '', null, $this->contactPageSlug);
        add_settings_field($prefix.'email', 'Email', function() : void {
            echo '<input type="email" name="'.$this->contactOptionsName.'['.$this->email.']" value="'.$this->getEmail().'" class="regular-text" required> ';
            echo '['.$this->emailShortcode.']';
        }, $this->contactPageSlug, $sectionID);
        add_settings_field($prefix.'linkedin', 'LinkedIn', function() : void {
            echo '<input type="url" name="'.$this->contactOptionsName.'['.$this->linkedIn.']" value="'.$this->getLinkedInUrl().'" class="regular-text" required> ';
            echo '['.$this->linkedInShortcode.']';
        }, $this->contactPageSlug, $sectionID);
        register_setting($this->contactOptionsName, $this->contactOptionsName, function(array $input) : array {
            return $this->validateSettingInputs($input);
        });
    }

    /**
     * Create the script inputs
     */
    private function createScriptInputs() : void {
        $sectionID = $this->scriptPageSlug.'-section-scripts';
        $prefix = $this->scriptPageSlug;
        add_settings_section($sectionID, '', null, $this->scriptPageSlug);
        add_settings_field($prefix.'header', 'Header', function() : void {
            echo '<textarea name="'.$this->scriptOptionsName.'['.$this->scriptHeader.']" cols="80" rows="10">'.$this->getHeaderScripts().'</textarea>';
        }, $this->scriptPageSlug, $sectionID);
        add_settings_field($prefix.'start-body', 'Start body', function() : void {
            echo '<textarea name="'.$this->scriptOptionsName.'['.$this->scriptStartBody.']" cols="80" rows="10">'.$this->getStartBodyScripts().'</textarea>';
        }, $this->scriptPageSlug, $sectionID);
        add_settings_field($prefix.'footer', 'Footer', function() : void {
            echo '<textarea name="'.$this->scriptOptionsName.'['.$this->scriptFooter.']" cols="80" rows="10">'.$this->getFooterScripts().'</textarea>';
        }, $this->scriptPageSlug, $sectionID);
        register_setting($this->scriptOptionsName, $this->scriptOptionsName, function(array $input) : array {
            return $this->validateSettingInputs($input);
        });
    }

    /**
     * Create the slider inputs
     */
    private function createSliderInputs() : void {
        $sectionID = $this->sliderPageSlug.'-section-slider';
        $prefix = $this->sliderPageSlug;
        add_settings_section($sectionID, '', null, $this->sliderPageSlug);
        add_settings_field($prefix.'delay', 'Delay', function() : void {
            echo '<input type="number" name="'.$this->sliderOptionsName.'['.$this->delay.']" value="'.$this->getDelay().'" min="1" max="10000">';
        }, $this->sliderPageSlug, $sectionID);
        add_settings_field($prefix.'duration', 'Duration', function() : void {
            echo '<input type="number" name="'.$this->sliderOptionsName.'['.$this->duration.']" value="'.$this->getDuration().'" min="1" max="10000">';
        }, $this->sliderPageSlug, $sectionID);
        add_settings_field($prefix.'animation', 'Animation', function() : void {
            ?>
            <select name="<?php echo $this->sliderOptionsName.'['.$this->animation.']'; ?>">
                <?php
                $animations = $this->getSliderAnimations();
                foreach($animations as $key => $value) {
                    echo '<option value="'.$key.'" '.selected($this->getAnimation(), $key).'>'.$value.'</option>';
                }
                ?>
            </select>
            <?php
        }, $this->sliderPageSlug, $sectionID);
        register_setting($this->sliderOptionsName, $this->sliderOptionsName, function(array $input) : array {
            return $this->validateSettingInputs($input);
        });
    }

    /**
     * Create other inputs
     */
    private function createOtherInputs() : void {
        $sectionID = $this->otherPageSlug.'-section-other';
        $prefix = $this->otherPageSlug;
        add_settings_section($sectionID, '', null, $this->otherPageSlug);
        add_settings_field($prefix.'github', 'GitHub', function() : void {
            echo '<input type="url" name="'.$this->otherOptionsName.'['.$this->gitHub.']" value="'.$this->getGitHubUrl().'" class="regular-text" required> ';
            echo '['.$this->gitHubShortcode.']';
        }, $this->otherPageSlug, $sectionID);
        add_settings_field($prefix.'images-per-page', 'Images per page', function() : void {
            echo '<input type="number" name="'.$this->otherOptionsName.'['.$this->imagesPerPage.']" value="'.$this->getImagesPerPage().'" min="1" max="50">';
        }, $this->otherPageSlug, $sectionID);
        add_settings_field($prefix.'files-per-page', 'Files per page', function() : void {
            echo '<input type="number" name="'.$this->otherOptionsName.'['.$this->filesPerPage.']" value="'.$this->getFilesPerPage().'" min="1" max="50">';
        }, $this->otherPageSlug, $sectionID);
        add_settings_field($prefix.'search-results-per-page', 'Search results per page', function() : void {
            echo '<input type="number" name="'.$this->otherOptionsName.'['.$this->searchResultsPerPage.']" value="'.$this->getSearchResultsPerPage().'" min="1" max="50">';
        }, $this->otherPageSlug, $sectionID);
        register_setting($this->otherOptionsName, $this->otherOptionsName, function(array $input) : array {
            return $this->validateSettingInputs($input);
        });
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
     * Use the wp_body_open action to add script snippets after the start body tag
     */
    private function addAfterStartBodyScripts() : void {
        add_action('wp_body_open', function() : void {
            echo $this->getStartBodyScripts();
        });
    }

    /**
     * Use the wp_footer action to add script snippets to the footer
     */
    private function addFooterScripts() : void {
        $priority = 100;
        add_action('wp_footer', function() : void {
            echo $this->getFooterScripts();
        }, $priority);
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
            'fade' => 'Fade',
            'slide_down' => 'Slide down',
            'slide_left' => 'Slide left',
            'slide_right' => 'Slide right',
            'slide_up' => 'Slide up'
        ];
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
     * Get the delay
     * 
     * @return int the delay
     */
    public function getDelay() : int {
        return intval($this->sliderOptions[$this->delay]);
    }

    /**
     * Get the duration
     * 
     * @return int the duration
     */
    public function getDuration() : int {
        return intval($this->sliderOptions[$this->duration]);
    }

    /**
     * Get the animation
     * 
     * @return string the animation
     */
    public function getAnimation() : string {
        return ($this->sliderOptions) ? stripslashes($this->sliderOptions[$this->animation]) : '';
    }
}