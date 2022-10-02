<?php
namespace KC\Core;

use KC\Core\Modules\IModule;
use KC\Core\Translations\TranslationHelper;
use KC\Core\Translations\TranslationString;

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
			if(!class_exists('RW_Meta_Box'))
				wp_die(TranslationHelper::getTranslatedString(TranslationString::MetaBoxIsNotActivated));
		});
	}

	/**
	 * Run the plugin
	 */
	public function run() : void {
		$modules = $this->getModules();
		foreach($modules as $module) {
			$m = new $module;
			$m->setupModule();
		}
		$this->addPostThumbnailsSupport();
		$this->loadAssets();
	}

	/**
	 * Get the modules which is the classes that implements the IModule interface
	 * 
	 * @return array the modules
	 */
	private function getModules() : array {
		return array_filter(get_declared_classes(), function(string $className) : bool {
			return in_array(IModule::class, class_implements($className));
		});
	}

	/**
	 * Add post thumbnails support
	 */
	private function addPostThumbnailsSupport() : void {
		add_action(Action::AFTER_SETUP_THEME, function() : void {
			add_theme_support('post-thumbnails');
		});
	}

	/**
	 * Load the assets
	 */
	private function loadAssets() : void {
		add_action(Action::ADMIN_ENQUEUE_SCRIPTS, function() : void {
			wp_enqueue_style('kc', plugin_dir_url(__FILE__).'../assets/css/style.css');
		});
	}
}