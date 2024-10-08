<?php

if (!defined('ABSPATH')) die('No direct access allowed');

if (class_exists('AIOWPSecurity_Commands')) return;

if (!trait_exists('AIOWPSecurity_Log_Commands_Trait')) require_once(AIO_WP_SECURITY_PATH.'/classes/commands/wp-security-log-commands.php');
if (!trait_exists('AIOWPSecurity_Ip_Commands_Trait')) require_once(AIO_WP_SECURITY_PATH.'/classes/commands/wp-security-ip-commands.php');
if (!trait_exists('AIOWPSecurity_Comment_Commands_Trait')) require_once(AIO_WP_SECURITY_PATH.'/classes/commands/wp-security-comment-commands.php');
if (!trait_exists('AIOWPSecurity_User_Security_Commands_Trait')) require_once(AIO_WP_SECURITY_PATH.'/classes/commands/wp-security-user-security-commands.php');
if (!trait_exists('AIOWPSecurity_Settings_Commands_Trait')) require_once(AIO_WP_SECURITY_PATH.'/classes/commands/wp-security-settings-commands.php');
if (!trait_exists('AIOWPSecurity_Files_Commands_Trait')) require_once(AIO_WP_SECURITY_PATH.'/classes/commands/wp-security-files-commands.php');
if (!trait_exists('AIOWPSecurity_Firewall_Commands_Trait')) require_once(AIO_WP_SECURITY_PATH.'/classes/commands/wp-security-firewall-commands.php');
if (!trait_exists('AIOWPSecurity_Tools_Commands_Trait')) require_once(AIO_WP_SECURITY_PATH.'/classes/commands/wp-security-tools-commands.php');
if (!trait_exists('AIOWPSecurity_File_Scan_Commands_Trait')) require_once(AIO_WP_SECURITY_PATH.'/classes/commands/wp-security-file-scan-commands.php');
class AIOWPSecurity_Commands {

	use AIOWPSecurity_Log_Commands_Trait;
	use AIOWPSecurity_Ip_Commands_Trait;
	use AIOWPSecurity_Comment_Commands_Trait;
	use AIOWPSecurity_User_Security_Commands_Trait;
	use AIOWPSecurity_Settings_Commands_Trait;
	use AIOWPSecurity_Files_Commands_Trait;
	use AIOWPSecurity_Firewall_Commands_Trait;
	use AIOWPSecurity_Tools_Commands_Trait;
	use AIOWPSecurity_File_Scan_Commands_Trait;

	/**
	 * This variable holds an instance of AIOWPSecurity_Feature_Item_Manager.
	 *
	 * @var AIOWPSecurity_Feature_Item_Manager $aiowps_feature_mgr
	 */
	private $aiowps_feature_mgr;

	/**
	 * The initializes the AIOWPS Feature Manager
	 *
	 * @return bool
	 */
	private function feature_mgr_init() {
		static $initialized = false;
		if ($initialized && !empty($this->aiowps_feature_mgr)) return true;

		$this->aiowps_feature_mgr = new AIOWPSecurity_Feature_Item_Manager();

		$initialized = true;

		return true;
	}

	/**
	 * Retrieves the feature manager object.
	 *
	 * This method initializes the feature manager if necessary and returns the
	 * AIOWPSecurity_Feature_Item_Manager instance. If the initialization fails or
	 * the feature manager object is empty, it returns a WP_Error.
	 *
	 * @return AIOWPSecurity_Feature_Item_Manager|WP_Error
	 */
	private function get_feature_mgr_object() {

		$do_init = $this->feature_mgr_init();

		if (true === $do_init && !empty($this->aiowps_feature_mgr)) return $this->aiowps_feature_mgr;

		return new WP_Error('not_initialized', __('The feature item manager could not be initialized.', 'all-in-one-wp-security-and-firewall'));
	}

	/**
	 * Get IP address of given method.
	 *
	 * @param array $data - the request data
	 *
	 * @return array|WP_Error - an array response or a WP_Error if there was an error
	 */
	public function get_ip_address_of_given_method($data) {
		$ip_method_id = $data['ip_retrieve_method'];
		$ip_retrieve_methods = AIOS_Abstracted_Ids::get_ip_retrieve_methods();
		if (isset($ip_retrieve_methods[$ip_method_id])) {
			return array(
				'ip_address' => isset($_SERVER[$ip_retrieve_methods[$ip_method_id]]) ? $_SERVER[$ip_retrieve_methods[$ip_method_id]] : '',
			);
		} else {
			return new WP_Error('aios-invalid-ip-retrieve-method', __('Invalid IP retrieve method.', 'all-in-one-wp-security-and-firewall'));
		}
		die;
	}

	/**
	 * Dismiss a notice
	 *
	 * @param array $data - the request data contains the notice to dismiss
	 *
	 * @return array
	 */
	public function dismiss_notice($data) {
		global $aio_wp_security;

		$time_now = $aio_wp_security->notices->get_time_now();
		
		if (in_array($data['notice'], array('dismissdashnotice', 'dismiss_season'))) {
			$aio_wp_security->configs->set_value($data['notice'], $time_now + (366 * 86400));
		} elseif (in_array($data['notice'], array('dismiss_page_notice_until', 'dismiss_notice'))) {
			$aio_wp_security->configs->set_value($data['notice'], $time_now + (84 * 86400));
		} elseif ('dismiss_review_notice' == $data['notice']) {
			if (empty($data['dismiss_forever'])) {
				$aio_wp_security->configs->set_value($data['notice'], $time_now + (84 * 86400));
			} else {
				$aio_wp_security->configs->set_value($data['notice'], $time_now + (100 * 365.25 * 86400));
			}
		} elseif ('dismiss_automated_database_backup_notice' == $data['notice']) {
			$aio_wp_security->delete_automated_backup_configs();
		} elseif ('dismiss_ip_retrieval_settings_notice' == $data['notice']) {
			$aio_wp_security->configs->set_value($data['notice'], 1);
		} elseif ('dismiss_ip_retrieval_settings_notice' == $data['notice']) {
			$aio_wp_security->configs->set_value('aiowps_is_login_whitelist_disabled_on_upgrade', 1);
		} elseif ('dismiss_login_whitelist_disabled_on_upgrade_notice' == $data['notice']) {
			if (isset($data['turn_it_back_on']) && '1' == $data['turn_it_back_on']) {
				$aio_wp_security->configs->set_value('aiowps_enable_whitelisting', '1');
			}
			$aio_wp_security->configs->delete_value('aiowps_is_login_whitelist_disabled_on_upgrade');
		} elseif ('dismiss_ip_blacklist_notice' == $data['notice']) {
			if (isset($data['turn_it_back_on']) && '1' == $data['turn_it_back_on']) {
				$aio_wp_security->configs->set_value('aiowps_enable_blacklisting', '1');
				AIOWPSecurity_Configure_Settings::set_blacklist_ip_firewall_configs();
				AIOWPSecurity_Configure_Settings::set_user_agent_firewall_configs();
			}
			$aio_wp_security->configs->delete_value('aiowps_is_ip_blacklist_settings_notice_on_upgrade');
		} elseif ('dismiss_firewall_settings_disabled_on_upgrade_notice' == $data['notice']) {
			$is_reactivated = (isset($data['turn_it_back_on']) && '1' == $data['turn_it_back_on']);
				if ($is_reactivated) {
					global $aiowps_firewall_config;
					$active_settings = $aio_wp_security->configs->get_value('aiowps_firewall_active_upgrade');
	
					if (!empty($active_settings)) {
						$active_settings = json_decode($active_settings);
						if (!empty($active_settings)) {
							foreach ($active_settings as $setting) {
								$aiowps_firewall_config->set_value($setting, true);
							}
						}
					}
				}

				$aio_wp_security->configs->delete_value('aiowps_firewall_active_upgrade');
		}
		

		$aio_wp_security->configs->save_config();
		
		return array();
	}


	/**
	 * This is a helper function to save settings options using key/value pairs
	 *
	 * @param array $options  - An array of options to save to the config
	 * @param null  $callback - A callback function to call when the options are saved
	 *
	 * @return bool
	 */
	public function save_settings($options, $callback = null) {
		global $aio_wp_security;

		$aiowps_feature_mgr = $this->get_feature_mgr_object();
		if (is_wp_error($aiowps_feature_mgr)) return false;


		foreach ($options as $key => $value) {
			$aio_wp_security->configs->set_value($key, $value);
		}
		//commit the config changes
		$aio_wp_security->configs->save_config();

		if (is_callable($callback)) {
			call_user_func($callback, $options);
		}

		$aiowps_feature_mgr->calculate_total_feature_points();

		return true;
	}

	/**
	 * This is a helper function to get the output feature details badge
	 *
	 * @param string $feature_id - the id of the feature we want to get the badge for
	 *
	 * @return string
	 */
	public function get_feature_details_badge($feature_id) {
		$aiowps_feature_mgr = $this->get_feature_mgr_object();
		if (is_wp_error($aiowps_feature_mgr)) return '';
		return $aiowps_feature_mgr->output_feature_details_badge($feature_id, true);
	}

	/**
	 * Retrieves the IDs and HTML content for features.
	 *
	 * This method processes an array of features and returns an associative array containing
	 * the IDs and corresponding HTML content for each feature badge.
	 *
	 * @param array $features - An array containing the features to retrieve IDs and HTML for.
	 *
	 * @return array An associative array containing the IDs and HTML content for each feature badge.
	 */
	public function get_features_id_and_html($features) {
		$result = array();
		foreach ($features as $feature) {
			$result[] = array(
				'id' => '#' . $feature . '-badge',
				'html' => $this->get_feature_details_badge($feature)
			);
		}

		return $result;
	}
}
