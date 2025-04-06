<?php
namespace KC\Shortcut;

use KC\Core\Filter;
use KC\Core\PluginService;
use KC\Core\Modules\IModule;
use KC\Core\PostTypes\FieldName;
use KC\Core\PostTypes\FieldType;
use KC\Core\PostTypes\PostType;
use KC\Core\Translations\TranslationService;
use KC\Core\Translations\TranslationString;

/**
 * The ShortcutModule class contains functionality to handle shortcuts.
 * The class cannot be inherited.
 */
final class ShortcutModule implements IModule {

	/**
	 * Setup the shortcut module
	 */
	public function setupModule() : void {
		$this->addMetaBoxes();
	}

	/**
	 * Add meta boxes to the page post type
	 */
	private function addMetaBoxes() : void {
		$pluginService = new PluginService();
		$pluginService->addFilter(Filter::META_BOXES, function(array $metaBoxes) : array {
			$translationService = new TranslationService();
			$metaBoxes[] = [
				'id' => 'shortcut_informations',
				'title' => $translationService->getTranslatedString(TranslationString::Shortcut),
				'post_types' => [PostType::Page->value],
				'fields' => [
					[
						'name' => $translationService->getTranslatedString(TranslationString::AltKey),
						'id' => FieldName::AltKey->value,
						'type' => FieldType::CheckBox->value
					],
					[
						'name' => $translationService->getTranslatedString(TranslationString::CtrlKey),
						'id' => FieldName::CtrlKey->value,
						'type' => FieldType::CheckBox->value
					],
					[
						'name' => $translationService->getTranslatedString(TranslationString::ShiftKey),
						'id' => FieldName::ShiftKey->value,
						'type' => FieldType::CheckBox->value
					],
					[
						'name' => $translationService->getTranslatedString(TranslationString::Key),
						'id' => FieldName::Key->value,
						'type' => FieldType::Select->value,
						'options' => [
							'A' => 'A',
							'B' => 'B',
							'C' => 'C',
							'D' => 'D',
							'E' => 'E',
							'F' => 'F',
							'G' => 'G',
							'H' => 'H',
							'I' => 'I',
							'J' => 'J',
							'K' => 'K',
							'L' => 'L',
							'M' => 'M',
							'N' => 'N',
							'O' => 'O',
							'P' => 'P',
							'Q' => 'Q',
							'R' => 'R',
							'S' => 'S',
							'T' => 'T',
							'U' => 'U',
							'V' => 'V',
							'W' => 'W',
							'X' => 'X',
							'Y' => 'Y',
							'Z' => 'Z'
						]
					]
				]
			];
			return $metaBoxes;
		});
	}
}