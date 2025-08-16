<?php
namespace KC\Core\Api\Responses;

use KC\Core\Api\HttpStatusCode;

/**
 * Represents a response with a no content status code.
 */
final class NoContentResponse extends Response {

	/**
	 * Initialize a new instance of the NoContentResponse class
	 */
	public function __construct() {
		parent::__construct(null, HttpStatusCode::NoContent);
	}
}