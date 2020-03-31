<?php
namespace KC\Slider;

use KC\Core\CustomPostType;

/**
 * The Slider class contains methods to handle the slides
 */
class Slider {

    /**
     * Initialize a new instance of the Slider class
     */
    public function __construct() {
        require_once 'SliderSettings.php';
        SliderSettings::getInstance();
        $this->init();
        $this->afterSetupTheme();
        $this->adminColumns();
    }

    /**
     * Use the init action to register the slides custom post type
     */
    private function init() : void {
        add_action('init', function() : void {
            register_post_type(CustomPostType::SLIDES, [
                'labels' => [
                    'name' => 'Slides',
                    'singular_name' => 'Slide'
                ],
                'public' => false,
                'has_archive' => false,
                'supports' => ['title', 'thumbnail'],
                'menu_icon' => 'dashicons-images-alt',
                'publicly_queryable' => true,
                'show_ui' => true,
                'exclude_from_search' => true,
                'show_in_nav_menus' => false,
                'rewrite' => false
            ]);
        });
    }

    /**
     * Use the after_setup_theme action to add post thumbnails support
     */
    private function afterSetupTheme() : void {
        add_action('after_setup_theme', function() : void {
            add_theme_support('post-thumbnails');
        });
    }

    /**
     * Use the manage_{$post_type}_posts_column and manage_{$post_type}_posts_custom_column filters to create custom
     * columns for the slides custom post type
     */
    private function adminColumns() : void {
        $imageColumnKey = 'image';
        add_filter('manage_'.CustomPostType::SLIDES.'_posts_columns', function(array $columns) use ($imageColumnKey) : array {
            $columns[$imageColumnKey] = 'Image';
            return $columns;
        });
        add_filter('manage_'.CustomPostType::SLIDES.'_posts_custom_column', function(string $columnName) use ($imageColumnKey) : void {
            if($columnName === $imageColumnKey) echo '<img src="'.$this->getSlideImageUrl(get_the_ID()).'" alt="'.get_the_title().'" style="height: 60px">';
        });
    }

    /**
     * Get the slide image url
     *
     * @param int $imageID the id of the image
     * @return string the slide image url
     */
    public function getSlideImageUrl(int $imageID) : string {
        return esc_url(get_the_post_thumbnail_url($imageID));
    }
}