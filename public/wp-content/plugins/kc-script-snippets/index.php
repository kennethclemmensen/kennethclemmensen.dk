<?php
/*
Plugin Name: KC Script Snippets
Description: Add script snippets to the site
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://www.kennethclemmensen.dk
*/
namespace KCScriptSnippets;
if(!defined('ABSPATH')) die();
require_once 'includes/KCScriptSnippets.php';
$plugin = new Includes\KCScriptSnippets();
$plugin->execute();