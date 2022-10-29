<?php
namespace KC\Core\PostTypes;

/**
 * The FieldName enum defines the field names
 */
enum FieldName: string {
	case AltKey = 'kc_page_alt_key';
	case CtrlKey = 'kc_page_ctrl_key';
	case File = 'kc_file_file';
	case FileDescription = 'kc_file_description';
	case FileDownloads = 'kc_file_downloads';
	case ImageGallery = 'kc_image_gallery';
	case Key = 'kc_page_key';
	case ParentPage = 'kc_gallery_parent_page';
	case ShiftKey = 'kc_page_shift_key';
}