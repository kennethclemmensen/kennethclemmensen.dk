<?php
namespace KCAPI\Includes;

use \WP_REST_Request;
use \WP_REST_Response;
use \WP_REST_Server;
use \WP_Query;

/**
 * Class KCAPI contains methods to handle the functionality of the plugin
 * @package KCAPI\Includes
 */
class KCAPI {

    /**
     * KCAPI constructor
     */
    public function __construct() {

    }

    /**
     * Execute the plugin
     */
    public function execute() {
        $this->restApiInit();
    }

    /**
     * Use the rest_api_init to register a route
     */
    private function restApiInit() {
        add_action('rest_api_init', function() {
            register_rest_route('kcapi/v1', '/search', [
                'methods' => [WP_REST_Server::CREATABLE],
                'callback' => function(WP_REST_Request $request) : WP_REST_Response {
                    $data = json_decode($request->get_body(), true);
                    $statusCode = 200;
                    return new WP_REST_Response($this->getPagesByTitle($data['title']), $statusCode);
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
    private function getPagesByTitle(string $title) : array {
        $pages = [];
        $args = [
            'post_type' => ['page'],
            'posts_per_page' => -1,
            's' => $title
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $pages[] = [
                'title' => get_the_title(),
                'link' => get_permalink(get_the_ID()),
                'excerpt' => get_the_excerpt()
            ];
        }
        return $pages;
    }
}