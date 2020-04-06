<?php
namespace KC\Slider;

use KC\Security\Security;

/**
 * The SliderSettings class contains functionality to handle the settings
 */
class SliderSettings {

    private $pageSlug;
    private $optionName;
    private $option;
    private $delay;
    private $duration;

    /**
     * Initialize a new instance of the SliderSettings class
     */
    public function __construct() {
        $this->pageSlug = 'kc-slider-settings';
        $this->optionName = $this->pageSlug.'-group';
        $this->option = get_option($this->optionName);
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
            $title = __('Settings');
            add_submenu_page('edit.php?post_type=slides', $title, $title, 'administrator', $this->pageSlug, function() : void {
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
     * Use the admin_init action to create and register the setting inputs
     */
    private function adminInit() : void {
        add_action('admin_init', function() : void {
            $sectionID = 'kc-slider-section';
            add_settings_section($sectionID, 'KC Slider Settings', null, $this->pageSlug);
            $prefix = 'kc-slider-';
            add_settings_field($prefix.'delay', 'Delay', function() : void {
                echo '<input type="number" name="'.$this->optionName.'['.$this->delay.']" value="'.$this->getDelay().'" required min="1" max="10000">';
            }, $this->pageSlug, $sectionID);
            add_settings_field($prefix.'duration', 'Duration', function() : void {
                echo '<input type="number" name="'.$this->optionName.'['.$this->duration.']" value="'.$this->getDuration().'" required min="1" max="10000">';
            }, $this->pageSlug, $sectionID);
            register_setting($this->optionName, $this->optionName, function(array $input) : array {
                return Security::validateInput($input);
            });
        });
    }

    /**
     * Get the delay
     *
     * @return int the delay
     */
    public function getDelay() : int {
        return intval(stripslashes($this->option[$this->delay]));
    }

    /**
     * Get the duration
     *
     * @return int the duration
     */
    public function getDuration() : int {
        return intval(stripslashes($this->option[$this->duration]));
    }
}