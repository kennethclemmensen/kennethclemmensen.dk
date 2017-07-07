<?php
include("../includes/db.php");
$sql_server_billede = "SELECT sti FROM skoleprojekter_netavisen_billeder WHERE nyhed_id='$_GET[nyhed_id]'";
$resultat_server_billede = mysqli_query($db, $sql_server_billede);
while($data_server_billede = mysqli_fetch_assoc($resultat_server_billede)) {
	$sti = "../billeder/nyheder/".$data_server_billede['sti'];
	unlink($sti);
}
$sql_slet_billede = "DELETE FROM skoleprojekter_netavisen_billeder WHERE nyhed_id='$_GET[nyhed_id]'";
$resultat_slet_billede = mysqli_query($db, $sql_slet_billede);
$sql_slet = "DELETE FROM skoleprojekter_netavisen_nyhed WHERE nyhed_id='$_GET[nyhed_id]'";
$resultat_slet = mysqli_query($db, $sql_slet);
header("location: journalist.php?page=slet_nyhed");
?>