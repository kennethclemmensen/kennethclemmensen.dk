<?php
namespace KC\Api;

/**
 * The API class contains functionality to set up the API
 */
class API {

    /**
     * Initialize a new instance of the API class
     */
    public function __construct() {
        require_once 'APIController.php';
        $this->restApiInit();
    }

    /**
     * Use the rest_api_init action to register routes
     */
    private function restApiInit() : void {
        add_action('rest_api_init', function() : void {
            $controller = new APIController();
            $controller->registerRoutes();
        });
    }
}