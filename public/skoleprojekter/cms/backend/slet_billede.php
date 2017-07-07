<?php
include("../includes/db.php");
$sql_slet = "SELECT sti, nyhed_id, galleri_id FROM skoleprojekter_netavisen_billeder WHERE billede_id='$_GET[billede_id]'";
$resultat_slet = mysqli_query($db, $sql_slet);
while($data_slet = mysqli_fetch_assoc($resultat_slet)) {
	if($data_slet['nyhed_id'] != 0) {
		//hvis billedet er til en nyhed
		$sti = "../billeder/nyheder/".$data_slet['sti'];
		unlink($sti);
	}
	if($data_slet['galleri_id'] != 0) {
		//hvis billedet er til et galleri
		$sti = "../billeder/galleri/".$data_slet['sti'];
		unlink($sti);
	}
}
$sql_slet_billede = "DELETE FROM skoleprojekter_netavisen_billeder WHERE billede_id='$_GET[billede_id]'";
$resultat_slet_billede = mysqli_query($db, $sql_slet_billede);
header("location: journalist.php?page=slet_billeder"); 
?>