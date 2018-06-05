<?php
namespace KCSlider;
if(!defined('WP_UNINSTALL_PLUGIN')) die();
$posts = get_posts(['post_type' => 'slides', 'posts_per_page' => -1]);
foreach($posts as $post) wp_delete_post($post->ID, true);
delete_option('kc-slider-settings-group');