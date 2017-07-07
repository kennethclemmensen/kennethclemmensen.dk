<?php
include("db.php");
$slet = mysqli_query($db, "DELETE FROM skoleprojekter_cmk_nyheder WHERE nyhed_id='$_GET[nyhed_id]'");
header("location: ../admin/admin.php?page=rediger_nyhed");
?>