<?php
namespace KC\Image;

use KC\Core\Action;
use KC\Core\BaseModule;
use KC\Core\Constant;
use KC\Core\Filter;
use KC\Core\IModule;
use KC\Core\PostType;
use KC\Utils\PluginHelper;
use \WP_Query;

/**
 * The ImageModule class contains functionality to handle images
 */
class ImageModule extends BaseModule implements IModule {

    private string $fieldImageGallery;

    /**
     * Initialize a new instance of the ImageModule class
     */
    public function __construct() {
        $this->fieldImageGallery = 'photo_gallery';
    }

    /**
     * Setup the image module
     */
    public function setupModule() : void {
        $this->registerPostType();
        $this->addMetaBoxes();
        $this->addAdminColumns();        
    }

    /**
     * Register the image custom post type
     */
    private function registerPostType() : void {
        add_action(Action::INIT, function() : void {
            register_post_type(PostType::IMAGE, [
                'labels' => [
                    'name' => __('Images'),
                    'singular_name' => __('Image')
                ],
                'public' => false,
                'has_archive' => false,
                'supports' => [Constant::TITLE, Constant::THUMBNAIL],
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
     * Add meta boxes to the image custom post type
     */
    private function addMetaBoxes() : void {
        add_filter(Filter::META_BOXES, function(array $metaBoxes) : array {
            $metaBoxes[] = [
                'id' => 'image_informations',
                'title' => 'Image informations',
                'post_types' => [PostType::IMAGE],
                'fields' => [
                    [
                        'name' => __('Gallery'),
                        'id' => $this->fieldImageGallery,
                        'type' => 'select',
                        'options' => parent::getAllPosts(PostType::GALLERY)
                    ]
                ]
            ];
            return $metaBoxes;
        });
    }

    /**
     * Add admin columns to the image custom post type
     */
    private function addAdminColumns() : void {
        $columnGalleryKey = 'gallery';
        $columnGalleryValue = 'Gallery';
        $columnImageKey = 'image';
        add_filter(Filter::getManagePostsColumnsFilter(PostType::IMAGE), function(array $columns) use ($columnGalleryKey, $columnGalleryValue, $columnImageKey) : array {
            $columns[$columnGalleryKey] = $columnGalleryValue;
            $columns[$columnImageKey] = ucfirst($columnImageKey);
            return $columns;
        });
        add_action(Action::getManagePostsCustomColumn(PostType::IMAGE), function(string $columnName) use ($columnGalleryKey, $columnImageKey) : void {
            if($columnName === $columnGalleryKey) {
                $galleryID = PluginHelper::getFieldValue($this->fieldImageGallery, get_the_ID());
                echo get_the_title($galleryID);
            } else if($columnName === $columnImageKey) {
                echo '<img src="'.PluginHelper::getImageUrl(get_the_ID(), Constant::THUMBNAIL).'" alt="'.get_the_title().'">';
            }
        });
    }

    /**
     * Get the images from a gallery
     * 
     * @param int $galleryId the gallery id
     * @return array the images
     */
    public function getImages(int $galleryId) : array {
        $images = [];
        $args = [
            'post_type' => PostType::IMAGE,
            'posts_per_page' => -1,
            'orderby' => [Constant::TITLE],
            'order' => Constant::ASC,
            'meta_key' => $this->fieldImageGallery,
            'meta_value' => $galleryId
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $id = get_the_ID();
            $url = PluginHelper::getImageUrl($id);
            $imageInfo = wp_get_attachment_image_src(attachment_url_to_postid($url));
            $images[] = [
                'title' => get_the_title(),
                'url' => $url,
                'thumbnail' => PluginHelper::getImageUrl($id, Constant::THUMBNAIL),
                'gallery' => $galleryId,
                'width' => $imageInfo[1].'px',
                'height' => $imageInfo[2].'px'
            ];
        }
        return $images;
    }
}