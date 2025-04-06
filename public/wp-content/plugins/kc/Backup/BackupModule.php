<?php
namespace KC\Backup;

use KC\Backup\Settings\BackupSettings;
use KC\Core\PluginService;
use KC\Core\Modules\IModule;
use KC\Core\Security\SecurityService;
use KC\Core\Translations\TranslationService;
use KC\Data\Files\FileManager;

/**
 * The BackupModule class contains functionality to set up the backup module.
 * The class cannot be inherited.
 */
final class BackupModule implements IModule {

	/**
	 * Setup the backup module
	 */
	public function setupModule() : void {
		$backupSettings = new BackupSettings(new FileManager(), new SecurityService(), new TranslationService(), new PluginService());
		$backupSettings->createSettingsPage();
	}
}