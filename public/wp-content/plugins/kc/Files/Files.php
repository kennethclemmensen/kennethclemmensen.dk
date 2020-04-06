<?php
namespace KC\Files;

use KC\Core\Constant;
use KC\Core\CustomPostType;
use KC\Utils\PluginHelper;

/**
 * The Files class contains functionality to handle files
 */
class Files {

    private $fieldDescription;
    private $fieldFile;

    /**
     * Initialize a new instance of the Files class
     */
    public function __construct() {
        $prefix = 'fdwc_field_';
        $this->fieldDescription = $prefix.'description';
        $this->fieldFile = $prefix.'file';
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
            register_taxonomy(PluginHelper::getFileTypeTaxonomyName(), [Constant::PAGE, CustomPostType::FILE], [
                'labels' => [
                    'name' => 'File types',
                    'singular_name' => 'File type'
                ],
                'show_admin_column' => true,
                'hierarchical' => true
            ]);
            register_taxonomy_for_object_type(PluginHelper::getFileTypeTaxonomyName(), Constant::PAGE);
            register_taxonomy_for_object_type(PluginHelper::getFileTypeTaxonomyName(), CustomPostType::FILE);
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
                        'id' => Constant::FILE_DOWNLOAD_COUNTER_FIELD_ID,
                        'type' => 'number',
                        'std' => 0
                    ]
                ],
                'validation' => [
                    'rules' => [
                        $this->fieldDescription => [
                            'required' => true
                        ],
                        Constant::FILE_DOWNLOAD_COUNTER_FIELD_ID => [
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
    public function getFileUrl(int $fileID) : string {
        $attachmentID = get_post_meta($fileID, $this->fieldFile, true);
        return esc_url(wp_get_attachment_url($attachmentID));
    }

    /**
     * Get the file name
     *
     * @param int $fileID the id of the file
     * @return string the file name
     */
    public function getFileName(int $fileID) : string {
        $attachmentID = get_post_meta($fileID, $this->fieldFile, true);
        return basename(get_attached_file($attachmentID));
    }

    /**
     * Get the file description
     *
     * @param int $fileID the id of the file
     * @return string the file description
     */
    public function getFileDescription(int $fileID) : string {
        return get_post_meta($fileID, $this->fieldDescription, true);
    }

    /**
     * Update the download counter for a file
     *
     * @param int $fileID the id of the file
     */
    public function updateFileDownloadCounter(int $fileID) : void {
        $downloads = PluginHelper::getFileDownloads($fileID);
        $downloads++;
        update_post_meta($fileID, Constant::FILE_DOWNLOAD_COUNTER_FIELD_ID, $downloads);
    }
}