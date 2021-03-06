<?php
namespace KC\Backup;

use KC\Backup\Settings\BackupSettings;
use KC\Core\IModule;
use KC\Data\FileManager;

/**
 * The BackupModule class contains functionality to set up the backup module
 */
class BackupModule implements IModule {

    /**
     * Setup the backup module
     */
    public function setupModule() : void {
        require_once 'Settings/BackupSettings.php';
        $backupSettings = new BackupSettings(new FileManager());
        $backupSettings->createSettingsPage();
    }
}