<?php
/*
Plugin Name: KC API
Description:
Version: 1.0
Author: Kenneth Clemmensen
Author URI: https://www.kennethclemmensen.dk
*/
namespace KCAPI;
if(!defined('ABSPATH')) die();
require_once 'includes/KCAPI.php';
$plugin = new Includes\KCAPI();
$plugin->execute();