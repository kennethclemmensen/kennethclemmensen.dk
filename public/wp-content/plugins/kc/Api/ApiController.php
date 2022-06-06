<?php
namespace KC\Api;

use KC\Core\Http\HttpMethod;
use KC\Data\DataManager;
use KC\Security\Security;
use \WP_REST_Controller;
use \WP_REST_Request;
use \WP_REST_Response;

/**
 * The ApiController contains methods to register routes and handle requests and responses
 */
class ApiController extends WP_REST_Controller {

	/**
	 * Initialize a new instance of the ApiController class
	 * 
	 * @param DataManager $dataManager the data manager
	 */
	public function __construct(private DataManager $dataManager) {
		$this->namespace = 'kcapi/v1';
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
		$title = 'title';
		register_rest_route($this->namespace, '/pages/(?P<'.$title.'>[\S]+)', [
			'methods' => [HttpMethod::Get->value],
			'callback' => function(WP_REST_Request $request) use ($title) : WP_REST_Response {
				$pages = $this->dataManager->getPagesByTitle($request->get_param($title));
				return new WP_REST_Response($pages);
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
			'methods' => [HttpMethod::Get->value],
			'callback' => function(WP_REST_Request $request) use ($type) : WP_REST_Response {
				$fileTypes = explode(',', $request->get_param($type));
				return new WP_REST_Response($this->dataManager->getFiles($fileTypes));
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
			'methods' => [HttpMethod::Put->value],
			'callback' => function(WP_REST_Request $request) use ($fileId) : WP_REST_Response {
				$this->dataManager->updateFileDownloadCounter($request->get_param($fileId));
				return new WP_REST_Response();
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
		register_rest_route($this->namespace, '/slides', [
			'methods' => [HttpMethod::Get->value],
			'callback' => function() : WP_REST_Response {
				return new WP_REST_Response($this->dataManager->getSlides());
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
		$route = '/galleries';
		register_rest_route($this->namespace, $route, [
			'methods' => [HttpMethod::Get->value],
			'callback' => function() : WP_REST_Response {
				return new WP_REST_Response($this->dataManager->getGalleries());
			},
			'permission_callback' => function() : bool {
				return Security::hasApiAccess();
			}
		]);
		$id = 'id';
		register_rest_route($this->namespace, $route.'/(?P<'.$id.'>[\S]+)', [
			'methods' => [HttpMethod::Get->value],
			'callback' => function(WP_REST_Request $request) use ($id) : WP_REST_Response {
				$galleryId = $request->get_param($id);
				return new WP_REST_Response($this->dataManager->getImages($galleryId));
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