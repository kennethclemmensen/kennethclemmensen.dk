<?php
namespace KC\Shortcut;

use KC\Core\Filter;
use KC\Core\Modules\IModule;
use KC\Core\PostTypes\FieldName;
use KC\Core\PostTypes\PostType;
use KC\Core\Translations\TranslationString;
use KC\Utils\PluginHelper;

/**
 * The ShortcutModule class contains functionality to handle shortcuts
 */
class ShortcutModule implements IModule {

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
		add_filter(Filter::META_BOXES, function(array $metaBoxes) : array {
			$metaBoxes[] = [
				'id' => 'shortcut_informations',
				'title' => PluginHelper::getTranslatedString(TranslationString::Shortcut),
				'post_types' => [PostType::Page->value],
				'fields' => [
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::AltKey),
						'id' => FieldName::AltKey->value,
						'type' => 'checkbox'
					],
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::CtrlKey),
						'id' => FieldName::CtrlKey->value,
						'type' => 'checkbox'
					],
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::ShiftKey),
						'id' => FieldName::ShiftKey->value,
						'type' => 'checkbox'
					],
					[
						'name' => PluginHelper::getTranslatedString(TranslationString::Key),
						'id' => FieldName::Key->value,
						'type' => 'select',
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