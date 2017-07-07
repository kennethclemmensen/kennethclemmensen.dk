<?php
session_start();
if($_SESSION['admin'] == true) {
	include("db.php");
	$slet_udsmykning_billede = mysqli_query($db, "SELECT sti FROM skoleprojekter_indigo_billeder WHERE udsmykning_id='$_GET[udsmykning_id]'");
	while($data_udsmykning_billede = mysqli_fetch_assoc($slet_udsmykning_billede)) {
		unlink("../billeder/udsmykninger/".$data_udsmykning_billede['sti']);
		mysqli_query($db, "DELETE FROM skoleprojekter_indigo_billeder WHERE udsmykning_id='$_GET[udsmykning_id]'");
	}
	$slet_udsmykning = mysqli_query($db, "DELETE FROM skoleprojekter_indigo_udsmykning WHERE udsmykning_id='$_GET[udsmykning_id]'");
	header("location: ../admin/admin.php?page=ret_udsmykning");
}
?>