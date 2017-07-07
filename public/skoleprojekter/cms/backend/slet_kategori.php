<?php
include("../includes/db.php");
$sql_slet_kategori = "DELETE FROM skoleprojekter_netavisen_menu WHERE menu_id='$_GET[menu_id]'";
$resultat_slet_kategori = mysqli_query($db, $sql_slet_kategori);
$sql_slet_nyhed = "DELETE FROM skoleprojekter_netavisen_nyhed WHERE menu_id='$_GET[menu_id]'";
$resultat_slet_nyhed = mysqli_query($db, $sql_slet_nyhed);
if($resultat_slet_kategori == true && $resultat_slet_nyhed == true) {
	header("location: redaktor.php?page=slet_kategori");
}
?>