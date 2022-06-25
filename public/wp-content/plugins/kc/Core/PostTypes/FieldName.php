<?php
namespace KC\Core\PostTypes;

/**
 * The FieldName enum defines the field names
 */
enum FieldName: string {
	case AltKey = 'field_alt_key';
	case CtrlKey = 'field_ctrl_key';
	case File = 'field_file';
	case FileDescription = 'field_description';
	case FileDownloads = 'field_download_counter';
	case ImageGallery = 'photo_gallery';
	case Key = 'field_key';
	case ParentPage = 'parent_page';
	case ShiftKey = 'field_shift_key';
}