<?php
namespace KC\Backup\Settings;

use KC\Core\Action;
use KC\Core\Filter;
use KC\Core\Security\SecurityService;
use KC\Core\Settings\BaseSettings;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use KC\Core\Users\UserRole;
use KC\Data\Api\DropboxApi;
use KC\Data\Database\DatabaseManager;
use KC\Data\Files\FileManager;

/**
 * The BackupSettings class contains methods to handle the backup settings
 */
final class BackupSettings extends BaseSettings {

	private const BACKUP_FOLDER = WP_CONTENT_DIR.'/kc_backup';
	private readonly string $dropboxSettingsPage;
	private readonly string $dropboxOptionGroup;
	private readonly array | bool $dropboxOptions;
	private readonly string $encryptionSettingsPage;
	private readonly string $encryptionOptionGroup;
	private readonly array | bool $encryptionOptions;
	private readonly string $appKey;
	private readonly string $appSecret;
	private readonly string $redirectUri;
	private readonly string $password;
	private readonly string $nonce;

	/**
	 * BackupSettings constructor
	 * 
	 * @param FileManager $fileManager the file manager
	 * @param SecurityService $securityService the security service
	 * @param TranslationService $translationService the translation service
	 */
	public function __construct(private readonly FileManager $fileManager, private readonly SecurityService $securityService, private readonly TranslationService $translationService) {
		$this->dropboxSettingsPage = 'kc-backup-dropbox-settings';
		$this->dropboxOptionGroup = $this->dropboxSettingsPage.'-option-group';
		$this->dropboxOptions = get_option($this->dropboxOptionGroup);
		$this->encryptionSettingsPage = 'kc-backup-encryption-settings';
		$this->encryptionOptionGroup = $this->encryptionSettingsPage.'-option-group';
		$this->encryptionOptions = get_option($this->encryptionOptionGroup);
		$prefix = 'dropbox_';
		$this->appKey = $prefix.'app_key';
		$this->appSecret = $prefix.'app_secret';
		$this->redirectUri = $prefix.'redirect_uri';
		$prefix = 'encryption_';
		$this->password = $prefix.'password';
		$this->nonce = $prefix.'nonce';
		$this->handleBackups();
		$this->createDropboxSettings();
		$this->createEncryptionSettings();
		$this->handleOptionsSaving();
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		add_action(Action::ADMIN_MENU, function() : void {
			$title = $this->translationService->getTranslatedString(TranslationString::Backup);
			$menuSlug = 'kc-backup';
			add_management_page($title, $title, UserRole::Administrator->value, $menuSlug, function() use ($title, $menuSlug) : void {
				$createBackup = $this->translationService->getTranslatedString(TranslationString::CreateBackup);
				$download = $this->translationService->getTranslatedString(TranslationString::Download);
				$delete = $this->translationService->getTranslatedString(TranslationString::Delete);
				$type = $this->translationService->getTranslatedString(TranslationString::Type);
				$database = $this->translationService->getTranslatedString(TranslationString::Database);
				$files = $this->translationService->getTranslatedString(TranslationString::Files);
				$everything = $this->translationService->getTranslatedString(TranslationString::Everything);
				$dropbox = $this->translationService->getTranslatedString(TranslationString::Dropbox);
				$upload = $this->translationService->getTranslatedString(TranslationString::Upload);
				$encryption = $this->translationService->getTranslatedString(TranslationString::Encryption);
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
						$encryptionTab = 'encryption';
						$activeTabClass = 'nav-tab-active';
						?>
						<a href="?page=<?php echo $menuSlug; ?>" class="nav-tab <?php echo ($activeTab === '') ? $activeTabClass : ''; ?>">
							<?php echo $title; ?>
						</a>
						<a href="?page=<?php echo $menuSlug; ?>&tab=<?php echo $dropboxTab; ?>" class="nav-tab <?php echo ($activeTab === $dropboxTab) ? $activeTabClass : ''; ?>">
							<?php echo $dropbox; ?>
						</a>
						<a href="?page=<?php echo $menuSlug; ?>&tab=<?php echo $encryptionTab; ?>" class="nav-tab <?php echo ($activeTab === $encryptionTab) ? $activeTabClass : ''; ?>">
							<?php echo $encryption; ?>
						</a>
					</h2>
					<?php
					switch($activeTab) {
						case $dropboxTab:
						case $encryptionTab:
							settings_errors();
							?>
							<form action="options.php" method="post">
								<?php
								if($activeTab === $dropboxTab) {
									settings_fields($this->dropboxOptionGroup);
									do_settings_sections($this->dropboxSettingsPage);
								} else {
									settings_fields($this->encryptionOptionGroup);
									do_settings_sections($this->encryptionSettingsPage);
								}
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
			$appKeyLabel = $this->translationService->getTranslatedString(TranslationString::AppKey);
			$appSecretLabel = $this->translationService->getTranslatedString(TranslationString::AppSecret);
			$redirectUriLabel = $this->translationService->getTranslatedString(TranslationString::RedirectUri);
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
	 * Create the encryption settings
	 */
	private function createEncryptionSettings() : void {
		add_action(Action::ADMIN_INIT, function() : void {
			$sectionId = $this->encryptionSettingsPage.'-section-encryption';
			$prefix = $this->encryptionSettingsPage;
			$password = $this->translationService->getTranslatedString(TranslationString::Password);
			add_settings_section($sectionId, '', function() : void {}, $this->encryptionSettingsPage);
			add_settings_field($prefix.'password', $password, function() : void {
				echo '<input type="password" name="'.$this->encryptionOptionGroup.'['.$this->password.']" required>';
			}, $this->encryptionSettingsPage, $sectionId);
			add_settings_field($prefix.'nonce', '', function() : void {
				echo '<input type="hidden" name="'.$this->encryptionOptionGroup.'['.$this->nonce.']">';
			}, $this->encryptionSettingsPage, $sectionId);
			$this->registerSetting($this->encryptionOptionGroup);
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
	 * Handle the options saving
	 */
	private function handleOptionsSaving() : void {
		add_filter(Filter::getPreUpdateOptionFilter($this->encryptionOptionGroup), function(array $value) : array {
			$key = $this->securityService->generateEncryptionKey($value[$this->password]);
			$value[$this->password] = $this->convertToHexadecimal($key);
			$nonce = $this->securityService->generateNonce();
			$value[$this->nonce] = $this->convertToHexadecimal($nonce);
			return $value;
		});
		add_filter(Filter::getPreUpdateOptionFilter($this->dropboxOptionGroup), function(array $value) : array {
			$nonce = $this->getNonce();
			$key = $this->getPassword();
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
		if(isset($this->dropboxOptions[$this->appKey])) {
			$message = $this->convertToBinary($this->dropboxOptions[$this->appKey]);
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
		if(isset($this->dropboxOptions[$this->appSecret])) {
			$message = $this->convertToBinary($this->dropboxOptions[$this->appSecret]);
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
		if(isset($this->dropboxOptions[$this->redirectUri])) {
			$message = $this->convertToBinary($this->dropboxOptions[$this->redirectUri]);
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
		if(isset($this->encryptionOptions[$this->nonce])) {
			return $this->convertToBinary($this->encryptionOptions[$this->nonce]);
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
		if(isset($this->encryptionOptions[$this->password])) {
			return $this->convertToBinary($this->encryptionOptions[$this->password]);
		} else {
			return '';
		}
	}
}