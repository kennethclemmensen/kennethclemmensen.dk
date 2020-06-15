<?php
namespace KC\Core;

use KC\Core\Constant;
use \WP_Query;

/**
 * The BaseModule class contains basic functionality for a module
 */
class BaseModule {

    /**
     * Get all the posts from a post type
     * 
     * @param string $postType the post type
     * @return array all the posts
     */
    protected function getAllPosts(string $postType) : array {
        $posts = [];
        $args = [
            'post_type' => $postType,
            'posts_per_page' => -1,
            'order' => Constant::ASC
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $posts[get_the_ID()] = get_the_title();
        }
        return $posts;
    }
}