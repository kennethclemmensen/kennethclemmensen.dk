<?php
namespace KC\Api;

use KC\Core\Action;
use KC\Core\Modules\IModule;
use KC\Data\DataManager;

/**
 * The ApiModule class contains functionality to set up the Api
 */
class ApiModule implements IModule {

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
			$controller = new ApiController(new DataManager());
			$controller->registerApiRoutes();
		});
	}
}