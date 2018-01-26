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
    public function loadStylesAndScripts() : void {
        add_action('wp_enqueue_scripts', function() : void {
            $lightbox = 'lightbox';
            $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.1/css/lightbox.min.css';
            $localFile = plugin_dir_url(__DIR__).'assets/css/lightbox-2.10.0.min.css';
            $this->addStyleWithLocalFallback($lightbox, $cdnFile, $localFile);
            $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.1/js/lightbox.min.js';
            $localFile = plugin_dir_url(__DIR__).'assets/js/lightbox-2.10.0.min.js';
            $this->addScriptWithLocalFallback($lightbox, $cdnFile, $localFile, ['jquery'], false, true);
        });
    }

    /**
     * Add a script from a CDN with a fallback to a local file
     *
     * @param string $handle the name of the script
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     * @param array $deps the dependencies of the script
     * @param bool $ver the script version number
     * @param bool $inFooter false if the script should be enqueued in the header
     */
    private function addScriptWithLocalFallback(string $handle, string $cdnFile, string $localFile, array $deps = [], bool $ver = false, bool $inFooter = true) : void {
        $file = @fopen($cdnFile, 'r');
        $src = ($file === false) ? $localFile : $cdnFile;
        wp_deregister_script($handle);
        wp_enqueue_script($handle, $src, $deps, $ver, $inFooter);
    }

    /**
     * Add a stylesheet from a CDN with a fallback to a local file
     *
     * @param string $handle the name of the stylesheet
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     */
    private function addStyleWithLocalFallback(string $handle, string $cdnFile, string $localFile) : void {
        $file = @fopen($cdnFile, 'r');
        $src = ($file === false) ? $localFile : $cdnFile;
        wp_enqueue_style($handle, $src);
    }
}