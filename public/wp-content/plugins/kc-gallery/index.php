<?php
/*
Plugin Name: KC Gallery
Description: Shows photos in galleries using <a href="http://lokeshdhakar.com/projects/lightbox2/">Lightbox</a>
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://www.kennethclemmensen.dk
*/
namespace KCGallery;
if(!defined('ABSPATH')) die();
require_once 'includes/class-kc-gallery.php';
$plugin = new Includes\KCGallery();
$plugin->activate(__FILE__);
$plugin->execute();