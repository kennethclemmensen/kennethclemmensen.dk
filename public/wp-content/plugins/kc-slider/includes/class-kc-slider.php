<?php
class KC_Slider {

	private $_field_slide_image;

	const SLIDES = 'slides';

	public function __construct() {
		$prefix = 'slider_';
		$this->_field_slide_image = $prefix.'slide_image';
	}

	public function activate(string $main_plugin_file) {
		register_activation_hook($main_plugin_file, function() {
			if(!class_exists('RW_Meta_Box')) {
				die('Meta Box is not activated');
			}
		});
	}

	public function execute() {
		$this->init();
		$this->rwmb_meta_boxes();
	}

	private function init() {
		add_action('init', function() {
			register_post_type(self::SLIDES, [
				'labels' => [
					'name' => 'Slides',
					'singular_name' => 'Slide'
				],
				'public' => true,
				'has_archive' => true,
				'supports' => ['title'],
				'menu_icon' => 'dashicons-images-alt'
			]);
		});
	}

	private function rwmb_meta_boxes() {
		add_filter('rwmb_meta_boxes', function(array $meta_boxes) : array {
			$meta_boxes[] = [
				'id' => 'slide_informations',
				'title' => 'Slide informations',
				'post_types' => [self::SLIDES],
				'fields' => [
					[
						'name' => 'Photo',
						'id' => $this->_field_slide_image,
						'type' => 'image_advanced',
						'max_file_uploads' => 1
					]
				]
			];
			return $meta_boxes;
		});
	}

	public function get_slide_image(int $post_id = null, array $args = []) : string {
		$image = rwmb_meta($this->_field_slide_image, $args, $post_id);
		return array_shift($image)['full_url'];
	}
}