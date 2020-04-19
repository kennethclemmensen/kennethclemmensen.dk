<?php
namespace KC\Api;

use KC\Files\Files;
use KC\Gallery\Gallery;
use KC\Security\Security;
use KC\Slider\Slider;
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
    private $files;

    /**
     * Initialize a new instance of the ApiController class
     */
    public function __construct() {
        $this->namespace = 'kcapi/v1';
        $this->statusCodeOk = 200;
        $this->files = new Files();
    }

    /**
     * Register routes to the API
     */
    public function registerRoutes() : void {
        $this->registerPagesRoute();
        $this->registerFilesRoute();
        $this->registerFileDownloadCounterRoute();
        $this->registerSlidesRoute();
        $this->registerGalleriesRoutes();
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
                return Security::hasApiAccess();
            }
        ]);
    }

    /**
     * Register the files route
     */
    private function registerFilesRoute() : void {
        $type = 'type';
        register_rest_route($this->namespace, '/files', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => function(WP_REST_Request $request) use ($type) : WP_REST_Response {
                $fileTypes = explode(',', $request->get_param($type));
                return new WP_REST_Response($this->files->getFiles($fileTypes), $this->statusCodeOk);
            },
            'args' => [
                $type => [
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
                return Security::hasApiAccess();
            }
        ]);
    }

    /**
     * Register the file download counter route
     */
    private function registerFileDownloadCounterRoute() : void {
        $fileId = 'fileid';
        register_rest_route($this->namespace, '/fileDownloads', [
            'methods' => ['PUT'],
            'callback' => function(WP_REST_Request $request) use ($fileId) : WP_REST_Response {
                $this->files->updateFileDownloadCounter($request->get_param($fileId));
                return new WP_REST_Response($this->statusCodeOk);
            },
            'args' => [
                $fileId => [
                    'required' => true,
                    'sanitize_callback' => function(int $value) : int {
                        return sanitize_text_field($value);
                    },
                    'validate_callback' => function(int $value) : bool {
                        return !empty($value);
                    }
                ]
            ],
            'permission_callback' => function() : bool {
                return Security::hasApiAccess();
            }
        ]);
    }

    /**
     * Register the slides route
     */
    private function registerSlidesRoute() : void {
        $slider = new Slider();
        register_rest_route($this->namespace, '/slides', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function() use ($slider) : WP_REST_Response {
                return new WP_REST_Response($slider->getSlides(), $this->statusCodeOk);
            },
            'permission_callback' => function() : bool {
                return Security::hasApiAccess();
            }
        ]);
    }

    /**
     * Register the galleries routes
     */
    private function registerGalleriesRoutes() : void {
        $gallery = new Gallery();
        $route = '/galleries';
        register_rest_route($this->namespace, $route, [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function() use ($gallery) : WP_REST_Response {
                return new WP_REST_Response($gallery->getGalleries(true), $this->statusCodeOk);
            },
            'permission_callback' => function() : bool {
                return Security::hasApiAccess();
            }
        ]);
        $id = 'id';
        register_rest_route($this->namespace, $route.'/(?P<'.$id.'>[\S]+)', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($id, $gallery) : WP_REST_Response {
                $galleryId = $request->get_param($id);
                return new WP_REST_Response($gallery->getImages($galleryId), $this->statusCodeOk);
            },
            'args' => [
                $id => [
                    'required' => true,
                    'sanitize_callback' => function(int $value) : int {
                        return sanitize_text_field($value);
                    },
                    'validate_callback' => function(int $value) : bool {
                        return !empty($value);
                    }
                ]
            ],
            'permission_callback' => function() : bool {
                return Security::hasApiAccess();
            }
        ]);
    }
}