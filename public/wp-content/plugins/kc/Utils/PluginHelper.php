<?php
namespace KC\Utils;

use KC\Core\Constant;
use \WP_Query;

/**
 * The PluginHelper class contains utility methods to use in the plugin
 */
class PluginHelper {

    /**
     * Get the pages by title
     *
     * @param string $title the title to get the pages from
     * @return array the pages
     */
    public static function getPagesByTitle(string $title) : array {
        $pages = [];
        $args = [
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'posts_per_page' => -1,
            'post_type' => [Constant::PAGE],
            's' => $title
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $pages[] = [
                'title' => get_the_title(),
                'link' => get_permalink(get_the_ID()),
                'excerpt' => html_entity_decode(get_the_excerpt())
            ];
        }
        return $pages;
    }

    /**
     * Get the image url
     * 
     * @param int $imageID the id of the image
     * @param string $size the size of the image
     * @return string the image url
     */
    public static function getImageUrl(int $imageID, string $size = 'post-thumbnail') : string {
        return esc_url(get_the_post_thumbnail_url($imageID, $size));
    }
}