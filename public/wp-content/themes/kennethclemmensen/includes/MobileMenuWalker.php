<?php
/**
 * The MobileMenuWalker class contains methods to build the structure of the mobile menu
 */
final class MobileMenuWalker extends Walker_Nav_Menu {

    /**
     * Starts the element output
     *
     * @param string $output Used to append additional content (passed by reference)
     * @param WP_Post $item Menu item data object
     * @param int $depth Depth of menu item. Used for padding
     * @param array $args An object of wp_nav_menu() arguments
     * @param int $id Current item ID
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) : void {
        $currentClass = ($item->current) ? 'mobile-nav__link--current' : '';
        $output .= '<li><a href="'.$item->url.'" class="mobile-nav__link '.$currentClass.'">'.$item->title;
        if($this->has_children) $output .= '<span class="mobile-nav__arrow"></span>';
        $output .= '</a>';
    }
}