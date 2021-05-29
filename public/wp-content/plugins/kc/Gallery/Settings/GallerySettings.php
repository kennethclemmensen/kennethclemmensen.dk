<?php
namespace KC\Gallery\Settings;

use KC\Core\Action;
use KC\Core\Capability;
use KC\Core\ImageSize;
use KC\Core\ISettings;
use KC\Core\PostType;
use KC\Core\TranslationString;
use KC\Security\Security;
use KC\Utils\PluginHelper;

/**
 * The GallerySettings class contains methods to handle the gallery settings
 */
class GallerySettings implements ISettings {

    private string $settingOptionsName;
    private array | bool $settingsOption;
    private string $settingsPageSlug;
    private string $galleryImageWidth;
    private string $galleryImageHeight;

    /**
     * GallerySettings constructor
     */
    public function __construct() {
        $this->settingOptionsName = 'kc-gallery-settings-options';
        $this->settingsOption = get_option($this->settingOptionsName);
        $this->settingsPageSlug = 'kc-gallery-settings';
        $prefix = 'gallery_image_';
        $this->galleryImageWidth = $prefix.'width';
        $this->galleryImageHeight = $prefix.'height';
    }

    /**
     * Create a settings page
     */
    public function createSettingsPage() : void {
        add_action(Action::ADMIN_MENU, function() : void {
            $title = PluginHelper::getTranslatedString(TranslationString::SETTINGS);
            add_submenu_page('edit.php?post_type='.PostType::GALLERY, $title, $title, Capability::ADMINISTRATOR, $this->settingsPageSlug, function() : void {
                settings_errors();
                ?>
                <form action="options.php" method="post">
                    <?php
                    settings_fields($this->settingOptionsName);
                    do_settings_sections($this->settingsPageSlug);
                    submit_button();
                    ?>
                </form>
                <?php
            });
        });
        $this->registerSettingInputs();
        $this->addImageSize();
    }

    /**
     * Use the admin_init action to register the setting inputs
     */
    private function registerSettingInputs() : void {
        add_action(Action::ADMIN_INIT, function() : void {
            $sectionID = $this->settingsPageSlug.'-section-gallery';
            $prefix = $this->settingsPageSlug.'galleryImage';
            add_settings_section($sectionID, '', null, $this->settingsPageSlug);
            add_settings_field($prefix.'Width', PluginHelper::getTranslatedString(TranslationString::WIDTH), function() : void {
                echo '<input type="number" name="'.$this->settingOptionsName.'['.$this->galleryImageWidth.']" value="'.$this->getGalleryImageWidth().'" min="1">';
            }, $this->settingsPageSlug, $sectionID);
            add_settings_field($prefix.'Height', PluginHelper::getTranslatedString(TranslationString::HEIGHT), function() : void {
                echo '<input type="number" name="'.$this->settingOptionsName.'['.$this->galleryImageHeight.']" value="'.$this->getGalleryImageHeight().'" min="1">';
            }, $this->settingsPageSlug, $sectionID);
            register_setting($this->settingOptionsName, $this->settingOptionsName, function(array $input) : array {
                return Security::validateSettingInputs($input);
            });
        });
    }

    /**
     * Use the init action to add an image size
     */
    private function addImageSize() : void {
        add_action(Action::INIT, function() : void {
            $width = $this->getGalleryImageWidth();
            $height = $this->getGalleryImageHeight();
            add_image_size(ImageSize::KC_GALLERY_IMAGE, $width, $height, true);
        });
    }

    /**
     * Get the gallery image width
     * 
     * @return int the gallery image width
     */
    private function getGalleryImageWidth() : int {
        return $this->settingsOption[$this->galleryImageWidth] ?? 1;
    }

    /**
     * Get the gallery image height
     * 
     * @return int the gallery image height
     */
    private function getGalleryImageHeight() : int {
        return $this->settingsOption[$this->galleryImageHeight] ?? 1;
    }
}