<?php
namespace KC\Gallery;

use KC\Core\Action;
use KC\Core\BaseModule;
use KC\Core\Constant;
use KC\Core\Filter;
use KC\Core\IModule;
use KC\Core\PostType;
use KC\Utils\PluginHelper;
use \WP_Query;

/**
 * The GalleryModule class contains functionality to handle galleries
 */
class GalleryModule extends BaseModule implements IModule {

    private string $fieldParentPage;

    /**
     * Initialize a new instance of the GalleryModule class
     */
    public function __construct() {
        $this->fieldParentPage = 'parent_page';
    }

    /**
     * Setup the gallery module
     */
    public function setupModule() : void {
        $this->registerPostType();
        $this->updatePostParent();
        $this->addMetaBoxes();        
    }

    /**
     * Register the gallery custom post type
     */
    private function registerPostType() : void {
        add_action(Action::INIT, function() : void {
            register_post_type(PostType::GALLERY, [
                'labels' => [
                    'name' => PluginHelper::getTranslatedString('Galleries'),
                    'singular_name' => PluginHelper::getTranslatedString('Gallery')
                ],
                'public' => true,
                'has_archive' => true,
                'supports' => [Constant::TITLE, Constant::EDITOR, Constant::THUMBNAIL],
                'menu_icon' => 'dashicons-format-gallery',
                'rewrite' => ['slug' => '/billeder', 'with_front' => false]
            ]);
        });
    }

    /**
     * Update the post_parent column in the database when saving a gallery
     */
    private function updatePostParent() : void {
        add_action(Action::getSavePostAction(PostType::GALLERY), function(int $postID) : void {
            global $wpdb;
            PluginHelper::setFieldValue($_REQUEST[$this->fieldParentPage], $this->fieldParentPage, $postID);
            $parentPage = PluginHelper::getFieldValue($this->fieldParentPage, $postID);
            $wpdb->update($wpdb->prefix.'posts', ['post_parent' => $parentPage], ['ID' => $postID]);
        });
    }

    /**
     * Add meta boxes to the gallery custom post type
     */
    private function addMetaBoxes() : void {
        add_filter(Filter::META_BOXES, function(array $metaBoxes) : array {
            $metaBoxes[] = [
                'id' => 'gallery_informations',
                'title' => PluginHelper::getTranslatedString('Gallery informations'),
                'post_types' => [PostType::GALLERY],
                'fields' => [
                    [
                        'name' => PluginHelper::getTranslatedString('Parent page'),
                        'id' => $this->fieldParentPage,
                        'type' => 'select',
                        'options' => parent::getAllPosts(PostType::PAGE)
                    ]
                ]
            ];
            return $metaBoxes;
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
            'post_type' => PostType::GALLERY,
            'posts_per_page' => -1,
            'order' => Constant::ASC
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $galleries[] = [
                'title' => get_the_title(),
                'link' => get_permalink(get_the_ID()),
                'image' => PluginHelper::getImageUrl(get_the_ID())
            ];
        }
        return $galleries;
    }
}