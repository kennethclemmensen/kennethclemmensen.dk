<?php
namespace KCAPI\Includes;

use \WP_REST_Request;
use \WP_REST_Response;
use \WP_REST_Server;
use \WP_Query;

/**
 * Class KC_API contains methods to handle the functionality of the plugin
 * @package KCAPI\Includes
 */
class KC_API {

    /**
     * KC_API constructor
     */
    public function __construct() {

    }

    /**
     * Execute the plugin
     */
    public function execute() {
        $this->rest_api_init();
    }

    /**
     * Use the rest_api_init to register a route
     */
    private function rest_api_init() {
        add_action('rest_api_init', function() {
            register_rest_route('kcapi/v1', '/search', [
                'methods' => [WP_REST_Server::CREATABLE],
                'callback' => function(WP_REST_Request $request) : WP_REST_Response {
                    $data = json_decode($request->get_body(), true);
                    $status_code = 200;
                    return new WP_REST_Response($this->get_pages_by_title($data['title']), $status_code);
                }
            ]);
        });
    }

    /**
     * Get the pages by title
     *
     * @param string $title the title to get the pages from
     * @return array the pages
     */
    private function get_pages_by_title(string $title) : array {
        $pages = [];
        $args = [
            'post_type' => ['page'],
            'posts_per_page' => -1,
            's' => $title
        ];
        $wp_query = new WP_Query($args);
        while($wp_query->have_posts()) {
            $wp_query->the_post();
            $pages[] = [
                'title' => get_the_title(),
                'link' => get_permalink(get_the_ID()),
                'excerpt' => get_the_excerpt()
            ];
        }
        return $pages;
    }
}