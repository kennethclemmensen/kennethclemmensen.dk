<?php
namespace KC\Core\Api\Responses;

use KC\Core\Api\HttpStatusCode;
use \WP_REST_Response;

/**
 * Represents a generic response for the API.
 */
class Response extends WP_REST_Response {

	/**
	 * Initialize a new instance of the Response class
	 *
	 * @param ?array $data the data to include in the response
	 * @param HttpStatusCode $httpStatusCode the HTTP status code for the response
	 */
	protected function __construct(?array $data, HttpStatusCode $httpStatusCode) {
		parent::__construct($data, $httpStatusCode->value);
	}
}