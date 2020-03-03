<?php
/*
Plugin Name: KC Slider
Description: A slider plugin
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://kennethclemmensen.dk
*/
namespace KCSlider;
if(!defined('ABSPATH')) die();
require_once 'includes/KCSlider.php';
$plugin = new Includes\KCSlider();
$plugin->execute();