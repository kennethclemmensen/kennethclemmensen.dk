<?php
namespace KC\Backup\Settings;

use KC\Core\Action;
use KC\Core\Security\SecurityHelper;
use KC\Core\Settings\BaseSettings;
use KC\Core\Settings\ISettings;
use KC\Core\Translations\TranslationHelper;
use KC\Core\Translations\TranslationString;
use KC\Core\Users\UserRole;
use KC\Data\Api\DropboxApi;
use KC\Data\DatabaseManager;
use KC\Data\FileManager;

/**
 * The BackupSettings class contains methods to handle the backup settings
 */
final class BackupSettings extends BaseSettings implements ISettings {

	private const BACKUP_FOLDER = WP_CONTENT_DIR.'/kc_backup';
	private readonly string $dropboxSettingsPage;
	private readonly string $dropboxOptionGroup;
	private readonly array | bool $dropboxOptions;
	private readonly string $appKey;
	private readonly string $appSecret;
	private readonly string $redirectUri;

	/**
	 * BackupSettings constructor
	 * 
	 * @param FileManager $fileManager the file manager
	 */
	public function __construct(private FileManager $fileManager) {
		$this->dropboxSettingsPage = 'kc-backup-dropbox-settings';
		$this->dropboxOptionGroup = $this->dropboxSettingsPage.'-option-group';
		$this->dropboxOptions = get_option($this->dropboxOptionGroup);
		$prefix = 'dropbox_';
		$this->appKey = $prefix.'app_key';
		$this->appSecret = $prefix.'app_secret';
		$this->redirectUri = $prefix.'redirect_uri';
		$this->handleBackups();
		$this->createDropboxSettings();
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		add_action(Action::ADMIN_MENU, function() : void {
			$title = TranslationHelper::getTranslatedString(TranslationString::Backup);
			$menuSlug = 'kc-backup';
			add_management_page($title, $title, UserRole::Administrator->value, $menuSlug, function() use ($title, $menuSlug) : void {
				$createBackup = TranslationHelper::getTranslatedString(TranslationString::CreateBackup);
				$download = TranslationHelper::getTranslatedString(TranslationString::Download);
				$delete = TranslationHelper::getTranslatedString(TranslationString::Delete);
				$type = TranslationHelper::getTranslatedString(TranslationString::Type);
				$database = TranslationHelper::getTranslatedString(TranslationString::Database);
				$files = TranslationHelper::getTranslatedString(TranslationString::Files);
				$everything = TranslationHelper::getTranslatedString(TranslationString::Everything);
				$dropbox = TranslationHelper::getTranslatedString(TranslationString::Dropbox);
				$upload = TranslationHelper::getTranslatedString(TranslationString::Upload);
				$name = 'createBackup';
				$databaseType = 'database';
				$filesType = 'files';
				if(isset($_POST[$name])) {
					$fileName = 'backup_'.time().'.zip';
					$sourceFolder = realpath(ABSPATH);
					$destinationFolder = self::BACKUP_FOLDER;
					switch($_POST['type']) {
						case $databaseType:
							$this->createDatabaseBackupFile();
							break;
						case $filesType:
							$this->fileManager->createZipFile($fileName, $sourceFolder, $destinationFolder);
							break;
						default:
							$this->createDatabaseBackupFile();
							$this->fileManager->createZipFile($fileName, $sourceFolder, $destinationFolder);
							break;
					}
				}
				?>
				<div class="wrap">
					<h2 class="nav-tab-wrapper">
						<?php
						$activeTab = (isset($_GET['tab'])) ? $_GET['tab'] : '';
						$dropboxTab = 'dropbox';
						$activeTabClass = 'nav-tab-active';
						?>
						<a href="?page=<?php echo $menuSlug; ?>" class="nav-tab <?php echo ($activeTab === '') ? $activeTabClass : ''; ?>">
							<?php echo $title; ?>
						</a>
						<a href="?page=<?php echo $menuSlug; ?>&tab=<?php echo $dropboxTab; ?>" class="nav-tab <?php echo ($activeTab === $dropboxTab) ? $activeTabClass : ''; ?>">
							<?php echo $dropbox; ?>
						</a>
					</h2>
					<?php
					switch($activeTab) {
						case $dropboxTab:
							settings_errors();
							?>
							<form action="options.php" method="post">
								<?php
								settings_fields($this->dropboxOptionGroup);
								do_settings_sections($this->dropboxSettingsPage);
								submit_button();
								?>
							</form>
							<?php
							break;
						default:
							?>
							<div class="kc-settings">
								<form action="" method="post">
									<label for="type"><?php echo $type; ?></label>
									<select name="type" id="type">
										<option value="<?php echo $databaseType; ?>">
											<?php echo $database; ?>
										</option>
										<option value="<?php echo $filesType; ?>">
											<?php echo $files; ?>
										</option>
										<option value="everything" selected>
											<?php echo $everything; ?>
										</option>
									</select>
									<input type="submit" value="<?php echo $createBackup; ?>" name="<?php echo $name; ?>">
								</form>
								<table>
									<thead>
										<tr>
											<th><?php echo $title; ?></th>
											<th><?php echo $download; ?></th>
											<th><?php echo $dropbox; ?></th>
											<th><?php echo $delete; ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$requestUri = $_SERVER['REQUEST_URI'];
										$backups = $this->fileManager->getFiles(self::BACKUP_FOLDER);
										$appKey = $this->getAppKey();
										$redirectUrl = $this->getRedirectUri();
										foreach($backups as $backup) {
											$dropboxLink = 'https://www.dropbox.com/oauth2/authorize?client_id='.$appKey;
											$dropboxLink .= '&redirect_uri='.$redirectUrl.'&response_type=code&state='.$backup;
										?>
										<tr>
											<td><?php echo $backup; ?></td>
											<td>
												<a href="<?php echo $requestUri.'&download='.$backup; ?>">
													<?php echo $download; ?>
												</a>
											</td>
											<td>
												<a href="<?php echo $dropboxLink; ?>"><?php echo $upload; ?></a>
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
							break;
					}
					?>
				</div>
				<?php
			});
		});
	}

	/**
	 * Create the dropbox settings
	 */
	private function createDropboxSettings() : void {
		add_action(Action::ADMIN_INIT, function() : void {
			$sectionId = $this->dropboxSettingsPage.'-section-dropbox';
			$prefix = $this->dropboxSettingsPage;
			$appKeyLabel = TranslationHelper::getTranslatedString(TranslationString::AppKey);
			$appSecretLabel = TranslationHelper::getTranslatedString(TranslationString::AppSecret);
			$redirectUriLabel = TranslationHelper::getTranslatedString(TranslationString::RedirectUri);
			add_settings_section($sectionId, '', function() : void {}, $this->dropboxSettingsPage);
			add_settings_field($prefix.'app-key', $appKeyLabel, function() : void {
				echo '<input type="text" name="'.$this->dropboxOptionGroup.'['.$this->appKey.']" value="'.$this->getAppKey().'">';
			}, $this->dropboxSettingsPage, $sectionId);
			add_settings_field($prefix.'app-secret', $appSecretLabel, function() : void {
				echo '<input type="text" name="'.$this->dropboxOptionGroup.'['.$this->appSecret.']" value="'.$this->getAppSecret().'">';
			}, $this->dropboxSettingsPage, $sectionId);
			add_settings_field($prefix.'redirect-uri', $redirectUriLabel, function() : void {
				echo '<input type="url" name="'.$this->dropboxOptionGroup.'['.$this->redirectUri.']" value="'.$this->getRedirectUri().'">';
			}, $this->dropboxSettingsPage, $sectionId);
			$this->registerSetting($this->dropboxOptionGroup);
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
			} else if(isset($_GET['code']) && isset($_GET['state'])) {
				$dropboxApi = new DropboxApi($this->getAppKey(), $this->getAppSecret(), $this->getRedirectUri(), $_GET['code']);
				$dropboxApi->uploadFile($_GET['state'], self::BACKUP_FOLDER);
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

	/**
	 * Get the app key
	 * 
	 * @return string the app key
	 */
	private function getAppKey() : string {
		if(isset($this->dropboxOptions[$this->appKey])) {
			return $this->dropboxOptions[$this->appKey];
		} else {
			return '';
		}
	}

	/**
	 * Get the app secret
	 * 
	 * @return string the app secret
	 */
	private function getAppSecret() : string {
		if(isset($this->dropboxOptions[$this->appSecret])) {
			return $this->dropboxOptions[$this->appSecret];
		} else {
			return '';
		}
	}

	/**
	 * Get the redirect uri
	 * 
	 * @return string the redirect uri
	 */
	private function getRedirectUri() : string {
		if(isset($this->dropboxOptions[$this->redirectUri])) {
			return SecurityHelper::escapeUrl($this->dropboxOptions[$this->redirectUri]);
		} else {
			return '';
		}
	}
}