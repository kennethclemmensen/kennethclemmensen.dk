<?php
namespace KC\Page;

use KC\Core\Constant;
use KC\Core\IModule;
use \WP_Query;

/**
 * The PageModule class contains functionality to handle pages
 */
class PageModule implements IModule {

    /**
     * Get the pages
     * 
     * @return array the pages
     */
    public function getPages() : array {
        $pages = [];
        $args = [
            'order' => Constant::ASC,
            'orderby' => 'menu_order',
            'posts_per_page' => -1,
            'post_type' => [Constant::PAGE]
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $pages[get_the_ID()] = get_the_title();
        }
        return $pages;
    }

    /**
     * Get the pages by title
     *
     * @param string $title the title to get the pages from
     * @return array the pages
     */
    public function getPagesByTitle(string $title) : array {
        $pages = [];
        $args = [
            'order' => Constant::ASC,
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
}