<?php
namespace KCGallery\Includes;

use \WP_Query;

/**
 * Class KCGallery contains methods to handle the functionality of the plugin
 * @package KCGallery\Includes
 */
class KCGallery {

    private $fieldGalleryPage;
    private $fieldPhotoGallery;

    public const GALLERY = 'gallery';
    public const PHOTO = 'photo';

    /**
     * KCGallery constructor
     */
    public function __construct() {
        $this->fieldGalleryPage = 'gallery_page';
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
        $this->loadDependencies();
        $loader = new KCGalleryLoader();
        $loader->loadStylesAndScripts();
        $this->init();
        $this->afterSetupTheme();
        $this->addMetaBoxes();
        $this->addShortcodes();
        $this->adminColumns();
    }

    /**
     * Load the dependencies files
     */
    private function loadDependencies() : void {
        require_once 'KCGalleryLoader.php';
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
                'public' => false,
                'has_archive' => false,
                'supports' => ['title', 'thumbnail'],
                'menu_icon' => 'dashicons-format-gallery',
                'publicly_queryable' => true,
                'show_ui' => true,
                'exclude_from_search' => true,
                'show_in_nav_menus' => false,
                'rewrite' => false
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
                'id' => 'gallery_informations',
                'title' => 'Gallery informations',
                'post_types' => [self::GALLERY],
                'fields' => [
                    [
                        'name' => 'Page',
                        'id' => $this->fieldGalleryPage,
                        'type' => 'select',
                        'options' => $this->getPages()
                    ]
                ]
            ];
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
     * Add the galleries and gallery shortcodes to show a list of galleries and a single gallery
     */
    private function addShortcodes() : void {
        add_shortcode('galleries', function() : string {
            $html = '<div class="kc-galleries">';
            $galleries = $this->getGalleries();
            foreach($galleries as $key => $gallery) {
                $html .= '<div class="kc-galleries__gallery">';
                $html .= '<a href="'.$this->getGalleryPageUrl($key).'"><img src="'.$this->getGalleryPhotoUrl($key).'" alt="'.get_the_title($key).'"></a>';
                $html .= '</div>';
            }
            $html .= '</div>';
            return $html;
        });
        add_shortcode('gallery', function(array $atts) : string {
            $html = '<div class="kc-gallery">';
            $galleryID = addslashes($atts['id']);
            $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
            $args = [
                'post_type' => self::PHOTO,
                'posts_per_page' => 39,
                'order' => 'ASC',
                'meta_key' => $this->fieldPhotoGallery,
                'meta_value' => $galleryID,
                'paged' => $paged
            ];
            $wpQuery = new WP_Query($args);
            while($wpQuery->have_posts()) {
                $wpQuery->the_post();
                $id = get_the_ID();
                $title = get_the_title();
                $html .= '<a href="'.$this->getPhotoUrl($id).'" data-title="'.$title.'" data-lightbox="'.$galleryID.'">';
                $html .= '<img src="'.$this->getPhotoThumbnailUrl($id).'" class="kc-gallery__photo" alt="'.$title.'"></a>';
            }
            $html .= '<div class="kc-gallery__pagination">';
            $big = 999999999; // need an unlikely integer
            $replace = '%#%';
            $html .= paginate_links([
                'base' => str_replace($big, $replace, esc_url(get_pagenum_link($big))),
                'format' => '?paged='.$replace,
                'current' => max(1, $paged),
                'total' => $wpQuery->max_num_pages,
                'prev_text' => 'Forrige',
                'next_text' => 'Næste'
            ]);
            $html .= '</div></div>';
            return $html;
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
                $galleryID = rwmb_meta($this->fieldPhotoGallery);
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
     * Get the pages
     *
     * @return array the pages
     */
    private function getPages() : array {
        $pages = [];
        $args = [
            'post_type' => 'page',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $pages[get_the_ID()] = get_the_title();
        }
        return $pages;
    }

    /**
     * Get the galleries
     *
     * @return array the galleries
     */
    private function getGalleries() : array {
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
     * Get the gallery page url
     *
     * @param int $galleryID the id of the gallery
     * @return string the gallery page url
     */
    private function getGalleryPageUrl(int $galleryID) : string {
        return get_permalink(rwmb_meta($this->fieldGalleryPage, [], $galleryID));
    }

    /**
     * Get the gallery photo url
     *
     * @param int $galleryID the id of the gallery
     * @return string the gallery photo url
     */
    private function getGalleryPhotoUrl(int $galleryID) : string {
        $url = get_the_post_thumbnail_url($galleryID);
        return (isset($url)) ? esc_url($url) : '';
    }

    /**
     * Get the photo url
     *
     * @param int $photoID the id of the photo
     * @return string the photo url
     */
    private function getPhotoUrl(int $photoID) : string {
        $url = get_the_post_thumbnail_url($photoID);
        return (isset($url)) ? esc_url($url) : '';
    }

    /**
     * Get the photo thumbnail url
     *
     * @param int $photoID the id of the photo
     * @return string the photo thumbnail
     */
    private function getPhotoThumbnailUrl(int $photoID) : string {
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
}