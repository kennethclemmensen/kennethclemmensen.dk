<?php
include("db.php");
$vis_billede = mysqli_query($db, "SELECT sti FROM skoleprojekter_cmk_billeder WHERE billede_id='$_GET[billede_id]'");
while($data_billede = mysqli_fetch_assoc($vis_billede)) {
	unlink("../billeder/mobler/".$data_billede['sti']);
}
$slet = mysqli_query($db, "DELETE FROM skoleprojekter_cmk_billeder WHERE billede_id='$_GET[billede_id]'");
header("location: ../admin/admin.php?page=slet_billeder");
?>