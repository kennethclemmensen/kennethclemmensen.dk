<?php
/**
 * The MobileMenuWalker class contains methods to build the structure of the mobile menu
 */
class MobileMenuWalker extends Walker_Nav_Menu {

    /**
     * Starts the element output
     *
     * @param string $output Used to append additional content (passed by reference)
     * @param WP_Post $item Menu item data object
     * @param int $depth Depth of menu item. Used for padding
     * @param stdClass $args An object of wp_nav_menu() arguments
     * @param int $id Current item ID
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $current_class = ($item->current) ? 'mobile-nav__link--current' : '';
        $link = get_permalink($item->object_id);
        $output .= '<li><a href="'.$link.'" class="mobile-nav__link '.$current_class.'">'.$item->title;
        if($this->hasSubPages($item->object_id)) $output .= '<span class="mobile-nav__arrow"></span>';
        $output .= '</a>';
    }

    /**
     * Check if a page with the specified ID has sub pages
     *
     * @param int $pageID the id of the page
     * @return bool true if the page has sub pages. False otherwise
     */
    private function hasSubPages(int $pageID) : bool {
        $children = get_pages(['child_of' => $pageID]);
        return count($children) !== 0;
    }
}