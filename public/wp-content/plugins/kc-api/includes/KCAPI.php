<?php
namespace KCAPI\Includes;

/**
 * Class KCAPI contains methods to set up API endpoints and get data
 * @package KCAPI\Includes
 */
class KCAPI {

    /**
     * Execute the plugin
     */
    public function execute() : void {
        $this->loadDependencies();
        $this->restApiInit();
    }

    /**
     * Load the dependent files
     */
    private function loadDependencies() : void {
        require_once 'KCAPIController.php';
    }

    /**
     * Use the rest_api_init to register a route
     */
    private function restApiInit() : void {
        add_action('rest_api_init', function() : void {
            $controller = new KCAPIController();
            $controller->registerRoutes();
        });
    }
}