<?php
namespace KCGallery\Includes;

use \WP_Query;

/**
 * Class KCGallery contains methods to handle the functionality of the plugin
 * @package KCGallery\Includes
 */
class KCGallery {

    private $fieldGalleryPage;
    private $fieldGalleryPhoto;
    private $fieldPhoto;
    private $fieldPhotoGallery;
    private $gallerySettings;

    private const GALLERY = 'gallery';
    private const PHOTO = 'photo';

    /**
     * KCGallery constructor
     */
    public function __construct() {
        $prefix = 'gallery_';
        $this->fieldGalleryPage = $prefix.'page';
        $this->fieldGalleryPhoto = $prefix.'photo';
        $prefix = 'photo_';
        $this->fieldPhoto = $prefix.'photo';
        $this->fieldPhotoGallery = $prefix.'gallery';
        $this->gallerySettings = null;
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
        $loader = new KCGalleryLoader();
        $loader->loadStylesAndScripts();
        $this->gallerySettings = new KCGallerySettings();
        $this->init();
        $this->addMetaBoxes();
        $this->addShortcodes();
        $this->adminColumns();
    }

    /**
     * Load the dependencies files
     */
    private function loadDependencies() : void {
        require_once 'KCGalleryLoader.php';
        require_once 'KCGallerySettings.php';
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
                'supports' => ['title'],
                'menu_icon' => 'dashicons-format-gallery'
            ]);
            register_post_type(self::PHOTO, [
                'labels' => [
                    'name' => 'Photos',
                    'singular_name' => 'Photo'
                ],
                'public' => true,
                'has_archive' => true,
                'supports' => ['title'],
                'menu_icon' => 'dashicons-format-image'
            ]);
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
                    ],
                    [
                        'name' => 'Photo',
                        'id' => $this->fieldGalleryPhoto,
                        'type' => 'image_advanced',
                        'max_file_uploads' => 1
                    ]
                ]
            ];
            $metaBoxes[] = [
                'id' => 'photo_informations',
                'title' => 'Photo informations',
                'post_types' => [self::PHOTO],
                'fields' => [
                    [
                        'name' => 'Photo',
                        'id' => $this->fieldPhoto,
                        'type' => 'image_advanced',
                        'max_file_uploads' => 1
                    ],
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
                $html .= '<a href="'.$this->getPhoto(get_the_ID()).'" data-title="'.get_the_title().'" data-lightbox="'.$galleryID.'">';
                $html .= '<img src="'.$this->getPhotoThumbnail(get_the_ID()).'" class="kc-gallery__photo" alt="'.get_the_title().'"></a>';
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
                echo '<img src="'.$this->getPhotoThumbnail().'" alt="'.get_the_title().'">';
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
     * @param int|null $postID the id of the post
     * @param array $args an array of arguments
     * @return string the gallery page url
     */
    private function getGalleryPageUrl(int $postID = null, array $args = []) : string {
        return get_permalink(rwmb_meta($this->fieldGalleryPage, $args, $postID));
    }

    /**
     * Get the gallery photo url
     *
     * @param int|null $postID the id of the post
     * @param array $args an array of arguments
     * @return string the gallery photo url
     */
    private function getGalleryPhotoUrl(int $postID = null, array $args = []) : string {
        $photo = rwmb_meta($this->fieldGalleryPhoto, $args, $postID);
        return array_shift($photo)['full_url'];
    }

    /**
     * Get the photo
     *
     * @param int|null $postID the id of the post
     * @param array $args an array of arguments
     * @return string the photo
     */
    private function getPhoto(int $postID = null, array $args = []) : string {
        $photo = rwmb_meta($this->fieldPhoto, $args, $postID);
        $photoID = array_shift($photo)['ID'];
        return wp_get_attachment_image_src($photoID, $this->gallerySettings->getPhotoKey())[0];
    }

    /**
     * Get the photo thumbnail
     *
     * @param int|null $postID the id of the post
     * @param array $args an array of arguments
     * @return string the photo thumbnail
     */
    private function getPhotoThumbnail(int $postID = null, array $args = []) : string {
        $photo = rwmb_meta($this->fieldPhoto, $args, $postID);
        $photoID = array_shift($photo)['ID'];
        return wp_get_attachment_image_src($photoID, $this->gallerySettings->getPhotoThumbnailKey())[0];
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