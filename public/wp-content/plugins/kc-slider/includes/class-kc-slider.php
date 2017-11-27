<?php
namespace KCSlider\Includes;

/**
 * Class KCSlider contains methods to handle the functionality of the plugin
 * @package KCSlider\Includes
 */
class KCSlider {

    private $fieldSlideImage;

    const SLIDES = 'slides';

    /**
     * KCSlider constructor
     */
    public function __construct() {
        $prefix = 'slider_';
        $this->fieldSlideImage = $prefix.'slide_image';
    }

    /**
     * Activate the plugin
     *
     * @param string $mainPluginFile the path to the main plugin file
     */
    public function activate(string $mainPluginFile) {
        register_activation_hook($mainPluginFile, function() {
            if(!class_exists('RW_Meta_Box')) {
                die('Meta Box is not activated');
            }
        });
    }

    /**
     * Execute the plugin
     */
    public function execute() {
        $this->loadDependencies();
        new KCSliderSettings();
        $this->init();
        $this->addMetaBoxes();
        $this->adminColumns();
    }

    /**
     * Load the dependencies files
     */
    private function loadDependencies() {
        require_once 'class-kc-slider-settings.php';
    }

    /**
     * Use the init action to register the slides custom post type
     */
    private function init() {
        add_action('init', function() {
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
    private function addMetaBoxes() {
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
    private function adminColumns() {
        $image_column_key = 'image';
        add_filter('manage_'.self::SLIDES.'_posts_columns', function(array $columns) use ($image_column_key) : array {
            $columns[$image_column_key] = 'Image';
            return $columns;
        });
        add_filter('manage_'.self::SLIDES.'_posts_custom_column', function(string $column_name) use ($image_column_key) {
            if($column_name === $image_column_key) {
                echo '<img src="'.$this->getSlideImage().'" alt="'.get_the_title().'" style="height: 60px">';
            }
        });
    }

    /**
     * Get the slide image
     *
     * @param int|null $postID the post id of the slide image
     * @param array $args an array of arguments
     *
     * @return string the url of the slide image
     */
    public function getSlideImage(int $postID = null, array $args = []) : string {
        $image = rwmb_meta($this->fieldSlideImage, $args, $postID);
        return array_shift($image)['full_url'];
    }
}