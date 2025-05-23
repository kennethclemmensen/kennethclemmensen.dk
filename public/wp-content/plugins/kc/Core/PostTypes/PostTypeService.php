<?php
namespace KC\Core\PostTypes;

/**
 * The PostTypeService class contains post type methods.
 * The class cannot be inherited.
 */
final class PostTypeService {

	/**
	 * Get a field value from a post
	 * 
	 * @param FieldName $fieldName the name of the field
	 * @param int $postId the id of the post
	 * @return string the field value
	 */
	public function getFieldValue(FieldName $fieldName, int $postId) : string | int {
		return get_post_meta($postId, $fieldName->value, true);
	}

	/**
	 * Set a field value on a post
	 * 
	 * @param mixed $value the value
	 * @param FieldName $fieldName the name of the field
	 * @param int $postId the id of the post
	 */
	public function setFieldValue(string | int $value, FieldName $fieldName, int $postId) : void {
		update_post_meta($postId, $fieldName->value, $value);
	}
}