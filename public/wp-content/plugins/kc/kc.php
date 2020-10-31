<?php
/*
Plugin Name: KC
Description: The plugin for the site
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://kennethclemmensen.dk
Requires at least: 5.5.3
Requires PHP: 7.4
*/
namespace KC;
if(!defined('ABSPATH')) wp_die();
require_once 'Core/IModule.php';
$files = glob(__DIR__.'/**/*.php');
foreach($files as $file) require_once $file;
$pluginActivator = new Core\PluginActivator();
$pluginActivator->activate(__FILE__);
$pluginActivator->run();