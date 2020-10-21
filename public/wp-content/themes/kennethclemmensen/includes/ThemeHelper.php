<?php
/**
 * The ThemeHelper class contains utility methods to use in the theme
 */
final class ThemeHelper {

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
     * Load the breadcrumb template
     */
    public static function loadBreadcrumbTemplate() : void {
        get_template_part('template-parts/breadcrumb');
    }

    /**
     * Load the slider template
     */
    public static function loadSliderTemplate() : void {
        get_template_part('template-parts/slider');
    }

    /**
     * Get the file types for the page
     * 
     * @return string the file types
     */
    public static function getFileTypes() : string {
        $fileTypes = [];
        $terms = get_the_terms(get_the_ID(), 'kc_tax_file_type');
        foreach($terms as $term) $fileTypes[] = $term->term_id;
        return implode(',', $fileTypes);
    }
}