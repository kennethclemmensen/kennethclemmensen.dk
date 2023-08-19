<?php
namespace KC\Mail\Settings;

use KC\Core\Action;
use KC\Core\Filter;
use KC\Core\Mail\Encryption;
use KC\Core\Settings\BaseSettings;
use KC\Core\Security\SecurityService;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use KC\Core\Users\UserRole;

/**
 * The MailSettings class contains methods to handle the mail settings
 */
final class MailSettings extends BaseSettings {

	private readonly string $settingsPageSlug;
	private readonly string $settingOptionsName;
	private readonly array | bool $settingsOption;
	private readonly string $mailServer;
	private readonly string $mailPort;
	private readonly string $mailEncryption;
	private readonly string $fromEmail;
	private readonly string $fromName;
	private readonly string $username;
	private readonly string $password;
	private readonly string $encryptionSettingsPage;
	private readonly string $encryptionOptionName;
	private readonly array | bool $encryptionOptions;
	private readonly string $encryptionPassword;
	private readonly string $nonce;

	/**
	 * MailSettings constructor
	 * 
	 * @param SecurityService $securityService the security service
	 * @param TranslationService $translationService the translation service
	 */
	public function __construct(private readonly SecurityService $securityService, private readonly TranslationService $translationService) {
		$this->settingsPageSlug = 'kc-mail';
		$this->settingOptionsName = $this->settingsPageSlug.'-options';
		$this->settingsOption = get_option($this->settingOptionsName);
		$prefix = 'mail_';
		$this->mailServer = $prefix.'server';
		$this->mailPort = $prefix.'port';
		$this->mailEncryption = $prefix.'encryption';
		$this->fromEmail = $prefix.'from_email';
		$this->fromName = $prefix.'from_name';
		$this->username = $prefix.'username';
		$this->password = $prefix.'password';
		$this->encryptionSettingsPage = 'kc-mail-encryption-settings';
		$this->encryptionOptionName = $this->encryptionSettingsPage.'-options';
		$this->encryptionOptions = get_option($this->encryptionOptionName);
		$prefix = 'encryption_';
		$this->encryptionPassword = $prefix.'password';
		$this->nonce = $prefix.'nonce';
		$this->registerSettingInputs();
		$this->registerEncryptionSettings();
		$this->handleOptionsSaving();
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		add_action(Action::ADMIN_MENU, function() : void {
			$title = 'Mail';
			add_management_page($title, $title, UserRole::Administrator->value, $this->settingsPageSlug, function() : void {
				settings_errors();
				$settingsTab = 'settings';
				$testMailTab = 'testmail';
				$encryptionTab = 'encryption';
				$currentTab = (isset($_GET['tab'])) ? $_GET['tab'] : $settingsTab;
				$activeTabClass = 'nav-tab-active';
				?>
				<div class="wrap">
					<h2 class="nav-tab-wrapper">
						<a href="?page=<?php echo $this->settingsPageSlug.'&tab='.$settingsTab; ?>" class="nav-tab <?php echo ($currentTab === $settingsTab) ? $activeTabClass : ''; ?>">
							<?php echo $this->translationService->getTranslatedString(TranslationString::Settings); ?>
						</a>
						<a href="?page=<?php echo $this->settingsPageSlug.'&tab='.$testMailTab; ?>" class="nav-tab <?php echo ($currentTab === $testMailTab) ? $activeTabClass : ''; ?>">
							<?php echo $this->translationService->getTranslatedString(TranslationString::TestMail); ?>
						</a>
						<a href="?page=<?php echo $this->settingsPageSlug.'&tab='.$encryptionTab; ?>" class="nav-tab <?php echo ($currentTab === $encryptionTab) ? $activeTabClass : ''; ?>">
							<?php echo $this->translationService->getTranslatedString(TranslationString::Encryption); ?>
						</a>
					</h2>
					<?php
					switch($currentTab) {
						case $testMailTab:
							$value = $this->translationService->getTranslatedString(TranslationString::SendMail);
							if(isset($_POST['sendMail'])) {
								$to = $_POST['email'];
								$subject = $_POST['subject'];
								$message = $_POST['message'];
								wp_mail($to, $subject, $message);
							}
							?>
							<form action="" method="post">
								<label for="email">
									<?php echo $this->translationService->getTranslatedString(TranslationString::Email); ?>
								</label>
								<input type="email" name="email" id="email" required>
								<label for="subject">
									<?php echo $this->translationService->getTranslatedString(TranslationString::Subject); ?>
								</label>
								<input type="text" name="subject" id="subject" required>
								<label for="message">
									<?php echo $this->translationService->getTranslatedString(TranslationString::Message); ?>
								</label>
								<textarea name="message" id="message" required></textarea>
								<input type="submit" name="sendMail" value="<?php echo $value; ?>" class="button button-primary">
							<?php
							break;
						default:
							?>
							<form action="options.php" method="post">
								<?php
								if ($currentTab === $encryptionTab) {
									settings_fields($this->encryptionOptionName);
									do_settings_sections($this->encryptionSettingsPage);
								} else {
									settings_fields($this->settingOptionsName);
									do_settings_sections($this->settingsPageSlug);
								}
								submit_button();
								?>
							</form>
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
	 * Use the admin_init action to register the setting inputs
	 */
	private function registerSettingInputs() : void {
		add_action(Action::ADMIN_INIT, function() : void {
			$sectionID = $this->settingsPageSlug.'-section-mail';
			$prefix = $this->settingsPageSlug;
			$server = $this->translationService->getTranslatedString(TranslationString::Server);
			$port = $this->translationService->getTranslatedString(TranslationString::Port);
			$fromEmail = $this->translationService->getTranslatedString(TranslationString::FromEmail);
			$fromName = $this->translationService->getTranslatedString(TranslationString::FromName);
			$username = $this->translationService->getTranslatedString(TranslationString::Username);
			$password = $this->translationService->getTranslatedString(TranslationString::Password);
			add_settings_section($sectionID, '', function() : void {}, $this->settingsPageSlug);
			add_settings_field($prefix.'server', $server, function() : void {
				echo '<input type="text" name="'.$this->settingOptionsName.'['.$this->mailServer.']" value="'.$this->getMailServer().'">';
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($prefix.'port', $port, function() : void {
				echo '<input type="number" name="'.$this->settingOptionsName.'['.$this->mailPort.']" value="'.$this->getMailPort().'">';
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($prefix.'encryption', $this->translationService->getTranslatedString(TranslationString::Encryption), function() : void {
				?>
				<select name="<?php echo $this->settingOptionsName.'['.$this->mailEncryption.']'; ?>">
					<?php
					$mailEncryption = $this->getMailEncryption();
					$none = $this->translationService->getTranslatedString(TranslationString::None);
					$tls = $this->translationService->getTranslatedString(TranslationString::Tls);
					echo '<option value="'.Encryption::None->value.'" '.selected($mailEncryption, Encryption::None->value).'>'.$none.'</option>';
					echo '<option value="'.Encryption::Tls->value.'" '.selected($mailEncryption, Encryption::Tls->value).'>'.$tls.'</option>';
					?>
				</select>
				<?php
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($prefix.'from_email', $fromEmail, function() : void {
				echo '<input type="email" name="'.$this->settingOptionsName.'['.$this->fromEmail.']" value="'.$this->getFromEmail().'">';
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($prefix.'from_name', $fromName, function() : void {
				echo '<input type="text" name="'.$this->settingOptionsName.'['.$this->fromName.']" value="'.$this->getFromName().'">';
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($prefix.'username', $username, function() : void {
				echo '<input type="text" name="'.$this->settingOptionsName.'['.$this->username.']" value="'.$this->getUsername().'">';
			}, $this->settingsPageSlug, $sectionID);
			add_settings_field($prefix.'password', $password, function() : void {
				echo '<input type="password" name="'.$this->settingOptionsName.'['.$this->password.']" value="'.$this->getPassword().'">';
			}, $this->settingsPageSlug, $sectionID);
			$this->registerSetting($this->settingOptionsName);
		});
	}

	/**
	 * Register the encryption settings
	 */
	private function registerEncryptionSettings() : void {
		add_action(Action::ADMIN_INIT, function() : void {
			$sectionId = $this->encryptionSettingsPage.'-section-encryption';
			$prefix = $this->encryptionSettingsPage;
			$password = $this->translationService->getTranslatedString(TranslationString::Password);
			add_settings_section($sectionId, '', function() : void {}, $this->encryptionSettingsPage);
			add_settings_field($prefix.'password', $password, function() : void {
				echo '<input type="password" name="'.$this->encryptionOptionName.'['.$this->encryptionPassword.']" required>';
			}, $this->encryptionSettingsPage, $sectionId);
			add_settings_field($prefix.'nonce', '', function() : void {
				echo '<input type="hidden" name="'.$this->encryptionOptionName.'['.$this->nonce.']">';
			}, $this->encryptionSettingsPage, $sectionId);
			$this->registerSetting($this->encryptionOptionName);
		});
	}

	/**
	 * Handle the options saving
	 */
	private function handleOptionsSaving() : void {
		add_filter(Filter::getPreUpdateOptionFilter($this->encryptionOptionName), function(array $value) : array {
			$key = $this->securityService->generateEncryptionKey($value[$this->encryptionPassword]);
			$value[$this->encryptionPassword] = $this->convertToHexadecimal($key);
			$nonce = $this->securityService->generateNonce();
			$value[$this->nonce] = $this->convertToHexadecimal($nonce);
			return $value;
		});
		add_filter(Filter::getPreUpdateOptionFilter($this->settingOptionsName), function(array $value) : array {
			$nonce = $this->getNonce();
			$key = $this->getEncryptionPassword();
			$password = $this->securityService->encryptMessage($value[$this->password], $nonce, $key);
			$value[$this->password] = $this->convertToHexadecimal($password);
			return $value;
		});
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
	 * Get the encryption password
	 * 
	 * @return string the encryption password
	 */
	private function getEncryptionPassword() : string {
		if(isset($this->encryptionOptions[$this->encryptionPassword])) {
			return $this->convertToBinary($this->encryptionOptions[$this->encryptionPassword]);
		} else {
			return '';
		}
	}

	/**
	 * Get the mail server
	 * 
	 * @return string the mail server
	 */
	public function getMailServer() : string {
		return $this->settingsOption[$this->mailServer] ?? '';
	}

	/**
	 * Get the mail port
	 * 
	 * @return int the mail port
	 */
	public function getMailPort() : ?int {
		$port = $this->settingsOption[$this->mailPort];
		return ($port !== '') ? $port : null;
	}

	/**
	 * Get the mail encryption
	 * 
	 * @return string the mail encryption
	 */
	public function getMailEncryption() : string {
		return $this->settingsOption[$this->mailEncryption] ?? Encryption::None->value;
	}

	/**
	 * Get the from email
	 * 
	 * @return string the from email
	 */
	public function getFromEmail() : string {
		return $this->settingsOption[$this->fromEmail] ?? '';
	}

	/**
	 * Get the from name
	 * 
	 * @return string the from name
	 */
	public function getFromName() : string {
		return $this->settingsOption[$this->fromName] ?? '';
	}

	/**
	 * Get the username
	 * 
	 * @return string the username
	 */
	public function getUsername() : string {
		return $this->settingsOption[$this->username] ?? '';
	}

	/**
	 * Get the password
	 * 
	 * @return string the password
	 */
	public function getPassword() : string {
		if(isset($this->settingsOption[$this->password])) {
			$message = $this->convertToBinary($this->settingsOption[$this->password]);
			$nonce = $this->getNonce();
			$key = $this->getEncryptionPassword();
			return $this->securityService->decryptMessage($message, $nonce, $key);
		} else {
			return '';	
		}
	}
}