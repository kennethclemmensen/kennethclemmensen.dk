<?php
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
     * @param int $versionNumber the script version number
     */
    public static function addScriptWithLocalFallback(string $name, string $cdnFile, string $localFile, ?int $versionNumber = null) : void {
        $file = @fopen($cdnFile, 'r');
        $src = ($file) ? $cdnFile : $localFile;
        wp_deregister_script($name);
        wp_enqueue_script($name, $src, [], $versionNumber, true);
    }

    /**
     * Add a stylesheet from a CDN with a fallback to a local file
     *
     * @param string $name the name of the stylesheet
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     * @param int $versionNumber the stylesheet version number
     */
    public static function addStyleWithLocalFallback(string $name, string $cdnFile, string $localFile, ?int $versionNumber = null) : void {
        $file = @fopen($cdnFile, 'r');
        $src = ($file) ? $cdnFile : $localFile;
        wp_deregister_style($name);
        wp_enqueue_style($name, $src, [], $versionNumber);
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
     * Load the breadcrumb template part
     */
    public static function loadBreadcrumbTemplatePart() : void {
        get_template_part('template-parts/breadcrumb');
    }

    /**
     * Load the slider template part
     */
    public static function loadSliderTemplatePart() : void {
        get_template_part('template-parts/slider');
    }

    /**
     * Get the slider
     * 
     * @return array the slider
     */
    public static function getSlider() : object {
        return json_decode(file_get_contents(self::getApiUrl().'/slider'));
    }

    /**
     * Get the galleries
     * 
     * @return array the galleries
     */
    public static function getGalleries() : array {
        return json_decode(file_get_contents(self::getApiUrl().'/galleries'), true);
    }

    /**
     * Get the images
     * 
     * @return array the images
     */
    public static function getImages() : array {
        return json_decode(file_get_contents(self::getApiUrl().'/galleries/'.get_the_ID()), true);
    }

    /**
     * Get the API url
     * 
     * @return string the API url
     */
    private static function getApiUrl() : string {
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/wp-json/kcapi/v1';
    }

    /**
     * Get the file types for the page
     * 
     * @return string the file types
     */
    public static function getFileTypes() : string {
        $fileTypes = [];
        $terms = get_the_terms(get_the_ID(), 'fdwc_tax_file_type');
        foreach($terms as $term) $fileTypes[] = $term->term_id;
        return implode(',', $fileTypes);
    }
}