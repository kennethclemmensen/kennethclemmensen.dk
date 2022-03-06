<?php
namespace KC\Core\Modules;

use KC\Core\SortingOrder;
use KC\Core\PostType;
use \WP_Query;

/**
 * The BaseModule class contains basic functionality for a module
 */
class BaseModule {

	/**
	 * Get all the posts from a post type
	 * 
	 * @param PostType $postType the post type
	 * @return array all the posts
	 */
	protected function getAllPosts(PostType $postType) : array {
		$posts = [];
		$args = [
			'post_type' => $postType->value,
			'posts_per_page' => -1,
			'order' => SortingOrder::Ascending->value
		];
		$wpQuery = new WP_Query($args);
		while($wpQuery->have_posts()) {
			$wpQuery->the_post();
			$posts[get_the_ID()] = get_the_title();
		}
		return $posts;
	}
}