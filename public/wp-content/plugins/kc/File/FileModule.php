<?php
namespace KC\File;

use KC\Core\Action;
use KC\Core\Constant;
use KC\Core\Filter;
use KC\Core\IModule;
use KC\Core\PostType;
use KC\Security\Security;
use KC\Utils\PluginHelper;
use \WP_Query;

/**
 * The FileModule class contains functionality to handle files
 */
class FileModule implements IModule {

    private string $fieldDescription;
    private string $fieldFile;
    private string $fieldFileDownloadCounter;
    private string $fileTypeTaxonomyName;

    /**
     * Initialize a new instance of the FileModule class
     */
    public function __construct() {
        $prefix = 'field_';
        $this->fieldDescription = $prefix.'description';
        $this->fieldFile = $prefix.'file';
        $this->fieldFileDownloadCounter = $prefix.'download_counter';
        $this->fileTypeTaxonomyName = 'kc_tax_file_type';
    }

    /**
     * Setup the file module
     */
    public function setupModule() : void {
        $this->registerPostTypesAndTaxonomies();
        $this->addMetaBoxes();
        $this->addMimeTypes();        
    }

    /**
     * Register post types and taxonomies
     */
    private function registerPostTypesAndTaxonomies() : void {
        add_action(Action::INIT, function() : void {
            register_post_type(PostType::FILE, [
                'labels' => [
                    'name' => 'Files',
                    'singular_name' => 'File'
                ],
                'public' => true,
                'exclude_from_search' => true,
                'has_archive' => true,
                'supports' => [Constant::TITLE]
            ]);
            register_taxonomy($this->fileTypeTaxonomyName, [PostType::PAGE, PostType::FILE], [
                'labels' => [
                    'name' => 'File types',
                    'singular_name' => 'File type'
                ],
                'show_admin_column' => true,
                'hierarchical' => true
            ]);
        });
    }

    /**
     * Add meta boxes to the custom post type file
     */
    private function addMetaBoxes() : void {
        add_filter(Filter::META_BOXES, function(array $metaBoxes) : array {
            $metaBoxes[] = [
                'id' => 'file_informations',
                'title' => 'File informations',
                'post_types' => [PostType::FILE],
                'fields' => [
                    [
                        'name' => PluginHelper::getTranslatedString('Description'),
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
     * Add mime types
     */
    private function addMimeTypes() : void {
        $priority = 1;
        add_filter(Filter::MIMES, function(array $mimeTypes) : array {
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
        $attachmentID = PluginHelper::getFieldValue($this->fieldFile, $fileID);
        return Security::escapeUrl(wp_get_attachment_url($attachmentID));
    }

    /**
     * Get the file name
     *
     * @param int $fileID the id of the file
     * @return string the file name
     */
    private function getFileName(int $fileID) : string {
        $attachmentID = PluginHelper::getFieldValue($this->fieldFile, $fileID);
        return basename(get_attached_file($attachmentID));
    }

    /**
     * Get the file description
     *
     * @param int $fileID the id of the file
     * @return string the file description
     */
    private function getFileDescription(int $fileID) : string {
        return PluginHelper::getFieldValue($this->fieldDescription, $fileID);
    }

    /**
     * Get the number of file downloads for a file
     *
     * @param int $fileID the id of the file
     * @return int the number of file downloads
     */
    private function getFileDownloads(int $fileID) : int {
        return PluginHelper::getFieldValue($this->fieldFileDownloadCounter, $fileID);
    }

    /**
     * Update the download counter for a file
     *
     * @param int $fileID the id of the file
     */
    public function updateFileDownloadCounter(int $fileID) : void {
        $downloads = $this->getFileDownloads($fileID);
        $downloads++;
        PluginHelper::setFieldValue($downloads, $this->fieldFileDownloadCounter, $fileID);
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
            'post_type' => PostType::FILE,
            'posts_per_page' => -1,
            'order' => Constant::ASC,
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