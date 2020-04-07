<?php
namespace KC\Gallery;

use KC\Core\Constant;
use KC\Core\CustomPostType;
use KC\Utils\PluginHelper;
use \WP_Query;

/**
 * The Gallery class contains functionality to handle galleries and images
 */
class Gallery {

    private $fieldImageGallery;

    /**
     * Initialize a new instance of the Gallery class
     */
    public function __construct() {
        $this->fieldImageGallery = 'photo_gallery';
        $this->init();
        $this->afterSetupTheme();
        $this->addMetaBoxes();
        $this->adminColumns();
    }

    /**
     * Use the init action to register the gallery and image custom post types
     */
    private function init() : void {
        add_action('init', function() : void {
            register_post_type(CustomPostType::GALLERY, [
                'labels' => [
                    'name' => 'Galleries',
                    'singular_name' => 'Gallery'
                ],
                'public' => true,
                'has_archive' => true,
                'supports' => ['title', 'editor', 'thumbnail'],
                'menu_icon' => 'dashicons-format-gallery',
                'rewrite' => ['slug' => '/billeder', 'with_front' => false]
            ]);
            register_post_type(CustomPostType::IMAGE, [
                'labels' => [
                    'name' => 'Images',
                    'singular_name' => 'Image'
                ],
                'public' => false,
                'has_archive' => false,
                'supports' => ['title', 'thumbnail'],
                'menu_icon' => 'dashicons-format-image',
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
     * Use the rwmb_meta_boxes filter to add meta boxes to the gallery and image custom post types
     */
    private function addMetaBoxes() : void {
        add_filter('rwmb_meta_boxes', function(array $metaBoxes) : array {
            $metaBoxes[] = [
                'id' => 'image_informations',
                'title' => 'Image informations',
                'post_types' => [CustomPostType::IMAGE],
                'fields' => [
                    [
                        'name' => 'Gallery',
                        'id' => $this->fieldImageGallery,
                        'type' => 'select',
                        'options' => $this->getGalleries()
                    ]
                ]
            ];
            return $metaBoxes;
        });
    }

    /**
     * Add admin columns to the gallery and image custom post types
     */
    private function adminColumns() : void {
        $columnGalleryKey = 'gallery';
        $columnGalleryValue = 'Gallery';
        $columnImageKey = 'image';
        add_filter('manage_'.CustomPostType::IMAGE.'_posts_columns', function(array $columns) use ($columnGalleryKey, $columnGalleryValue, $columnImageKey) : array {
            $columns[$columnGalleryKey] = $columnGalleryValue;
            $columns[$columnImageKey] = ucfirst($columnImageKey);
            return $columns;
        });
        add_action('manage_'.CustomPostType::IMAGE.'_posts_custom_column', function(string $columnName) use ($columnGalleryKey, $columnImageKey) : void {
            if($columnName === $columnGalleryKey) {
                $galleryID = get_post_meta(get_the_ID(), $this->fieldImageGallery, true);
                echo get_post($galleryID)->post_title;
            } else if($columnName === $columnImageKey) {
                echo '<img src="'.PluginHelper::getImageUrl(get_the_ID(), Constant::THUMBNAIL).'" alt="'.get_the_title().'">';
            }
        });
        add_filter('manage_edit-'.CustomPostType::IMAGE.'_sortable_columns', function(array $columns) use ($columnGalleryKey, $columnGalleryValue) : array {
            $columns[$columnGalleryKey] = $columnGalleryValue;
            return $columns;
        });
        $columnNumberOfImagesKey = 'number_of_images';
        $columnNumberOfImagesValue = 'Images';
        add_filter('manage_'.CustomPostType::GALLERY.'_posts_columns', function(array $columns) use ($columnNumberOfImagesKey, $columnNumberOfImagesValue) : array {
            $columns[$columnNumberOfImagesKey] = $columnNumberOfImagesValue;
            return $columns;
        });
        add_action('manage_'.CustomPostType::GALLERY.'_posts_custom_column', function(string $columnName) use ($columnNumberOfImagesKey) {
            if($columnName === $columnNumberOfImagesKey) echo $this->getNumberOfImagesInGallery(get_the_ID());
        });
    }

    /**
     * Get the number of images in a gallery
     *
     * @param int $galleryID the id of the gallery
     * @return int the number of images in a gallery
     */
    private function getNumberOfImagesInGallery(int $galleryID) : int {
        $args = [
            'post_type' => CustomPostType::IMAGE,
            'posts_per_page' => -1,
            'meta_key' => $this->fieldImageGallery,
            'meta_value' => $galleryID
        ];
        $wpQuery = new WP_Query($args);
        return $wpQuery->found_posts;
    }

    /**
     * Get the galleries
     *
     * @param bool $isCalledFromApi a value that indicates whether the method is called from the API
     * @return array the galleries
     */
    public function getGalleries(bool $isCalledFromApi = false) : array {
        $galleries = [];
        $args = [
            'post_type' => CustomPostType::GALLERY,
            'posts_per_page' => -1,
            'order' => 'ASC'
        ];
        $wpQuery = new WP_Query($args);
        if($isCalledFromApi === true) {
            while($wpQuery->have_posts()) {
                $wpQuery->the_post();
                $galleries[] = [
                    'title' => get_the_title(),
                    'link' => get_permalink(get_the_ID()),
                    'image' => PluginHelper::getImageUrl(get_the_ID())
                ];
            }
        } else {
            while($wpQuery->have_posts()) {
                $wpQuery->the_post();
                $galleries[get_the_ID()] = get_the_title();
            }
        }
        return $galleries;
    }

    /**
     * Get the image gallery field id
     *
     * @return string the image gallery field id
     */
    public function getImageGalleryFieldID() : string {
        return $this->fieldImageGallery;
    }
}