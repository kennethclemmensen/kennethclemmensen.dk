<?php
namespace KC\File;

use KC\Core\Action;
use KC\Core\Constant;
use KC\Core\Filter;
use KC\Core\IModule;
use KC\Core\PostType;
use KC\Core\TranslationString;
use KC\Utils\PluginHelper;

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
                    'name' => PluginHelper::getTranslatedString(TranslationString::FILES),
                    'singular_name' => PluginHelper::getTranslatedString(TranslationString::FILE)
                ],
                'public' => true,
                'exclude_from_search' => true,
                'has_archive' => true,
                'supports' => [Constant::TITLE]
            ]);
            register_taxonomy($this->fileTypeTaxonomyName, [PostType::PAGE, PostType::FILE], [
                'labels' => [
                    'name' => PluginHelper::getTranslatedString(TranslationString::FILE_TYPES),
                    'singular_name' => PluginHelper::getTranslatedString(TranslationString::FILE_TYPE)
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
                'title' => PluginHelper::getTranslatedString(TranslationString::FILE_INFORMATIONS),
                'post_types' => [PostType::FILE],
                'fields' => [
                    [
                        'name' => PluginHelper::getTranslatedString(TranslationString::DESCRIPTION),
                        'id' => $this->fieldDescription,
                        'type' => 'textarea'
                    ],
                    [
                        'name' => PluginHelper::getTranslatedString(TranslationString::FILE),
                        'id' => $this->fieldFile,
                        'type' => 'file_advanced',
                        'max_file_uploads' => 1
                    ],
                    [
                        'name' => PluginHelper::getTranslatedString(TranslationString::DOWNLOAD_COUNTER),
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
}