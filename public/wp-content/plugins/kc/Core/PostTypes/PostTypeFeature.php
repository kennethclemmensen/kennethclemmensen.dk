<?php
namespace KC\Core\PostTypes;

/**
 * The PostTypeFeature enum defines the post type features
 */
enum PostTypeFeature: string {
	case Editor = 'editor';
	case Thumbnail = 'thumbnail';
	case Title = 'title';
}