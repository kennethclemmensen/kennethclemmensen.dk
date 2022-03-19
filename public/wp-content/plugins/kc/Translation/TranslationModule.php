<?php
namespace KC\Translation;

use KC\Core\Action;
use KC\Core\Modules\IModule;

/**
 * The TranslationModule class contains functionality to handle translations
 */
class TranslationModule implements IModule {

	/**
	 * Setup the translation module
	 */
	public function setupModule() : void {
		$this->loadLanguages();
	}

	/**
	 * Load the languages
	 */
	private function loadLanguages() : void {
		add_action(Action::PLUGINS_LOADED, function() : void {
			load_plugin_textdomain('kc', false, 'kc/languages/');
		});
	}
}