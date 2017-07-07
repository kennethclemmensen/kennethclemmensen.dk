<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['elev'] == true) {
	include("db.php");
	//sletter artiklen og billedet
	$sql_artikel_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_artikler WHERE artikel_id='$_GET[artikel_id]'");
	$data_artikel_billede = mysqli_fetch_assoc($sql_artikel_billede);
	unlink("../billeder/artikler/".$data_artikel_billede['billede']);
	mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_artikler WHERE artikel_id='$_GET[artikel_id]'");
	if($_SESSION['admin'] == true) {
		header("location: ../admin/admin.php?page=rediger_artikel");
	} else if ($_SESSION['elev'] == true) {
		header("location: ../sider/elev.php?page=ret_artikel");
	}
}
?>