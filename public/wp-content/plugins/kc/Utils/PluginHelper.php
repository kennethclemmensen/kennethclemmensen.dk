<?php
namespace KC\Utils;

use KC\Core\Constant;

/**
 * The PluginHelper class contains utility methods to use in the plugin
 */
class PluginHelper {

    /**
     * Get the number of file downloads
     *
     * @param int $fileID the id of the file
     * @return int the number of file downloads
     */
    public static function getFileDownloads(int $fileID) : int {
        return get_post_meta($fileID, Constant::FILE_DOWNLOAD_COUNTER_FIELD_ID, true);
    }

    /**
     * Get the file type taxonomy name
     * 
     * @return string the file type taxonomy name
     */
    public static function getFileTypeTaxonomyName(): string {
        return 'fdwc_tax_file_type';
    }
}