<?php
namespace KC\Backup\Settings;

use KC\Core\Action;
use KC\Core\Filter;
use KC\Core\PluginService;
use KC\Core\Security\SecurityService;
use KC\Core\Settings\BaseSettings;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use KC\Core\Users\UserRole;
use KC\Data\Api\DropboxApi;
use KC\Data\Database\DatabaseManager;
use KC\Data\Files\FileManager;

/**
 * The BackupSettings class contains methods to handle the backup settings.
 * The class cannot be inherited.
 */
final class BackupSettings extends BaseSettings {

	private const string BACKUP_FOLDER = WP_CONTENT_DIR.'/kc_backup';
	private readonly string $dropboxSettingsPage;
	private readonly string $dropboxSettingsName;
	private readonly array | bool $dropboxSettings;
	private readonly string $appKey;
	private readonly string $appSecret;
	private readonly string $redirectUri;
	private readonly string $encryptionPassword;
	private readonly string $nonce;

	/**
	 * BackupSettings constructor
	 * 
	 * @param FileManager $fileManager the file manager
	 * @param SecurityService $securityService the security service
	 * @param TranslationService $translationService the translation service
	 * @param PluginService $pluginService the plugin service
	 */
	public function __construct(private readonly FileManager $fileManager, private readonly SecurityService $securityService, private readonly TranslationService $translationService, private readonly PluginService $pluginService) {
		parent::__construct('kc-backup', '');
		$this->dropboxSettingsPage = 'kc-backup-dropbox-settings';
		$this->dropboxSettingsName = $this->dropboxSettingsPage.'-option-group';
		$this->dropboxSettings = get_option($this->dropboxSettingsName);
		$prefix = 'dropbox_';
		$this->appKey = $prefix.'app_key';
		$this->appSecret = $prefix.'app_secret';
		$this->redirectUri = $prefix.'redirect_uri';
		$this->encryptionPassword = $prefix.'encryption_password';
		$this->nonce = $prefix.'nonce';
		$this->handleBackups();
		$this->createDropboxSettings();
		$this->handleOptionsSaving();
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		$this->pluginService->addAction(Action::ADMIN_MENU, function() : void {
			$title = $this->translationService->getTranslatedString(TranslationString::Backup);
			$this->addManagementPage($title, UserRole::Administrator->value, $this->settingsPage, function() use ($title) : void {
				$dropbox = $this->translationService->getTranslatedString(TranslationString::Dropbox);
				$tabs = [
					'backup' => [
						'title' => $title,
						'content' => function() use($dropbox, $title) : void {
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
							$createBackup = $this->translationService->getTranslatedString(TranslationString::CreateBackup);
							$download = $this->translationService->getTranslatedString(TranslationString::Download);
							$delete = $this->translationService->getTranslatedString(TranslationString::Delete);
							$type = $this->translationService->getTranslatedString(TranslationString::Type);
							$database = $this->translationService->getTranslatedString(TranslationString::Database);
							$files = $this->translationService->getTranslatedString(TranslationString::Files);
							$everything = $this->translationService->getTranslatedString(TranslationString::Everything);
							$upload = $this->translationService->getTranslatedString(TranslationString::Upload);
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
						}
					],
					'dropbox' => [
						'title' => $dropbox,
						'content' => function() : void {
							?>
							<form action="options.php" method="post">
								<?php
								settings_fields($this->dropboxSettingsName);
								do_settings_sections($this->dropboxSettingsPage);
								submit_button();
								?>
							</form>
							<?php
						}
					]
				];
				$this->showTabs($tabs);
			});
		});
	}

	/**
	 * Create the dropbox settings
	 */
	private function createDropboxSettings() : void {
		$this->pluginService->addAction(Action::ADMIN_INIT, function() : void {
			$sectionId = $this->dropboxSettingsPage.'-section-dropbox';
			$prefix = $this->dropboxSettingsPage;
			$appKeyLabel = $this->translationService->getTranslatedString(TranslationString::AppKey);
			$appSecretLabel = $this->translationService->getTranslatedString(TranslationString::AppSecret);
			$redirectUriLabel = $this->translationService->getTranslatedString(TranslationString::RedirectUri);
			add_settings_section($sectionId, '', function() : void {}, $this->dropboxSettingsPage);
			add_settings_field($prefix.'app-key', $appKeyLabel, function() : void {
				echo '<input type="text" name="'.$this->dropboxSettingsName.'['.$this->appKey.']" value="'.$this->getAppKey().'">';
			}, $this->dropboxSettingsPage, $sectionId);
			add_settings_field($prefix.'app-secret', $appSecretLabel, function() : void {
				echo '<input type="text" name="'.$this->dropboxSettingsName.'['.$this->appSecret.']" value="'.$this->getAppSecret().'">';
			}, $this->dropboxSettingsPage, $sectionId);
			add_settings_field($prefix.'redirect-uri', $redirectUriLabel, function() : void {
				echo '<input type="url" name="'.$this->dropboxSettingsName.'['.$this->redirectUri.']" value="'.$this->getRedirectUri().'">';
			}, $this->dropboxSettingsPage, $sectionId);
			add_settings_field($prefix.'encryption-password', '', function() : void {
				echo '<input type="hidden" name="'.$this->dropboxSettingsName.'['.$this->encryptionPassword.']">';
			}, $this->dropboxSettingsPage, $sectionId);
			add_settings_field($prefix.'nonce', '', function() : void {
				echo '<input type="hidden" name="'.$this->dropboxSettingsName.'['.$this->nonce.']">';
			}, $this->dropboxSettingsPage, $sectionId);
			$this->registerSetting($this->dropboxSettingsName);
		});
	}

	/**
	 * Use the init action to handle the backups
	 */
	private function handleBackups() : void {
		$this->pluginService->addAction(Action::INIT, function() : void {
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
	 * Handle the options saving
	 */
	private function handleOptionsSaving() : void {
		$this->pluginService->addFilter(Filter::getPreUpdateOptionFilter($this->dropboxSettingsName), function(array $value) : array {
			$key = $this->securityService->generateEncryptionKey($this->securityService->generatePassword());
			$value[$this->encryptionPassword] = $this->convertToHexadecimal($key);
			$nonce = $this->securityService->generateNonce();
			$value[$this->nonce] = $this->convertToHexadecimal($nonce);
			$appKey = $this->securityService->encryptMessage($value[$this->appKey], $nonce, $key);
			$appSecret = $this->securityService->encryptMessage($value[$this->appSecret], $nonce, $key);
			$redirectUri = $this->securityService->encryptMessage($value[$this->redirectUri], $nonce, $key);
			$value[$this->appKey] = $this->convertToHexadecimal($appKey);
			$value[$this->appSecret] = $this->convertToHexadecimal($appSecret);
			$value[$this->redirectUri] = $this->convertToHexadecimal($redirectUri);
			return $value;
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
		if(isset($this->dropboxSettings[$this->appKey])) {
			$message = $this->convertToBinary($this->dropboxSettings[$this->appKey]);
			$nonce = $this->getNonce();
			$key = $this->getPassword();
			return $this->securityService->decryptMessage($message, $nonce, $key);
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
		if(isset($this->dropboxSettings[$this->appSecret])) {
			$message = $this->convertToBinary($this->dropboxSettings[$this->appSecret]);
			$nonce = $this->getNonce();
			$key = $this->getPassword();
			return $this->securityService->decryptMessage($message, $nonce, $key);
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
		if(isset($this->dropboxSettings[$this->redirectUri])) {
			$message = $this->convertToBinary($this->dropboxSettings[$this->redirectUri]);
			$nonce = $this->getNonce();
			$key = $this->getPassword();
			$url = $this->securityService->decryptMessage($message, $nonce, $key);
			return $this->securityService->escapeUrl($url);
		} else {
			return '';
		}
	}

	/**
	 * Get the nonce
	 * 
	 * @return string the nonce
	 */
	private function getNonce() : string {
		if(isset($this->dropboxSettings[$this->nonce])) {
			return $this->convertToBinary($this->dropboxSettings[$this->nonce]);
		} else {
			return '';
		}
	}

	/**
	 * Get the password
	 * 
	 * @return string the password
	 */
	private function getPassword() : string {
		if(isset($this->dropboxSettings[$this->encryptionPassword])) {
			return $this->convertToBinary($this->dropboxSettings[$this->encryptionPassword]);
		} else {
			return '';
		}
	}
}