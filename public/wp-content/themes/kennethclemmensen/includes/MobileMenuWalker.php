<?php
class MobileMenuWalker extends Walker_Nav_Menu {

    public function __construct() {

    }

    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $current_class = ($item->current) ? 'mobile-nav__link--current' : '';
        $link = get_permalink($item->object_id);
        $output .= '<li><a href="'.$link.'" class="mobile-nav__link '.$current_class.'">'.$item->title;
        if($this->hasChildren($item->object_id)) $output .= '<span class="mobile-nav__arrow"></span>';
        $output .= '</a>';
    }

    private function hasChildren(int $pageID) : bool {
        $children = get_pages(['child_of' => $pageID]);
        return count($children) !== 0;
    }
}