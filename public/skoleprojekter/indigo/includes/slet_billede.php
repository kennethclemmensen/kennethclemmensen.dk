<?php
session_start();
if($_SESSION['admin'] == true) {
	include("db.php");
	$slet = mysqli_query($db, "SELECT galleri_id, sti FROM skoleprojekter_indigo_billeder WHERE billede_id='$_GET[billede_id]'");
	$dataSlet = mysqli_fetch_assoc($slet);
	//hvis billedet er til et galleri
	if($dataSlet['galleri_id'] != 0) {
		unlink("../billeder/galleri_billeder/".$dataSlet['sti']);
	} else {
		//hvis billedet er til en udsmykning
		unlink("../billeder/udsmykninger/".$dataSlet['sti']);
	}
	mysqli_query($db, "DELETE FROM skoleprojekter_indigo_billeder WHERE billede_id='$_GET[billede_id]'");
	header("location: ../admin/admin.php?page=slet_billeder");
}
?>