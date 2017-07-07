<?php
include("db.php");
$slet_ikke_godkendt_kommentar = mysqli_query($db, "DELETE FROM skoleprojekter_bixen_kommentar WHERE id='$_GET[id]'");
header("location: administrator.php?page=godkend_kommentarer");
?>