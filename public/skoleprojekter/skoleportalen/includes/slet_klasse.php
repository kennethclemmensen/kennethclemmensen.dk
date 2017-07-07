<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['laerer'] == true) {
	include("db.php");
	//sletter klassen og billedet
	$sql_klasse_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_klasser WHERE klasse_id='$_GET[klasse_id]'");
	$data_klasse_billede = mysqli_fetch_assoc($sql_klasse_billede);
	unlink("../billeder/klasser/".$data_klasse_billede['billede']);
	mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_klasser WHERE klasse_id='$_GET[klasse_id]'");
	//sletter eleverne og billederne
	$sql_elev = mysqli_query($db, "SELECT elev_id, billede FROM skoleprojekter_skoleportalen_elever WHERE klasse_id='$_GET[klasse_id]'");
	while($data_elev = mysqli_fetch_assoc($sql_elev)) {
		unlink("../billeder/elever/".$data_elev['billede']);
		//sletter artiklerne og billederne som h�rer til
		$sql_artikel_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_artikler WHERE elev_id='$data_elev[elev_id]'");
		$data_artikel_billede = mysqli_fetch_assoc($sql_artikel_billede);
		unlink("../billeder/artikler/".$data_artikel_billede['billede']);
		mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_artikler WHERE elev_id='$data_elev[elev_id]'");
	}
	mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_elever WHERE klasse_id='$_GET[klasse_id]'");
	if($_SESSION['admin'] == true) {
		header("location: ../admin/admin.php?page=rediger_klasse");
	} else if ($_SESSION['laerer'] == true) {
		header("location: ../sider/laerer.php?page=rediger_klasse");
	}
}	
?>