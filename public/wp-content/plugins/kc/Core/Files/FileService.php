<?php
namespace KC\Core\Files;

/**
 * The FileService class contains file methods
 */
final readonly class FileService {

	/**
	 * Get the content from a file
	 * 
	 * @param string $file the file
	 * @return string the file content
	 */
	public function getFileContent(string $file) : string {
		return file_get_contents($file);
	}
}