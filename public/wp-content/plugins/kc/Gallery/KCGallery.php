<?php
namespace KC\Gallery;

use \WP_Query;

/**
 * Class KCGallery contains methods to handle the functionality of the plugin
 * @package KCGallery\Includes
 */
class KCGallery {

    private $fieldPhotoGallery;

    public const GALLERY = 'gallery';
    public const PHOTO = 'photo';

    /**
     * KCGallery constructor
     */
    public function __construct() {
        $this->fieldPhotoGallery = 'photo_gallery';
    }

    /**
     * Activate the plugin
     *
     * @param string $mainPluginFile the path to the main plugin file
     */
    public function activate(string $mainPluginFile) : void {
        register_activation_hook($mainPluginFile, function() : void {
            if(!class_exists('RW_Meta_Box')) die('Meta Box is not activated');
        });
    }

    /**
     * Execute the plugin
     */
    public function execute() : void {
        $this->init();
        $this->afterSetupTheme();
        $this->addMetaBoxes();
        $this->adminColumns();
    }

    /**
     * Use the init action to register the gallery and photo custom post types
     */
    private function init() : void {
        add_action('init', function() : void {
            register_post_type(self::GALLERY, [
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
            register_post_type(self::PHOTO, [
                'labels' => [
                    'name' => 'Photos',
                    'singular_name' => 'Photo'
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
     * Use the rwmb_meta_boxes filter to add meta boxes to the gallery and photo custom post types
     */
    private function addMetaBoxes() : void {
        add_filter('rwmb_meta_boxes', function(array $metaBoxes) : array {
            $metaBoxes[] = [
                'id' => 'photo_informations',
                'title' => 'Photo informations',
                'post_types' => [self::PHOTO],
                'fields' => [
                    [
                        'name' => 'Gallery',
                        'id' => $this->fieldPhotoGallery,
                        'type' => 'select',
                        'options' => $this->getGalleries()
                    ]
                ]
            ];
            return $metaBoxes;
        });
    }

    /**
     * Add admin columns to the gallery and photo custom post types
     */
    private function adminColumns() : void {
        $columnGalleryKey = 'gallery';
        $columnGalleryValue = 'Gallery';
        $columnPhotoKey = 'photo';
        add_filter('manage_'.self::PHOTO.'_posts_columns', function(array $columns) use ($columnGalleryKey, $columnGalleryValue, $columnPhotoKey) : array {
            $columns[$columnGalleryKey] = $columnGalleryValue;
            $columns[$columnPhotoKey] = 'Photo';
            return $columns;
        });
        add_action('manage_'.self::PHOTO.'_posts_custom_column', function(string $columnName) use ($columnGalleryKey, $columnPhotoKey) : void {
            if($columnName === $columnGalleryKey) {
                $galleryID = get_post_meta(get_the_ID(), $this->fieldPhotoGallery, true);
                echo get_post($galleryID)->post_title;
            } else if($columnName === $columnPhotoKey) {
                echo '<img src="'.$this->getPhotoThumbnailUrl(get_the_ID()).'" alt="'.get_the_title().'">';
            }
        });
        add_filter('manage_edit-'.self::PHOTO.'_sortable_columns', function(array $columns) use ($columnGalleryKey, $columnGalleryValue) : array {
            $columns[$columnGalleryKey] = $columnGalleryValue;
            return $columns;
        });
        $columnNumberOfPhotosKey = 'number_of_photos';
        $columnNumberOfPhotosValue = 'Photos';
        add_filter('manage_'.self::GALLERY.'_posts_columns', function(array $columns) use ($columnNumberOfPhotosKey, $columnNumberOfPhotosValue) : array {
            $columns[$columnNumberOfPhotosKey] = $columnNumberOfPhotosValue;
            return $columns;
        });
        add_action('manage_'.self::GALLERY.'_posts_custom_column', function(string $columnName) use ($columnNumberOfPhotosKey) {
            if($columnName === $columnNumberOfPhotosKey) echo $this->getNumberOfPhotosInGallery(get_the_ID());
        });
    }

    /**
     * Get the galleries
     *
     * @return array the galleries
     */
    public function getGalleries() : array {
        $galleries = [];
        $args = [
            'post_type' => self::GALLERY,
            'posts_per_page' => -1,
            'order' => 'ASC'
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $galleries[get_the_ID()] = get_the_title();
        }
        return $galleries;
    }

    /**
     * Get the gallery photo url
     *
     * @param int $galleryID the id of the gallery
     * @return string the gallery photo url
     */
    public function getGalleryPhotoUrl(int $galleryID) : string {
        $url = get_the_post_thumbnail_url($galleryID);
        return (isset($url)) ? esc_url($url) : '';
    }

    /**
     * Get the photo url
     *
     * @param int $photoID the id of the photo
     * @return string the photo url
     */
    public function getPhotoUrl(int $photoID) : string {
        $url = get_the_post_thumbnail_url($photoID);
        return (isset($url)) ? esc_url($url) : '';
    }

    /**
     * Get the photo thumbnail url
     *
     * @param int $photoID the id of the photo
     * @return string the photo thumbnail
     */
    public function getPhotoThumbnailUrl(int $photoID) : string {
        $url = get_the_post_thumbnail_url($photoID, 'thumbnail');
        return (isset($url)) ? esc_url($url) : '';
    }

    /**
     * Get the number of photos in a gallery
     *
     * @param int $galleryID the id of the gallery
     * @return int the number of photos in a gallery
     */
    private function getNumberOfPhotosInGallery(int $galleryID) : int {
        $args = [
            'post_type' => self::PHOTO,
            'posts_per_page' => -1,
            'meta_key' => $this->fieldPhotoGallery,
            'meta_value' => $galleryID
        ];
        $wpQuery = new WP_Query($args);
        return $wpQuery->found_posts;
    }

    /**
     * Get the photo gallery field id
     *
     * @return string the photo gallery field id
     */
    public function getPhotoGalleryFieldID() : string {
        return $this->fieldPhotoGallery;
    }
}