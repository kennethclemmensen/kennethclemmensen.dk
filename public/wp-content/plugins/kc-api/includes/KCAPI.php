<?php
namespace KCAPI\Includes;

use \WP_REST_Request;
use \WP_REST_Response;
use \WP_REST_Server;
use \WP_Query;

/**
 * Class KCAPI contains methods to set up API endpoints and get data
 * @package KCAPI\Includes
 */
class KCAPI {

    /**
     * Execute the plugin
     */
    public function execute() : void {
        $this->restApiInit();
    }

    /**
     * Use the rest_api_init to register a route
     */
    private function restApiInit() : void {
        add_action('rest_api_init', function() : void {
            register_rest_route('kcapi/v1', '/search/pagesbytitle/(?P<title>[\S]+)', [
                'methods' => [WP_REST_Server::READABLE],
                'callback' => function(WP_REST_Request $request) : WP_REST_Response {
                    $title = sanitize_text_field($request->get_param('title'));
                    $statusCode = 200;
                    return new WP_REST_Response($this->getPagesByTitle($title), $statusCode);
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