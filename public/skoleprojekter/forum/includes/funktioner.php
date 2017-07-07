<?php
$side = $_GET['page'];
function style($var) {
	global $side;
	if($side == $var) {
		return "id='menu_link'";
	}
}
function brugere_online($kolonne, $tabelnavn, $kolonne2, $vaerdi) {
	global $db;
	$sql = mysqli_query($db, "SELECT ".$kolonne." FROM ".$tabelnavn." WHERE ".$kolonne2."='".$vaerdi."'");
	$antal = mysqli_num_rows($sql);
	if($antal != 0) {
		if($antal == 1) {
			return "<p>1 bruger online</p>";
		} else if($antal > 1) {
			return "<p>".$antal." brugere online</p>";
		}
	} else {
		return "<p>0 brugere online</p>";
	}
}
?>