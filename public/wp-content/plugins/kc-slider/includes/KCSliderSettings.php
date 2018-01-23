<?php
namespace KCSlider\Includes;

/**
 * Class KCSliderSettings contains methods to handle the settings for the plugin
 * @package KCSlider\Includes
 */
class KCSliderSettings {

    private $pageSlug;
    private $optionGroup;
    private $option;
    private $delay;
    private $duration;

    /**
     * KCSliderSettings constructor
     */
    public function __construct() {
        $this->pageSlug = 'kc-slider-settings';
        $this->optionGroup = $this->pageSlug.'-group';
        $this->option = get_option($this->optionGroup);
        $prefix = 'kc_slider_';
        $this->delay = $prefix.'delay';
        $this->duration = $prefix.'duration';
        $this->adminMenu();
        $this->adminInit();
    }

    /**
     * Use the admin_menu action to create a settings page
     */
    private function adminMenu() : void {
        add_action('admin_menu', function() : void {
            $title = 'Settings';
            add_submenu_page('edit.php?post_type=slides', $title, $title, 'administrator', $this->pageSlug, function() : void {
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
     * Use the admin_init action to create and register the setting inputs
     */
    private function adminInit() : void {
        add_action('admin_init', function() : void {
            $sectionID = 'kc-slider-section';
            add_settings_section($sectionID, '', function() : void {
                echo '<h2>KC Slider Settings</h2>';
            }, $this->pageSlug);
            add_settings_field('kc-slider-delay', 'Delay', function() : void {
                echo '<input type="number" name="'.$this->optionGroup.'['.$this->delay.']" value="'.$this->getDelay().'" required min="1" max="10000" step="500">';
            }, $this->pageSlug, $sectionID);
            add_settings_field('kc-slider-duration', 'Duration', function() : void {
                echo '<input type="number" name="'.$this->optionGroup.'['.$this->duration.']" value="'.$this->getDuration().'" required min="1" max="10000" step="500">';
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
     *
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
     * Get the delay
     *
     * @return int the delay
     */
    public function getDelay() : int {
        return $this->option[$this->delay];
    }

    /**
     * Get the duration
     *
     * @return int the duration
     */
    public function getDuration() : int {
        return $this->option[$this->duration];
    }
}