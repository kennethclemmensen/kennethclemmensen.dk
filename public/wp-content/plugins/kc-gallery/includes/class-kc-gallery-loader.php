<?php
namespace KCGallery\Includes;

/**
 * Class KCGalleryLoader contains a method to load CSS and JavaScript files
 * @package KCGallery\Includes
 */
class KCGalleryLoader {

    /**
     * Load lightbox files
     */
    public function loadStylesAndScripts() {
        add_action('wp_enqueue_scripts', function() {
            $lightbox = 'lightbox';
            wp_enqueue_style($lightbox, '//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.9.0/css/lightbox.css');
            wp_enqueue_script($lightbox, '//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.9.0/js/lightbox.min.js', ['jquery'], false, true);
        });
    }
}