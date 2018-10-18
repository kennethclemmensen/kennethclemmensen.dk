<?php
/**
 * The ThemeSettings class contains methods to setup and retrieve the theme settings
 */
class ThemeSettings {

    private static $instance = null;
    private $contactPageSlug;
    private $otherPageSlug;
    private $contactOptionsName;
    private $otherOptionsName;
    private $contactOptions;
    private $otherOptions;
    private $email;
    private $linkedIn;
    private $gitHub;
    private $emailShortcode;
    private $linkedInShortcode;
    private $gitHubShortcode;

    /**
     * ThemeSettings constructor
     */
    private function __construct() {
        $prefix = 'kc-theme-settings-';
        $suffix = '-options';
        $this->contactPageSlug = $prefix.'contact';
        $this->otherPageSlug = $prefix.'other';
        $this->contactOptionsName = $this->contactPageSlug.$suffix;
        $this->otherOptionsName = $this->otherPageSlug.$suffix;
        $this->contactOptions = get_option($this->contactOptionsName);
        $this->otherOptions = get_option($this->otherOptionsName);
        $this->email = 'email';
        $this->linkedIn = 'linkedin';
        $this->gitHub = 'github';
        $prefix = 'kc-';
        $this->emailShortcode = $prefix.$this->email;
        $this->linkedInShortcode = $prefix.$this->linkedIn;
        $this->gitHubShortcode = $prefix.$this->gitHub;
        $this->adminMenu();
        $this->adminInit();
        $this->addShortcodes();
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
            $title = 'Theme settings';
            add_submenu_page('themes.php', $title, $title, 'administrator', $this->contactPageSlug, function() : void {
                settings_errors();
                ?>
                <div class="wrap">
                    <h2 class="nav-tab-wrapper">
                        <?php
                        $contactTab = 'contact_options';
                        $otherTab = 'other_options';
                        $activeTab = (isset($_GET['tab'])) ? $_GET['tab'] : $contactTab;
                        $currentTab = 'nav-tab-active';
                        ?>
                        <a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $contactTab; ?>"
                           class="nav-tab <?php echo ($activeTab === $contactTab) ? $currentTab : ''; ?>">Contact</a>
                        <a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $otherTab; ?>"
                           class="nav-tab <?php echo ($activeTab === $otherTab) ? $currentTab : ''; ?>">Other</a>
                    </h2>
                    <form action="options.php" method="post">
                        <?php
                        if($activeTab === $contactTab) {
                            settings_fields($this->contactOptionsName);
                            do_settings_sections($this->contactPageSlug);
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
     * Use the admin_init action to create and register the settings inputs
     */
    private function adminInit() : void {
        add_action('admin_init', function() : void {
            $this->setupContactInputs();
            $this->setupOtherInputs();
        });
    }

    /**
     * Setup contact inputs
     */
    private function setupContactInputs() : void {
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
            return $this->validateInput($input);
        });
    }

    /**
     * Setup other inputs
     */
    private function setupOtherInputs() : void {
        $sectionID = $this->otherPageSlug.'-section-other';
        $prefix = $this->otherPageSlug;
        add_settings_section($sectionID, '', null, $this->otherPageSlug);
        add_settings_field($prefix.'github', 'GitHub', function() : void {
            echo '<input type="url" name="'.$this->otherOptionsName.'['.$this->gitHub.']" value="'.$this->getGitHubUrl().'" class="regular-text" required> ';
            echo '['.$this->gitHubShortcode.']';
        }, $this->otherPageSlug, $sectionID);
        register_setting($this->otherOptionsName, $this->otherOptionsName, function(array $input) : array {
            return $this->validateInput($input);
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
     * Validate the setting inputs
     *
     * @param array $input the input to validate
     * @return array the validated input
     */
    private function validateInput(array $input) : array {
        $output = [];
        foreach($input as $key => $value) {
            $output[$key] = strip_tags(stripslashes($input[$key]));
        }
        return apply_filters(__FUNCTION__, $output, $input);
    }

    /**
     * Get the email
     *
     * @return string the email
     */
    private function getEmail() : string {
        return (isset($this->contactOptions[$this->email])) ? $this->contactOptions[$this->email] : '';
    }

    /**
     * Get the LinkedIn url
     *
     * @return string the LinkedIn url
     */
    private function getLinkedInUrl() : string {
        return (isset($this->contactOptions[$this->linkedIn])) ? esc_url($this->contactOptions[$this->linkedIn]) : '';
    }

    /**
     * Get the GitHub url
     *
     * @return string the GitHub url
     */
    private function getGitHubUrl() : string {
        return (isset($this->otherOptions[$this->gitHub])) ? esc_url($this->otherOptions[$this->gitHub]) : '';
    }
}