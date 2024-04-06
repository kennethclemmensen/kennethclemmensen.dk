<?php
/*
Plugin Name: KC
Description: The plugin for the site
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://kennethclemmensen.dk
Requires at least: 6.5
Requires PHP: 8.3
Domain Path: /languages
*/
namespace KC;
if(!defined('ABSPATH')) wp_die();
require_once 'Core/Api/BaseApi.php';
require_once 'Core/Modules/IModule.php';
require_once 'Core/Settings/BaseSettings.php';
$directoryIterator = new \RecursiveDirectoryIterator(__DIR__);
$recursiveIterator = new \RecursiveIteratorIterator($directoryIterator);
$files = new \RegexIterator($recursiveIterator, '/^(?!kc\.php).+\.php$/i');
foreach($files as $file) {
	require_once $file->getPathname();
}
$pluginActivator = new Core\PluginActivator();
$pluginActivator->activate(__FILE__);
$pluginActivator->run();