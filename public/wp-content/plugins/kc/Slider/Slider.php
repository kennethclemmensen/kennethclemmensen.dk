<?php
namespace KC\Slider;

use KC\Core\Constant;
use KC\Core\CustomPostType;
use KC\Core\IModule;
use KC\Utils\PluginHelper;
use \WP_Query;

/**
 * The Slider class contains methods to handle the slides
 */
class Slider implements IModule {

    /**
     * Initialize a new instance of the Slider class
     */
    public function __construct() {
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
                'supports' => ['title', Constant::THUMBNAIL],
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
            if($columnName === $imageColumnKey) echo '<img src="'.PluginHelper::getImageUrl(get_the_ID()).'" alt="'.get_the_title().'" style="height: 60px">';
        });
    }

    /**
     * Get the slides
     * 
     * @return array the slides
     */
    public function getSlides() : array {
        $slides = [];
        $args = [
            'post_type' => CustomPostType::SLIDES,
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $slides[] = [
                'image' => PluginHelper::getImageUrl(get_the_ID())
            ];
        }
        return $slides;
    }
}