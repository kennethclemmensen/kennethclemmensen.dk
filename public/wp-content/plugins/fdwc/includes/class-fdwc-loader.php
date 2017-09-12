<?php
namespace FDWC\Includes;

class FDWC_Loader {

	public function load_scripts() {
		add_action('wp_enqueue_scripts', function() {
			$script = 'fdwc-js';
			$script_file = 'assets/js/script.js';
			$version = filemtime(__DIR__.'/../'.$script_file);
			wp_enqueue_script($script, plugin_dir_url(__DIR__).$script_file, ['jquery'], $version, true);
			wp_localize_script($script, 'fdwc_ajax', ['ajax_url' => admin_url('admin-ajax.php')]);
		});
	}
}