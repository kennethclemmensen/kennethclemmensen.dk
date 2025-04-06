<?php
namespace KC\Core\Files;

/**
 * The FileService class contains file methods.
 * The class cannot be inherited.
 */
final class FileService {

	/**
	 * Get the content from a file
	 * 
	 * @param string $file the file
	 * @return string the file content
	 */
	public function getFileContent(string $file) : string {
		return file_get_contents($file);
	}

	/**
	 * Get the filesize for a file
	 * 
	 * @param string $file the file
	 * @return int the filesize
	 */
	public function getFilesize(string $file) : int {
		return filesize($file);
	}
}