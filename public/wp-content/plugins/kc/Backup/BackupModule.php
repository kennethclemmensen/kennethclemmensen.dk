<?php
namespace KC\Backup;

use KC\Backup\Settings\BackupSettings;
use KC\Core\Modules\IModule;
use KC\Core\Security\SecurityService;
use KC\Core\Translations\TranslationService;
use KC\Data\Files\FileManager;

/**
 * The BackupModule class contains functionality to set up the backup module
 */
final readonly class BackupModule implements IModule {

	/**
	 * Setup the backup module
	 */
	public function setupModule() : void {
		$backupSettings = new BackupSettings(new FileManager(), new SecurityService(), new TranslationService());
		$backupSettings->createSettingsPage();
	}
}