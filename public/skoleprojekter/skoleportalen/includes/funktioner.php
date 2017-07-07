<?php
$side = $_GET['page'];
function style($var) {
	global $side;
	if($side == $var) {
		return "class='menu_link'";
	}
}
?>