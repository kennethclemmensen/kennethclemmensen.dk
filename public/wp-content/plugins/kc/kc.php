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
require_once 'Core/PluginActivator.php';
$pluginActivator = new Core\PluginActivator();
$pluginActivator->activate(__FILE__);
$pluginActivator->run();