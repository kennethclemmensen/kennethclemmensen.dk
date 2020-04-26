<?php
/*
Plugin Name: KC
Description: The plugin for the site
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://kennethclemmensen.dk
Requires at least: 5.4
Requires PHP: 7.3
*/
namespace KC;
if(!defined('ABSPATH')) wp_die();
require_once 'Core/IModule.php';
require_once 'Api/Api.php';
require_once 'Api/ApiController.php';
require_once 'Core/Constant.php';
require_once 'Core/CustomPostType.php';
require_once 'Core/PluginActivator.php';
require_once 'Files/Files.php';
require_once 'Gallery/Gallery.php';
require_once 'Security/Security.php';
require_once 'Slider/Slider.php';
require_once 'Utils/PluginHelper.php';
$pluginActivator = new Core\PluginActivator();
$pluginActivator->activate(__FILE__);
$pluginActivator->run();