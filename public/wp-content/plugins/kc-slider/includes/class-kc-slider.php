<?php
namespace KCSlider\Includes;

/**
 * Class KC_Slider contains methods to handle the functionality of the plugin
 * @package KCSlider\Includes
 */
class KC_Slider {

	private $_field_slide_image;

	const SLIDES = 'slides';

	/**
	 * KC_Slider constructor
	 */
	public function __construct() {
		$prefix = 'slider_';
		$this->_field_slide_image = $prefix.'slide_image';
	}

	/**
	 * Activate the plugin
	 *
	 * @param string $main_plugin_file the path to the main plugin file
	 */
	public function activate(string $main_plugin_file) {
		register_activation_hook($main_plugin_file, function() {
			if(!class_exists('RW_Meta_Box')) {
				die('Meta Box is not activated');
			}
		});
	}

	/**
	 * Execute the plugin
	 */
	public function execute() {
		$this->load_dependencies();
		new KC_Slider_Settings();
		$this->init();
		$this->rwmb_meta_boxes();
		$this->admin_columns();
	}

	/**
	 * Load the dependencies files
	 */
	private function load_dependencies() {
		require_once 'class-kc-slider-settings.php';
	}

	/**
	 * Use the init action to register the slides custom post type
	 */
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

	/**
	 * Use the rwmb_meta_boxes filter to add meta boxes to the slides custom post type
	 */
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

	/**
	 * Use the manage_{$post_type}_posts_column and manage_{$post_type}_posts_custom_column filters to create custom
	 * columns for the slides custom post type
	 */
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

	/**
	 * Get the side image
	 *
	 * @param int|null $post_id the post id of the slide image
	 * @param array $args an array of arguments
	 *
	 * @return string the url of the slide image
	 */
	public function get_slide_image(int $post_id = null, array $args = []) : string {
		$image = rwmb_meta($this->_field_slide_image, $args, $post_id);
		return array_shift($image)['full_url'];
	}
}