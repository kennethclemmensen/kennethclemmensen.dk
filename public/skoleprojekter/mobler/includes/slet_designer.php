<?php
include("db.php");
$slet = mysqli_query($db, "DELETE FROM skoleprojekter_cmk_designer WHERE designer_id='$_GET[designer_id]'");
header("location: ../admin/admin.php?page=rediger_designer");
?>