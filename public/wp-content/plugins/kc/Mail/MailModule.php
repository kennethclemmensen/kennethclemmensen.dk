<?php
namespace KC\Mail;

use KC\Core\Action;
use KC\Core\Mail\MailService;
use KC\Core\Modules\IModule;
use KC\Core\Security\SecurityService;
use KC\Core\Translations\TranslationService;
use KC\Mail\Settings\MailSettings;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * The MailModule class contains functionality to set up the mail module
 */
final readonly class MailModule implements IModule {

	private readonly MailSettings $mailSettings;

	/**
	 * MailModule constructor.
	 */
	public function __construct() {
		$securityService = new SecurityService();
		$translationService = new TranslationService();
		$mailService = new MailService();
		$this->mailSettings = new MailSettings($securityService, $translationService, $mailService);
	}

	/**
	 * Setup the mail module
	 */
	public function setupModule() : void {
		$this->mailSettings->createSettingsPage();
		add_action(Action::PHPMAILER_INIT, function(PHPMailer $phpMailer) : void {
			$phpMailer->isSMTP();
			$phpMailer->Host = $this->mailSettings->getMailServer();
			$phpMailer->SMTPSecure = $this->mailSettings->getMailEncryption();
			$phpMailer->Port = $this->mailSettings->getMailPort();
			$phpMailer->From = $this->mailSettings->getFromEmail();
			$phpMailer->FromName = $this->mailSettings->getFromName();
			$phpMailer->Sender = $phpMailer->From;
			$phpMailer->SMTPAuth = true;
			$phpMailer->Username = $this->mailSettings->getUsername();
			$phpMailer->Password = $this->mailSettings->getPassword();
		});
	}
}