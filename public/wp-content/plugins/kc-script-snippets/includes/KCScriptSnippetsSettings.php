<?php
namespace KCScriptSnippets\Includes;

/**
 * Class KCScriptSnippetsSettings contains methods to handle the settings for the plugin
 * @package KCScriptSnippets\Includes
 */
class KCScriptSnippetsSettings {

    private $pageSlug;
    private $optionName;
    private $option;
    private $headerScripts;
    private $afterStartBodyScripts;
    private $footerScripts;

    /**
     * KCScriptSnippetsSettings constructor
     */
    public function __construct() {
        $this->pageSlug = 'kc-script-snippets-settings';
        $this->optionName = $this->pageSlug.'-group';
        $this->option = get_option($this->optionName);
        $prefix = 'kc_script_snippets_';
        $this->headerScripts = $prefix.'header';
        $this->afterStartBodyScripts = $prefix.'after_start_body';
        $this->footerScripts = $prefix.'footer';
        $this->adminMenu();
        $this->adminInit();
    }

    /**
     * Use the admin_menu action to create a settings page
     */
    private function adminMenu() : void {
        add_action('admin_menu', function() : void {
            $title = 'Script Snippets';
            add_submenu_page('options-general.php', $title, $title, 'administrator', $this->pageSlug, function() : void {
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
     * Use the admin_init action to create and register the setting inputs
     */
    private function adminInit() : void {
        add_action('admin_init', function() : void {
            $sectionID = 'kc-script-snippets-section';
            add_settings_section($sectionID, 'Script Snippets', null, $this->pageSlug);
            $prefix = 'kc-script-snippets-';
            add_settings_field($prefix.'header', 'Header', function() : void {
                echo '<textarea name="'.$this->optionName.'['.$this->headerScripts.']" cols="120" rows="7">'.$this->getHeaderScriptSnippets().'</textarea>';
            }, $this->pageSlug, $sectionID);
            add_settings_field($prefix.'after_start_body', 'After start body', function() : void {
                echo '<textarea name="'.$this->optionName.'['.$this->afterStartBodyScripts.']" cols="120" rows="7">'.$this->getAfterStartBodyScriptSnippets().'</textarea>';
            }, $this->pageSlug, $sectionID);
            add_settings_field($prefix.'footer', 'Footer', function() : void {
                echo '<textarea name="'.$this->optionName.'['.$this->footerScripts.']" cols="120" rows="7">'.$this->getFooterScriptSnippets().'</textarea>';
            }, $this->pageSlug, $sectionID);
            register_setting($this->optionName, $this->optionName, function(array $input) : array {
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
            $output[$key] = strip_tags(stripslashes($input[$key]), '<script>');
        }
        return apply_filters(__FUNCTION__, $output, $input);
    }

    /**
     * Get the script snippets in the header
     *
     * @return string the script snippets in the header
     */
    public function getHeaderScriptSnippets() : string {
        return (isset($this->option[$this->headerScripts])) ? $this->option[$this->headerScripts] : '';
    }

    /**
     * Get the script snippets after the start body tag
     *
     * @return string the script snippets after the start body tag
     */
    public function getAfterStartBodyScriptSnippets() : string {
        return (isset($this->option[$this->afterStartBodyScripts])) ? $this->option[$this->afterStartBodyScripts] : '';
    }

    /**
     * Get the script snippets in the footer
     *
     * @return string the script snippets in the footer
     */
    public function getFooterScriptSnippets() : string {
        return (isset($this->option[$this->footerScripts])) ? $this->option[$this->footerScripts] : '';
    }
}