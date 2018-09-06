<?php
namespace KCSlider\Includes;

/**
 * Class KCSlider contains methods to handle the functionality of the plugin
 * @package KCSlider\Includes
 */
class KCSlider {

    private $fieldSlideImage;

    public const SLIDES = 'slides';

    /**
     * KCSlider constructor
     */
    public function __construct() {
        $this->fieldSlideImage = 'slider_slide_image';
    }

    /**
     * Activate the plugin
     *
     * @param string $mainPluginFile the path to the main plugin file
     */
    public function activate(string $mainPluginFile) : void {
        register_activation_hook($mainPluginFile, function() : void {
            if(!class_exists('RW_Meta_Box')) {
                die('Meta Box is not activated');
            }
        });
    }

    /**
     * Execute the plugin
     */
    public function execute() : void {
        $this->loadDependencies();
        new KCSliderSettings();
        $this->init();
        $this->addMetaBoxes();
        $this->adminColumns();
    }

    /**
     * Load the dependencies files
     */
    private function loadDependencies() : void {
        require_once 'KCSliderSettings.php';
    }

    /**
     * Use the init action to register the slides custom post type
     */
    private function init() : void {
        add_action('init', function() : void {
            register_post_type(self::SLIDES, [
                'labels' => [
                    'name' => 'Slides',
                    'singular_name' => 'Slide'
                ],
                'public' => true,
                'has_archive' => true,
                'supports' => ['title'],
                'menu_icon' => 'dashicons-images-alt'
            ]);
        });
    }

    /**
     * Use the rwmb_meta_boxes filter to add meta boxes to the slides custom post type
     */
    private function addMetaBoxes() : void {
        add_filter('rwmb_meta_boxes', function(array $metaBoxes) : array {
            $metaBoxes[] = [
                'id' => 'slide_informations',
                'title' => 'Slide informations',
                'post_types' => [self::SLIDES],
                'fields' => [
                    [
                        'name' => 'Photo',
                        'id' => $this->fieldSlideImage,
                        'type' => 'image_advanced',
                        'max_file_uploads' => 1
                    ]
                ]
            ];
            return $metaBoxes;
        });
    }

    /**
     * Use the manage_{$post_type}_posts_column and manage_{$post_type}_posts_custom_column filters to create custom
     * columns for the slides custom post type
     */
    private function adminColumns() : void {
        $imageColumnKey = 'image';
        add_filter('manage_'.self::SLIDES.'_posts_columns', function(array $columns) use ($imageColumnKey) : array {
            $columns[$imageColumnKey] = 'Image';
            return $columns;
        });
        add_filter('manage_'.self::SLIDES.'_posts_custom_column', function(string $columnName) use ($imageColumnKey) : void {
            if($columnName === $imageColumnKey) echo '<img src="'.$this->getSlideImage().'" alt="'.get_the_title().'" style="height: 60px">';
        });
    }

    /**
     * Get the slide image url
     *
     * @param int|null $slideImageID the id of the slide image
     * @return string the slide image url
     */
    public function getSlideImage(int $slideImageID = null) : string {
        $image = rwmb_meta($this->fieldSlideImage, [], $slideImageID);
        $url = array_shift($image)['full_url'];
        return esc_url($url);
    }
}