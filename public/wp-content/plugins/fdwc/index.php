<?php
/*
Plugin Name: File Download With Counter
Description: Download files and counts the number of downloads
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://www.kennethclemmensen.dk
*/
if(!defined('ABSPATH')) die();
require_once 'includes/class-fdwc.php';
$plugin = new \FDWC\Includes\FDWC();
$plugin->activate(__FILE__);
$plugin->execute();