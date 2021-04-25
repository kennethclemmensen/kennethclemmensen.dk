<?php
namespace KC\Data;

/**
 * The FileManager class contains functionality to manage files
 */
class FileManager {

    /**
     * Create a file
     * 
     * @param string $fileName the file name
     * @param string $content the file content
     * @param string $folder the folder to create the file in
     */
    public function createFile(string $fileName, string $content, string $folder) : void {
        @mkdir($folder);
        $file = fopen($folder.'/'.$fileName, 'w');
        fwrite($file, $content);
        fclose($file);
    }
}