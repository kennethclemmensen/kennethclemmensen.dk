<?php
namespace KC\Core;

use KC\Core\PostTypes\PostType;

/**
 * The Action class defines the actions.
 * The class cannot be inherited.
 */
final class Action {

	public const string ADMIN_ENQUEUE_SCRIPTS = 'admin_enqueue_scripts';

	public const string ADMIN_INIT = 'admin_init';

	public const string ADMIN_MENU = 'admin_menu';

	public const string AFTER_SETUP_THEME = 'after_setup_theme';

	public const string INIT = 'init';

	public const string PHPMAILER_INIT = 'phpmailer_init';

	public const string PLUGINS_LOADED = 'plugins_loaded';

	public const string REST_API_INIT = 'rest_api_init';

	/**
	 * Get the save_post_{$post->post_type} action for a post type
	 * 
	 * @param PostType $postType the post type
	 * @return string the save post action for the post type
	 */
	public static function getSavePostAction(PostType $postType) : string {
		return 'save_post_'.$postType->value;
	}

	/**
	 * Get the manage_{$post->post_type}_posts_custom_column action for a post type
	 * 
	 * @param PostType $postType the post type
	 * @return string the manage posts custom column action for the post type
	 */
	public static function getManagePostsCustomColumn(PostType $postType) : string {
		return 'manage_'.$postType->value.'_posts_custom_column';
	}
}