<?php
namespace KC\Api;

use KC\Core\Action;
use KC\Core\IModule;

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
        add_action(Action::API_INIT, function() : void {
            $controller = new ApiController();
            $controller->registerApiRoutes();
        });
    }
}