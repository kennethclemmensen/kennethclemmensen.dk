<?php
namespace KC\Core\Http;

/**
 * The HttpMethod enum defines the http methods
 */
enum HttpMethod: string {
	case Get = 'GET';
	case Put = 'PUT';
}