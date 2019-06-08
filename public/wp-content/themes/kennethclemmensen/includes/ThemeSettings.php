<?php
/**
 * The ThemeSettings class contains methods to setup and retrieve the theme settings
 */
final class ThemeSettings {

    private static $instance = null;
    private $contactPageSlug;
    private $scriptsPageSlug;
    private $otherPageSlug;
    private $contactOptionsName;
    private $scriptsOptionsName;
    private $otherOptionsName;
    private $contactOptions;
    private $scriptsOptions;
    private $otherOptions;
    private $email;
    private $linkedIn;
    private $gitHub;
    private $photosPerPage;
    private $imagesPage;
    private $scriptsHeader;
    private $scriptsStartBody;
    private $scriptsFooter;
    private $emailShortcode;
    private $linkedInShortcode;
    private $gitHubShortcode;

    /**
     * ThemeSettings constructor
     */
    private function __construct() {
        $prefix = 'kc-theme-settings-';
        $postfix = '-options';
        $this->contactPageSlug = $prefix.'contact';
        $this->scriptsPageSlug = $prefix.'scripts';
        $this->otherPageSlug = $prefix.'other';
        $this->contactOptionsName = $this->contactPageSlug.$postfix;
        $this->scriptsOptionsName = $this->scriptsPageSlug.$postfix;
        $this->otherOptionsName = $this->otherPageSlug.$postfix;
        $this->contactOptions = get_option($this->contactOptionsName);
        $this->scriptsOptions = get_option($this->scriptsOptionsName);
        $this->otherOptions = get_option($this->otherOptionsName);
        $this->email = 'email';
        $this->linkedIn = 'linkedin';
        $this->gitHub = 'github';
        $this->photosPerPage = 'photos_per_page';
        $this->imagesPage = 'images_page';
        $prefix = 'scripts_';
        $this->scriptsHeader = $prefix.'header';
        $this->scriptsStartBody = $prefix.'start_body';
        $this->scriptsFooter = $prefix.'footer';
        $prefix = 'kc-';
        $this->emailShortcode = $prefix.$this->email;
        $this->linkedInShortcode = $prefix.$this->linkedIn;
        $this->gitHubShortcode = $prefix.$this->gitHub;
        $this->adminMenu();
        $this->adminInit();
        $this->addShortcodes();
        $this->addScriptSnippets2Header();
        $this->addScriptSnippets2AfterStartBody();
        $this->addScriptSnippets2Footer();
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
                        $otherTab = 'other_options';
                        $activeTab = (isset($_GET['tab'])) ? $_GET['tab'] : $contactTab;
                        $currentTab = 'nav-tab-active';
                        ?>
                        <a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $contactTab; ?>"
                           class="nav-tab <?php echo ($activeTab === $contactTab) ? $currentTab : ''; ?>">Contact</a>
                        <a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $scriptsTab; ?>"
                           class="nav-tab <?php echo ($activeTab === $scriptsTab) ? $currentTab : ''; ?>">Scripts</a>
                        <a href="?page=<?php echo $this->contactPageSlug; ?>&tab=<?php echo $otherTab; ?>"
                           class="nav-tab <?php echo ($activeTab === $otherTab) ? $currentTab : ''; ?>">Other</a>
                    </h2>
                    <form action="options.php" method="post">
                        <?php
                        if($activeTab === $contactTab) {
                            settings_fields($this->contactOptionsName);
                            do_settings_sections($this->contactPageSlug);
                        } else if($activeTab === $scriptsTab) {
                            settings_fields($this->scriptsOptionsName);
                            do_settings_sections($this->scriptsPageSlug);
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
            $this->setupScriptsInputs();
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
     * Setup scripts inputs
     */
    private function setupScriptsInputs() : void {
        $sectionID = $this->scriptsPageSlug.'-section-scripts';
        $prefix = $this->scriptsPageSlug;
        add_settings_section($sectionID, '', null, $this->scriptsPageSlug);
        add_settings_field($prefix.'header', 'Header', function() : void {
            echo '<textarea name="'.$this->scriptsOptionsName.'['.$this->scriptsHeader.']" cols="80" rows="10">'.$this->getHeaderScripts().'</textarea>';
        }, $this->scriptsPageSlug, $sectionID);
        add_settings_field($prefix.'start-body', 'Start body', function() : void {
            echo '<textarea name="'.$this->scriptsOptionsName.'['.$this->scriptsStartBody.']" cols="80" rows="10">'.$this->getStartBodyScripts().'</textarea>';
        }, $this->scriptsPageSlug, $sectionID);
        add_settings_field($prefix.'footer', 'Footer', function() : void {
            echo '<textarea name="'.$this->scriptsOptionsName.'['.$this->scriptsFooter.']" cols="80" rows="10">'.$this->getFooterScripts().'</textarea>';
        }, $this->scriptsPageSlug, $sectionID);
        register_setting($this->scriptsOptionsName, $this->scriptsOptionsName, function(array $input) : array {
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
        add_settings_field($prefix.'photos-per-page', 'Photos per page', function() : void {
            echo '<input type="number" name="'.$this->otherOptionsName.'['.$this->photosPerPage.']" value="'.$this->getPhotosPerPage().'" min="1" max="50">';
        }, $this->otherPageSlug, $sectionID);
        add_settings_field($prefix.'images-page', 'Images page', function() : void {
            $html = '<select name="'.$this->otherOptionsName.'['.$this->imagesPage.']">';
            $pages = $this->getPages();
            foreach($pages as $id => $title) {
                $html .= '<option value="'.$id.'" '.selected($id, $this->getImagesPageID(), false).'>'.$title.'</option>';
            }
            $html .= '</select>';
            echo $html;
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
     * Use the wp_head action to add script snippets to the header
     */
    private function addScriptSnippets2Header() : void {
        $priority = 0;
        add_action('wp_head', function() : void {
            echo $this->getHeaderScripts();
        }, $priority);
    }

    /**
     * Use the wp_body_open action to add script snippets after the start body tag
     */
    private function addScriptSnippets2AfterStartBody() : void {
        add_action('wp_body_open', function() : void {
            echo $this->getStartBodyScripts();
        });
    }

    /**
     * Use the wp_footer action to add script snippets to the footer
     */
    private function addScriptSnippets2Footer() : void {
        $priority = 100;
        add_action('wp_footer', function() : void {
            echo $this->getFooterScripts();
        }, $priority);
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
            $output[$key] = strip_tags(addslashes($input[$key]), '<script>');
        }
        return $output;
    }

    /**
     * Get all pages
     *
     * @return array all pages
     */
    private function getPages() : array {
        $pages = [];
        $args = [
            'post_type' => 'page',
            'posts_per_page' => -1
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $pages[get_the_ID()] = get_the_title();
        }
        wp_reset_postdata();
        return $pages;
    }

    /**
     * Get the email
     *
     * @return string the email
     */
    private function getEmail() : string {
        return (isset($this->contactOptions[$this->email])) ? stripslashes($this->contactOptions[$this->email]) : '';
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

    /**
     * Get the header scripts
     *
     * @return string the header scripts
     */
    private function getHeaderScripts() : string {
        return (isset($this->scriptsOptions[$this->scriptsHeader])) ? stripslashes($this->scriptsOptions[$this->scriptsHeader]) : '';
    }

    /**
     * Get the start body scripts
     *
     * @return string the start body scripts
     */
    private function getStartBodyScripts() : string {
        return (isset($this->scriptsOptions[$this->scriptsStartBody])) ? stripslashes($this->scriptsOptions[$this->scriptsStartBody]) : '';
    }

    /**
     * Get the footer scripts
     *
     * @return string the footer scripts
     */
    private function getFooterScripts() : string {
        return (isset($this->scriptsOptions[$this->scriptsFooter])) ? stripslashes($this->scriptsOptions[$this->scriptsFooter]) : '';
    }

    /**
     * Get the number of photos per page
     *
     * @return int the number of photos per page
     */
    public function getPhotosPerPage() : int {
        $defaultValue = 39;
        return (isset($this->otherOptions[$this->photosPerPage])) ? intval($this->otherOptions[$this->photosPerPage]) : $defaultValue;
    }

    /**
     * Get the images page id
     *
     * @return int the images page id
     */
    public function getImagesPageID() : ?int {
        return (isset($this->otherOptions[$this->imagesPage])) ? $this->otherOptions[$this->imagesPage] : null;
    }
}