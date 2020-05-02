<?php
namespace KC\Gallery;

use KC\Core\Action;
use KC\Core\Constant;
use KC\Core\Filter;
use KC\Core\IModule;
use KC\Core\PostType;
use KC\Page\PageModule;
use KC\Utils\PluginHelper;
use \WP_Query;

/**
 * The GalleryModule class contains functionality to handle galleries
 */
class GalleryModule implements IModule {

    private $fieldParentPage;

    /**
     * Initialize a new instance of the GalleryModule class
     */
    public function __construct() {
        $this->fieldParentPage = 'parent_page';
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
                    'name' => 'Galleries',
                    'singular_name' => 'Gallery'
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
            $pageModule = new PageModule();
            $metaBoxes[] = [
                'id' => 'gallery_informations',
                'title' => 'Gallery informations',
                'post_types' => [PostType::GALLERY],
                'fields' => [
                    [
                        'name' => 'Parent page',
                        'id' => $this->fieldParentPage,
                        'type' => 'select',
                        'options' => $pageModule->getPages()
                    ]
                ]
            ];
            return $metaBoxes;
        });
    }

    /**
     * Get the galleries
     *
     * @param bool $isCalledFromApi a value that indicates whether the method is called from the Api
     * @return array the galleries
     */
    public function getGalleries(bool $isCalledFromApi = false) : array {
        $galleries = [];
        $args = [
            'post_type' => PostType::GALLERY,
            'posts_per_page' => -1,
            'order' => Constant::ASC
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
}