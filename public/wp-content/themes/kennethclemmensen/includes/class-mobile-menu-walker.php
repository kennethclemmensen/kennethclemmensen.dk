<?php
class Mobile_Menu_Walker extends Walker_Nav_Menu {

    public function __construct() {

    }

    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $current_class = ($item->current) ? 'mobile-nav__link--current' : '';
        $link = get_permalink($item->object_id);
        $output .= '<li><a href="'.$link.'" class="mobile-nav__link '.$current_class.'">'.$item->title;
        if($this->has_children($item->object_id)) $output .= '<span class="mobile-nav__arrow"></span>';
        $output .= '</a></li>';
    }

    private function has_children(int $page_id) : bool {
        $children = get_pages(['child_of' => $page_id]);
        return count($children) !== 0;
    }
}