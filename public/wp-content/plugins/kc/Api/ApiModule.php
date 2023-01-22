<?php
namespace KC\Api;

use KC\Core\Action;
use KC\Core\Images\ImageService;
use KC\Core\Modules\IModule;
use KC\Core\PostTypes\PostTypeService;
use KC\Core\Security\SecurityService;
use KC\Data\DataManager;

/**
 * The ApiModule class contains functionality to set up the Api
 */
final readonly class ApiModule implements IModule {

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
		add_action(Action::REST_API_INIT, function() : void {
			$securityService = new SecurityService();
			$dataManager = new DataManager(new PostTypeService(), $securityService, new ImageService());
			$controller = new ApiController($dataManager, $securityService);
			$controller->registerApiRoutes();
		});
	}
}