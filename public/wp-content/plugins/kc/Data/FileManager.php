<?php
namespace KC\Data;

use KC\Utils\PluginHelper;
use \ZipArchive;

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
        $this->createFolder($folder);
        PluginHelper::appendSlash($folder);
        $file = fopen($folder.$fileName, 'w');
        fwrite($file, $content);
        fclose($file);
    }

    /**
     * Create a zip file
     * 
     * @param string $fileName the file name
     * @param string $files the files to add to the zip file
     * @param string $sourceFolder the folder to create the zip file from
     * @param string $destinationFolder the folder to create the zip file in
     */
    public function createZipFile(string $fileName, string $files, string $sourceFolder, string $destinationfolder) : void {
        $this->createFolder($destinationfolder);
        PluginHelper::appendSlash($destinationfolder);
        $zip = new ZipArchive();
        $zip->open($destinationfolder.$fileName, ZipArchive::CREATE);
        $options = [
            'add_path' => '/',
            'remove_path' => $sourceFolder
        ];
        $zip->addGlob($files, options: $options);
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
        PluginHelper::appendSlash($folder);
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
        PluginHelper::appendSlash($folder);
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
}