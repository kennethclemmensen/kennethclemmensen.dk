<?php
namespace KC\Core\Api;

/**
 * The BaseApi class contains basic functionality for an api
 */
class BaseApi {

	/**
	 * Create a post request with an url, headers and post fields
	 * 
	 * @param string $url the url
	 * @param array $headers the headers
	 * @param string $postFields the post fields
	 * @return array the result of the post request
	 */
	protected function createPostRequest(string $url, array $headers, string $postFields) : array {
		$curlHandle = curl_init($url);
		curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, HttpMethod::Post->value);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postFields);
		$result = json_decode(curl_exec($curlHandle), true);
		curl_close($curlHandle);
		return $result;
	}
}