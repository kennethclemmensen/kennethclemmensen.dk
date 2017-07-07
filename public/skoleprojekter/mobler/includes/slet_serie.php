<?php
include("db.php");
$slet = mysqli_query($db, "DELETE FROM skoleprojekter_cmk_mobelserie WHERE serie_id='$_GET[serie_id]'");
header("location: ../admin/admin.php?page=rediger_mobelserie");
?>