<?php
namespace FDWC\Includes;

use \WP_Query;

/**
 * Class FDWC contains methods to handle the functionality of the plugin
 * @package FDWC\Includes
 */
class FDWC {

    private $fieldDescription;
    private $fieldFile;
    private $fieldDownloadCounter;
    private $fieldFileType;
    private $taxFileType;

    private const FDWC_FILE = 'fdwc_file';

    /**
     * FDWC constructor
     */
    public function __construct() {
        $prefix = 'fdwc_field_';
        $this->fieldDescription = $prefix.'description';
        $this->fieldFile = $prefix.'file';
        $this->fieldDownloadCounter = $prefix.'download_counter';
        $this->fieldFileType = $prefix.'file_type';
        $this->taxFileType = 'fdwc_tax_file_type';
    }

    /**
     * Activate the plugin
     *
     * @param string $mainPluginFile the path to the main plugin file
     */
    public function activate(string $mainPluginFile) : void {
        register_activation_hook($mainPluginFile, function() : void {
            if(!class_exists('RW_Meta_Box')) die('Meta Box is not activated');
        });
    }

    /**
     * Execute the plugin
     */
    public function execute() : void {
        $this->loadDepedencies();
        $loader = new FDWCLoader();
        $loader->loadScripts();
        $this->init();
        $this->adminMenu();
        $this->addMetaBoxes();
        $this->addShortcode();
        $this->uploadMimes();
        $this->wpAjax();
    }

    /**
     * Load the dependencies files
     */
    private function loadDepedencies() : void {
        require_once 'FDWCLoader.php';
    }

    /**
     * Use the init action to register the fdwc file custom post type and the file types taxonomy
     */
    private function init() : void {
        add_action('init', function() : void {
            register_post_type(self::FDWC_FILE, [
                'labels' => [
                    'name' => 'Files',
                    'singular_name' => 'File'
                ],
                'public' => true,
                'exclude_from_search' => true,
                'has_archive' => true,
                'supports' => ['title']
            ]);
            register_taxonomy($this->taxFileType, self::FDWC_FILE, [
                'labels' => [
                    'name' => 'File types',
                    'singular_name' => 'File type'
                ],
                'show_admin_column' => true,
                'hierarchical' => true
            ]);
            register_taxonomy_for_object_type($this->taxFileType, self::FDWC_FILE);
        });
    }

    /**
     * Use the admin_menu action to remove the File types meta box
     */
    private function adminMenu() : void {
        add_action('admin_menu', function() : void {
            remove_meta_box('tagsdiv-'.$this->taxFileType, self::FDWC_FILE, 'normal');
        });
    }

    /**
     * Use the rwmb_meta_boxes filter to add meta boxes to the fdwc file custom post type
     */
    private function addMetaBoxes() : void {
        add_filter('rwmb_meta_boxes', function(array $metaBoxes) : array {
            $metaBoxes[] = [
                'id' => 'file_informations',
                'title' => 'File informations',
                'post_types' => [self::FDWC_FILE],
                'fields' => [
                    [
                        'name' => 'Description',
                        'id' => $this->fieldDescription,
                        'type' => 'textarea'
                    ],
                    [
                        'name' => 'File',
                        'id' => $this->fieldFile,
                        'type' => 'file_advanced',
                        'max_file_uploads' => 1
                    ],
                    [
                        'name' => 'Download counter',
                        'id' => $this->fieldDownloadCounter,
                        'type' => 'number',
                        'std' => 0
                    ],
                    [
                        'name' => 'File type',
                        'id' => $this->fieldFileType,
                        'type' => 'taxonomy',
                        'taxonomy' => $this->taxFileType,
                        'field_type' => 'select'
                    ]
                ],
                'validation' => [
                    'rules' => [
                        $this->fieldDescription => [
                            'required' => true
                        ],
                        $this->fieldDownloadCounter => [
                            'required' => true,
                            'min' => 0
                        ],
                        $this->fieldFileType => [
                            'required' => true
                        ]
                    ]
                ]
            ];
            return $metaBoxes;
        });
    }

    /**
     * Add the fdwc_files shortcode to show a list of files
     */
    private function addShortcode() : void {
        add_shortcode('fdwc_files', function(array $atts) : string {
            $html = '<div class="fdwc">';
            $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
            $args = [
                'post_type' => self::FDWC_FILE,
                'posts_per_page' => 7,
                'order' => 'ASC',
                'tax_query' => [
                    [
                        'taxonomy' => $this->taxFileType,
                        'terms' => $atts['file_type_id']
                    ]
                ],
                'paged' => $paged
            ];
            $wpQuery = new WP_Query($args);
            while($wpQuery->have_posts()) {
                $wpQuery->the_post();
                $id = get_the_ID();
                $html .= '<div class="fdwc__section">';
                $html .= '<a href="'.$this->getFileUrl($id).'" class="fdwc__link" rel="nofollow" data-post-id="'.$id.'" download>'.$this->getFileName($id).'</a>';
                $html .= '<p>'.$this->getFileDescription($id).'</p>';
                $html .= '<p>Antal downloads: '.$this->getFileDownloads($id).'</p>';
                $html .= '</div>';
            }
            $big = 999999999; // need an unlikely integer
            $replace = '%#%';
            $html .= '<div class="fdwc__pagination">';
            $html .= paginate_links([
                'base' => str_replace($big, $replace, esc_url(get_pagenum_link($big))),
                'format' => '?paged='.$replace,
                'current' => max(1, $paged),
                'total' => $wpQuery->max_num_pages,
                'prev_text' => 'Forrige',
                'next_text' => 'Næste'
            ]);
            $html .= '</div></div>';
            return $html;
        });
    }

    /**
     * Use the upload_mimes filter to allow java files upload
     */
    private function uploadMimes() : void {
        $priority = 1;
        add_filter('upload_mimes', function(array $mimeTypes) : array {
            $mimeTypes['java'] = 'application/java';
            return $mimeTypes;
        }, $priority);
    }

    /**
     * Use the wp_ajax wp_ajax_nopriv actions to update the counter
     */
    private function wpAjax() : void {
        $action = 'fdwc_download';
        add_action('wp_ajax_nopriv_'.$action, function() {
            $this->updateCounter();
            wp_die();
        });
        add_action('wp_ajax_'.$action, function() {
            $this->updateCounter();
            wp_die();
        });
    }

    /**
     * Update the counter for a file
     */
    private function updateCounter() : void {
        $id = $_POST['post_id'];
        $downloads = $this->getFileDownloads($id);
        $downloads++;
        update_post_meta($id, $this->fieldDownloadCounter, $downloads);
    }

    /**
     * Get the file url
     *
     * @param int $fileID the id of the file
     * @return string the file url
     */
    private function getFileUrl(int $fileID) : string {
        $file = rwmb_meta($this->fieldFile, [], $fileID);
        $file = array_shift($file);
        return esc_url($file['url']);
    }

    /**
     * Get the file path
     *
     * @param int $fileID the id of the file
     * @return string the file path
     */
    private function getFilePath(int $fileID) : string {
        $file = rwmb_meta($this->fieldFile, [], $fileID);
        $file = array_shift($file);
        return $file['path'];
    }

    /**
     * Get the file name
     *
     * @param int $fileID the id of the file
     * @return string the file name
     */
    private function getFileName(int $fileID) : string {
        return basename($this->getFilePath($fileID));
    }

    /**
     * Get the file description
     *
     * @param int $fileID the id of the file
     * @return string the file description
     */
    private function getFileDescription(int $fileID) : string {
        return rwmb_meta($this->fieldDescription, [], $fileID);
    }

    /**
     * Get the number of file downloads
     *
     * @param int $fileID the id of the file
     * @return int the number of file downloads
     */
    private function getFileDownloads(int $fileID) : int {
        return rwmb_meta($this->fieldDownloadCounter, [], $fileID);
    }
}