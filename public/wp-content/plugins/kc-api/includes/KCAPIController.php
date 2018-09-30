<?php
namespace KCAPI\Includes;

use \WP_REST_Controller;
use \WP_REST_Request;
use \WP_REST_Response;
use \WP_REST_Server;
use \WP_Query;

/**
 * Class KCAPIController contains methods to set up API endpoints and get data
 * @package KCAPI\Includes
 */
final class KCAPIController extends WP_REST_Controller {

    /**
     * Register routes
     */
    public function registerRoutes() : void {
        $title = 'title';
        $page = 'page';
        $perPage = 'per_page';
        register_rest_route('kcapi/v1', '/pages/(?P<'.$title.'>[\S]+)', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($title, $page, $perPage) : WP_REST_Response {
                $pages = $this->getPagesByTitle($request->get_param($title), $request->get_param($page) - 1, $request->get_param($perPage));
                $statusCode = 200;
                return new WP_REST_Response($pages, $statusCode);
            },
            'args' => [
                $title => [
                    'required' => true,
                    'sanitize_callback' => function(string $value) : string {
                        return sanitize_text_field($value);
                    },
                    'validate_callback' => function(string $value) : bool {
                        return !empty($value);
                    }
                ],
                $page => [
                    'default' => 1,
                    'required' => false,
                    'validate_callback' => function(int $value) : bool {
                        return is_int($value) && $value > 0;
                    }
                ],
                $perPage => [
                    'required' => false,
                    'validate_callback' => function(int $value) : bool {
                        return is_int($value) && $value > 0;
                    }
                ]
            ],
            'permission_callback' => function() : bool {
                return true;
            }
        ]);
    }

    /**
     * Get the pages by title
     *
     * @param string $title the title to get the pages from
     * @param int $offset the number of pages to pass over
     * @param int $resultsPerPage the number of results per page
     * @return array the pages
     */
    private function getPagesByTitle(string $title, int $offset, int $resultsPerPage = null) : array {
        $pages = [];
        $args = [
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'offset' => $offset,
            'posts_per_page' => ($resultsPerPage !== null) ? $resultsPerPage : -1,
            'post_type' => ['page'],
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