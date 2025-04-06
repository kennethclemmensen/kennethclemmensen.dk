<?php
namespace KC\Mail;

use KC\Core\Action;
use KC\Core\PluginService;
use KC\Core\Mail\MailService;
use KC\Core\Modules\IModule;
use KC\Core\Security\SecurityService;
use KC\Core\Translations\TranslationService;
use KC\Mail\Settings\MailSettings;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * The MailModule class contains functionality to set up the mail module.
 * The class cannot be inherited.
 */
final class MailModule implements IModule {

	private readonly MailSettings $mailSettings;
	private readonly PluginService $pluginService;

	/**
	 * MailModule constructor.
	 */
	public function __construct() {
		$securityService = new SecurityService();
		$translationService = new TranslationService();
		$mailService = new MailService();
		$this->pluginService = new PluginService();
		$this->mailSettings = new MailSettings($securityService, $translationService, $mailService, $this->pluginService);
	}

	/**
	 * Setup the mail module
	 */
	public function setupModule() : void {
		$this->mailSettings->createSettingsPage();
		$this->pluginService->addAction(Action::PHPMAILER_INIT, function(PHPMailer $phpMailer) : void {
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