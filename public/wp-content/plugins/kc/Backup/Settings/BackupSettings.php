<?php
namespace KC\Backup\Settings;

use KC\Core\Action;
use KC\Core\Capability;
use KC\Core\Settings\ISettings;
use KC\Core\TranslationString;
use KC\Data\DatabaseManager;
use KC\Data\FileManager;
use KC\Utils\PluginHelper;

/**
 * The BackupSettings class contains methods to handle the backup settings
 */
class BackupSettings implements ISettings {

	private const BACKUP_FOLDER = WP_CONTENT_DIR.'/kc_backup';

	/**
	 * BackupSettings constructor
	 * 
	 * @param FileManager $fileManager the file manager
	 */
	public function __construct(private FileManager $fileManager) {
		$this->handleBackups();
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		add_action(Action::ADMIN_MENU, function() : void {
			$title = PluginHelper::getTranslatedString(TranslationString::BACKUP);
			add_management_page($title, $title, Capability::ADMINISTRATOR, 'kc-backup', function() use ($title) : void {
				$createBackup = PluginHelper::getTranslatedString(TranslationString::CREATE_BACKUP);
				$download = PluginHelper::getTranslatedString(TranslationString::DOWNLOAD);
				$delete = PluginHelper::getTranslatedString(TranslationString::DELETE);
				$name = 'createBackup';
				if(isset($_POST[$name])) {
					$this->createDatabaseBackupFile();
					$sourceFolder = WP_CONTENT_DIR.'/uploads';
					$this->fileManager->createZipFile('uploads_'.time().'.zip', $sourceFolder.'/**/**/*.*', $sourceFolder, self::BACKUP_FOLDER);
				}
				?>
				<div class="kc-settings">
					<h1 class="kc-settings__heading"><?php echo $title; ?></h1>
					<form action="" method="post">
						<input type="submit" value="<?php echo $createBackup; ?>" name="<?php echo $name; ?>">
					</form>
					<table>
						<thead>
							<tr>
								<th><?php echo $title; ?></th>
								<th><?php echo $download; ?></th>
								<th><?php echo $delete; ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$requestUri = $_SERVER['REQUEST_URI'];
							$backups = $this->fileManager->getFiles(self::BACKUP_FOLDER);
							foreach($backups as $backup) {
							?>
							<tr>
								<td><?php echo $backup; ?></td>
								<td>
									<a href="<?php echo $requestUri.'&download='.$backup; ?>">
										<?php echo $download; ?>
									</a>
								</td>
								<td>
									<a href="<?php echo $requestUri.'&delete='.$backup; ?>">
										<?php echo $delete; ?>
									</a>
								</td>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
				<?php
			});
		});
	}

	/**
	 * Use the init action to handle the backups
	 */
	private function handleBackups() : void {
		add_action(Action::INIT, function() : void {
			if(isset($_GET['download'])) {
				$this->fileManager->downloadFile($_GET['download'], self::BACKUP_FOLDER);
			} else if(isset($_GET['delete'])) {
				$this->fileManager->deleteFile($_GET['delete'], self::BACKUP_FOLDER);
				wp_redirect('/wp-admin/tools.php?page=kc-backup');
			}
		});
	}

	/**
	 * Create a database backup file
	 */
	private function createDatabaseBackupFile() : void {
		$dbManager = new DatabaseManager();
		$fileName = 'backup_'.time().'.sql';
		$content = $dbManager->getDatabaseStructure();
		$this->fileManager->createFile($fileName, $content, self::BACKUP_FOLDER);
	}
}