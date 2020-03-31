<?php
namespace KC\Core;

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
}