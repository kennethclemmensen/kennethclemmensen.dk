<?php
/**
 * The ApiClient class contains methods to get data from the Api
 */
final class ApiClient {

	/**
	 * Get the slides
	 * 
	 * @return array the slides
	 */
	public static function getSlides() : array {
		return self::getData('/slides');
	}

	/**
	 * Get the galleries
	 * 
	 * @return array the galleries
	 */
	public static function getGalleries() : array {
		return self::getData('/galleries');
	}

	/**
	 * Get the images
	 * 
	 * @return array the images
	 */
	public static function getImages() : array {
		return self::getData('/galleries/'.get_the_ID());
	}

	/**
	 * Get data from the Api
	 * 
	 * @param string $apiUrl the url to the Api
	 * @return array the data
	 */
	private static function getData(string $apiUrl) : array {
		$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/wp-json/kcapi/v1';
		$data = @json_decode(file_get_contents($url.$apiUrl), true);
		return ($data !== null) ? $data : [];
	}
}