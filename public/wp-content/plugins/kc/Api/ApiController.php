<?php
namespace KC\Api;

use KC\Core\IModule;
use KC\File\FileModule;
use KC\Gallery\GalleryModule;
use KC\Image\ImageModule;
use KC\Page\PageModule;
use KC\Security\Security;
use KC\Slider\SliderModule;
use \WP_REST_Request;
use \WP_REST_Response;
use \WP_REST_Server;

/**
 * The ApiController contains methods to register routes and handle requests and responses
 */
class ApiController {

    private string $namespace;
    private int $statusCodeOk;
    private IModule $fileModule;

    /**
     * Initialize a new instance of the ApiController class
     */
    public function __construct() {
        $this->namespace = 'kcapi/v1';
        $this->statusCodeOk = 200;
        $this->fileModule = new FileModule();
    }

    /**
     * Register the Api routes
     */
    public function registerApiRoutes() : void {
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
        $pageModule = new PageModule();
        $title = 'title';
        register_rest_route($this->namespace, '/pages/(?P<'.$title.'>[\S]+)', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($pageModule, $title) : WP_REST_Response {
                $pages = $pageModule->getPagesByTitle($request->get_param($title));
                return new WP_REST_Response($pages, $this->statusCodeOk);
            },
            'args' => [
                $title => [
                    'required' => true,
                    'sanitize_callback' => function(string $value) : string {
                        return Security::sanitizeString($value);
                    },
                    'validate_callback' => function(string $value) : bool {
                        return Security::isValid($value);
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
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($type) : WP_REST_Response {
                $fileTypes = explode(',', $request->get_param($type));
                return new WP_REST_Response($this->fileModule->getFiles($fileTypes), $this->statusCodeOk);
            },
            'args' => [
                $type => [
                    'required' => true,
                    'sanitize_callback' => function(string $value) : string {
                        return Security::sanitizeString($value);
                    },
                    'validate_callback' => function(string $value) : bool {
                        return Security::isValid($value);
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
                $this->fileModule->updateFileDownloadCounter($request->get_param($fileId));
                return new WP_REST_Response($this->statusCodeOk);
            },
            'args' => [
                $fileId => [
                    'required' => true,
                    'sanitize_callback' => function(string $value) : string {
                        return Security::sanitizeString($value);
                    },
                    'validate_callback' => function(string $value) : bool {
                        return Security::isValid($value);
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
        $sliderModule = new SliderModule();
        register_rest_route($this->namespace, '/slides', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function() use ($sliderModule) : WP_REST_Response {
                return new WP_REST_Response($sliderModule->getSlides(), $this->statusCodeOk);
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
        $galleryModule = new GalleryModule();
        $imageModule = new ImageModule();
        $route = '/galleries';
        register_rest_route($this->namespace, $route, [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function() use ($galleryModule) : WP_REST_Response {
                return new WP_REST_Response($galleryModule->getGalleries(), $this->statusCodeOk);
            },
            'permission_callback' => function() : bool {
                return Security::hasApiAccess();
            }
        ]);
        $id = 'id';
        register_rest_route($this->namespace, $route.'/(?P<'.$id.'>[\S]+)', [
            'methods' => [WP_REST_Server::READABLE],
            'callback' => function(WP_REST_Request $request) use ($id, $imageModule) : WP_REST_Response {
                $galleryId = $request->get_param($id);
                return new WP_REST_Response($imageModule->getImages($galleryId), $this->statusCodeOk);
            },
            'args' => [
                $id => [
                    'required' => true,
                    'sanitize_callback' => function(string $value) : string {
                        return Security::sanitizeString($value);
                    },
                    'validate_callback' => function(string $value) : bool {
                        return Security::isValid($value);
                    }
                ]
            ],
            'permission_callback' => function() : bool {
                return Security::hasApiAccess();
            }
        ]);
    }
}