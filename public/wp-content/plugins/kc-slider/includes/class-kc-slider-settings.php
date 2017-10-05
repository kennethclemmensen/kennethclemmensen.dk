<?php
namespace KCSlider\Includes;

/**
 * Class KC_Slider_Settings contains methods to handle the settings for the plugin
 * @package KCSlider\Includes
 */
class KC_Slider_Settings {

	private $_page_slug;
	private $_option_group;
	private $_option;
	private $_delay;
	private $_duration;

	/**
	 * KC_Slider_Settings constructor
	 */
	public function __construct() {
		$this->_page_slug = 'kc-slider-settings';
		$this->_option_group = $this->_page_slug.'-group';
		$this->_option = get_option($this->_option_group);
		$prefix = 'kc_slider_';
		$this->_delay = $prefix.'delay';
		$this->_duration = $prefix.'duration';
		$this->admin_menu();
		$this->admin_init();
	}

	/**
	 * Use the admin_menu action to create a settings page
	 */
	private function admin_menu() {
		add_action('admin_menu', function() {
			$title = 'Settings';
			add_submenu_page('edit.php?post_type=slides', $title, $title, 'administrator', $this->_page_slug, function() {
				settings_errors();
				?>
                <form action="options.php" method="post">
					<?php
					settings_fields($this->_option_group);
					do_settings_sections($this->_page_slug);
					submit_button();
					?>
                </form>
				<?php
			});
		});
	}

	/**
	 * Use the admin_init action to create and register the setting inputs
	 */
	private function admin_init() {
		add_action('admin_init', function() {
			$section_id = 'kc-slider-section';
			add_settings_section($section_id, '', function() {
				echo '<h2>KC Slider Settings</h2>';
			}, $this->_page_slug);
			add_settings_field('kc-slider-delay', 'Delay', function() {
				echo '<input type="number" name="'.$this->_option_group.'['.$this->_delay.']" value="'.$this->get_delay().'">';
			}, $this->_page_slug, $section_id);
			add_settings_field('kc-slider-duration', 'Duration', function() {
				echo '<input type="number" name="'.$this->_option_group.'['.$this->_duration.']" value="'.$this->get_duration().'">';
			}, $this->_page_slug, $section_id);
			register_setting($this->_option_group, $this->_option_group, function(array $input) : array {
				return $this->validate_input($input);
			});
		});
	}

	/**
	 * Validate the setting inputs
	 *
	 * @param array $input the input to validate
	 *
	 * @return array the validated input
	 */
	private function validate_input(array $input) : array {
		$output = [];
		$default_value = 1000;
		foreach($input as $key => $value) {
			if(isset($input[$key])) {
				$output[$key] = ($input[$key] <= 0) ? $default_value : strip_tags(stripslashes($input[$key]));
			}
		}
		return apply_filters(__FUNCTION__, $output, $input);
	}

	/**
	 * Get the delay
	 *
	 * @return int the delay
	 */
	public function get_delay() : int {
		return $this->_option[$this->_delay];
	}

	/**
	 * Get the duration
	 *
	 * @return int the duration
	 */
	public function get_duration() : int {
		return $this->_option[$this->_duration];
	}
}