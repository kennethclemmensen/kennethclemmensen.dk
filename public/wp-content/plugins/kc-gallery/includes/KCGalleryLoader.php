<?php
namespace KCGallery\Includes;

/**
 * Class KCGalleryLoader contains methods to load CSS and JavaScript files
 * @package KCGallery\Includes
 */
class KCGalleryLoader {

    /**
     * Load lightbox files
     */
    public function loadStylesAndScripts() : void {
        add_action('wp_enqueue_scripts', function() : void {
            $lightbox = 'lightbox';
            $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/css/lightbox.min.css';
            $localFile = 'public/css/lightbox-2.10.0.min.css';
            $version = filemtime(plugin_dir_path(__DIR__).$localFile);
            $this->addStyleWithLocalFallback($lightbox, $cdnFile, plugin_dir_url(__DIR__).$localFile, [], $version);
            $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.10.0/js/lightbox.min.js';
            $localFile = 'public/js/lightbox-2.10.0.min.js';
            $version = filemtime(plugin_dir_path(__DIR__).$localFile);
            $this->addScriptWithLocalFallback($lightbox, $cdnFile, plugin_dir_url(__DIR__).$localFile, ['jquery'], $version);
        });
    }

    /**
     * Add a script from a CDN with a fallback to a local file
     *
     * @param string $name the name of the script
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     * @param array $deps the dependencies of the script
     * @param int $ver the script version number
     * @param bool $inFooter false if the script should be enqueued in the header
     */
    private function addScriptWithLocalFallback(string $name, string $cdnFile, string $localFile, array $deps = [], int $ver = null, bool $inFooter = true) : void {
        $file = @fopen($cdnFile, 'r');
        if($file === false) {
            $src = $localFile;
        } else {
            $src = $cdnFile;
            $ver = null;
        }
        wp_deregister_script($name);
        wp_enqueue_script($name, $src, $deps, $ver, $inFooter);
    }

    /**
     * Add a stylesheet from a CDN with a fallback to a local file
     *
     * @param string $name the name of the stylesheet
     * @param string $cdnFile the path to the CDN file
     * @param string $localFile the path to the local file
     * @param array $deps the dependencies to the local file
     * @param int $ver the version of the local file
     */
    private function addStyleWithLocalFallback(string $name, string $cdnFile, string $localFile, array $deps = [], int $ver = null) : void {
        $file = @fopen($cdnFile, 'r');
        if($file === false) {
            $src = $localFile;
        } else {
            $src = $cdnFile;
            $ver = null;
        }
        wp_deregister_style($name);
        wp_enqueue_style($name, $src, $deps, $ver);
    }
}