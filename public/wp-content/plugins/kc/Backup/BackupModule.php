<?php
namespace KC\Backup;

use KC\Backup\Settings\BackupSettings;
use KC\Core\Modules\IModule;
use KC\Data\FileManager;

/**
 * The BackupModule class contains functionality to set up the backup module
 */
final class BackupModule implements IModule {

	/**
	 * Setup the backup module
	 */
	public function setupModule() : void {
		$backupSettings = new BackupSettings(new FileManager());
		$backupSettings->createSettingsPage();
	}
}