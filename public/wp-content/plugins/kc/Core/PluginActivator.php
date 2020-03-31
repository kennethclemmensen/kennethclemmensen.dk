<?php
namespace KC\Core;

use KC\API\API;
use KC\Files\Files;
use KC\Gallery\Gallery;
use KC\Slider\Slider;

/**
 * The PluginActivator class contains functionality to activate and run the plugin
 */
class PluginActivator {

    /**
     * Activate the plugin
     *
     * @param string $mainPluginFile the path to the main plugin file
     */
    public function activate(string $mainPluginFile) : void {
        register_activation_hook($mainPluginFile, function() : void {
            if(!class_exists('RW_Meta_Box')) die('Meta Box is not activated');
        });
    }

    /**
     * Run the plugin
     */
    public function run(): void {
        $basePath = __DIR__.'/../';
        require_once 'CustomPostType.php';
        require_once $basePath.'Api/API.php';
        require_once $basePath.'Files/Files.php';
        require_once $basePath.'Gallery/Gallery.php';
        require_once $basePath.'Slider/Slider.php';
        new API();
        new Files();
        new Gallery();
        new Slider();
    }
}