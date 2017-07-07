<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['laerer'] == true) {
	include("db.php");
	//sletter eleven og billedet
	$sql_elev_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_elever WHERE elev_id='$_GET[elev_id]'");
	$data_elev_billede = mysqli_fetch_assoc($sql_elev_billede);
	unlink("../billeder/elever/".$data_elev_billede['billede']);
	mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_elever WHERE elev_id='$_GET[elev_id]'");
	//sletter artiklen og billedet som h�rer til
	$sql_artikel_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_artikler WHERE elev_id='$_GET[elev_id]'");
	$data_artikel_billede = mysqli_fetch_assoc($sql_artikel_billede);
	unlink("../billeder/artikler/".$data_artikel_billede['billede']);
	mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_artikler WHERE elev_id='$_GET[elev_id]'");
	if($_SESSION['admin'] == true) {
		header("location: ../admin/admin.php?page=rediger_elev");
	} else if ($_SESSION['laerer'] == true) {
		header("location: ../sider/laerer.php?page=rediger_elev");
	}
}
?>