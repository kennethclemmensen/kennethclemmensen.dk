<?php
/*
Plugin Name: KC
Description: The plugin for the site
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://kennethclemmensen.dk
Requires at least: 5.8
Requires PHP: 8.0
Text Domain: kc
Domain Path: /languages
*/
namespace KC;
if(!defined('ABSPATH')) wp_die();
require_once 'Core/Modules/IModule.php';
require_once 'Core/Settings/ISettings.php';
$directoryIterator = new \RecursiveDirectoryIterator(__DIR__);
$iteratorIterator = new \RecursiveIteratorIterator($directoryIterator);
$files = new \RegexIterator($iteratorIterator, '/^.+\.php$/i');
foreach($files as $file) {
	$fileName = $file->getFilename();
	if($fileName !== 'kc.php' && $fileName !== 'uninstall.php') {
		require_once $file->getPathname();
	}
}
$pluginActivator = new Core\PluginActivator();
$pluginActivator->activate(__FILE__);
$pluginActivator->run();