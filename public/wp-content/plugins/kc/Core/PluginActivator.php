<?php
namespace KC\Core;

use KC\Core\Feature;
use KC\Core\PluginService;
use KC\Core\Modules\IModule;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;

/**
 * The PluginActivator class contains functionality to activate and run the plugin.
 * The class cannot be inherited.
 */
final class PluginActivator {

	private readonly PluginService $pluginService;

	/**
	 * PluginActivator constructor
	 */
	public function __construct() {
		$this->pluginService = new PluginService();
	}

	/**
	 * Activate the plugin
	 *
	 * @param string $mainPluginFile the path to the main plugin file
	 */
	public function activate(string $mainPluginFile) : void {
		register_activation_hook($mainPluginFile, function() : void {
			if(!class_exists('RW_Meta_Box')) {
				$translationService = new TranslationService();
				wp_die($translationService->getTranslatedString(TranslationString::MetaBoxIsNotActivated));
			}
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
		$this->pluginService->addAction(Action::AFTER_SETUP_THEME, function() : void {
			add_theme_support(Feature::PostThumbnails->value);
		});
	}

	/**
	 * Load the assets
	 */
	private function loadAssets() : void {
		$this->pluginService->addAction(Action::ADMIN_ENQUEUE_SCRIPTS, function() : void {
			wp_enqueue_style('kc', plugin_dir_url(__FILE__).'../assets/css/style.css');
		});
	}
}