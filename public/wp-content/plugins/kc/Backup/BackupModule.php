<?php
namespace KC\Backup;

use KC\Core\Action;
use KC\Core\IModule;
use KC\Core\TranslationString;
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
        $pathname = WP_CONTENT_DIR.'/kc_backup';
        @mkdir($pathname);
        $file = fopen($pathname.'/backup_'.time().'.sql', 'w');
        fwrite($file, $this->getDatabaseStructure());
        fclose($file);
    }

    /**
     * Get the database structure
     * 
     * @return string the database structure
     */
    private function getDatabaseStructure() : string {
        global $wpdb;
        $structure = '';
        $tables = $wpdb->get_results('SHOW TABLES LIKE "'.$wpdb->prefix.'%";', ARRAY_N);
        foreach($tables as $table) {
            $structure .= $this->getTableStructure($table[0]);
        }
        return $structure;
    }

    /**
     * Get the table structure based on the table name
     * 
     * @param string $tableName the table name
     * @return string the table structure
     */
    private function getTableStructure(string $tableName) : string {
        global $wpdb;
        $createTable = $wpdb->get_row('SHOW CREATE TABLE '.$tableName.';', ARRAY_N);
        $structure = 'DROP TABLE IF EXISTS `'.$tableName.'`;'.PHP_EOL;
        $structure .= $createTable[1].';'.PHP_EOL;
        $structure .= 'LOCK TABLES `'.$tableName.'` WRITE;'.PHP_EOL;
        $structure .= $this->getTableContent($tableName);
        $structure .= 'UNLOCK TABLES;'.PHP_EOL.PHP_EOL;
        return $structure;
    }

    /**
     * Get the table content based on the table name
     * 
     * @param string $tableName the table name
     * @return string the table content
     */
    private function getTableContent(string $tableName) : string {
        global $wpdb;
        $content = '';
        $tableRows = $wpdb->get_results('SELECT * FROM '.$tableName.';', ARRAY_N);
        $count = count($tableRows);
        if($count > 0) {
            $content .= 'INSERT INTO `'.$tableName.'` VALUES ';
            foreach($tableRows as $key => $tableRow) {
                $values = '(';
                $c = count($tableRow);
                foreach($tableRow as $k => $data) {
                    $values .= ($data === null) ? 'NULL' : "'".preg_replace('~[[:cntrl:]]~', '', addslashes($data))."'";
                    if($k !== $c - 1) $values .= ',';
                }
                $content .= $values.')';
                if($key !== $count - 1) $content .= ',';
            }
            $content .= ';'.PHP_EOL;
        }
        return $content;
    }
}