<?php
namespace KC\Data\Api;

use KC\Core\Api\BaseApi;
use KC\Core\Api\ContentType;
use KC\Core\Api\HttpHeader;
use KC\Core\Files\FileService;

/**
 * The DropboxApi class contains functionality to communicate with the Dropbox Api
 */
final class DropboxApi extends BaseApi {

	private readonly string $accessToken;

	/**
	 * DropboxApi constructor
	 * 
	 * @param string $appKey the app key
	 * @param string $appSecret the app secret
	 * @param string $redirectUri the redirect uri
	 * @param string $code the code
	 */
	public function __construct(string $appKey, string $appSecret, string $redirectUri, string $code) {
		$url = 'https://api.dropboxapi.com/oauth2/token';
		$headers = [
			HttpHeader::ContentType->value.': '.ContentType::FormUrlEncoded->value
		];
		$postFields = http_build_query([
			'code' => $code,
			'grant_type' => 'authorization_code',
			'client_id' => $appKey,
			'client_secret' => $appSecret,
			'redirect_uri' => $redirectUri
		]);
		$result = $this->createPostRequest($url, $headers, $postFields);
		$this->accessToken = (isset($result['access_token'])) ? $result['access_token'] : '';
	}

	/**
	 * Upload a file from a folder
	 * 
	 * @param string $file the file to upload
	 * @param string $folder the folder that contains the file
	 */
	public function uploadFile(string $file, string $folder) : void {
		$url = 'https://content.dropboxapi.com/2/files/upload';
		$headers = [
			HttpHeader::Authorization->value.': Bearer '.$this->accessToken,
			HttpHeader::ContentType->value.': '.ContentType::OctetStream->value,
			HttpHeader::DropboxApiArg->value.': '.json_encode(['path' => '/'.$file])
		];
		$fileService = new FileService();
		$postFields = $fileService->getFileContent($folder.'/'.$file);
		$this->createPostRequest($url, $headers, $postFields);
	}
}