<?php if (!defined('ABSPATH')) die('No direct access.'); ?>
<div class="postbox aio_hidden" data-template="wp-rest-api">
	<h3 class="hndle"><label for="title"><?php _e('WP REST API settings', 'all-in-one-wp-security-and-firewall'); ?></label></h3>
	<div class="inside">
		<div>
			<div id="disallow-unauthorised-requests-badge">
				<?php
				//Display security info badge
				$aiowps_feature_mgr->output_feature_details_badge("disallow-unauthorised-requests");
				?>
			</div>
				<div class="aio_blue_box">
					<?php
					echo '<p>'.__('This feature allows you to block WordPress REST API access for unauthorized requests.', 'all-in-one-wp-security-and-firewall').'</p>';
					echo '<p>'.__('When enabled this feature will only allow REST requests to be processed if the user is logged in.', 'all-in-one-wp-security-and-firewall').'</p>';
					?>
				</div>
				<div class="aio_orange_box">
					<p>
						<?php
						echo __('Beware that if you are using other plugins which have registered REST endpoints (e.g Contact Form 7, WooCommerce), then this feature will also block REST requests used by these plugins if the user is not logged in.', 'all-in-one-wp-security-and-firewall').' '.__('It is recommended that you leave this feature disabled if you want uninterrupted functionality for such plugins.', 'all-in-one-wp-security-and-firewall');
						?>
					</p>
				</div>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e('Disallow unauthorized REST requests', 'all-in-one-wp-security-and-firewall'); ?>:</th>
						<td>
							<div class="aiowps_switch_container">
								<?php AIOWPSecurity_Utility_UI::setting_checkbox(__('Enable this to stop REST API access for non-logged in requests.', 'all-in-one-wp-security-and-firewall'), 'aiowps_disallow_unauthorized_rest_requests', '1' == $aio_wp_security->configs->get_value('aiowps_disallow_unauthorized_rest_requests')); ?>
							</div>
						</td>
					</tr>
				</table>
		</div>
	</div>
</div>
