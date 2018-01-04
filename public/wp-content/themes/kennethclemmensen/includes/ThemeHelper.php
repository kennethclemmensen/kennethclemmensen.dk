<?php
/**
 * The ThemeHelper class contains utility methods to use in the theme
 */
class ThemeHelper {

    /**
     * Add a script from a CDN with a fallback to a local file
     *
     * @param string $handle the name of the script
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     * @param array $deps the dependencies of the script
     * @param bool $ver the script version number
     * @param bool $inFooter false if the script should be enqueued in the header
     */
    public static function addScriptWithLocalFallback(string $handle, string $cdnFile, string $localFile, array $deps = [], bool $ver = false, bool $inFooter = true) {
        $file = @fopen($cdnFile, 'r');
        $src = ($file === false) ? $localFile : $cdnFile;
        wp_deregister_script($handle);
        wp_enqueue_script($handle, $src, $deps, $ver, $inFooter);
    }

    /**
     * Add a stylesheet from a CDN with a fallback to a local file
     *
     * @param string $handle the name of the stylesheet
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     */
    public static function addStyleWithLocalFallback(string $handle, string $cdnFile, string $localFile) {
        $file = @fopen($cdnFile, 'r');
        $src = ($file === false) ? $localFile : $cdnFile;
        wp_enqueue_style($handle, $src);
    }

    /**
     * Get the breadcrumb as an array of page ids
     *
     * @return array an array of page ids
     */
    public static function getBreadcrumb() : array {
        global $post;
        if(!is_front_page()) {
            $pages[] = $post->ID;
            $parent = $post->post_parent;
            while($parent !== 0) {
                $page = get_post($parent);
                $pages[] = $page->ID;
                $parent = $page->post_parent;
            }
        }
        $pages[] = get_option('page_on_front');
        return array_reverse($pages);
    }
}