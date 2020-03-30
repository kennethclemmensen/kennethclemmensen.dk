<?php
namespace KC\Files;

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

    public const FDWC_FILE = 'fdwc_file';

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
        $this->init();
        $this->adminMenu();
        $this->addMetaBoxes();
        $this->addShortcode();
        $this->uploadMimes();
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
        add_shortcode('fdwc_files', function(array $attributes) : string {
            $html = '<div class="fdwc">';
            $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
            $args = [
                'post_type' => self::FDWC_FILE,
                'posts_per_page' => 7,
                'order' => 'ASC',
                'tax_query' => [
                    [
                        'taxonomy' => $this->taxFileType,
                        'terms' => $attributes['file_type_id']
                    ]
                ],
                'paged' => $paged
            ];
            $wpQuery = new WP_Query($args);
            while($wpQuery->have_posts()) {
                $wpQuery->the_post();
                $id = get_the_ID();
                $html .= '<div class="fdwc__section">';
                $html .= '<a href="'.$this->getFileUrl($id).'" class="fdwc__link" rel="nofollow" data-file-id="'.$id.'" download>'.$this->getFileName($id).'</a>';
                $html .= '<p>'.$this->getFileDescription($id).'</p>';
                $html .= '<p>Antal downloads: <span class="fdwc__downloads">'.$this->getFileDownloads($id).'</span></p>';
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
     * Get the file url
     *
     * @param int $fileID the id of the file
     * @return string the file url
     */
    private function getFileUrl(int $fileID) : string {
        $attachmentID = get_post_meta($fileID, $this->fieldFile, true);
        return esc_url(wp_get_attachment_url($attachmentID));
    }

    /**
     * Get the file name
     *
     * @param int $fileID the id of the file
     * @return string the file name
     */
    private function getFileName(int $fileID) : string {
        $attachmentID = get_post_meta($fileID, $this->fieldFile, true);
        return basename(get_attached_file($attachmentID));
    }

    /**
     * Get the file description
     *
     * @param int $fileID the id of the file
     * @return string the file description
     */
    private function getFileDescription(int $fileID) : string {
        return get_post_meta($fileID, $this->fieldDescription, true);
    }

    /**
     * Get the number of file downloads
     *
     * @param int $fileID the id of the file
     * @return int the number of file downloads
     */
    private function getFileDownloads(int $fileID) : int {
        return get_post_meta($fileID, $this->fieldDownloadCounter, true);
    }

    /**
     * Get the file type taxonomy
     *
     * @return string the file type taxonomy
     */
    public function getFileTypeTaxonomy() : string {
        return $this->taxFileType;
    }
}