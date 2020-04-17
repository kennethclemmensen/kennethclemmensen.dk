<?php
/**
 * The Pagination class contains functionality to handle pagination
 */
final class Pagination {

    private $items;
    private $perPage;
    private $urlParameterName;
    private $offset;

    /**
     * Initialize a new instance of the Pagination class with the items and the number of items per page
     * 
     * @param array $items the items
     * @param int $perPage the number of items per page
     */
    public function __construct(array $items, int $perPage) {
        $this->items = $items;
        $this->perPage = $perPage;
        $this->urlParameterName = 'offset';
        $this->offset = (isset($_GET[$this->urlParameterName]) && is_numeric($_GET[$this->urlParameterName])) ? $_GET[$this->urlParameterName] : 0;
    }

    /**
     * Get the items based on the offset and the number of items per page
     * 
     * @return array the items
     */
    public function getItems() : array {
        return array_slice($this->items, $this->offset, $this->perPage);
    }

    /**
     * Get the pagination links with the previous and next texts
     * 
     * @param string $previousText the previous text
     * @param string $nextText the next text
     * @return string the pagination links 
     */
    public function getPaginationLinks(string $previousText, string $nextText) : string {
        $html = '';
        $href = $_SERVER['REDIRECT_URL'].'?'.$this->urlParameterName.'=';
        if($this->offset > 0) {
            $offset = $this->offset - $this->perPage;
            $html = '<a href="'.$href.$offset.'">'.$previousText.'</a>';
        }
        if($this->offset < (count($this->items) - $this->perPage)) {
            $offset = $this->offset + $this->perPage;
            $html .= '<a href="'.$href.$offset.'">'.$nextText.'</a>';
        }
        return $html;
    }
}