<?php
namespace KC\Backup;

use KC\Core\IModule;

/**
 * The BackupModule class contains functionality to set up the backup module
 */
class BackupModule implements IModule {

    /**
     * Setup the backup module
     */
    public function setupModule() : void {
        $backupSettings = new BackupSettings();
        $backupSettings->createSettingsPage();
    }
}