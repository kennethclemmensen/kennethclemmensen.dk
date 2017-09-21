<?php
namespace KCSlider\Includes;

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
		$this->load_dependencies();
		new KC_Slider_Settings();
		$this->init();
		$this->rwmb_meta_boxes();
		$this->admin_columns();
	}

	private function load_dependencies() {
		require_once 'class-kc-slider-settings.php';
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

	private function admin_columns() {
		$image_column_key = 'image';
		add_filter('manage_'.self::SLIDES.'_posts_columns', function(array $columns) use($image_column_key) : array {
			$columns[$image_column_key] = 'Image';
			return $columns;
		});
		add_filter('manage_'.self::SLIDES.'_posts_custom_column', function(string $column_name) use($image_column_key) {
			if($column_name === $image_column_key) {
				echo '<img src="'.$this->get_slide_image().'" alt="'.get_the_title().'" style="height: 60px">';
			}
		});
	}

	public function get_slide_image(int $post_id = null, array $args = []) : string {
		$image = rwmb_meta($this->_field_slide_image, $args, $post_id);
		return array_shift($image)['full_url'];
	}
}