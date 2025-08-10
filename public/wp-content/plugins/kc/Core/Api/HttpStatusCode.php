<?php
namespace KC\Core\Api;

/**
 * The HttpStatusCode enum defines the http status codes
 */
enum HttpStatusCode: int {
	case OK = 200;
	case NoContent = 204;
}