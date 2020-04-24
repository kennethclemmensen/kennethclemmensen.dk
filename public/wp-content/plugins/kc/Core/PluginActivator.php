<?php
namespace KC\Core;

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
            if(!class_exists('RW_Meta_Box')) wp_die('Meta Box is not activated');
        });
    }

    /**
     * Run the plugin
     */
    public function run(): void {
        $basePath = __DIR__.'/../';
        require_once 'Constant.php';
        require_once 'CustomPostType.php';
        require_once 'IModule.php';
        require_once $basePath.'Api/Api.php';
        require_once $basePath.'Files/Files.php';
        require_once $basePath.'Gallery/Gallery.php';
        require_once $basePath.'Security/Security.php';
        require_once $basePath.'Slider/Slider.php';
        require_once $basePath.'Utils/PluginHelper.php';
        $modules = $this->getModules();
        foreach($modules as $module) {
            new $module;
        }
    }

    /**
     * Get the modules which are the classes that implements the IModule interface
     * 
     * @return array the modules
     */
    private function getModules() : array {
        return array_filter(get_declared_classes(), function(string $className) {
            return in_array(IModule::class, class_implements($className));
        });
    }
}