<?php
/**
 * The MobileMenuWalker class contains methods to build the structure of the mobile menu
 */
final class MobileMenuWalker extends Walker_Nav_Menu {

    /**
     * Starts the element output
     *
     * @param string $output used to append additional content
     * @param WP_Post $item menu item data object
     * @param int $depth depth of menu item
     * @param array $args an object of wp_nav_menu() arguments
     * @param int $id the current item ID
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) : void {
        $currentClass = ($item->current) ? 'mobile-menu__link--current' : '';
        $output .= '<li><a href="'.$item->url.'" class="mobile-menu__link '.$currentClass.'">'.$item->title;
        if($this->has_children) $output .= '<span class="mobile-menu__arrow"></span>';
        $output .= '</a>';
    }
}