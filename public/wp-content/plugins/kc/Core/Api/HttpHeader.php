<?php
namespace KC\Core\Api;

/**
 * The HttpHeader enum defines the http headers
 */
enum HttpHeader: string {
	case AccessControlAllowOrigin = 'Access-Control-Allow-Origin';
	case CacheControl = 'Cache-Control';
	case ContentDescription = 'Content-Description';
	case ContentDisposition = 'Content-Disposition';
	case ContentLength = 'Content-Length';
	case ContentType = 'Content-Type';
	case Expires = 'Expires';
	case Pragma = 'Pragma';
}