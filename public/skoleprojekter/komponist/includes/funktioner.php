<?php
$side = $_GET['page'];
function style($var) {
	global $side;
	if($side == $var) {
		return "id='menu_link'";
	}
}
?>