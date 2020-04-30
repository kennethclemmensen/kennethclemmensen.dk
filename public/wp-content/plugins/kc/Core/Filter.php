<?php
namespace KC\Core;

/**
 * The Filter class defines the filters
 */
class Filter {

    public const META_BOXES = 'rwmb_meta_boxes';

    public const MIMES = 'upload_mimes';

    /**
     * Get the manage_{$post_type}_posts_columns filter for a post type
     * 
     * @param string $postType the post type
     * @return string the manage posts columns filter for the post type
     */
    public static function getManagePostsColumnsFilter(string $postType) : string {
        return 'manage_'.$postType.'_posts_columns';
    }

    /**
     * Get the manage_{$this->screen->id}_sortable_columns filter for a post type
     * 
     * @param string $postType the post type
     * @return string the manage edit sortable columns filter for the post type
     */
    public static function getSortableColumnsFilter(string $postType) : string {
        return 'manage_edit-'.$postType.'_sortable_columns';
    }
}