<?php
namespace KC\File;

use KC\Core\Action;
use KC\Core\Filter;
use KC\Core\PluginService;
use KC\Core\Modules\IModule;
use KC\Core\PostTypes\FieldName;
use KC\Core\PostTypes\FieldType;
use KC\Core\PostTypes\PostType;
use KC\Core\PostTypes\PostTypeFeature;
use KC\Core\Taxonomies\TaxonomyName;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;

/**
 * The FileModule class contains functionality to handle files.
 * The class cannot be inherited.
 */
final class FileModule implements IModule {

	private readonly TranslationService $translationService;
	private readonly PluginService $pluginService;

	/**
	 * FileModule constructor
	 */
	public function __construct() {
		$this->translationService = new TranslationService();
		$this->pluginService = new PluginService();
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
		$this->pluginService->addAction(Action::INIT, function() : void {
			register_post_type(PostType::File->value, [
				'labels' => [
					'name' => $this->translationService->getTranslatedString(TranslationString::Files),
					'singular_name' => $this->translationService->getTranslatedString(TranslationString::File)
				],
				'public' => true,
				'exclude_from_search' => true,
				'has_archive' => true,
				'supports' => [PostTypeFeature::Title->value]
			]);
			register_taxonomy(TaxonomyName::FileType->value, [PostType::Page->value, PostType::File->value], [
				'labels' => [
					'name' => $this->translationService->getTranslatedString(TranslationString::FileTypes),
					'singular_name' => $this->translationService->getTranslatedString(TranslationString::FileType)
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
		$this->pluginService->addFilter(Filter::META_BOXES, function(array $metaBoxes) : array {
			$metaBoxes[] = [
				'id' => 'file_informations',
				'title' => $this->translationService->getTranslatedString(TranslationString::FileInformations),
				'post_types' => [PostType::File->value],
				'fields' => [
					[
						'name' => $this->translationService->getTranslatedString(TranslationString::Description),
						'id' => FieldName::FileDescription->value,
						'type' => FieldType::TextArea->value
					],
					[
						'name' => $this->translationService->getTranslatedString(TranslationString::File),
						'id' => FieldName::File->value,
						'type' => FieldType::File->value,
						'max_file_uploads' => 1
					],
					[
						'name' => $this->translationService->getTranslatedString(TranslationString::DownloadCounter),
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
		$this->pluginService->addFilter(Filter::MIMES, function(array $mimeTypes) : array {
			$mimeTypes['java'] = 'application/java';
			return $mimeTypes;
		}, $priority);
	}
}