<?php
/*
Plugin Name: KC Slider
Description: A slider plugin
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://www.kennethclemmensen.dk
*/
namespace KCSlider;
if(!defined('ABSPATH')) die();
require_once 'includes/class-kc-slider.php';
$plugin = new Includes\KCSlider();
$plugin->activate(__FILE__);
$plugin->execute();