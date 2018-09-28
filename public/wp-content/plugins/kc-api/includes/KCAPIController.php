<?php
namespace KCAPI\Includes;

use \WP_REST_Controller;
use \WP_REST_Request;
use \WP_REST_Response;
use \WP_REST_Server;
use \WP_Query;

class KCAPIController extends WP_REST_Controller {

    /**
     * Register routes
     */
    public function registerRoutes() : void {
        register_rest_route('kcapi/v1', '/pages/(?P<title>[\S]+)', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) : WP_REST_Response {
                $title = sanitize_text_field($request->get_param('title'));
                $statusCode = 200;
                return new WP_REST_Response($this->getPagesByTitle($title), $statusCode);
            }
        ]);
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