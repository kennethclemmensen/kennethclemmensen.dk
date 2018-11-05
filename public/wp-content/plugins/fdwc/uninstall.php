<?php
namespace FDWC;
if(!defined('WP_UNINSTALL_PLUGIN')) die();
require_once 'includes/FDWC.php';
unregister_post_type(Includes\FDWC::FDWC_FILE);