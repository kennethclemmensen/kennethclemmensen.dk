<?php
include("db.php");
$vis_billede = mysqli_query($db, "SELECT sti FROM skoleprojekter_cmk_billeder WHERE mobel_id='$_GET[mobel_id]'");
while($data_billede = mysqli_fetch_assoc($vis_billede)) {
	unlink("../billeder/mobler/".$data_billede['sti']);
}
$slet = mysqli_query($db, "DELETE FROM skoleprojekter_cmk_mobel WHERE mobel_id='$_GET[mobel_id]'");
$slet_billede = mysqli_query($db, "DELETE FROM skoleprojekter_cmk_billeder WHERE mobel_id='$_GET[mobel_id]'");
header("location: ../admin/admin.php?page=rediger_mobel");
?>