<?php
namespace KC\Api;

use KC\Core\Action;
use KC\Core\IModule;

/**
 * The ApiModule class contains functionality to set up the Api
 */
class ApiModule implements IModule {

    /**
     * Initialize a new instance of the ApiModule class
     */
    public function __construct() {
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