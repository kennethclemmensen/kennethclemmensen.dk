<?php
namespace KC\Core\Api;

/**
 * The HttpService class contains http methods
 */
final readonly class HttpService {

	/**
	 * Send a http header
	 * 
	 * @param HttpHeader $header the header to send
	 * @param mixed $value the value of the header
	 */
	public function sendHttpHeader(HttpHeader $header, string | int $value) : void {
		header($header->value.':'.$value);
	}
}