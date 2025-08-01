<?php
if (!defined('ABSPATH')) die('No direct access allowed');

if (!class_exists('Simba_Two_Factor_Authentication_1')) require AIO_WP_SECURITY_PATH.'/includes/simba-tfa/simba-tfa.php';

/**
 * This parent-child relationship enables the two to be split without affecting backwards compatibility for developers making direct calls
 *
 * This class is for the plugin encapsulation.
 */
class AIO_WP_Security_Simba_Two_Factor_Authentication_Plugin extends Simba_Two_Factor_Authentication_1 {

	/**
	 * Whether the TFA plugin is being integrated into the AIOS plugin.
	 *
	 * @var boolean
	 */
	public $is_tfa_integrated;

	/**
	 * Stores the current plugin version.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Class constructor
	 *
	 * @uses __FILE__
	 *
	 * @return Void
	 */
	public function __construct() {

		add_filter('aiowpsecurity_setting_tabs', array($this, 'add_two_factor_setting_tab'));
		add_filter('tfa_user_ip_address', array($this, 'aios_set_user_ip_address'));

		if (false !== $this->is_incompatible_plugin_active()) return;

		if (!function_exists('mcrypt_get_iv_size') && !function_exists('openssl_cipher_iv_length')) {
			add_action('all_admin_notices', array($this, 'admin_notice_missing_mcrypt_and_openssl'));
			return;
		}

		$this->is_tfa_integrated = true;

		// Run at a priority ensuring that this will be after AIOS has registered its translation domain
		add_action('init', array($this, 'plugin_text_domain_loaded'), 11);
		
		add_action('admin_menu', array($this, 'menu_entry_for_user'), 30);
		$this->version = AIO_WP_SECURITY_VERSION;
		$this->set_user_settings_page_slug(AIOWPSEC_TWO_FACTOR_AUTH_MENU_SLUG);
		
		$this->set_plugin_translate_url('https://translate.wordpress.org/projects/wp-plugins/all-in-one-wp-security-and-firewall/');
		$this->set_site_wide_administration_url(admin_url('admin.php?page=aiowpsec_settings&tab=two-factor-authentication'));
		$this->set_premium_version_url('https://teamupdraft.com/all-in-one-security/pricing/?utm_source=aios-plugin&utm_medium=referral&utm_campaign=paac&utm_content=emergency-codes-feature&utm_creative_format=text');
		$this->set_faq_url('https://wordpress.org/plugins/all-in-one-wp-security-and-firewall/#faq');
		parent::__construct();
	}
	
	/**
	 * Runs upon the WP action init (once the text domain has been loaded)
	 */
	public function plugin_text_domain_loaded() {
		$this->set_settings_page_heading(__('Two factor authentication - Admin settings', 'all-in-one-wp-security-and-firewall'));
	}
	
	/**
	 * Detect plugins that cause us to self-deactivate
	 *
	 * @return Boolean|String
	 */
	private function is_incompatible_plugin_active() {
		
		if (defined('WORDFENCE_LS_VERSION')) return 'Wordfence Login Security';
				
		$active_plugins = $this->get_active_plugins();
		foreach ($active_plugins as $plugin_file_rel_to_plugins_dir) {
			$temp_plugin_file_name = substr($plugin_file_rel_to_plugins_dir, strpos($plugin_file_rel_to_plugins_dir, '/') + 1);
			if ('wordfence-login-security.php' == $temp_plugin_file_name) {
				return 'Wordfence Login Security';
			}
			if ('wordfence.php' == $temp_plugin_file_name) {
				return 'Wordfence';
			}
		}
		return false;
	}
	
	/**
	 * Gets an array of plugins active on either the current site, or site-wide
	 *
	 * @return Array - a list of plugin paths (relative to the plugin directory)
	 */
	private function get_active_plugins() {
		
		// Gets all active plugins on the current site
		$active_plugins = get_option('active_plugins');
		
		if (is_multisite()) {
			$network_active_plugins = get_site_option('active_sitewide_plugins');
			if (!empty($network_active_plugins)) {
				$network_active_plugins = array_keys($network_active_plugins);
				$active_plugins = array_merge($active_plugins, $network_active_plugins);
			}
		}
		
		return $active_plugins;
	}
	
	/**
	 * Runs upon the WP actions admin_menu and network_admin_menu
	 */
	public function menu_entry_for_user() {
		
		global $current_user;
		if ($this->is_activated_for_user($current_user->ID)) {
			if (!AIOWPSecurity_Utility_Permissions::has_manage_cap()) {
				$menu_icon_url = AIO_WP_SECURITY_URL . '/images/aios-plugin-icon.svg';
				add_menu_page(__('AIOS', 'all-in-one-wp-security-and-firewall'), __('AIOS', 'all-in-one-wp-security-and-firewall'), apply_filters('aios_management_permission', 'manage_options'), AIOWPSEC_MAIN_MENU_SLUG, '', $menu_icon_url);
			}
			add_submenu_page(AIOWPSEC_MAIN_MENU_SLUG, __('Two Factor Auth', 'all-in-one-wp-security-and-firewall'),  __('Two Factor Auth', 'all-in-one-wp-security-and-firewall'), 'read', AIOWPSEC_TWO_FACTOR_AUTH_MENU_SLUG, array($this, 'show_dashboard_user_settings_page'));
		}
	}
	
	/**
	 * AIOS settings based user IP address
	 *
	 * @return string IP address
	 */
	public function aios_set_user_ip_address() {
		return AIOS_Helper::get_user_ip_address();
	}
	
	/**
	 * Builds Two Factor Authentication tab
	 *
	 * @param array $tabs array that contain tab name and call back function
	 * @return array Returns all tabs with callback function name
	 */
	public function add_two_factor_setting_tab($tabs = array()) {
		if (!AIOWPSecurity_Utility_Permissions::has_manage_cap()) return;

		$tabs['two-factor-authentication'] = array(
			'title' => __('Two factor authentication', 'all-in-one-wp-security-and-firewall'),
			'render_callback' => array($this, 'render_two_factor_authentication'),
			'display_condition_callback' => 'is_main_site',
		);
		return $tabs;
	}

	/**
	 * Display the Two Factor Authentication tab & handle the operations
	 */
	public function render_two_factor_authentication() {
		$plugin = $this->is_incompatible_plugin_active();
		if (false !== $plugin) {
			global $aio_wp_security;
			$aio_wp_security->include_template('admin/incompatible-plugin.php', false, array(
				'incompatible_plugin' => $plugin,
			));
			return;
		}
		
		$this->show_admin_settings_page();
	}
	
	/**
	 * Include the admin settings page code.
	 */
	public function show_admin_settings_page() {

		if (!is_admin() || !AIOWPSecurity_Utility_Permissions::has_manage_cap()) return;
		
		// Check if there are any settings errors and display them (this is needed because the forms from this template submit to the TFA options page not AIOS, so we need to grab them and output them manually).
		$settings_errors = get_settings_errors();
		foreach ($settings_errors as $error) {
			$type = 'success' == $error['type'] ? 'updated' : 'error';
			$this->show_admin_warning($error['message'], $type);
		}
		
		// The value for totp_controller is already set by versions of the TFA plugin after 3 Oct 2022
		$this->include_template('admin-settings.php', array(
			'totp_controller' => $this->get_controller('totp'),
			'settings_page_heading' => $this->get_settings_page_heading(),
			'admin_settings_links' => array(),
		));
	}

	/**
	 * Runs conditionally on the WP action all_admin_notices.
	 */
	public function admin_notice_missing_mcrypt_and_openssl() {
		$this->show_admin_warning('<strong>'.__('PHP OpenSSL or mcrypt module required', 'all-in-one-wp-security-and-firewall').'</strong><br> '.__('The All-In-One Security plugin\'s Two Factor Authentication module requires either the PHP openssl (preferred) or mcrypt module to be installed.', 'all-in-one-wp-security-and-firewall') . ' ' . __('Please ask your web hosting company to install one of them.', 'all-in-one-wp-security-and-firewall'), 'error');
	}
}

if (false === AIOWPSecurity_Utility::is_incompatible_tfa_premium_version_active() && false === AIOWPSecurity_Utility::is_tfa_or_self_plugin_activating()) {
	$GLOBALS['simba_two_factor_authentication'] = new AIO_WP_Security_Simba_Two_Factor_Authentication_Plugin();
}
