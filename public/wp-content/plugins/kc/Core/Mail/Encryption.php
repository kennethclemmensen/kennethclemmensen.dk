<?php
namespace KC\Core\Mail;

/**
 * The Encryption enum defines the encryption methods
 */
enum Encryption: string {
	case None = '';
	case Tls = 'tls';
}