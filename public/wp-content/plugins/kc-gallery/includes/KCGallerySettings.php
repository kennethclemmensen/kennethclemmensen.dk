<?php
namespace KCGallery\Includes;

/**
 * Class KCGallerySettings contains methods to handle the settings for the plugin
 * @package KCGallery\Includes
 */
class KCGallerySettings {

    private $photoKey;
    private $photoThumbnailKey;
    private $pageSlug;
    private $optionGroup;
    private $option;
    private $photoWidth;
    private $photoHeight;
    private $thumbnailWidth;
    private $thumbnailHeight;

    /**
     * KCGallerySettings constructor
     */
    public function __construct() {
        $this->photoKey = 'kc-photo';
        $this->photoThumbnailKey = 'kc-photo-thumbnail';
        add_image_size($this->photoKey, $this->getPhotoWidth(), $this->getPhotoHeight(), true);
        add_image_size($this->photoThumbnailKey, $this->getThumbnailWidth(), $this->getThumbnailHeight(), true);
        $this->pageSlug = 'kc-gallery-settings';
        $this->optionGroup = $this->pageSlug.'-group';
        $this->option = get_option($this->optionGroup);
        $this->photoWidth = 'kc_photo_width';
        $this->photoHeight = 'kc_photo_height';
        $this->thumbnailWidth = 'kc_thumbnail_width';
        $this->thumbnailHeight = 'kc_thumbnail_height';
        //$this->adminMenu();
        //$this->adminInit();
    }

    /**
     * Use the admin_menu action to create a settings page
     */
    private function adminMenu() {
        add_action('admin_menu', function() {
            $title = 'KC Gallery';
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
     * Use the admin_init action to create and register the setting inputs
     */
    private function adminInit() {
        add_action('admin_init', function() {
            $sectionID = 'kc-gallery-section';
            add_settings_section($sectionID, '', function() {
                echo '<h2>KC Gallery Settings</h2>';
            }, $this->pageSlug);
            add_settings_field('kc-photo-width', 'Width', function() {
                echo '<input type="number" name="'.$this->optionGroup.'['.$this->photoWidth.']" value="'.$this->getPhotoWidth().'">';
            }, $this->pageSlug, $sectionID);
            add_settings_field('kc-photo-height', 'Height', function() {
                echo '<input type="number" name="'.$this->optionGroup.'['.$this->photoHeight.']" value="'.$this->getPhotoHeight().'">';
            }, $this->pageSlug, $sectionID);
            add_settings_field('kc-thumbnail-width', 'Thumbnail width', function() {
                echo '<input type="number" name="'.$this->optionGroup.'['.$this->thumbnailWidth.']" value="'.$this->getThumbnailWidth().'">';
            }, $this->pageSlug, $sectionID);
            add_settings_field('kc-thumbnail-height', 'Thumbnail height', function() {
                echo '<input type="number" name="'.$this->optionGroup.'['.$this->thumbnailHeight.']" value="'.$this->getThumbnailHeight().'">';
            }, $this->pageSlug, $sectionID);
            register_setting($this->optionGroup, $this->optionGroup, function($input) : array {
                return $this->validateInput($input);
            });
        });
    }

    /**
     * Validate the input
     *
     * @param array $input the input to validate
     * @return array the validated input
     */
    private function validateInput(array $input) : array {
        $output = [];
        foreach($input as $key => $value) {
            if(isset($input[$key])) {
                $output[$key] = strip_tags(stripslashes($input[$key]));
            }
        }
        add_image_size($this->photoKey, $this->getPhotoWidth(), $this->getPhotoHeight(), true);
        add_image_size($this->photoThumbnailKey, $this->getThumbnailWidth(), $this->getThumbnailHeight(), true);
        return apply_filters(__FUNCTION__, $output, $input);
    }

    /**
     * Get the photo width
     *
     * @return int the photo width
     */
    private function getPhotoWidth() : int {
        $default_value = 550;
        return (isset($this->option[$this->photoWidth])) ? $this->option[$this->photoWidth] : $default_value;
    }

    /**
     * Get the photo height
     *
     * @return int the photo height
     */
    private function getPhotoHeight() : int {
        $default_value = 350;
        return (isset($this->option[$this->photoHeight])) ? $this->option[$this->photoHeight] : $default_value;
    }

    /**
     * Get the thumbnail width
     *
     * @return int the thumbnail width
     */
    private function getThumbnailWidth() : int {
        $default_value = 55;
        return (isset($this->option[$this->thumbnailWidth])) ? $this->option[$this->thumbnailWidth] : $default_value;
    }

    /**
     * Get the thumbnail height
     *
     * @return int the thumbnail height
     */
    private function getThumbnailHeight() : int {
        $default_value = 35;
        return (isset($this->option[$this->thumbnailHeight])) ? $this->option[$this->thumbnailHeight] : $default_value;
    }

    /**
     * Get the photo key
     *
     * @return string the photo key
     */
    public function getPhotoKey() : string {
        return $this->photoKey;
    }

    /**
     * Get the thumbnail key
     *
     * @return string the thumbnail key
     */
    public function getPhotoThumbnailKey() : string {
        return $this->photoThumbnailKey;
    }
}