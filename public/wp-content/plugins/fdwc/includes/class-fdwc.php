<?php
namespace FDWC\Includes;

class FDWC {

	private $_field_description;
	private $_field_file;
	private $_field_download_counter;
	private $_field_file_type;
	private $_tax_file_type;

	const FDWC_FILE = 'fdwc_file';

	public function __construct() {
		$prefix = 'fdwc_field_';
		$this->_field_description = $prefix.'description';
		$this->_field_file = $prefix.'file';
		$this->_field_download_counter = $prefix.'download_counter';
		$this->_field_file_type = $prefix.'file_type';
		$prefix = 'fdwc_tax_';
		$this->_tax_file_type = $prefix.'file_type';
	}

	public function activate(string $main_plugin_file) {
		register_activation_hook($main_plugin_file, function() {
			if(!class_exists('RW_Meta_Box')) {
				die('Meta Box is not activated');
			}
		});
	}

	public function execute() {
		$this->load_depedencies();
		$loader = new FDWC_Loader();
		$loader->load_scripts();
		$this->init();
		$this->admin_menu();
		$this->rwmb_meta_boxes();
		$this->add_shortcode();
		$this->upload_mimes();
		$this->wp_ajax();
	}

	private function load_depedencies() {
		require_once 'class-fdwc-loader.php';
	}

	private function init() {
		add_action('init', function() {
			register_post_type(self::FDWC_FILE, [
				'labels' => [
					'name' => 'Files',
					'singular_name' => 'File'
				],
				'public' => true,
				'exclude_from_search' => true,
				'has_archive' => true,
				'supports' => ['title']
			]);
			register_taxonomy($this->_tax_file_type, self::FDWC_FILE, [
				'labels' => [
					'name' => 'File types',
					'singular_name' => 'File type'
				],
				'show_admin_column' => true
			]);
			register_taxonomy_for_object_type($this->_tax_file_type, self::FDWC_FILE);
		});
	}

	private function admin_menu() {
		add_action('admin_menu', function() {
			remove_meta_box('tagsdiv-'.$this->_tax_file_type, self::FDWC_FILE, 'normal');
		});
	}

	private function rwmb_meta_boxes() {
		add_filter('rwmb_meta_boxes', function(array $meta_boxes) : array {
			$meta_boxes[] = [
				'id' => 'file_informations',
				'title' => 'File informations',
				'post_types' => [self::FDWC_FILE],
				'fields' => [
					[
						'name' => 'Description',
						'id' => $this->_field_description,
						'type' => 'textarea'
					],
					[
						'name' => 'File',
						'id' => $this->_field_file,
						'type' => 'file_advanced',
						'max_file_uploads' => 1
					],
					[
						'name' => 'Download counter',
						'id' => $this->_field_download_counter,
						'type' => 'number',
						'std' => 0
					],
					[
						'name' => 'File type',
						'id' => $this->_field_file_type,
						'type' => 'taxonomy',
						'taxonomy' => $this->_tax_file_type,
						'field_type' => 'select'
					]
				],
				'validation' => [
					'rules' => [
						$this->_field_description => [
							'required' => true
						],
						$this->_field_download_counter => [
							'required' => true,
							'min' => 0
						],
						$this->_field_file_type => [
							'required' => true
						]
					]
				]
			];
			return $meta_boxes;
		});
	}

	private function add_shortcode() {
		add_shortcode('fdwc_files', function(array $atts) : string {
			$html = '<div class="fdwc">';
			$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
			$args = [
				'post_type' => self::FDWC_FILE,
				'posts_per_page' => 7,
				'order' => 'ASC',
				'tax_query' => [
					[
						'taxonomy' => $this->_tax_file_type,
						'terms' => $atts['file_type_id']
					]
				],
				'paged' => $paged
			];
			$wp_query = new \WP_Query($args);
			while($wp_query->have_posts()) {
				$wp_query->the_post();
				$html .= '<div class="fdwc__section">';
				$html .= '<a href="'.$this->get_file_url().'" class="fdwc__link" rel="nofollow" data-post-id="'.get_the_ID().'" download>'.$this->get_file_name().'</a>';
				$html .= '<p>'.$this->get_file_description().'</p>';
				$html .= '<p>Antal downloads: '.$this->get_file_downloads().'</p>';
				$html .= '</div>';
			}
			$big = 999999999; // need an unlikely integer
			$replace = '%#%';
			$html .= '<div class="fdwc__pagination">';
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

	private function upload_mimes() {
		$priority = 1;
		add_filter('upload_mimes', function(array $mime_types) : array {
			$mime_types['java'] = 'application/java';
			return $mime_types;
		}, $priority);
	}

	private function wp_ajax() {
		$action = 'fdwc_download';
		add_action('wp_ajax_nopriv_'.$action, function() {
			$this->update_counter();
			wp_die();
		});
		add_action('wp_ajax_'.$action, function() {
			$this->update_counter();
			wp_die();
		});
	}

	private function update_counter() {
		$id = $_POST['post_id'];
		$downloads = $this->get_file_downloads($id);
		$downloads++;
		update_post_meta($id, $this->_field_download_counter, $downloads);
	}

	private function get_file_url(int $post_id = null, array $args = []) : string {
		$file = rwmb_meta($this->_field_file, $args, $post_id);
		$file = array_shift($file);
		return $file['url'];
	}

	private function get_file_path(int $post_id = null, array $args = []) : string {
		$file = rwmb_meta($this->_field_file, $args, $post_id);
		$file = array_shift($file);
		return $file['path'];
	}

	private function get_file_name(int $post_id = null, array $args = []) : string {
		return basename($this->get_file_path($post_id, $args));
	}

	private function get_file_description(int $post_id = null, array $args = []) : string {
		return rwmb_meta($this->_field_description, $args, $post_id);
	}

	private function get_file_downloads(int $post_id = null, array $args = []) : int {
		return rwmb_meta($this->_field_download_counter, $args, $post_id);
	}
}