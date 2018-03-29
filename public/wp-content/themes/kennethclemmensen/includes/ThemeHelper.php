<?php
/**
 * The ThemeHelper class contains utility methods to use in the theme
 */
class ThemeHelper {

    /**
     * Add a script from a CDN with a fallback to a local file
     *
     * @param string $name the name of the script
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     * @param array $deps the dependencies of the script
     * @param int $ver the script version number
     * @param bool $inFooter false if the script should be enqueued in the header
     */
    public static function addScriptWithLocalFallback(string $name, string $cdnFile, string $localFile, array $deps = [], int $ver = null, bool $inFooter = true) : void {
        $file = @fopen($cdnFile, 'r');
        if($file === false) {
            $src = $localFile;
        } else {
            $src = $cdnFile;
            $ver = null;
        }
        wp_deregister_script($name);
        wp_enqueue_script($name, $src, $deps, $ver, $inFooter);
    }

    /**
     * Add a stylesheet from a CDN with a fallback to a local file
     *
     * @param string $name the name of the stylesheet
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     * @param array $deps the dependencies of the stylesheet
     * @param int $ver the stylesheet version number
     */
    public static function addStyleWithLocalFallback(string $name, string $cdnFile, string $localFile, array $deps = [], int $ver = null) : void {
        $file = @fopen($cdnFile, 'r');
        if($file === false) {
            $src = $localFile;
        } else {
            $src = $cdnFile;
            $ver = null;
        }
        wp_enqueue_style($name, $src, $deps, $ver);
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