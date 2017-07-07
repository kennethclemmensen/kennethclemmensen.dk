<?php
include("../db.php");
$slet_kommentar = mysqli_query($db, "DELETE FROM skoleprojekter_lojstrup_kommentar WHERE kommentar_id='$_GET[kommentar_id]'");
header("location: admin.php?page=godkend_kommentar");
?>