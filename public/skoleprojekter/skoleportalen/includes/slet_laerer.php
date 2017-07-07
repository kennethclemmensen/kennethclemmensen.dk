<?php
session_start();
if($_SESSION['admin'] == true) {
	include("db.php");
	//sletter billedet af l�reren
	$sql_slet_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_laererer WHERE laerer_id='$_GET[laerer_id]'");
	$data_slet_billede = mysqli_fetch_assoc($sql_slet_billede);
	unlink("../billeder/laerere/".$data_slet_billede['billede']);
	//sletter l�reren
	$slet_laerer = mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_laererer WHERE laerer_id='$_GET[laerer_id]'");
	header("location: ../admin/admin.php?page=rediger_laerer");
}
?>