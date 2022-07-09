<?php
namespace KC\File;

use KC\Core\Action;
use KC\Core\Filter;
use KC\Core\Modules\IModule;
use KC\Core\PostTypes\FieldName;
use KC\Core\PostTypes\FieldType;
use KC\Core\PostTypes\PostType;
use KC\Core\PostTypes\PostTypeFeature;
use KC\Core\Taxonomies\TaxonomyName;
use KC\Core\Translations\TranslationString;
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
			register_post_type(PostType::File->value, [
				'labels' => [
					'name' => PluginHelper::getTranslatedString(TranslationString::Files),
					'singular_name' => PluginHelper::getTranslatedString(TranslationString::File)
				],
				'public' => true,
				'exclude_from_search' => true,
				'has_archive' => true,
				'supports' => [PostTypeFeature::Title->value]
			]);
			register_taxonomy(TaxonomyName::FileType->value, [PostType::Page->value, PostType::File->value], [
				'labels' => [
					'name' => PluginHelper::getTranslatedString(TranslationString::FileTypes),
					'singular_name' => PluginHelper::getTranslatedString(TranslationString::FileType)
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
				'title' => PluginHelper::getTranslatedString(TranslationString::FileInformations),
				'post_types' => [PostType::File->value],
				'fields' => [
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::Description),
						'id' => FieldName::FileDescription->value,
						'type' => FieldType::TextArea->value
					],
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::File),
						'id' => FieldName::File->value,
						'type' => FieldType::File->value,
						'max_file_uploads' => 1
					],
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::DownloadCounter),
						'id' => FieldName::FileDownloads->value,
						'type' => FieldType::Number->value,
						'std' => 0
					]
				],
				'validation' => [
					'rules' => [
						FieldName::FileDescription->value => [
							'required' => true
						],
						FieldName::FileDownloads->value => [
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