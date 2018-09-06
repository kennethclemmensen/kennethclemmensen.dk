<?php
/**
 * The ThemeSettings class contains methods to setup and retrieve the theme settings
 */
class ThemeSettings {

    private static $instance = null;
    private $title;
    private $pageSlug;
    private $optionName;
    private $option;
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
        $this->title = 'Theme settings';
        $this->pageSlug = 'kc-theme-settings';
        $this->optionName = $this->pageSlug.'-group';
        $this->option = get_option($this->optionName);
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
        if(self::$instance === null) self::$instance = new ThemeSettings();
        return self::$instance;
    }

    /**
     * Use the admin_menu action to create a settings page
     */
    private function adminMenu() : void {
        add_action('admin_menu', function() : void {
            add_submenu_page('themes.php', $this->title, $this->title, 'administrator', $this->pageSlug, function() : void {
                settings_errors();
                ?>
                <form action="options.php" method="post">
                    <?php
                    settings_fields($this->optionName);
                    do_settings_sections($this->pageSlug);
                    submit_button();
                    ?>
                </form>
                <?php
            });
        });
    }

    /**
     * Use the admin_init action to create and register the settings inputs
     */
    private function adminInit() : void {
        add_action('admin_init', function() : void {
            $sectionID = $this->pageSlug.'-section';
            add_settings_section($sectionID, $this->title, null, $this->pageSlug);
            $prefix = 'kc-theme-settings-';
            add_settings_field($prefix.'email', 'Email', function() : void {
                echo '<input type="email" name="'.$this->optionName.'['.$this->email.']" value="'.$this->getEmail().'" required> ';
                echo '['.$this->emailShortcode.']';
            }, $this->pageSlug, $sectionID);
            add_settings_field($prefix.'linkedin', 'LinkedIn', function() : void {
                echo '<input type="url" name="'.$this->optionName.'['.$this->linkedIn.']" value="'.$this->getLinkedInUrl().'" required> ';
                echo '['.$this->linkedInShortcode.']';
            }, $this->pageSlug, $sectionID);
            add_settings_field($prefix.'github', 'GitHub', function() : void {
                echo '<input type="url" name="'.$this->optionName.'['.$this->gitHub.']" value="'.$this->getGitHubUrl().'" required> ';
                echo '['.$this->gitHubShortcode.']';
            }, $this->pageSlug, $sectionID);
            register_setting($this->optionName, $this->optionName, function(array $input) : array {
                return $this->validateInput($input);
            });
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
        return (isset($this->option[$this->email])) ? $this->option[$this->email] : '';
    }

    /**
     * Get the LinkedIn url
     *
     * @return string the LinkedIn url
     */
    private function getLinkedInUrl() : string {
        return (isset($this->option[$this->linkedIn])) ? esc_url($this->option[$this->linkedIn]) : '';
    }

    /**
     * Get the GitHub url
     *
     * @return string the GitHub url
     */
    private function getGitHubUrl() : string {
        return (isset($this->option[$this->gitHub])) ? esc_url($this->option[$this->gitHub]) : '';
    }
}