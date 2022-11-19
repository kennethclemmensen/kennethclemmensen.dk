<?php
namespace KC\Core\Api;

/**
 * The ContentType enum defines the content types
 */
enum ContentType: string {
	case FormUrlEncoded = 'application/x-www-form-urlencoded';
	case OctetStream = 'application/octet-stream';
}