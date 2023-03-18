<?php
namespace KC\Data\Files;

use KC\Core\Api\ContentType;
use KC\Core\Api\HttpHeader;
use KC\Core\Api\HttpService;
use KC\Core\Exceptions\EmptyStringException;
use KC\Core\Files\FileService;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \ZipArchive;

/**
 * The FileManager class contains functionality to manage files
 */
final readonly class FileManager {

	/**
	 * Create a file
	 * 
	 * @param string $fileName the file name
	 * @param string $content the file content
	 * @param string $folder the folder to create the file in
	 */
	public function createFile(string $fileName, string $content, string $folder) : void {
		$this->createFolder($folder);
		$path = '';
		try {
			$this->appendSlash($folder);
			$path .= $folder.$fileName;
		} catch(EmptyStringException) {
			$path .= $fileName;
		}
		$file = fopen($path, 'w');
		fwrite($file, $content);
		fclose($file);
	}

	/**
	 * Create a zip file
	 * 
	 * @param string $fileName the file name
	 * @param string $sourceFolder the folder to create the zip file from
	 * @param string $destinationFolder the folder to create the zip file in
	 */
	public function createZipFile(string $fileName, string $sourceFolder, string $destinationFolder) : void {
		$this->createFolder($destinationFolder);
		$path = '';
		try {
			$this->appendSlash($destinationFolder);
			$path .= $destinationFolder.$fileName;
		} catch(EmptyStringException) {
			$path .= $fileName;
		}
		$zip = new ZipArchive();
		$zip->open($path, ZipArchive::CREATE);
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceFolder));
		foreach($files as $file) {
			if(!$file->isDir()) {
				$filePath = $file->getRealPath();
				$entryName = substr($filePath, strlen($sourceFolder) + 1);
				$zip->addFile($filePath, $entryName);
			}
		}
		$zip->close();
	}

	/**
	 * Get the files from a folder
	 * 
	 * @param string $folder the folder to get the files from
	 * @return array the files from the folder
	 */
	public function getFiles(string $folder) : array {
		$files = [];
		if(file_exists($folder)) {
			$dir = opendir($folder);
			while($file = readdir($dir)) {
				if($file !== '.' && $file !== '..') {
					$files[] = $file;
				}
			}
		}
		return $files;
	}

	/**
	 * Download a file from a folder
	 * 
	 * @param string $fileName the file name
	 * @param string $folder the folder
	 */
	public function downloadFile(string $fileName, string $folder) : void {
		$file = '';
		try {
			$this->appendSlash($folder);
			$file .= $folder.$fileName;
		} catch(EmptyStringException) {
			$file .= $fileName;
		}
		$fileService = new FileService();
		$httpService = new HttpService();
		$httpService->sendHttpHeader(HttpHeader::ContentDescription, 'File Transfer');
		$httpService->sendHttpHeader(HttpHeader::ContentType, ContentType::OctetStream->value);
		$httpService->sendHttpHeader(HttpHeader::ContentDisposition, 'attachment; filename="'.basename($file).'"');
		$httpService->sendHttpHeader(HttpHeader::Expires, 0);
		$httpService->sendHttpHeader(HttpHeader::CacheControl, 'must-revalidate');
		$httpService->sendHttpHeader(HttpHeader::Pragma, 'public');
		$httpService->sendHttpHeader(HttpHeader::ContentLength, $fileService->getFilesize($file));
		readfile($file);
		exit;
	}

	/**
	 * Delete a file from a folder
	 * 
	 * @param string $fileName the file name
	 * @param string $folder the folder
	 */
	public function deleteFile(string $fileName, string $folder) : void {
		$path = '';
		try {
			$this->appendSlash($folder);
			$path .= $folder.$fileName;
		} catch(EmptyStringException) {
			$path .= $fileName;
		}
		unlink($path);
	}

	/**
	 * Create a folder
	 * 
	 * @param string $folder the folder to create
	 */
	private function createFolder(string $folder) : void {
		if(!file_exists($folder)) mkdir($folder);
	}

	/**
	 * Append a slash to a string
	 * 
	 * @param string $string the string to append the slash to
	 * @throws EmptyStringException if string is empty
	 */
	private function appendSlash(string &$string) : void {
		if($string === '') throw new EmptyStringException();
		$string .= '/';
	}
}