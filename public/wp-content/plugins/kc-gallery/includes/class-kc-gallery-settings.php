<?php
namespace KCGallery\Includes;

/**
 * Class KC_Gallery_Settings contains methods to handle the settings for the plugin
 * @package KCGallery\Includes
 */
class KC_Gallery_Settings {

    private $_photo_key;
    private $_photo_thumbnail_key;
    private $_page_slug;
    private $_option_group;
    private $_option;
    private $_photo_width;
    private $_photo_height;
    private $_thumbnail_width;
    private $_thumbnail_height;

    /**
     * KC_Gallery_Settings constructor
     */
    public function __construct() {
        $this->_photo_key = 'kc-photo';
        $this->_photo_thumbnail_key = 'kc-photo-thumbnail';
        add_image_size($this->_photo_key, $this->get_photo_width(), $this->get_photo_height(), true);
        add_image_size($this->_photo_thumbnail_key, $this->get_thumbnail_width(), $this->get_thumbnail_height(), true);
        $this->_page_slug = 'kc-gallery-settings';
        $this->_option_group = $this->_page_slug.'-group';
        $this->_option = get_option($this->_option_group);
        $this->_photo_width = 'kc_photo_width';
        $this->_photo_height = 'kc_photo_height';
        $this->_thumbnail_width = 'kc_thumbnail_width';
        $this->_thumbnail_height = 'kc_thumbnail_height';
        //$this->admin_menu();
        //$this->admin_init();
    }

    /**
     * Use the admin_menu action to create a settings page
     */
    private function admin_menu() {
        add_action('admin_menu', function() {
            $title = 'KC Gallery';
            add_menu_page($title, $title, 'administrator', $this->_page_slug, function() {
                settings_errors();
                ?>
                <form action="options.php" method="post">
                    <?php
                    settings_fields($this->_option_group);
                    do_settings_sections($this->_page_slug);
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
    private function admin_init() {
        add_action('admin_init', function() {
            $section_id = 'kc-gallery-section';
            add_settings_section($section_id, '', function() {
                echo '<h2>KC Gallery Settings</h2>';
            }, $this->_page_slug);
            add_settings_field('kc-photo-width', 'Width', function() {
                echo '<input type="number" name="'.$this->_option_group.'['.$this->_photo_width.']" value="'.$this->get_photo_width().'">';
            }, $this->_page_slug, $section_id);
            add_settings_field('kc-photo-height', 'Height', function() {
                echo '<input type="number" name="'.$this->_option_group.'['.$this->_photo_height.']" value="'.$this->get_photo_height().'">';
            }, $this->_page_slug, $section_id);
            add_settings_field('kc-thumbnail-width', 'Thumbnail width', function() {
                echo '<input type="number" name="'.$this->_option_group.'['.$this->_thumbnail_width.']" value="'.$this->get_thumbnail_width().'">';
            }, $this->_page_slug, $section_id);
            add_settings_field('kc-thumbnail-height', 'Thumbnail height', function() {
                echo '<input type="number" name="'.$this->_option_group.'['.$this->_thumbnail_height.']" value="'.$this->get_thumbnail_height().'">';
            }, $this->_page_slug, $section_id);
            register_setting($this->_option_group, $this->_option_group, function($input) : array {
                return $this->validate_input($input);
            });
        });
    }

    /**
     * Validate the input
     *
     * @param array $input the input to validate
     * @return array the validated input
     */
    private function validate_input(array $input) : array {
        $output = [];
        foreach($input as $key => $value) {
            if(isset($input[$key])) {
                $output[$key] = strip_tags(stripslashes($input[$key]));
            }
        }
        add_image_size($this->_photo_key, $this->get_photo_width(), $this->get_photo_height(), true);
        add_image_size($this->_photo_thumbnail_key, $this->get_thumbnail_width(), $this->get_thumbnail_height(), true);
        return apply_filters(__FUNCTION__, $output, $input);
    }

    /**
     * Get the photo width
     *
     * @return int the photo width
     */
    private function get_photo_width() : int {
        $default_value = 550;
        return (isset($this->_option[$this->_photo_width])) ? $this->_option[$this->_photo_width] : $default_value;
    }

    /**
     * Get the photo height
     *
     * @return int the photo height
     */
    private function get_photo_height() : int {
        $default_value = 350;
        return (isset($this->_option[$this->_photo_height])) ? $this->_option[$this->_photo_height] : $default_value;
    }

    /**
     * Get the thumbnail width
     *
     * @return int the thumbnail width
     */
    private function get_thumbnail_width() : int {
        $default_value = 55;
        return (isset($this->_option[$this->_thumbnail_width])) ? $this->_option[$this->_thumbnail_width] : $default_value;
    }

    /**
     * Get the thumbnail height
     *
     * @return int the thumbnail height
     */
    private function get_thumbnail_height() : int {
        $default_value = 35;
        return (isset($this->_option[$this->_thumbnail_height])) ? $this->_option[$this->_thumbnail_height] : $default_value;
    }

    /**
     * Get the photo key
     *
     * @return string the photo key
     */
    public function get_photo_key() : string {
        return $this->_photo_key;
    }

    /**
     * Get the thumbnail key
     *
     * @return string the thumbnail key
     */
    public function get_photo_thumbnail_key() : string {
        return $this->_photo_thumbnail_key;
    }
}