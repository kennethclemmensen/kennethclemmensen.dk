<?php
namespace KCGallery;
if(!defined('WP_UNINSTALL_PLUGIN')) die();
require_once 'includes/KCGallery.php';
global $wpdb;
$query = 'DELETE p, tr, pm FROM '.$wpdb->posts.' p LEFT JOIN '.$wpdb->term_relationships.' tr ON p.ID = tr.object_id LEFT JOIN '.$wpdb->postmeta.' pm ON p.ID = pm.post_id WHERE p.post_type = "%s"';
$wpdb->query($wpdb->prepare($query, Includes\KCGallery::GALLERY));
$query = ' DELETE p, tr, pm FROM '.$wpdb->posts.' p LEFT JOIN '.$wpdb->term_relationships.' tr ON p.ID = tr.object_id LEFT JOIN '.$wpdb->postmeta.' pm ON p.ID = pm.post_id WHERE p.post_type = "%s"';
$wpdb->query($wpdb->prepare($query, Includes\KCGallery::PHOTO));
unregister_post_type(Includes\KCGallery::GALLERY);
unregister_post_type(Includes\KCGallery::PHOTO);