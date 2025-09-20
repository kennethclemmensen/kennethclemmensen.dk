<?php
/**
 * The ApiClient class contains methods to get data from the Api
 */
final readonly class ApiClient {

	/**
	 * Get the slides
	 * 
	 * @return array the slides
	 */
	public function getSlides() : array {
		return $this->getData('/slides');
	}

	/**
	 * Get the galleries
	 * 
	 * @return array the galleries
	 */
	public function getGalleries() : array {
		return $this->getData('/galleries');
	}

	/**
	 * Get the images
	 * 
	 * @return array the images
	 */
	public function getImages() : array {
		return $this->getData('/galleries/'.get_the_ID());
	}

	/**
	 * Get data from the Api
	 * 
	 * @param string $apiUrl the url to the Api
	 * @return array the data
	 */
	private function getData(string $apiUrl) : array {
		$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/wp-json/kcapi/v1';
		$data = @json_decode(file_get_contents($url.$apiUrl), true);
		return ($data !== null) ? $data : [];
	}
}