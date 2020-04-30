<?php
namespace KC\Core;

/**
 * The Action class defines the actions
 */
class Action {

    public const API_INIT = 'rest_api_init';

    public const INIT = 'init';

    public const SETUP_THEME = 'after_setup_theme';

    /**
     * Get the save_post_{$post->post_type} action for a post type
     * 
     * @param string $postType the post type
     * @return string the save post action for the post type
     */
    public static function getSavePostAction(string $postType) : string {
        return 'save_post_'.$postType;
    }

    /**
     * Get the manage_{$post->post_type}_posts_custom_column action for a post type
     * 
     * @param string $postType the post type
     * @return string the manage posts custom column action for the post type
     */
    public static function getManagePostsCustomColumn(string $postType) : string {
        return 'manage_'.$postType.'_posts_custom_column';
    }
}