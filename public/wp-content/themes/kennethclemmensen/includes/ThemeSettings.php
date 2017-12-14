<?php
/**
 * The ThemeSettings class contains methods to setup and retrieve the theme settings
 */
class ThemeSettings {

    private $pageSlug;
    private $optionGroup;
    private $option;
    private $email;
    private $linkedIn;
    private $gitHub;

    /**
     * ThemeSettings constructor
     */
    public function __construct() {
        $this->pageSlug = 'kc-theme-settings';
        $this->optionGroup = $this->pageSlug.'-group';
        $this->option = get_option($this->optionGroup);
        $this->email = 'email';
        $this->linkedIn = 'linkedin';
        $this->gitHub = 'github';
        $this->adminMenu();
        $this->adminInit();
    }

    /**
     * Use the admin_menu action to create a settings page
     */
    private function adminMenu() {
        add_action('admin_menu', function() {
            $title = 'Theme settings';
            add_menu_page($title, $title, 'administrator', $this->pageSlug, function() {
                settings_errors();
                ?>
                <form action="options.php" method="post">
                    <?php
                    settings_fields($this->optionGroup);
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
    private function adminInit() {
        add_action('admin_init', function() {
            $sectionID = 'kc-theme-settings-section';
            add_settings_section($sectionID, '', function() {
                echo '<h2>Theme settings</h2>';
            }, $this->pageSlug);
            $prefix = 'kc-theme-settings-';
            add_settings_field($prefix.'email', 'Email', function() {
                echo '<input type="email" name="'.$this->optionGroup.'['.$this->email.']" value="'.$this->getEmail().'" required>';
            }, $this->pageSlug, $sectionID);
            add_settings_field($prefix.'linkedin', 'LinkedIn', function() {
                echo '<input type="url" name="'.$this->optionGroup.'['.$this->linkedIn.']" value="'.$this->getLinkedInUrl().'" required>';
            }, $this->pageSlug, $sectionID);
            add_settings_field($prefix.'github', 'GitHub', function() {
                echo '<input type="url" name="'.$this->optionGroup.'['.$this->gitHub.']" value="'.$this->getGitHubUrl().'" required>';
            }, $this->pageSlug, $sectionID);
            register_setting($this->optionGroup, $this->optionGroup, function(array $input) : array {
                return $this->validateInput($input);
            });
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
            if(isset($input[$key])) $output[$key] = strip_tags(stripslashes($input[$key]));
        }
        return apply_filters(__FUNCTION__, $output, $input);
    }

    /**
     * Get the email
     *
     * @return string the email
     */
    public function getEmail() : string {
        return (isset($this->option[$this->email])) ? $this->option[$this->email] : '';
    }

    /**
     * Get the LinkedIn url
     *
     * @return string the LinkedIn url
     */
    public function getLinkedInUrl() : string {
        return (isset($this->option[$this->linkedIn])) ? $this->option[$this->linkedIn] : '';
    }

    /**
     * Get the GitHub url
     *
     * @return string the GitHub url
     */
    public function getGitHubUrl() : string {
        return (isset($this->option[$this->gitHub])) ? $this->option[$this->gitHub] : '';
    }
}