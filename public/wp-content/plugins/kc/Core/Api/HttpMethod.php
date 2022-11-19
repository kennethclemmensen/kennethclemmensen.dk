<?php
namespace KC\Core\Api;

/**
 * The HttpMethod enum defines the http methods
 */
enum HttpMethod: string {
	case Get = 'GET';
	case Post = 'POST';
	case Put = 'PUT';
}