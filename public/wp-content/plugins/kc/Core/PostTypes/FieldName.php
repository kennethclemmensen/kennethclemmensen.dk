<?php
namespace KC\Core\PostTypes;

/**
 * The FieldName enum defines the field names
 */
enum FieldName: string {
	case File = 'field_file';
	case FileDescription = 'field_description';
	case FileDownloads = 'field_download_counter';
	case ImageGallery = 'photo_gallery';
	case ParentPage = 'parent_page';
}