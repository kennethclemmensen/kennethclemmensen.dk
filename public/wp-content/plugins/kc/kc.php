<?php
/*
Plugin Name: KC
Description: The plugin for the site
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://kennethclemmensen.dk
Requires at least: 5.9
Requires PHP: 8.1
Domain Path: /languages
*/
namespace KC;
if(!defined('ABSPATH')) wp_die();
require_once 'Core/Modules/BaseModule.php';
require_once 'Core/Modules/IModule.php';
require_once 'Core/Settings/ISettings.php';
$directoryIterator = new \RecursiveDirectoryIterator(__DIR__);
$recursiveIterator = new \RecursiveIteratorIterator($directoryIterator);
$files = new \RegexIterator($recursiveIterator, '/^(?!kc\.php).+\.php$/i');
foreach($files as $file) {
	require_once $file->getPathname();
}
$pluginActivator = new Core\PluginActivator();
$pluginActivator->activate(__FILE__);
$pluginActivator->run();