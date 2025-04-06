<?php
namespace KC\Core\Mail;

/**
 * Provides functionality to handle mails.
 * The class cannot be inherited.
 */
final class MailService {

	/**
	 * Send a mail to a recipient with a subject and a message
	 * 
	 * @param string $recipient the recipient of the mail
	 * @param string $subject the subject of the mail
	 * @param string $message the message of the mail
	 * @return bool true if the mail is send. False if the mail isn't send
	 */
	public function sendMail(string $recipient, string $subject, string $message) : bool {
		return wp_mail($recipient, $subject, $message);
	}
}