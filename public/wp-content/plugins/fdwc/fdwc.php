<?php
/*
Plugin Name: File Download With Counter
Description: Download files and counts the number of downloads
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://kennethclemmensen.dk
*/
namespace FDWC;
if(!defined('ABSPATH')) die();
require_once 'includes/FDWC.php';
$plugin = new Includes\FDWC();
$plugin->activate(__FILE__);
$plugin->execute();