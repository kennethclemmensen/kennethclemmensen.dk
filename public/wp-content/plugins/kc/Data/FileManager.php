<?php
namespace KC\Data;

use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \ZipArchive;

/**
 * The FileManager class contains functionality to manage files
 */
final class FileManager {

	/**
	 * Create a file
	 * 
	 * @param string $fileName the file name
	 * @param string $content the file content
	 * @param string $folder the folder to create the file in
	 */
	public function createFile(string $fileName, string $content, string $folder) : void {
		$this->createFolder($folder);
		$this->appendSlash($folder);
		$file = fopen($folder.$fileName, 'w');
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
		$this->appendSlash($destinationFolder);
		$zip = new ZipArchive();
		$zip->open($destinationFolder.$fileName, ZipArchive::CREATE);
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceFolder));
		foreach($files as $key => $file) {
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
		$this->appendSlash($folder);
		$file = $folder.$fileName;
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: '.filesize($file));
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
		$this->appendSlash($folder);
		unlink($folder.$fileName);
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
	 * @param string $str the string to append the slash to
	 */
	private static function appendSlash(string &$str) : void {
		$str .= '/';
	}
}