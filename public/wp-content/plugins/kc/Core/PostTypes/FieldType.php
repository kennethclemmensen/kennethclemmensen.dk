<?php
namespace KC\Core\PostTypes;

/**
 * The FieldType enum defines the field types
 */
enum FieldType: string {
	case CheckBox = 'checkbox';
	case File = 'file_advanced';
	case Number = 'number';
	case Select = 'select';
	case TextArea = 'textarea';
}