<?php
namespace KC\Core\Api\Responses;

use KC\Core\Api\HttpStatusCode;

/**
 * Represents a response with an OK status code.
 */
final class OkResponse extends Response {

	/**
	 * Initialize a new instance of the OkResponse class
	 * 
	 * @param array $data the data to include in the response
	 */
	public function __construct(array $data) {
		parent::__construct($data, HttpStatusCode::OK);
	}
}