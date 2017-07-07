<?php
include("db.php");
$sletKategori = mysqli_query($db, "DELETE FROM skoleprojekter_forum_kategorier WHERE kategori_id='$_GET[kategori_id]'");
$sql_indlaeg = mysqli_query($db, "SELECT indlaeg_id FROM skoleprojekter_forum_indlaeg WHERE kategori_id='$_GET[kategori_id]'");
while($data_indlaeg = mysqli_fetch_assoc($sql_indlaeg)) {
	//sletter indl�g fra kategorien
	$sletIndlaeg = mysqli_query($db, "DELETE FROM skoleprojekter_forum_indlaeg WHERE kategori_id='$_GET[kategori_id]'");
	//sletter kommentarer
	$sletKommentar = mysqli_query($db, "DELETE FROM skoleprojekter_forum_kommentar WHERE indlaeg_id='$data_indlaeg[indlaeg_id]'");
}
header("location: ../admin/admin.php?page=ret_kategori");
?>