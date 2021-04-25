<?php
namespace KC\Backup;

use KC\Core\Action;
use KC\Core\IModule;
use KC\Core\TranslationString;
use KC\Data\DatabaseManager;
use KC\Data\FileManager;
use KC\Utils\PluginHelper;

/**
 * The BackupModule class contains functionality to set up the backup module
 */
class BackupModule implements IModule {

    /**
     * Setup the backup module
     */
    public function setupModule() : void {
        $this->createSettingsPage();
    }

    /**
     * Create a settings page
     */
    private function createSettingsPage() : void {
        add_action(Action::ADMIN_MENU, function() : void {
            $title = PluginHelper::getTranslatedString(TranslationString::BACKUP);
            add_management_page($title, $title, 'administrator', 'kc-backup', function() use ($title) : void {
                $value = PluginHelper::getTranslatedString(TranslationString::CREATE_BACKUP);
                $name = 'createBackup';
                if(isset($_POST[$name])) $this->createDatabaseBackupFile();
                ?>
                <div class="wrap">
                    <h1 class="wp-heading-inline"><?php echo $title; ?></h1>
                    <form action="" method="post">
                        <input type="submit" value="<?php echo $value; ?>" name="<?php echo $name; ?>">
                    </form>
                </div>
                <?php
            });
        });
    }

    /**
     * Create a database backup file
     */
    private function createDatabaseBackupFile() : void {
        $dbManager = new DatabaseManager();
        $fileManager = new FileManager();
        $fileName = 'backup_'.time().'.sql';
        $content = $dbManager->getDatabaseStructure();
        $fileManager->createFile($fileName, $content, WP_CONTENT_DIR.'/kc_backup');
    }
}