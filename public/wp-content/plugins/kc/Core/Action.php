<?php
namespace KC\Core;

/**
 * The Action class defines the actions
 */
class Action {

    public const ADMIN_ENQUEUE_SCRIPTS = 'admin_enqueue_scripts';

    public const ADMIN_INIT = 'admin_init';

    public const ADMIN_MENU = 'admin_menu';

    public const AFTER_SETUP_THEME = 'after_setup_theme';

    public const INIT = 'init';

    public const PLUGINS_LOADED = 'plugins_loaded';

    public const REST_API_INIT = 'rest_api_init';

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