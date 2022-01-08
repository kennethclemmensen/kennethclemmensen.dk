<?php
namespace KC\File;

use KC\Core\Action;
use KC\Core\Constant;
use KC\Core\FieldName;
use KC\Core\Filter;
use KC\Core\Modules\IModule;
use KC\Core\PostType;
use KC\Core\TaxonomyName;
use KC\Core\TranslationString;
use KC\Utils\PluginHelper;

/**
 * The FileModule class contains functionality to handle files
 */
class FileModule implements IModule {

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
			register_taxonomy(TaxonomyName::FILE_TYPE, [PostType::PAGE, PostType::FILE], [
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
						'id' => FieldName::FILE_DESCRIPTION,
						'type' => 'textarea'
					],
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::FILE),
						'id' => FieldName::FILE,
						'type' => 'file_advanced',
						'max_file_uploads' => 1
					],
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::DOWNLOAD_COUNTER),
						'id' => FieldName::FILE_DOWNLOADS,
						'type' => 'number',
						'std' => 0
					]
				],
				'validation' => [
					'rules' => [
						FieldName::FILE_DESCRIPTION => [
							'required' => true
						],
						FieldName::FILE_DOWNLOADS => [
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