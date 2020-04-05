<?php
namespace KC\Api;

use KC\Core\Constant;
use KC\Core\CustomPostType;
use KC\Slider\Slider;
use KC\Slider\SliderSettings;
use KC\Utils\PluginHelper;
use \WP_REST_Request;
use \WP_REST_Response;
use \WP_REST_Server;
use \WP_Query;

/**
 * The APIController contains methods to register routes and handle requests and responses
 */
class APIController {

    private $namespace;
    private $statusCodeOk;

    /**
     * Initialize a new instance of the APIController class
     */
    public function __construct() {
        $this->namespace = 'kcapi/v1';
        $this->statusCodeOk = 200;
    }

    /**
     * Register routes to the API
     */
    public function registerRoutes() : void {
        $this->registerPagesRoute();
        $this->registerFileDownloadCounterRoutes();
        $this->registerSliderRoute();
    }

    /**
     * Register the pages route
     */
    private function registerPagesRoute() : void {
        $title = 'title';
        $page = 'page';
        $perPage = 'per_page';
        register_rest_route($this->namespace, '/pages/(?P<'.$title.'>[\S]+)', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($title, $page, $perPage) : WP_REST_Response {
                $pages = $this->getPagesByTitle($request->get_param($title), $request->get_param($page) - 1, $request->get_param($perPage));
                return new WP_REST_Response($pages, $this->statusCodeOk);
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
    }

    /**
     * Register the file download counter routes
     */
    private function registerFileDownloadCounterRoutes() : void {
        $route = '/fileDownloads';
        $key = 'fileid';
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
        register_rest_route($this->namespace, $route, [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($key) : WP_REST_Response {
                $fileDownloads = PluginHelper::getFileDownloads($request->get_param($key));
                return new WP_REST_Response($fileDownloads, $this->statusCodeOk);
            },
            'args' => $args,
            'permission_callback' => function() : bool {
                return true;
            }
        ]);
        register_rest_route($this->namespace, $route, [
            'methods' => ['PUT'],
            'callback' => function(WP_REST_Request $request) use ($key) : WP_REST_Response {
                $this->updateFileDownloadCounter($request->get_param($key));
                return new WP_REST_Response($this->statusCodeOk);
            },
            'args' => $args,
            'permission_callback' => function() : bool {
                return true;
            }
        ]);
    }

    /**
     * Register the slider route
     */
    private function registerSliderRoute() : void {
        register_rest_route($this->namespace, '/slider', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) : WP_REST_Response {                
                $sliderSettings = SliderSettings::getInstance();
                $data = [
                    'delay' => $sliderSettings->getDelay(),
                    'duration' => $sliderSettings->getDuration(),
                    'slidesImages' => $this->getSlidesImages()
                ];
                return new WP_REST_Response($data, $this->statusCodeOk);
            },
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
        $downloads = PluginHelper::getFileDownloads($fileID);
        $downloads++;
        update_post_meta($fileID, Constant::FILE_DOWNLOAD_COUNTER_FIELD_ID, $downloads);
    }

    /**
     * Get the slides images
     * 
     * @return array the slides images
     */
    private function getSlidesImages() : array {
        $slidesImages = [];
        $slider = new Slider();
        $args = [
            'post_type' => CustomPostType::SLIDES,
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $slidesImages[] = $slider->getSlideImageUrl(get_the_ID());
        }
        return $slidesImages;
    }
}