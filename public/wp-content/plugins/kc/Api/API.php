<?php
namespace KC\Api;

/**
 * The Api class contains functionality to set up the API
 */
class Api {

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