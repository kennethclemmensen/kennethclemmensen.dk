<?php
namespace KC\Api;

use KC\Core\Constant;
use KC\Files\Files;
use KC\Gallery\Gallery;
use KC\Slider\Slider;
use KC\Slider\SliderSettings;
use KC\Utils\PluginHelper;
use \WP_REST_Request;
use \WP_REST_Response;
use \WP_REST_Server;

/**
 * The ApiController contains methods to register routes and handle requests and responses
 */
class ApiController {

    private $namespace;
    private $statusCodeOk;

    /**
     * Initialize a new instance of the ApiController class
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
        $this->registerGalleriesRoute();
    }

    /**
     * Register the pages route
     */
    private function registerPagesRoute() : void {
        $title = 'title';
        register_rest_route($this->namespace, '/pages/(?P<'.$title.'>[\S]+)', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($title) : WP_REST_Response {
                $pages = PluginHelper::getPagesByTitle($request->get_param($title));
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
        $fileId = 'fileid';
        $args = [
            $fileId => [
                'required' => true,
                'sanitize_callback' => function(int $value) : int {
                    return sanitize_text_field($value);
                },
                'validate_callback' => function(int $value) : bool {
                    return !empty($value);
                }
            ]
        ];
        $files = new Files();
        register_rest_route($this->namespace, $route, [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($fileId) : WP_REST_Response {
                $fileDownloads = PluginHelper::getFileDownloads($request->get_param($fileId));
                return new WP_REST_Response($fileDownloads, $this->statusCodeOk);
            },
            'args' => $args,
            'permission_callback' => function() : bool {
                return true;
            }
        ]);
        register_rest_route($this->namespace, $route, [
            'methods' => [Constant::PUT],
            'callback' => function(WP_REST_Request $request) use ($files, $fileId) : WP_REST_Response {
                $files->updateFileDownloadCounter($request->get_param($fileId));
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
        $slider = new Slider();
        $sliderSettings = new SliderSettings();
        register_rest_route($this->namespace, '/slider', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function() use ($slider, $sliderSettings) : WP_REST_Response {
                $data = [
                    'delay' => $sliderSettings->getDelay(),
                    'duration' => $sliderSettings->getDuration(),
                    'slidesImages' => $slider->getSlidesImages()
                ];
                return new WP_REST_Response($data, $this->statusCodeOk);
            },
            'permission_callback' => function() : bool {
                return true;
            }
        ]);
    }

    /**
     * Register the galleries route
     */
    private function registerGalleriesRoute() : void {
        $gallery = new Gallery();
        register_rest_route($this->namespace, '/galleries', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function() use ($gallery) : WP_REST_Response {
                return new WP_REST_Response($gallery->getGalleries(true), $this->statusCodeOk);
            },
            'permission_callback' => function() : bool {
                return true;
            }
        ]);
    }
}