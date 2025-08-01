<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('AIO_WP_Security')) {

	class AIO_WP_Security {

		public $version = '5.4.2';

		public $db_version = '2.1.4';

		public $firewall_version = '1.0.8';

		public $plugin_url;

		public $plugin_path;

		public $configs;

		/**
		 * Notice class object.
		 *
		 * @var object instance of AIOWPSecurity_Notices
		 */
		public $notices;

		public $admin_init;

		public $debug_logger;

		public $cron_handler;

		public $user_login_obj;

		public $user_registration_obj;

		public $scan_obj;

		public $captcha_obj;
				
		public $cleanup_obj;

		public $sender_obj;

		public $debug_obj;

		/**
		 * Whether the page is admin dashboard page.
		 *
		 * @var boolean
		 */
		public $is_admin_dashboard_page;

		/**
		 * Whether the page is admin plugin page.
		 *
		 * @var boolean
		 */
		public $is_plugin_admin_page;

		/**
		 * Whether the page is admin AIOS page.
		 *
		 * @var boolean
		 */
		public $is_aiowps_admin_page;

		/**
		 * Whether the page is AIOS Login reCAPTCHA page.
		 *
		 * @var boolean
		 */
		public $is_aiowps_google_recaptcha_tab_page;

		/**
		 * Defines constants, loads configs, includes required files and adds actions.
		 *
		 * @return Void
		 */
		public function __construct() {
			// Add management permission filter early before any of the includes try to use it
			add_filter('aios_management_permission', array($this, 'aios_management_permission'), 10, 2);

			$this->define_constants();
			$this->load_configs();
			$this->includes();
			$this->loader_operations();

			add_action('init', array($this, 'wp_security_plugin_init'), 0);
			add_action('init', array($this, 'load_plugin_textdomain'));
			add_action('wp_loaded', array($this, 'aiowps_wp_loaded_handler'));

			$add_update_action_prefixes = array(
				'add_option_',
				'update_option_',
			);
			foreach ($add_update_action_prefixes as $add_update_action_prefix) {
				add_action($add_update_action_prefix . '_updraft_interval_database', array($this, 'udp_schedule_db_option_add_update_action_handler'), 10, 2);
			}

			if ('1' == $this->configs->get_site_value('aiowps_enable_salt_postfix')) {
				add_filter('salt', array($this, 'salt'), 10, 2);
			}

			do_action('aiowpsecurity_loaded');

		}

		/**
		 * Return the URL for the plugin directory
		 *
		 * @return String
		 */
		public function plugin_url() {
			if ($this->plugin_url) return $this->plugin_url;
			return $this->plugin_url = plugins_url('', __FILE__);
		}

		public function plugin_path() {
			if ($this->plugin_path) return $this->plugin_path;
			return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
		}

		public function load_configs() {
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-config.php');
			$this->configs = AIOWPSecurity_Config::get_instance();
		}

		public function define_constants() {
			define('AIO_WP_SECURITY_VERSION', $this->version);
			define('AIO_WP_SECURITY_DB_VERSION', $this->db_version);
			define('AIO_WP_SECURITY_FIREWALL_VERSION', $this->firewall_version);
			define('AIOWPSEC_WP_HOME_URL', home_url());
			define('AIOWPSEC_WP_SITE_URL', site_url());
			define('AIOWPSEC_WP_URL', AIOWPSEC_WP_SITE_URL); // for backwards compatibility
			define('AIO_WP_SECURITY_URL', $this->plugin_url());
			define('AIO_WP_SECURITY_PATH', $this->plugin_path());
			define('AIO_WP_SECURITY_BACKUPS_DIR_NAME', 'aiowps_backups');
			define('AIO_WP_SECURITY_BACKUPS_PATH', AIO_WP_SECURITY_PATH.'/backups');
			define('AIO_WP_SECURITY_LIB_PATH', AIO_WP_SECURITY_PATH.'/lib');
			if (!defined('AIOWPSEC_MANAGEMENT_PERMISSION')) { // This will allow the user to define custom capability for this constant in wp-config file
				define('AIOWPSEC_MANAGEMENT_PERMISSION', 'manage_options');
			}
			define('AIOWPSEC_MENU_SLUG_PREFIX', 'aiowpsec');
			define('AIOWPSEC_MAIN_MENU_SLUG', 'aiowpsec');
			define('AIOWPSEC_SETTINGS_MENU_SLUG', 'aiowpsec_settings');
			define('AIOWPSEC_USER_SECURITY_MENU_SLUG', 'aiowpsec_usersec');
			define('AIOWPSEC_DB_SEC_MENU_SLUG', 'aiowpsec_database');
			define('AIOWPSEC_FILESYSTEM_MENU_SLUG', 'aiowpsec_filesystem');
			define('AIOWPSEC_FIREWALL_MENU_SLUG', 'aiowpsec_firewall');
			define('AIOWPSEC_SPAM_MENU_SLUG', 'aiowpsec_spam');
			define('AIOWPSEC_FILESCAN_MENU_SLUG', 'aiowpsec_filescan');
			define('AIOWPSEC_BRUTE_FORCE_MENU_SLUG', 'aiowpsec_brute_force');
			define('AIOWPSEC_TWO_FACTOR_AUTH_MENU_SLUG', 'aiowpsec_two_factor_auth_user');
			define('AIOWPSEC_TOOLS_MENU_SLUG', 'aiowpsec_tools');
			define('AIOWPSEC_CAPTCHA_SHORTCODE', 'aios_captcha');
			
			if (!defined('AIOS_TFA_PREMIUM_LATEST_INCOMPATIBLE_VERSION')) define('AIOS_TFA_PREMIUM_LATEST_INCOMPATIBLE_VERSION', '1.14.7');
			
			if (!defined('AIOWPSEC_PURGE_FAILED_LOGIN_RECORDS_AFTER_DAYS')) define('AIOWPSEC_PURGE_FAILED_LOGIN_RECORDS_AFTER_DAYS', 90);
			if (!defined('AIOS_PURGE_EVENTS_RECORDS_AFTER_DAYS')) define('AIOS_PURGE_EVENTS_RECORDS_AFTER_DAYS', 90);
			if (!defined('AIOS_PURGE_LOGIN_LOCKOUT_RECORDS_AFTER_DAYS')) define('AIOS_PURGE_LOGIN_LOCKOUT_RECORDS_AFTER_DAYS', 90);
			if (!defined('AIOS_PURGE_LOGIN_ACTIVITY_RECORDS_AFTER_DAYS')) define('AIOS_PURGE_LOGIN_ACTIVITY_RECORDS_AFTER_DAYS', 90);
			if (!defined('AIOS_PURGE_GLOBAL_META_DATA_RECORDS_AFTER_DAYS')) define('AIOS_PURGE_GLOBAL_META_DATA_RECORDS_AFTER_DAYS', 90);
			if (!defined('AIOS_DEFAULT_BRUTE_FORCE_FEATURE_SECRET_WORD')) define('AIOS_DEFAULT_BRUTE_FORCE_FEATURE_SECRET_WORD', 'aiossecret');
			if (!defined('AIOS_FIREWALL_MAX_FILE_UPLOAD_LIMIT_MB')) define('AIOS_FIREWALL_MAX_FILE_UPLOAD_LIMIT_MB', 100);
			if (!defined('AIOS_UPDATE_ANTIBOT_KEYS_AFTER_DAYS')) define('AIOS_UPDATE_ANTIBOT_KEYS_AFTER_DAYS', 5);

			global $wpdb;
			define('AIOWPSEC_TBL_LOGIN_LOCKOUT', $wpdb->prefix . 'aiowps_login_lockdown');
			define('AIOWPSEC_TBL_FAILED_LOGINS', $wpdb->prefix . 'aiowps_failed_logins');
			define('AIOWPSEC_TBL_USER_LOGIN_ACTIVITY', $wpdb->prefix . 'aiowps_login_activity');
			define('AIOWPSEC_TBL_GLOBAL_META_DATA', $wpdb->prefix . 'aiowps_global_meta');
			define('AIOWPSEC_TBL_EVENTS', $wpdb->prefix . 'aiowps_events');
			define('AIOWPSEC_TBL_PERM_BLOCK', $wpdb->prefix . 'aiowps_permanent_block');

			$base_prefix = $this->get_table_prefix();
			define('AIOWPSEC_TBL_AUDIT_LOG', $base_prefix . 'aiowps_audit_log');
			define('AIOWPSEC_TBL_DEBUG_LOG', $base_prefix . 'aiowps_debug_log');
			define('AIOWPSEC_TBL_LOGGED_IN_USERS', $base_prefix . 'aiowps_logged_in_users');
			define('AIOWPSEC_TBL_MESSAGE_STORE', $base_prefix . 'aiowps_message_store');
		}

		/**
		 * Includes required files.
		 *
		 * @return void
		 */
		public function includes() {
			// Load firewall, if it has not yet been loaded by this point
			if (!defined('AIOWPSEC_FIREWALL_DONE')) {
				$this->load_aio_firewall();
			}

			// Load common files for everywhere
			if (!class_exists('Updraft_Semaphore_3_0')) {
				include_once AIO_WP_SECURITY_PATH.'/vendor/team-updraft/common-libs/src/updraft-semaphore/class-updraft-semaphore.php';
			}
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-firewall-resource-unavailable.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-firewall-resource.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-audit-event-handler.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-debug-logger.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-abstract-ids.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-helper.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-utility.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-utility-htaccess.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-utility-ip-address.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-utility-file.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-utility-permissions.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-utility-ui.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-utility-api.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-general-init-tasks.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-wp-loaded-tasks.php');

			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-user-login.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-user-registration.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-captcha.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-cleanup.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-file-scan.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-comment.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-cronjob-handler.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/grade-system/wp-security-feature-item.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/grade-system/wp-security-feature-item-manager.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-wp-footer-content.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-blocking.php');
			include_once(AIO_WP_SECURITY_PATH .'/classes/wp-security-two-factor-login.php');

			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-utility-firewall.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-block-file.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-block-bootstrap.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-block-htaccess.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-block-litespeed.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-block-userini.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-block-wpconfig.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-block-muplugin.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-hibp.php');

			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-reporting.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-debug.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-sender-service.php');

			// At this time, sometimes is_admin() can't be populated, It gives the error PHP Fatal error:  Uncaught Error: Class 'AIOWPSecurity_Admin_Init' not found.
			// so we should not use is_admin() condition.
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-settings-tasks.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-configure-settings.php');
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-notices.php');
			require_once(AIO_WP_SECURITY_PATH.'/admin/wp-security-admin-init.php');
			include_once(AIO_WP_SECURITY_PATH.'/admin/general/wp-security-list-table.php');
			include_once(AIO_WP_SECURITY_PATH.'/admin/general/wp-security-ajax-data-table.php');
			include_once(AIO_WP_SECURITY_PATH.'/admin/wp-security-firewall-setup-notice.php');
		}

		public function loader_operations() {
			add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));//plugins loaded hook
			add_action('plugins_loaded', array($this, 'set_pagenow_for_renamed_loginpage'));

			$debug_config = $this->configs->get_value('aiowps_enable_debug');
			$debug_enabled = empty($debug_config) ? false : true;
			$this->debug_logger = new AIOWPSecurity_Logger($debug_enabled);

			$this->load_ajax_handler();
		}

		/**
		 * A filter function to get the management permission for AIOS
		 *
		 * @param string $permission - the management permission
		 *
		 * @return string - the filtered permission
		 */
		public function aios_management_permission($permission) {
			if (defined('AIOWPSEC_MANAGEMENT_PERMISSION') && AIOWPSEC_MANAGEMENT_PERMISSION) return AIOWPSEC_MANAGEMENT_PERMISSION;
			return $permission;
		}

		/**
		 * Activation handler function.
		 *
		 * @return void
		 */
		public static function activate_handler() {
			// Only runs when the plugin activates
			global $wpdb;// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- Used for the include below
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-installer.php');
			AIOWPSecurity_Installer::run_installer();
			AIOWPSecurity_Installer::set_cron_tasks_upon_activation();
		}

		/**
		 * Delete automated backup configs
		 *
		 * @return void
		 */
		public function delete_automated_backup_configs() {
			$automated_config_keys = array(
				'aiowps_enable_automated_backups',
				'aiowps_db_backup_frequency',
				'aiowps_backup_files_stored',
				'aiowps_send_backup_email_address',
				'aiowps_backup_email_address',
			);
			foreach ($automated_config_keys as $automated_config_key) {
				$this->configs->delete_value($automated_config_key);
			}
		}

		/**
		 * UpdraftPlus schedule option add/update action handler.
		 *
		 * @param String $option Option name.
		 * @param String $value  Option value.
		 * @return Void
		 */
		public function udp_schedule_db_option_add_update_action_handler($option, $value) {
			// For extra caution
			if ('updraft_interval_database' != $option) {
				return;
			}

			if (empty($value) || 'manual' == $value) {
				return;
			}

			$this->delete_automated_backup_configs();
			$this->configs->save_config();
		}

		/**
		 * Output, or return, the results of running a template (from the 'templates' directory, unless a filter over-rides it). Templates are run with $aio_wp_security and $wpdb set.
		 *
		 * @param String  $path                   - path to the template
		 * @param Boolean $return_instead_of_echo - by default, the template is echo-ed; set this to instead return it
		 * @param Array   $extract_these          - variables to inject into the template's run context
		 *
		 * @return Void|String
		 */
		public function include_template($path, $return_instead_of_echo = false, $extract_these = array()) {
			if ($return_instead_of_echo) ob_start();

			if (!isset($template_file)) $template_file = AIO_WP_SECURITY_PATH.'/templates/'.$path;

			$template_file = apply_filters('aio_wp_security_template', $template_file, $path);

			do_action('aio_wp_security_before_template', $path, $template_file, $return_instead_of_echo, $extract_these);

			if (!file_exists($template_file)) {
				error_log("All-In-One Security: template not found: $template_file");
				echo __('Error:', 'all-in-one-wp-security-and-firewall').' '.__('template not found', 'all-in-one-wp-security-and-firewall')." ($template_file)";
			} else {
				extract($extract_these);
				global $wpdb;// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- Bring variable into the included template's scope
				$aiowps_firewall_config = AIOS_Firewall_Resource::request(AIOS_Firewall_Resource::CONFIG); // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- Bring variable into the included template's scope
				global $aiowps_feature_mgr; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- Bring variable into the included template's scope
				$aio_wp_security = $this;// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- Bring variable into the included template's scope
				include $template_file;
			}

			do_action('aio_wp_security_after_template', $path, $template_file, $return_instead_of_echo, $extract_these);

			if ($return_instead_of_echo) return ob_get_clean();
		}

		/**
		 * Deactivation AIOS plugin.
		 *
		 * @return void
		 */
		public static function deactivation_handler() {
			require_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-deactivation-tasks.php');
			AIOWPSecurity_Deactivation_Tasks::run();
			do_action('aiowps_deactivation_complete');
		}

		/**
		 * Unintall AIOS plugin.
		 *
		 * @return void
		 */
		public static function uninstall_handler() {
			require_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-uninstallation-tasks.php');
			AIOWPSecurity_Uninstallation_Tasks::run();
			do_action('aiowps_uninstallation_complete');
		}

		/**
		 * Firewall configs upgrade.
		 *
		 * @return void.
		 */
		public function firewall_upgrade_handler() {
			if (get_option('aiowpsec_firewall_version') != AIO_WP_SECURITY_FIREWALL_VERSION) {
				AIOWPSecurity_Configure_Settings::set_firewall_configs();
				AIOWPSecurity_Utility_Htaccess::write_to_htaccess(false);
			}
		}

		/**
		 * Upgrades the database.
		 *
		 * @return void
		 */
		public function db_upgrade_handler() {
			$aiowps_firewall_config = AIOS_Firewall_Resource::request(AIOS_Firewall_Resource::CONFIG);

			if (get_option('aiowpsec_db_version') != AIO_WP_SECURITY_DB_VERSION) {
				require_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-installer.php');
				AIOWPSecurity_Installer::run_installer();
				AIOWPSecurity_Installer::set_cron_tasks_upon_activation();
				AIOWPSecurity_Utility_Htaccess::write_to_htaccess(false);

				/**
				 * Update our config file's header if needed.
				 */
				if (is_main_site()) {
					$aiowps_firewall_config->update_prefix();
				}
			}
		}


		/**
		 * Loads our firewall
		 *
		 * @return void
		 */
		public function load_aio_firewall() {
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-utility-firewall.php');
			$firewall_path = AIOWPSecurity_Utility_Firewall::get_firewall_path();

			if (!(@include_once($firewall_path))) {
				error_log('AIOS firewall error: failed to load the firewall. Unable to include wp-security-firewall.php.');
			}
		}

		/**
		 * Runs when plugins_loaded hook is fired
		 *
		 * @return void
		 */
		public function plugins_loaded_handler() {
			//Runs when plugins_loaded action gets fired
			// Add filter for 'cron_schedules' must be run before $this->db_upgrade_handler()
			// so, AIOWPSecurity_Cronjob_Handler __construct runs this filter so the object should be initialized here.
			$this->cron_handler = new AIOWPSecurity_Cronjob_Handler();
			// DB upgrade handler - run outside admin interface
			$this->db_upgrade_handler();
			$this->firewall_upgrade_handler();
			if (is_admin()) {
				//Do plugins_loaded operations for admin side
				$this->admin_init = new AIOWPSecurity_Admin_Init();
				$this->notices = new AIOWPSecurity_Notices();
			}
			AIOWPSecurity_Audit_Event_Handler::instance();
		}

		/**
		 * Load plugin text domain
		 *
		 * @return void
		 */
		public function load_plugin_textdomain() {
				load_plugin_textdomain('all-in-one-wp-security-and-firewall', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		}

		/**
		 * Initializes the plugin. This is hooked into the inbuilt 'init' action.
		 *
		 * @return Void
		 */
		public function wp_security_plugin_init() {
			//Actions, filters, shortcodes goes here
			$this->user_login_obj = new AIOWPSecurity_User_Login();//Do the user login operation tasks
			$this->user_registration_obj = new AIOWPSecurity_User_Registration();//Do the user login operation tasks
			$this->captcha_obj = new AIOWPSecurity_Captcha(); // Do the CAPTCHA tasks
			$this->cleanup_obj = new AIOWPSecurity_Cleanup(); // Object to handle cleanup tasks
			$this->scan_obj = new AIOWPSecurity_Scan();//Object to handle scan tasks
			$this->sender_obj = new AIOWPSecurity_Sender_Service();//Object to handle sending emails
			$this->debug_obj =new AIOWPSecurity_Debug();//Object to handle debug tasks
			add_action('wp_footer', array($this, 'aiowps_footer_content'));

			add_action('wp_login', array('AIOWPSecurity_User_Login', 'wp_login_action_handler'), 10, 2);
			// For admin side force log out.
			add_action('admin_init', array($this, 'do_action_force_logout_check'));
			// For front side force log out.
			add_action('template_redirect', array($this, 'do_action_force_logout_check'));

			new AIOWPSecurity_General_Init_Tasks();
			new AIOWPSecurity_Comment();
			new AIOWPSecurity_Reporting();

			$this->redirect_user_after_force_logout();
		}

		public function aiowps_wp_loaded_handler() {
			new AIOWPSecurity_WP_Loaded_Tasks();
		}

		public function aiowps_footer_content() {
			new AIOWPSecurity_WP_Footer_Content();
		}

		/**
		 * Get the installation's base table prefix, optionally allowing the result to be filtered
		 *
		 * @return String
		 */
		public function get_table_prefix() {
			global $wpdb;
			if (is_multisite() && !defined('MULTISITE')) {
				// In this case (which should only be possible on installs upgraded from pre WP 3.0 WPMU), $wpdb->get_blog_prefix() cannot be made to return the right thing. $wpdb->base_prefix is not explicitly marked as public, so we prefer to use get_blog_prefix if we can, for future compatibility.
				$prefix = $wpdb->base_prefix;
			} else {
				$prefix = $wpdb->get_blog_prefix(0);
			}
			return $prefix;
		}

		/**
		 * Redirect user to proper login page after forced logout
		 *
		 * @return void
		 */
		private function redirect_user_after_force_logout() {
			global $aio_wp_security;
			if (isset($_GET['aiowpsec_do_log_out'])) {
				$nonce = isset($_GET['_wpnonce']) ? $_GET['_wpnonce'] : '';
				// We can not use AIOWPSecurity_Utility_Permissions::check_nonce_and_user_cap to check user capabilities = manage_option as subscriber, editor etc users do not have it only administrators will have. If that check is applied it can not force the logout user and creates too many redirect errors.
				if (!wp_verify_nonce($nonce, 'aio_logout')) {
					return;
				}
				wp_logout();
				if (isset($_GET['after_logout'])) { //Redirect to the after logout url directly
					$after_logout_url = esc_url($_GET['after_logout']);
					AIOWPSecurity_Utility::redirect_to_url($after_logout_url);
				}
				$additional_data = strip_tags($_GET['al_additional_data']);
				if (isset($additional_data)) {
					$login_url = '';

					if (AIOWPSecurity_Utility::is_woocommerce_plugin_active()) {
						$login_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
					} elseif ('1' == $aio_wp_security->configs->get_value('aiowps_enable_rename_login_page')) { //Check if rename login feature enabled.
						if (get_option('permalink_structure')) {
							$home_url = trailingslashit(home_url());
						} else {
							$home_url = trailingslashit(home_url()) . '?';
						}
						$login_url = $home_url.$aio_wp_security->configs->get_value('aiowps_login_page_slug');
					} else {
						$login_url = wp_login_url();
					}

					//Inspect the payload and do redirect to login page with a msg and redirect url
					$logout_payload = (is_multisite() ? get_site_transient('aiowps_logout_payload') : get_transient('aiowps_logout_payload'));
					if (!empty($logout_payload['redirect_to'])) {
						$login_url = AIOWPSecurity_Utility::add_query_data_to_url($login_url, 'redirect_to', $logout_payload['redirect_to']);
					}
					if (!empty($logout_payload['msg'])) {
						$login_url .= '&'.$logout_payload['msg'];
					}
					if (!empty($login_url)) {
						AIOWPSecurity_Utility::redirect_to_url($login_url);
					}
				}
			}
		}

		/**
		 * Check whether current admin page is Admin Dashboard page or not.
		 *
		 * @return boolean True if Admin Dashboard page, Otherwise false.
		 */
		public function is_admin_dashboard_page() {
			if (isset($this->is_admin_dashboard_page)) {
				return $this->is_admin_dashboard_page;
			}
			global $pagenow;
			$this->is_admin_dashboard_page = 'index.php' == $pagenow;
			return $this->is_admin_dashboard_page;
		}

		/**
		 * Check whether current admin page is plugin page or not.
		 *
		 * @return boolean True if Admin Plugin page, Otherwise false.
		 */
		public function is_plugin_admin_page() {
			if (isset($this->is_plugin_admin_page)) {
				return $this->is_plugin_admin_page;
			}
			global $pagenow;
			$this->is_plugin_admin_page = 'plugins.php' == $pagenow;
			return $this->is_plugin_admin_page;
		}

		/**
		 * Check whether current admin page is All-In-One Security admin page or not.
		 *
		 * @return boolean True if All-In-One Security admin page, Otherwise false.
		 */
		public function is_aiowps_admin_page() {
			if (isset($this->is_aiowps_admin_page)) {
				return $this->is_aiowps_admin_page;
			}
			global $pagenow;
			$this->is_aiowps_admin_page = ('admin.php' == $pagenow && isset($_GET['page']) && false !== strpos($_GET['page'], AIOWPSEC_MENU_SLUG_PREFIX));
			return $this->is_aiowps_admin_page;
		}

		/**
		 * Check whether current admin page is Google reCAPTCHA tab page or not.
		 *
		 * @return boolean True if Google reCAPTCHA tab page, Otherwise false.
		 */
		public function is_aiowps_google_recaptcha_tab_page() {
			if (isset($this->is_aiowps_google_recaptcha_tab_page)) {
				return $this->is_aiowps_google_recaptcha_tab_page;
			}
			global $pagenow;
			$this->is_aiowps_google_recaptcha_tab_page = ('admin.php' == $pagenow
															&& isset($_GET['page'])
															&& 'aiowpsec_brute_force' == $_GET['page']
															&& isset($_GET['tab'])
															&& 'captcha-settings' == $_GET['tab']
			);
			return $this->is_aiowps_google_recaptcha_tab_page;
		}
		
		/**
		 * Set pagenow global variable to wp-login.php for renamed login page
		 *
		 * @return void
		 */
		public function set_pagenow_for_renamed_loginpage() {
			global $pagenow;
			if ('1' == $this->configs->get_value('aiowps_enable_rename_login_page')) {
				include_once(AIO_WP_SECURITY_PATH . '/classes/wp-security-process-renamed-login-page.php');
				$login_slug = $this->configs->get_value('aiowps_login_page_slug');
				if (AIOWPSecurity_Process_Renamed_Login_Page::is_renamed_login_page_requested($login_slug)) {
					//wp-login.php pagenow variable required in determine_locale method for language change to work by login page dropdown
					$pagenow = 'wp-login.php';
				}
			}
		}

		/**
		 * Invokes all functions attached to action hook aiowps_force_logout_check
		 *
		 * @return void
		 */
		public function do_action_force_logout_check() {
			do_action('aiowps_force_logout_check');
		}

		/**
		 * Check AIOS_DISABLE_LOGIN_LOCKOUT constant value
		 *
		 * @return boolean True if the AIOS_DISABLE_LOGIN_LOCKOUT constant defined with true value, otherwise false.
		 */
		public function is_login_lockdown_by_const() {
			return defined('AIOS_DISABLE_LOGIN_LOCKOUT') && AIOS_DISABLE_LOGIN_LOCKOUT;
		}

		/**
		 * Instantiate Ajax handling class
		 */
		private function load_ajax_handler() {
			include_once(AIO_WP_SECURITY_PATH.'/classes/wp-security-ajax.php');
			AIOWPSecurity_Ajax::get_instance();
		}

		/**
		 * Append salt postfixes.
		 *
		 * @param string $salt   Salt
		 * @param string $scheme Authentication scheme. Values include 'auth', 'secure_auth', 'logged_in', and 'nonce'.
		 * @return new salt
		 */
		public function salt($salt, $scheme) {
			$salt_postfixes = $this->configs->get_site_value('aiowps_salt_postfixes');
			if (!isset($salt_postfixes[$scheme])) {
				AIOWPSecurity_Utility::change_salt_postfixes();
				$salt_postfixes = $this->configs->get_site_value('aiowps_salt_postfixes');
			}

			if (empty($salt_postfixes[$scheme])) {
				return $salt;
			}

			return $salt.$salt_postfixes[$scheme];
		}

	} // End of class

}//End of class not exists check

$GLOBALS['aio_wp_security'] = new AIO_WP_Security();
