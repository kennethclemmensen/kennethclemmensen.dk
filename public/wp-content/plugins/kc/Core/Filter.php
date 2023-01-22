<?php
namespace KC\Core;

use KC\Core\PostTypes\PostType;

/**
 * The Filter class defines the filters
 */
final readonly class Filter {

	public const META_BOXES = 'rwmb_meta_boxes';

	public const MIMES = 'upload_mimes';

	/**
	 * Get the manage_{$post_type}_posts_columns filter for a post type
	 * 
	 * @param PostType $postType the post type
	 * @return string the manage posts columns filter for the post type
	 */
	public static function getManagePostsColumnsFilter(PostType $postType) : string {
		return 'manage_'.$postType->value.'_posts_columns';
	}

	/**
	 * Get the pre_update_option_{$option} filter for an option
	 * 
	 * @param string $option the option
	 * @return string the pre_update_option filter
	 */
	public static function getPreUpdateOptionFilter(string $option) : string {
		return 'pre_update_option_'.$option;
	}
}