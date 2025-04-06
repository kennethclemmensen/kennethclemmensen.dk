<?php
namespace KC\Translation;

use KC\Core\Action;
use KC\Core\PluginService;
use KC\Core\Modules\IModule;

/**
 * The TranslationModule class contains functionality to handle translations.
 * The class cannot be inherited.
 */
final class TranslationModule implements IModule {

	private readonly PluginService $pluginService;

	/**
	 * Setup the translation module
	 */
	public function setupModule() : void {
		$this->pluginService = new PluginService();
		$this->loadLanguages();
	}

	/**
	 * Load the languages
	 */
	private function loadLanguages() : void {
		$this->pluginService->addAction(Action::PLUGINS_LOADED, function() : void {
			load_plugin_textdomain('kc', false, 'kc/languages/');
		});
	}
}