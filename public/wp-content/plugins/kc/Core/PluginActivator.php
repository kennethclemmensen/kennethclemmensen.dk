<?php
namespace KC\Core;

/**
 * The PluginActivator class contains functionality to activate the plugin
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
}