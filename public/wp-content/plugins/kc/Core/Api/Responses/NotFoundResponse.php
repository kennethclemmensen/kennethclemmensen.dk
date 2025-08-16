<?php
namespace KC\Core\Api\Responses;

use KC\Core\Api\HttpStatusCode;

/**
 * Represents a response with a not found status code.
 */
final class NotFoundResponse extends Response {

	/**
	 * Initialize a new instance of the NotFoundResponse class
	 */
	public function __construct() {
		parent::__construct(null, HttpStatusCode::NotFound);
	}
}