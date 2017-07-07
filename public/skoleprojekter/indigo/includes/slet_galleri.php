<?php
session_start();
if($_SESSION['admin'] == true) {
	include("db.php");
	//sletter galleriet
	$slet_galleri = mysqli_query($db, "DELETE FROM skoleprojekter_indigo_galleri WHERE galleri_id='$_GET[galleri_id]'");
	$slet_billede = mysqli_query($db, "SELECT sti FROM skoleprojekter_indigo_billeder WHERE galleri_id='$_GET[galleri_id]'");
	while($data_slet_billede = mysqli_fetch_assoc($slet_billede)) {
		//sletter billederne fra serveren og databasen
		unlink("../billeder/galleri_billeder/".$data_slet_billede['sti']);
		mysqli_query($db, "DELETE FROM skoleprojekter_indigo_billeder WHERE galleri_id='$_GET[galleri_id]'");
	}
	header("location: ../admin/admin.php?page=ret_galleri");
}
?>