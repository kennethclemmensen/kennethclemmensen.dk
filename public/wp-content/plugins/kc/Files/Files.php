<?php
namespace KC\Files;

use KC\Core\Constant;
use KC\Core\CustomPostType;
use KC\Core\IModule;
use KC\Security\Security;
use \WP_Query;

/**
 * The Files class contains functionality to handle files
 */
class Files implements IModule {

    private $fieldDescription;
    private $fieldFile;
    private $fieldFileDownloadCounter;
    private $fileTypeTaxonomyName;

    /**
     * Initialize a new instance of the Files class
     */
    public function __construct() {
        $prefix = 'fdwc_field_';
        $this->fieldDescription = $prefix.'description';
        $this->fieldFile = $prefix.'file';
        $this->fieldFileDownloadCounter = $prefix.'download_counter';
        $this->fileTypeTaxonomyName = 'fdwc_tax_file_type';
        $this->init();
        $this->addMetaBoxes();
        $this->uploadMimes();
    }

    /**
     * Use the init action to register the file custom post type and the file types taxonomy
     */
    private function init() : void {
        add_action('init', function() : void {
            register_post_type(CustomPostType::FILE, [
                'labels' => [
                    'name' => 'Files',
                    'singular_name' => 'File'
                ],
                'public' => true,
                'exclude_from_search' => true,
                'has_archive' => true,
                'supports' => ['title']
            ]);
            register_taxonomy($this->fileTypeTaxonomyName, [Constant::PAGE, CustomPostType::FILE], [
                'labels' => [
                    'name' => 'File types',
                    'singular_name' => 'File type'
                ],
                'show_admin_column' => true,
                'hierarchical' => true
            ]);
            register_taxonomy_for_object_type($this->fileTypeTaxonomyName, Constant::PAGE);
            register_taxonomy_for_object_type($this->fileTypeTaxonomyName, CustomPostType::FILE);
        });
    }

    /**
     * Use the rwmb_meta_boxes filter to add meta boxes to the file custom post type
     */
    private function addMetaBoxes() : void {
        add_filter('rwmb_meta_boxes', function(array $metaBoxes) : array {
            $metaBoxes[] = [
                'id' => 'file_informations',
                'title' => 'File informations',
                'post_types' => [CustomPostType::FILE],
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
                        'id' => $this->fieldFileDownloadCounter,
                        'type' => 'number',
                        'std' => 0
                    ]
                ],
                'validation' => [
                    'rules' => [
                        $this->fieldDescription => [
                            'required' => true
                        ],
                        $this->fieldFileDownloadCounter => [
                            'required' => true,
                            'min' => 0
                        ]
                    ]
                ]
            ];
            return $metaBoxes;
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
        return Security::escapeUrl(wp_get_attachment_url($attachmentID));
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
     * Get the number of file downloads for a file
     *
     * @param int $fileID the id of the file
     * @return int the number of file downloads
     */
    private function getFileDownloads(int $fileID) : int {
        return get_post_meta($fileID, $this->fieldFileDownloadCounter, true);
    }

    /**
     * Update the download counter for a file
     *
     * @param int $fileID the id of the file
     */
    public function updateFileDownloadCounter(int $fileID) : void {
        $downloads = $this->getFileDownloads($fileID);
        $downloads++;
        update_post_meta($fileID, $this->fieldFileDownloadCounter, $downloads);
    }

    /**
     * Get the files based on the file types
     * 
     * @param array $fileTypes the file types
     * @return array the files
     */
    public function getFiles(array $fileTypes) : array {
        $files = [];
        $args = [
            'post_type' => CustomPostType::FILE,
            'posts_per_page' => -1,
            'order' => 'ASC',
            'tax_query' => [
                [
                    'taxonomy' => $this->fileTypeTaxonomyName,
                    'terms' => $fileTypes
                ]
            ]
        ];
        $wpQuery = new WP_Query($args);
        while($wpQuery->have_posts()) {
            $wpQuery->the_post();
            $id = get_the_ID();
            $files[] = [
                'id' => $id,
                'fileName' => $this->getFileName($id), 
                'url' => $this->getFileUrl($id),
                'description' => $this->getFileDescription($id),
                'downloads' => $this->getFileDownloads($id)
            ];
        }
        return $files;
    }
}