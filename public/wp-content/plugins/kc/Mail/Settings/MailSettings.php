<?php
namespace KC\Mail\Settings;

use KC\Core\Action;
use KC\Core\Filter;
use KC\Core\PluginService;
use KC\Core\Mail\Encryption;
use KC\Core\Mail\MailService;
use KC\Core\Settings\BaseSettings;
use KC\Core\Security\SecurityService;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;
use KC\Core\Users\UserRole;

/**
 * The MailSettings class contains methods to handle the mail settings.
 * The class cannot be inherited.
 */
final class MailSettings extends BaseSettings {

	private readonly string $mailServer;
	private readonly string $mailPort;
	private readonly string $mailEncryption;
	private readonly string $fromEmail;
	private readonly string $fromName;
	private readonly string $username;
	private readonly string $password;
	private readonly string $encryptionPassword;
	private readonly string $nonce;
	private readonly SecurityService $securityService;
	private readonly TranslationService $translationService;
	private readonly MailService $mailService;
	private readonly PluginService $pluginService;

	/**
	 * MailSettings constructor
	 * 
	 * @param SecurityService $securityService the security service
	 * @param TranslationService $translationService the translation service
	 * @param MailService $mailService the mail service
	 * @param PluginService $pluginService the plugin service
	 */
	public function __construct(SecurityService $securityService, TranslationService $translationService, MailService $mailService, PluginService $pluginService) {
		parent::__construct('kc-mail', 'kc-mail-options');
		$prefix = 'mail_';
		$this->mailServer = $prefix.'server';
		$this->mailPort = $prefix.'port';
		$this->mailEncryption = $prefix.'encryption';
		$this->fromEmail = $prefix.'from_email';
		$this->fromName = $prefix.'from_name';
		$this->username = $prefix.'username';
		$this->password = $prefix.'password';
		$this->encryptionPassword = $prefix.'encryption_password';
		$this->nonce = $prefix.'nonce';
		$this->securityService = $securityService;
		$this->translationService = $translationService;
		$this->mailService = $mailService;
		$this->pluginService = $pluginService;
		$this->registerSettingInputs();
		$this->handleOptionsSaving();
	}

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void {
		$this->pluginService->addAction(Action::ADMIN_MENU, function() : void {
			$title = $this->translationService->getTranslatedString(TranslationString::Mail);
			$this->addManagementPage($title, UserRole::Administrator->value, $this->settingsPage, function() : void {
				$tabs = [
					'settings' => [
						'title' => $this->translationService->getTranslatedString(TranslationString::Settings),
						'content' => function() : void {
							?>
							<form action="options.php" method="post">
								<?php
								settings_fields($this->settingsName);
								do_settings_sections($this->settingsPage);
								submit_button();
								?>
							</form>
							<?php
						}
					],
					'testmail' => [
						'title' => $this->translationService->getTranslatedString(TranslationString::TestMail),
						'content' => function() : void {
							$value = $this->translationService->getTranslatedString(TranslationString::SendMail);
							if(isset($_POST['sendMail'])) {
								$to = $_POST['email'];
								$subject = $_POST['subject'];
								$message = $_POST['message'];
								$isSend = $this->mailService->sendMail($to, $subject, $message);
								if ($isSend === true) {
									echo '<p>'.$this->translationService->getTranslatedString(TranslationString::TheEmailWasSent).'</p>';
								} else {
									echo '<p>'.$this->translationService->getTranslatedString(TranslationString::TheEmailWasNotSent).'</p>';
								}
							}
							?>
							<form action="" method="post" class="kc-mail-form">
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
						}
					]
				];
				$this->showTabs($tabs);
			});
		});
	}

	/**
	 * Use the admin_init action to register the setting inputs
	 */
	private function registerSettingInputs() : void {
		$this->pluginService->addAction(Action::ADMIN_INIT, function() : void {
			$sectionID = $this->settingsPage.'-section-mail';
			$prefix = $this->settingsPage;
			$server = $this->translationService->getTranslatedString(TranslationString::Server);
			$port = $this->translationService->getTranslatedString(TranslationString::Port);
			$fromEmail = $this->translationService->getTranslatedString(TranslationString::FromEmail);
			$fromName = $this->translationService->getTranslatedString(TranslationString::FromName);
			$username = $this->translationService->getTranslatedString(TranslationString::Username);
			$password = $this->translationService->getTranslatedString(TranslationString::Password);
			add_settings_section($sectionID, '', function() : void {}, $this->settingsPage);
			add_settings_field($prefix.'server', $server, function() : void {
				echo '<input type="text" name="'.$this->settingsName.'['.$this->mailServer.']" value="'.$this->getMailServer().'">';
			}, $this->settingsPage, $sectionID);
			add_settings_field($prefix.'port', $port, function() : void {
				echo '<input type="number" name="'.$this->settingsName.'['.$this->mailPort.']" value="'.$this->getMailPort().'">';
			}, $this->settingsPage, $sectionID);
			add_settings_field($prefix.'encryption', $this->translationService->getTranslatedString(TranslationString::Encryption), function() : void {
				?>
				<select name="<?php echo $this->settingsName.'['.$this->mailEncryption.']'; ?>">
					<?php
					$mailEncryption = $this->getMailEncryption();
					$none = $this->translationService->getTranslatedString(TranslationString::None);
					$tls = $this->translationService->getTranslatedString(TranslationString::Tls);
					echo '<option value="'.Encryption::None->value.'" '.selected($mailEncryption, Encryption::None->value).'>'.$none.'</option>';
					echo '<option value="'.Encryption::Tls->value.'" '.selected($mailEncryption, Encryption::Tls->value).'>'.$tls.'</option>';
					?>
				</select>
				<?php
			}, $this->settingsPage, $sectionID);
			add_settings_field($prefix.'from_email', $fromEmail, function() : void {
				echo '<input type="email" name="'.$this->settingsName.'['.$this->fromEmail.']" value="'.$this->getFromEmail().'">';
			}, $this->settingsPage, $sectionID);
			add_settings_field($prefix.'from_name', $fromName, function() : void {
				echo '<input type="text" name="'.$this->settingsName.'['.$this->fromName.']" value="'.$this->getFromName().'">';
			}, $this->settingsPage, $sectionID);
			add_settings_field($prefix.'username', $username, function() : void {
				echo '<input type="text" name="'.$this->settingsName.'['.$this->username.']" value="'.$this->getUsername().'">';
			}, $this->settingsPage, $sectionID);
			add_settings_field($prefix.'password', $password, function() : void {
				echo '<input type="password" name="'.$this->settingsName.'['.$this->password.']" value="'.$this->getPassword().'">';
			}, $this->settingsPage, $sectionID);
			add_settings_field($prefix.'encryption-password', '', function() : void {
				echo '<input type="hidden" name="'.$this->settingsName.'['.$this->encryptionPassword.']">';
			}, $this->settingsPage, $sectionID);
			add_settings_field($prefix.'nonce', '', function() : void {
				echo '<input type="hidden" name="'.$this->settingsName.'['.$this->nonce.']">';
			}, $this->settingsPage, $sectionID);
			$this->registerSetting($this->settingsName);
		});
	}

	/**
	 * Handle the options saving
	 */
	private function handleOptionsSaving() : void {
		$this->pluginService->addFilter(Filter::getPreUpdateOptionFilter($this->settingsName), function(array $value) : array {
			$key = $this->securityService->generateEncryptionKey($this->securityService->generatePassword());
			$value[$this->encryptionPassword] = $this->convertToHexadecimal($key);
			$nonce = $this->securityService->generateNonce();
			$value[$this->nonce] = $this->convertToHexadecimal($nonce);
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
		if(isset($this->settings[$this->nonce])) {
			return $this->convertToBinary($this->settings[$this->nonce]);
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
		if(isset($this->settings[$this->encryptionPassword])) {
			return $this->convertToBinary($this->settings[$this->encryptionPassword]);
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
		return $this->settings[$this->mailServer] ?? '';
	}

	/**
	 * Get the mail port
	 * 
	 * @return int the mail port
	 */
	public function getMailPort() : ?int {
		return $this->settings[$this->mailPort] ?? null;
	}

	/**
	 * Get the mail encryption
	 * 
	 * @return string the mail encryption
	 */
	public function getMailEncryption() : string {
		return $this->settings[$this->mailEncryption] ?? Encryption::None->value;
	}

	/**
	 * Get the from email
	 * 
	 * @return string the from email
	 */
	public function getFromEmail() : string {
		return $this->settings[$this->fromEmail] ?? '';
	}

	/**
	 * Get the from name
	 * 
	 * @return string the from name
	 */
	public function getFromName() : string {
		return $this->settings[$this->fromName] ?? '';
	}

	/**
	 * Get the username
	 * 
	 * @return string the username
	 */
	public function getUsername() : string {
		return $this->settings[$this->username] ?? '';
	}

	/**
	 * Get the password
	 * 
	 * @return string the password
	 */
	public function getPassword() : string {
		if(isset($this->settings[$this->password])) {
			$message = $this->convertToBinary($this->settings[$this->password]);
			$nonce = $this->getNonce();
			$key = $this->getEncryptionPassword();
			return $this->securityService->decryptMessage($message, $nonce, $key);
		} else {
			return '';	
		}
	}
}