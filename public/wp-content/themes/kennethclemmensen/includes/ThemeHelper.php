<?php
use \KCGallery\Includes\KCGallery;

/**
 * The ThemeHelper class contains utility methods to use in the theme
 */
final class ThemeHelper {

    /**
     * Add a script from a CDN with a fallback to a local file
     *
     * @param string $name the name of the script
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     * @param int $ver the script version number
     * @param array $deps the dependencies of the script
     * @param bool $inFooter false if the script should be enqueued in the header. True is default
     */
    public static function addScriptWithLocalFallback(string $name, string $cdnFile, string $localFile, ?int $ver = null, array $deps = [], bool $inFooter = true) : void {
        $file = @fopen($cdnFile, 'r');
        $src = ($file) ? $cdnFile : $localFile;
        wp_deregister_script($name);
        wp_enqueue_script($name, $src, $deps, $ver, $inFooter);
    }

    /**
     * Add a stylesheet from a CDN with a fallback to a local file
     *
     * @param string $name the name of the stylesheet
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     * @param int $ver the stylesheet version number
     * @param array $deps the dependencies of the stylesheet
     */
    public static function addStyleWithLocalFallback(string $name, string $cdnFile, string $localFile, ?int $ver = null, array $deps = []) : void {
        $file = @fopen($cdnFile, 'r');
        $src = ($file) ? $cdnFile : $localFile;
        wp_deregister_style($name);
        wp_enqueue_style($name, $src, $deps, $ver);
    }

    /**
     * Remove the version query string from the source
     *
     * @param string $src the source to remove the version query string from
     * @return string the source without the version query string
     */
    public static function removeVersionQueryString(string $src) : string {
        return explode('?ver', $src)[0];
    }

    /**
     * Get the breadcrumb as an array of page IDs
     *
     * @return array the breadcrumb as an array of page IDs
     */
    public static function getBreadcrumb() : array {
        if(!is_front_page()) {
            global $post;
            $pages[] = $post->ID;
            $parent = $post->post_parent;
            while($parent !== 0) {
                $page = get_post($parent);
                $pages[] = $page->ID;
                $parent = $page->post_parent;
            }
            if($post->post_type === KCGallery::GALLERY) $pages[] = ThemeSettings::getInstance()->getImagesPageID();
        }
        $pages[] = get_option('page_on_front');
        return array_reverse($pages);
    }

    /**
     * Get the id of the footer sidebar
     *
     * @return string the id of the footer sidebar
     */
    public static function getFooterSidebarID() : string {
        return 'footer';
    }

    /**
     * Get the id of the page not found sidebar
     *
     * @return string the id of the page not found sidebar
     */
    public static function getPageNotFoundSidebarID() : string {
        return 'page-not-found';
    }

    /**
     * Get the main menu key
     *
     * @return string the main menu key
     */
    public static function getMainMenuKey() : string {
        return 'main-menu';
    }

    /**
     * Get the mobile menu key
     *
     * @return string the mobile menu key
     */
    public static function getMobileMenuKey() : string {
        return 'mobile-menu';
    }
}