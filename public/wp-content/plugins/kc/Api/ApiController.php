<?php
namespace KC\Api;

use KC\Core\Filter;
use KC\Core\PluginService;
use KC\Core\Api\HttpHeader;
use KC\Core\Api\HttpMethod;
use KC\Core\Api\HttpService;
use KC\Core\Api\Responses\NoContentResponse;
use KC\Core\Api\Responses\NotFoundResponse;
use KC\Core\Api\Responses\OkResponse;
use KC\Core\Api\Responses\Response;
use KC\Core\Security\SecurityService;
use KC\Data\Database\DataManager;
use \WP_REST_Controller;
use \WP_REST_Request;

/**
 * The ApiController contains methods to register routes and handle requests and responses.
 * The class cannot be inherited.
 */
final class ApiController extends WP_REST_Controller {

	/**
	 * Initialize a new instance of the ApiController class
	 * 
	 * @param DataManager $dataManager the data manager
	 * @param SecurityService $securityService the security service
	 * @param PluginService $pluginService the plugin service
	 */
	public function __construct(private readonly DataManager $dataManager, private readonly SecurityService $securityService, private readonly PluginService $pluginService) {
		$this->namespace = 'kcapi/v1';
		$this->addHeaders();
	}

	/**
	 * Register the Api routes
	 */
	public function registerApiRoutes() : void {
		$this->registerPagesRoute();
		$this->registerFilesRoute();
		$this->registerFileDownloadsRoute();
		$this->registerSlidesRoute();
		$this->registerGalleriesRoutes();
		$this->registerShortcutsRoute();
	}

	/**
	 * Add headers to the http response
	 */
	private function addHeaders() : void {
		$this->pluginService->addFilter(Filter::REST_PRE_SERVE_REQUEST, function() : void {
			$httpService = new HttpService();
			$value = $this->securityService->escapeUrl(site_url());
			$httpService->sendHttpHeader(HttpHeader::AccessControlAllowOrigin, $value);
		});
	}

	/**
	 * Register the pages route
	 */
	private function registerPagesRoute() : void {
		$title = 'title';
		$this->registerRoute("/pages/(?P<$title>[\S]+)", HttpMethod::Get, function(WP_REST_Request $request) use ($title) : Response {
			$pages = $this->dataManager->getPagesByTitle($request->get_param($title));
			return new OkResponse($pages);
		}, [$title]);
	}

	/**
	 * Register the files route
	 */
	private function registerFilesRoute() : void {
		$type = 'type';
		$this->registerRoute('/files', HttpMethod::Get, function(WP_REST_Request $request) use ($type) : Response {
			$fileTypes = explode(',', $request->get_param($type));
			return new OkResponse($this->dataManager->getFiles($fileTypes));
		}, [$type]);
	}

	/**
	 * Register the file downloads route
	 */
	private function registerFileDownloadsRoute() : void {
		$urlParameter = 'fileid';
		$this->registerRoute('/fileDownloads', HttpMethod::Patch, function(WP_REST_Request $request) use ($urlParameter) : Response {
			$fileId = $request->get_param($urlParameter);
			if(!$this->dataManager->fileExists($fileId)) {
				return new NotFoundResponse();
			} else {
				$this->dataManager->updateFileDownloadCounter($fileId);
				return new NoContentResponse();
			}
		}, [$urlParameter]);
	}

	/**
	 * Register the slides route
	 */
	private function registerSlidesRoute() : void {
		$callback = fn() : Response => new OkResponse($this->dataManager->getSlides());
		$this->registerRoute('/slides', HttpMethod::Get, $callback);
	}

	/**
	 * Register the galleries routes
	 */
	private function registerGalleriesRoutes() : void {
		$route = '/galleries';
		$callback = fn() : Response => new OkResponse($this->dataManager->getGalleries());
		$id = 'id';
		$this->registerRoute($route, HttpMethod::Get, $callback);
		$this->registerRoute("$route/(?P<$id>[\S]+)", HttpMethod::Get, function(WP_REST_Request $request) use ($id) : Response {
			$galleryId = $request->get_param($id);
			return new OkResponse($this->dataManager->getImages($galleryId));
		}, [$id]);
	}

	/**
	 * Register the shortcuts route
	 */
	private function registerShortcutsRoute() : void {
		$callback = fn() : Response => new OkResponse($this->dataManager->getShortcuts());
		$this->registerRoute('/shortcuts', HttpMethod::Get, $callback);
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
		$permissionCallback = fn() : bool => $this->securityService->hasApiAccess();
		$routeOptions = [
			'methods' => [$httpMethod->value],
			'callback' => $callback,
			'permission_callback' => $permissionCallback
		];
		if(!empty($parameters)) {
			$parameterOptions = [];
			foreach($parameters as $parameter) {
				$sanitizeCallback = fn(string $value) : string => $this->securityService->sanitizeString($value);
				$validateCallback = fn(string $value) : bool => $this->securityService->isValid($value);
				$parameterOptions[$parameter] = [
					'required' => true,
					'sanitize_callback' => $sanitizeCallback,
					'validate_callback' => $validateCallback
				];
			}
			$routeOptions['args'] = $parameterOptions;
		}
		register_rest_route($this->namespace, $route, $routeOptions);
	}
}