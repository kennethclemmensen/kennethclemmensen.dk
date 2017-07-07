<?php
session_start();
if($_SESSION['admin'] == true) {
	include("db.php");
	//sletter kategorien
	$slet_kategori = mysqli_query($db, "DELETE FROM skoleprojekter_indigo_cv_kategori WHERE kategori_id='$_GET[kategori_id]'");
	//sletter cv�et som h�rer til
	$slet_cv = mysqli_query($db, "DELETE FROM skoleprojekter_indigo_cv WHERE kategori_id='$_GET[kategori_id]'");
	header("location: ../admin/admin.php?page=ret_cv_kategori");
}
?>