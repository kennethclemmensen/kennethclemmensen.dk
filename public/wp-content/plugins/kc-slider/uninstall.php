<?php
namespace KCSlider;
if(!defined('WP_UNINSTALL_PLUGIN')) die();
require_once 'includes/KCSlider.php';
require_once 'includes/KCSliderSettings.php';
use KCSlider\Includes\KCSlider;
use KCSlider\Includes\KCSliderSettings;
$kcSliderSettings = new KCSliderSettings();
$posts = get_posts(['post_type' => KCSlider::SLIDES, 'posts_per_page' => -1]);
foreach($posts as $post) wp_delete_post($post->ID, true);
delete_option($kcSliderSettings->getOptionName());