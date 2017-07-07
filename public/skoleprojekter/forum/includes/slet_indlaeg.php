<?php
include("db.php");
$sql_indlaeg = mysqli_query($db, "SELECT indlaeg_id FROM skoleprojekter_forum_indlaeg WHERE indlaeg_id='$_GET[indlaeg_id]'");
while($data_indlaeg = mysqli_fetch_assoc($sql_indlaeg)) {
	//sletter kommentarene til indl�gget
	$slet_kommentar = mysqli_query($db, "DELETE FROM skoleprojekter_forum_kommentar WHERE indlaeg_id='$data_indlaeg[indlaeg_id]'");
}
//sletter indl�gget
$slet_indlaeg = mysqli_query($db, "DELETE FROM skoleprojekter_forum_indlaeg WHERE indlaeg_id='$_GET[indlaeg_id]'");
header("location: ../admin/admin.php?page=slet_indlaeg");
?>