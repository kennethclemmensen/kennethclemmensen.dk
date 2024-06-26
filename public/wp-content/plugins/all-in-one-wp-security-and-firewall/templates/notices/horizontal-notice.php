<?php if (!defined('AIO_WP_SECURITY_PATH')) die('No direct access allowed'); ?>

<?php if (!empty($button_meta) && 'review' == $button_meta) : ?>

	<div class="aiowps_ad_container updated">
	<div class="aiowps_notice_container aiowps_review_notice_container">
		<div class="aiowps_advert_content_left_extra">
			<img src="<?php echo AIO_WP_SECURITY_URL.'/images/'.$image;?>" width="100" alt="<?php _e('notice image', 'all-in-one-wp-security-and-firewall');?>" />
		</div>
		<div class="aiowps_advert_content_right">
			<p>
				<?php echo $text; ?>
			</p>
					
			<?php if (!empty($button_link)) { ?>
				<div class="aiowps_advert_button_container">
					<a class="button button-primary" href="<?php esc_attr_e($button_link, 'all-in-one-wp-security-and-firewall');?>" target="_blank" onclick="jQuery(this).closest('.aiowps_ad_container').slideUp(); jQuery.post(ajaxurl, {action: 'aios_ajax', subaction: 'dismiss_notice', nonce: '<?php echo wp_create_nonce('wp-security-ajax-nonce'); ?>', data: { notice: '<?php echo $dismiss_time;?>', dismiss_forever: '1'}});">
						<?php _e('Ok, you deserve it', 'all-in-one-wp-security-and-firewall'); ?>
					</a>
					<div class="dashicons dashicons-calendar"></div>
					<a class="aiowps_notice_link" href="#" onclick="jQuery(this).closest('.aiowps_ad_container').slideUp(); jQuery.post(ajaxurl, {action: 'aios_ajax', subaction: 'dismiss_notice', nonce: '<?php echo wp_create_nonce('wp-security-ajax-nonce'); ?>', data: { notice: '<?php echo $dismiss_time;?>', dismiss_forever: '0'}});">
						<?php _e('Maybe later', 'all-in-one-wp-security-and-firewall'); ?>
					</a>
					<div class="dashicons dashicons-no-alt"></div>
					<a class="aiowps_notice_link" href="#" onclick="jQuery(this).closest('.aiowps_ad_container').slideUp(); jQuery.post(ajaxurl, {action: 'aios_ajax', subaction: 'dismiss_notice', nonce: '<?php echo wp_create_nonce('wp-security-ajax-nonce'); ?>', data: { notice: '<?php echo $dismiss_time;?>', dismiss_forever: '1'}});">
						<?php _e('Never', 'all-in-one-wp-security-and-firewall'); ?>
					</a>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php else : ?>

<div class="aiowps_ad_container updated">
	<div class="aiowps_notice_container">
		<div class="aiowps_advert_content_left">
			<img src="<?php echo AIO_WP_SECURITY_URL.'/images/'.$image;?>" width="60" height="60" alt="<?php _e('notice image', 'all-in-one-wp-security-and-firewall');?>" />
		</div>
		<div class="aiowps_advert_content_right">
			<h3 class="aiowps_advert_heading">
				<?php
					if (!empty($prefix)) echo $prefix.' ';
					echo $title;
				?>
				<div class="aiowps_advert_dismiss">
				<?php if (!empty($dismiss_time)) { ?>
					<a href="#" onclick="jQuery(this).closest('.aiowps_ad_container').slideUp(); jQuery.post(ajaxurl, {action: 'aios_ajax', subaction: 'dismiss_notice', nonce: '<?php echo wp_create_nonce('wp-security-ajax-nonce'); ?>', data: { notice: '<?php echo $dismiss_time;?>'}});"><?php _e('Dismiss', 'all-in-one-wp-security-and-firewall'); ?></a>
				<?php } else { ?>
					<a href="#" onclick="jQuery(this).closest('.aiowps_ad_container').slideUp();"><?php _e('Dismiss', 'all-in-one-wp-security-and-firewall'); ?></a>
				<?php } ?>
				</div>
			</h3>
			<p>
				<?php
					echo $text;

					if (isset($discount_code)) echo ' <b>' . $discount_code . '</b>';
					
					if (!empty($button_link) && !empty($button_meta)) {
				?>
				<a class="aiowps_notice_link" href="<?php esc_attr_e($button_link, 'all-in-one-wp-security-and-firewall');?>"><?php
						if ('updraftcentral' == $button_meta) {
							_e('Get UpdraftCentral', 'all-in-one-wp-security-and-firewall');
						} elseif ('updraftplus' == $button_meta) {
							_e('Get UpdraftPlus', 'all-in-one-wp-security-and-firewall');
						} elseif ('wp-optimize' == $button_meta) {
							_e('Get WP-Optimize', 'all-in-one-wp-security-and-firewall');
						} elseif ('all-in-one-wp-security-and-firewall' == $button_meta) {
							_e('Get Premium.', 'all-in-one-wp-security-and-firewall');
						} elseif ('signup' == $button_meta) {
							_e('Sign up', 'all-in-one-wp-security-and-firewall');
						} elseif ('go_there' == $button_meta) {
							_e('Go there', 'all-in-one-wp-security-and-firewall');
						} elseif ('learn_more' == $button_meta) {
							_e('Learn more', 'all-in-one-wp-security-and-firewall');
						} else {
							_e('Read more', 'all-in-one-wp-security-and-firewall');
						}
					?></a>
				<?php
					}
				?>
			</p>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php

endif;