<?php
namespace KCSlider\Includes;

/**
 * Class KCSlider contains methods to handle the functionality of the plugin
 * @package KCSlider\Includes
 */
class KCSlider {

    public const SLIDES = 'slides';

    /**
     * Execute the plugin
     */
    public function execute() : void {
        $this->loadDependencies();
        KCSliderSettings::getInstance();
        $this->init();
        $this->afterSetupTheme();
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
        add_filter('manage_'.self::SLIDES.'_posts_columns', function(array $columns) use ($imageColumnKey) : array {
            $columns[$imageColumnKey] = 'Image';
            return $columns;
        });
        add_filter('manage_'.self::SLIDES.'_posts_custom_column', function(string $columnName) use ($imageColumnKey) : void {
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
        $url = get_the_post_thumbnail_url($imageID);
        return (isset($url)) ? esc_url($url) : '';
    }
}