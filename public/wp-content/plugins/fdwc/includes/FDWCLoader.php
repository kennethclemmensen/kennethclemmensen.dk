<?php
namespace FDWC\Includes;

/**
 * Class FDWCLoader contains a method to load JavaScript files
 * @package FDWC\Includes
 */
class FDWCLoader {

    /**
     * Load a JavaScript file
     */
    public function loadScripts() {
        add_action('wp_enqueue_scripts', function() {
            $script = 'fdwc-js';
            $scriptFile = 'assets/js/script.min.js';
            $version = filemtime(__DIR__.'/../'.$scriptFile);
            wp_enqueue_script($script, plugin_dir_url(__DIR__).$scriptFile, ['jquery'], $version, true);
            wp_localize_script($script, 'fdwc_ajax', ['ajax_url' => admin_url('admin-ajax.php')]);
        });
    }
}