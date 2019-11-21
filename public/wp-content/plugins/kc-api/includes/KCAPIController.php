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

    private $downloadCounterField;

    /**
     * KCAPIController constructor
     */
    public function __construct() {
        $this->downloadCounterField = 'fdwc_field_download_counter';
    }

    /**
     * Register routes
     */
    public function registerRoutes() : void {
        $namespace = 'kcapi/v1';
        $title = 'title';
        $page = 'page';
        $perPage = 'per_page';
        $statusCodeOk = 200;
        register_rest_route($namespace, '/pages/(?P<'.$title.'>[\S]+)', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($title, $page, $perPage, $statusCodeOk) : WP_REST_Response {
                $pages = $this->getPagesByTitle($request->get_param($title), $request->get_param($page) - 1, $request->get_param($perPage));
                return new WP_REST_Response($pages, $statusCodeOk);
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
                        return $value > 0;
                    }
                ],
                $perPage => [
                    'required' => false,
                    'validate_callback' => function(int $value) : bool {
                        return $value > 0;
                    }
                ]
            ],
            'permission_callback' => function() : bool {
                return true;
            }
        ]);
        $key = 'fileid';
        $route = '/fileDownloads';
        $args = [
            $key => [
                'required' => true,
                'sanitize_callback' => function(int $value) : int {
                    return sanitize_text_field($value);
                },
                'validate_callback' => function(int $value) : bool {
                    return !empty($value);
                }
            ]
        ];
        register_rest_route($namespace, $route, [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($key, $statusCodeOk) : WP_REST_Response {
                $fileDownloads = $this->getFileDownloads($request->get_param($key));
                return new WP_REST_Response($fileDownloads, $statusCodeOk);
            },
            'args' => $args,
            'permission_callback' => function() : bool {
                return true;
            }
        ]);
        register_rest_route($namespace, $route, [
            'methods' => ['PUT'],
            'callback' => function(WP_REST_Request $request) use ($key, $statusCodeOk) : WP_REST_Response {
                $this->updateFileDownloadCounter($request->get_param($key));
                return new WP_REST_Response($statusCodeOk);
            },
            'args' => $args,
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
    private function getPagesByTitle(string $title, int $offset, ?int $resultsPerPage = null) : array {
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

    /**
     * Update the download counter for a file
     *
     * @param int $fileID the id of the file
     */
    private function updateFileDownloadCounter(int $fileID) : void {
        $downloads = $this->getFileDownloads($fileID);
        $downloads++;
        update_post_meta($fileID, $this->downloadCounterField, $downloads);
    }

    /**
     * Get the number of file downloads for a file
     *
     * @param int $fileID the id of the file
     * @return int the number of file downloads
     */
    private function getFileDownloads(int $fileID) : int {
        return get_post_meta($fileID, $this->downloadCounterField, true);
    }
}