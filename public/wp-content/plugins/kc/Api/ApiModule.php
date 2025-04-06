<?php
namespace KC\Api;

use KC\Core\Action;
use KC\Core\PluginService;
use KC\Core\Images\ImageService;
use KC\Core\Modules\IModule;
use KC\Core\PostTypes\PostTypeService;
use KC\Core\Security\SecurityService;
use KC\Data\Database\DataManager;

/**
 * The ApiModule class contains functionality to set up the Api.
 * The class cannot be inherited.
 */
final class ApiModule implements IModule {

	private readonly PluginService $pluginService;

	/**
	 * ApiModule constructor
	 */
	public function __construct() {
		$this->pluginService = new PluginService();
	}

	/**
	 * Setup the api module
	 */
	public function setupModule() : void {
		$this->setupApiRoutes();
	}

	/**
	 * Setup the Api routes
	 */
	private function setupApiRoutes() : void {
		$this->pluginService->addAction(Action::REST_API_INIT, function() : void {
			$securityService = new SecurityService();
			$dataManager = new DataManager(new PostTypeService(), $securityService, new ImageService());
			$controller = new ApiController($dataManager, $securityService, $this->pluginService);
			$controller->registerApiRoutes();
		});
	}
}