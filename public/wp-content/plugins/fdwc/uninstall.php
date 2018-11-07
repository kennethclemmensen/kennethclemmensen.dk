<?php
namespace FDWC;
if(!defined('WP_UNINSTALL_PLUGIN')) die();
require_once 'includes/FDWC.php';
use FDWC\Includes\FDWC;
$posts = get_posts(['post_type' => FDWC::FDWC_FILE, 'posts_per_page' => -1]);
foreach($posts as $post) wp_delete_post($post->ID, true);
global $wpdb;
$fdwc = new FDWC();
$taxonomy = $fdwc->getFileTypeTaxonomy();
$terms = $wpdb->get_results($wpdb->prepare('SELECT t.name, t.term_id FROM '.$wpdb->terms.' AS t INNER JOIN '.$wpdb->term_taxonomy.' AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = "%s"', $taxonomy));
foreach($terms as $term) wp_delete_term($term->term_id, $taxonomy);
unregister_taxonomy_for_object_type($taxonomy, FDWC::FDWC_FILE);
unregister_taxonomy($taxonomy);
unregister_post_type(FDWC::FDWC_FILE);