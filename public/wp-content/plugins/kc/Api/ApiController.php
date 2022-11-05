<?php
namespace KC\Api;

use KC\Core\Api\HttpMethod;
use KC\Core\Security\SecurityHelper;
use KC\Data\DataManager;
use \WP_REST_Controller;
use \WP_REST_Request;
use \WP_REST_Response;

/**
 * The ApiController contains methods to register routes and handle requests and responses
 */
final class ApiController extends WP_REST_Controller {

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
		$this->registerShortcutsRoute();
	}

	/**
	 * Register the pages route
	 */
	private function registerPagesRoute() : void {
		$title = 'title';
		$this->registerRoute('/pages/(?P<'.$title.'>[\S]+)', HttpMethod::Get, function(WP_REST_Request $request) use ($title) : WP_REST_Response {
			$pages = $this->dataManager->getPagesByTitle($request->get_param($title));
			return new WP_REST_Response($pages);
		}, [$title]);
	}

	/**
	 * Register the files route
	 */
	private function registerFilesRoute() : void {
		$type = 'type';
		$this->registerRoute('/files', HttpMethod::Get, function(WP_REST_Request $request) use ($type) : WP_REST_Response {
			$fileTypes = explode(',', $request->get_param($type));
			return new WP_REST_Response($this->dataManager->getFiles($fileTypes));
		}, [$type]);
	}

	/**
	 * Register the file download counter route
	 */
	private function registerFileDownloadCounterRoute() : void {
		$fileId = 'fileid';
		$this->registerRoute('/fileDownloads', HttpMethod::Put, function(WP_REST_Request $request) use ($fileId) : WP_REST_Response {
			$this->dataManager->updateFileDownloadCounter($request->get_param($fileId));
			return new WP_REST_Response();
		}, [$fileId]);
	}

	/**
	 * Register the slides route
	 */
	private function registerSlidesRoute() : void {
		$this->registerRoute('/slides', HttpMethod::Get, function() : WP_REST_Response {
			return new WP_REST_Response($this->dataManager->getSlides());
		});
	}

	/**
	 * Register the galleries routes
	 */
	private function registerGalleriesRoutes() : void {
		$route = '/galleries';
		$this->registerRoute($route, HttpMethod::Get, function() : WP_REST_Response {
			return new WP_REST_Response($this->dataManager->getGalleries());
		});
		$id = 'id';
		$this->registerRoute($route.'/(?P<'.$id.'>[\S]+)', HttpMethod::Get, function(WP_REST_Request $request) use ($id) : WP_REST_Response {
			$galleryId = $request->get_param($id);
			return new WP_REST_Response($this->dataManager->getImages($galleryId));
		}, [$id]);
	}

	/**
	 * Register the shortcuts route
	 */
	private function registerShortcutsRoute() : void {
		$this->registerRoute('/shortcuts', HttpMethod::Get, function() : WP_REST_Response {
			return new WP_REST_Response($this->dataManager->getShortcuts());
		});
	}

	/**
	 * Register a route
	 * 
	 * @param string $route the route to register
	 * @param HttpMethod $httpMethod the http method
	 * @param callable $callback the callback
	 * @param array $parameters the parameters
	 */
	private function registerRoute(string $route, HttpMethod $httpMethod, callable $callback, array $parameters = []) : void {
		$routeOptions = [
			'methods' => [$httpMethod->value],
			'callback' => $callback,
			'permission_callback' => function() : bool {
				return SecurityHelper::hasApiAccess();
			}
		];
		if(!empty($parameters)) {
			$parameterOptions = [];
			foreach($parameters as $parameter) {
				$parameterOptions[$parameter] = [
					'required' => true,
					'sanitize_callback' => function(string $value) : string {
						return SecurityHelper::sanitizeString($value);
					},
					'validate_callback' => function(string $value) : bool {
						return SecurityHelper::isValid($value);
					}
				];
			}
			$routeOptions['args'] = $parameterOptions;
		}
		register_rest_route($this->namespace, $route, $routeOptions);
	}
}