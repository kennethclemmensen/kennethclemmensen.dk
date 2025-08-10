<?php
namespace KC\Api;

use KC\Core\Filter;
use KC\Core\PluginService;
use KC\Core\Api\HttpHeader;
use KC\Core\Api\HttpMethod;
use KC\Core\Api\HttpService;
use KC\Core\Api\HttpStatusCode;
use KC\Core\Security\SecurityService;
use KC\Data\Database\DataManager;
use \WP_REST_Controller;
use \WP_REST_Request;
use \WP_REST_Response;

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
		$this->registerFileDownloadCounterRoute();
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
		$this->registerRoute('/pages/(?P<'.$title.'>[\S]+)', HttpMethod::Get, function(WP_REST_Request $request) use ($title) : WP_REST_Response {
			$pages = $this->dataManager->getPagesByTitle($request->get_param($title));
			return $this->createResponse($pages);
		}, [$title]);
	}

	/**
	 * Register the files route
	 */
	private function registerFilesRoute() : void {
		$type = 'type';
		$this->registerRoute('/files', HttpMethod::Get, function(WP_REST_Request $request) use ($type) : WP_REST_Response {
			$fileTypes = explode(',', $request->get_param($type));
			return $this->createResponse($this->dataManager->getFiles($fileTypes));
		}, [$type]);
	}

	/**
	 * Register the file download counter route
	 */
	private function registerFileDownloadCounterRoute() : void {
		$fileId = 'fileid';
		$this->registerRoute('/fileDownloads', HttpMethod::Patch, function(WP_REST_Request $request) use ($fileId) : WP_REST_Response {
			$this->dataManager->updateFileDownloadCounter($request->get_param($fileId));
			return $this->createResponse(httpStatusCode: HttpStatusCode::NoContent);
		}, [$fileId]);
	}

	/**
	 * Register the slides route
	 */
	private function registerSlidesRoute() : void {
		$callback = fn() : WP_REST_Response => $this->createResponse($this->dataManager->getSlides());
		$this->registerRoute('/slides', HttpMethod::Get, $callback);
	}

	/**
	 * Register the galleries routes
	 */
	private function registerGalleriesRoutes() : void {
		$route = '/galleries';
		$callback = fn() : WP_REST_Response => $this->createResponse($this->dataManager->getGalleries());
		$this->registerRoute($route, HttpMethod::Get, $callback);
		$id = 'id';
		$this->registerRoute($route.'/(?P<'.$id.'>[\S]+)', HttpMethod::Get, function(WP_REST_Request $request) use ($id) : WP_REST_Response {
			$galleryId = $request->get_param($id);
			return $this->createResponse($this->dataManager->getImages($galleryId));
		}, [$id]);
	}

	/**
	 * Register the shortcuts route
	 */
	private function registerShortcutsRoute() : void {
		$callback = fn() : WP_REST_Response => $this->createResponse($this->dataManager->getShortcuts());
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

	/**
	 * Create a response
	 * 
	 * @param array $data the data to include in the response
	 * @param HttpStatusCode $httpStatusCode the http status code
	 * @return WP_REST_Response the response
	 */
	private function createResponse(?array $data = null, HttpStatusCode $httpStatusCode = HttpStatusCode::OK) : WP_REST_Response {
		return new WP_REST_Response($data, $httpStatusCode->value);
	}
}