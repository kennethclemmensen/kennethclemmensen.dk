<?php
namespace KCGallery\Includes;

class KC_Gallery {

	private $_field_gallery_page;
	private $_field_gallery_photo;
	private $_field_photo;
	private $_field_photo_gallery;
	private $_gallery_settings;

	const GALLERY = 'gallery';
	const PHOTO = 'photo';

	public function __construct() {
		$prefix = 'gallery_';
		$this->_field_gallery_page = $prefix.'page';
		$this->_field_gallery_photo = $prefix.'photo';
		$prefix = 'photo_';
		$this->_field_photo = $prefix.'photo';
		$this->_field_photo_gallery = $prefix.'gallery';
		$this->_gallery_settings = null;
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
		$loader = new KC_Gallery_Loader();
		$loader->load_styles_and_scripts();
		$this->_gallery_settings = new KC_Gallery_Settings();
		$this->init();
		$this->rwmb_meta_boxes();
		$this->shortcodes();
		$this->admin_columns();
		$this->pre_get_posts();
	}

	private function load_dependencies() {
		require_once 'class-kc-gallery-loader.php';
		require_once 'class-kc-gallery-settings.php';
	}

	private function init() {
		add_action('init', function() {
			register_post_type(self::GALLERY, [
				'labels' => [
					'name' => 'Galleries',
					'singular_name' => 'Gallery'
				],
				'public' => true,
				'has_archive' => true,
				'supports' => ['title'],
				'menu_icon' => 'dashicons-format-gallery'
			]);
			register_post_type(self::PHOTO, [
				'labels' => [
					'name' => 'Photos',
					'singular_name' => 'Photo'
				],
				'public' => true,
				'has_archive' => true,
				'supports' => ['title'],
				'menu_icon' => 'dashicons-format-image'
			]);
		});
	}

	private function rwmb_meta_boxes() {
		add_filter('rwmb_meta_boxes', function(array $meta_boxes) : array {
			$meta_boxes[] = [
				'id' => 'gallery_informations',
				'title' => 'Gallery informations',
				'post_types' => [self::GALLERY],
				'fields' => [
					[
						'name' => 'Page',
						'id' => $this->_field_gallery_page,
						'type' => 'select',
						'options' => $this->get_pages()
					],
					[
						'name' => 'Photo',
						'id' => $this->_field_gallery_photo,
						'type' => 'image_advanced',
						'max_file_uploads' => 1
					]
				]
			];
			$meta_boxes[] = [
				'id' => 'photo_informations',
				'title' => 'Photo informations',
				'post_types' => [self::PHOTO],
				'fields' => [
					[
						'name' => 'Photo',
						'id' => $this->_field_photo,
						'type' => 'image_advanced',
						'max_file_uploads' => 1
					],
					[
						'name' => 'Gallery',
						'id' => $this->_field_photo_gallery,
						'type' => 'select',
						'options' => $this->get_galleries()
					]
				]
			];
			return $meta_boxes;
		});
	}

	private function shortcodes() {
		add_shortcode('galleries', function() : string {
			$html = '<div class="kc-galleries">';
			$galleries = $this->get_galleries();
			foreach($galleries as $key => $gallery) {
				$html .= '<div class="kc-galleries__gallery">';
				$html .= '<a href="'.$this->get_gallery_page($key).'"><img src="'.$this->get_gallery_photo($key).'" alt="'.get_the_title($key).'"></a>';
				$html .= '</div>';
			}
			$html .= '</div>';
			return $html;
		});
		add_shortcode('gallery', function(array $atts) : string {
			$html = '<div class="kc-gallery">';
			$gallery_id = addslashes($atts['id']);
			$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
			$args = [
				'post_type' => self::PHOTO,
				'posts_per_page' => 39,
				'order' => 'ASC',
				'meta_key' => $this->_field_photo_gallery,
				'meta_value' => $gallery_id,
				'paged' => $paged
			];
			$wp_query = new \WP_Query($args);
			while($wp_query->have_posts()) {
				$wp_query->the_post();
				$html .= '<a href="'.$this->get_photo(get_the_ID()).'" data-title="'.get_the_title().'" data-lightbox="'.$gallery_id.'">';
				$html .= '<img src="'.$this->get_photo_thumbnail(get_the_ID()).'" class="kc-gallery__photo" alt="'.get_the_title().'"></a>';
			}
			$html .= '<div class="kc-gallery__pagination">';
			$big = 999999999; // need an unlikely integer
			$replace = '%#%';
			$html .= paginate_links([
				'base' => str_replace($big, $replace, esc_url(get_pagenum_link($big))),
				'format' => '?paged='.$replace,
				'current' => max(1, $paged),
				'total' => $wp_query->max_num_pages,
				'prev_text' => 'Forrige',
				'next_text' => 'Næste'
			]);
			$html .= '</div></div>';
			return $html;
		});
	}

	private function admin_columns() {
		$column_gallery_key = 'gallery';
		$column_gallery_value = 'Gallery';
		$column_photo_key = 'photo';
		add_filter('manage_'.self::PHOTO.'_posts_columns', function(array $columns) use ($column_gallery_key, $column_gallery_value, $column_photo_key) : array {
			$columns[$column_gallery_key] = $column_gallery_value;
			$columns[$column_photo_key] = 'Photo';
			return $columns;
		});
		add_action('manage_'.self::PHOTO.'_posts_custom_column', function(string $column_name) use ($column_gallery_key, $column_photo_key) {
			if($column_name === $column_gallery_key) {
				$gallery_id = rwmb_meta($this->_field_photo_gallery);
				echo get_post($gallery_id)->post_title;
			} else if($column_name === $column_photo_key) {
				echo '<img src="'.$this->get_photo_thumbnail().'" alt="'.get_the_title().'">';
			}
		});
		add_filter('manage_edit-'.self::PHOTO.'_sortable_columns', function(array $columns) use ($column_gallery_key, $column_gallery_value) : array {
			$columns[$column_gallery_key] = $column_gallery_value;
			return $columns;
		});
		$column_number_of_photos_key = 'number_of_photos';
		$column_number_of_photos_value = 'Photos';
		add_filter('manage_'.self::GALLERY.'_posts_columns', function(array $columns) use ($column_number_of_photos_key, $column_number_of_photos_value) : array {
			$columns[$column_number_of_photos_key] = $column_number_of_photos_value;
			return $columns;
		});
		add_action('manage_'.self::GALLERY.'_posts_custom_column', function(string $column_name) use ($column_number_of_photos_key) {
			if($column_name === $column_number_of_photos_key) {
				echo $this->get_number_of_photos_in_gallery(get_the_ID());
			}
		});
		add_filter('manage_edit-'.self::GALLERY.'_sortable_columns', function(array $columns) use ($column_number_of_photos_key, $column_number_of_photos_value) : array {
			$columns[$column_number_of_photos_key] = $column_number_of_photos_value;
			return $columns;
		});
	}

	private function pre_get_posts() {
		add_action('pre_get_posts', function(\WP_Query $query) {
			if(!is_admin()) {
				return;
			}
			if($query->get('orderby') === 'menu_order') {
				$query->set('meta_key', $this->_field_photo_gallery);
				$query->set('orderby', 'meta_value');
			}
		});
	}

	private function get_pages() : array {
		$pages = [];
		$args = [
			'post_type' => 'page',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'orderby' => 'menu_order'
		];
		$wp_query = new \WP_Query($args);
		while($wp_query->have_posts()) {
			$wp_query->the_post();
			$pages[get_the_ID()] = get_the_title();
		}
		return $pages;
	}

	private function get_galleries() : array {
		$galleries = [];
		$args = [
			'post_type' => self::GALLERY,
			'posts_per_page' => -1,
			'order' => 'ASC'
		];
		$wp_query = new \WP_Query($args);
		while($wp_query->have_posts()) {
			$wp_query->the_post();
			$galleries[get_the_ID()] = get_the_title();
		}
		return $galleries;
	}

	private function get_gallery_page(int $post_id = null, array $args = []) : string {
		return get_permalink(rwmb_meta($this->_field_gallery_page, $args, $post_id));
	}

	private function get_gallery_photo(int $post_id = null, array $args = []) : string {
		$photo = rwmb_meta($this->_field_gallery_photo, $args, $post_id);
		return array_shift($photo)['full_url'];
	}

	private function get_photo(int $post_id = null, array $args = []) : string {
		$photo = rwmb_meta($this->_field_photo, $args, $post_id);
		$photo_id = array_shift($photo)['ID'];
		return wp_get_attachment_image_src($photo_id, $this->_gallery_settings->get_photo_key())[0];
	}

	private function get_photo_thumbnail(int $post_id = null, array $args = []) : string {
		$photo = rwmb_meta($this->_field_photo, $args, $post_id);
		$photo_id = array_shift($photo)['ID'];
		return wp_get_attachment_image_src($photo_id, $this->_gallery_settings->get_photo_thumbnail_key())[0];
	}

	private function get_number_of_photos_in_gallery($gallery_id) : int {
		$args = [
			'post_type' => self::PHOTO,
			'posts_per_page' => -1,
			'meta_key' => $this->_field_photo_gallery,
			'meta_value' => $gallery_id
		];
		$wp_query = new \WP_Query($args);
		return $wp_query->found_posts;
	}
}