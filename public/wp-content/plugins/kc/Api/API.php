<?php
namespace KC\Api;

use KC\Core\IModule;

/**
 * The Api class contains functionality to set up the API
 */
class Api implements IModule {

    /**
     * Initialize a new instance of the Api class
     */
    public function __construct() {
        require_once 'ApiController.php';
        $this->restApiInit();
    }

    /**
     * Use the rest_api_init action to register routes
     */
    private function restApiInit() : void {
        add_action('rest_api_init', function() : void {
            $controller = new ApiController();
            $controller->registerRoutes();
        });
    }
}