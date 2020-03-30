<?php
/*
Plugin Name: KC
Description: The plugin for the site
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://kennethclemmensen.dk
*/
namespace KC;
if(!defined('ABSPATH')) die();
require_once 'Api/KCAPI.php';
$plugin = new Api\KCAPI();
$plugin->execute();
require_once 'Files/FDWC.php';
$plugin = new Files\FDWC();
$plugin->activate(__FILE__);
$plugin->execute();
require_once 'Gallery/KCGallery.php';
$plugin = new Gallery\KCGallery();
$plugin->activate(__FILE__);
$plugin->execute();
require_once 'Slider/KCSlider.php';
$plugin = new Slider\KCSlider();
$plugin->execute();