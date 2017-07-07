<?php
include("db.php");
$slet_vaerk = mysqli_query($db, "DELETE FROM skoleprojekter_komponist_vaerkliste WHERE vaerk_id='$_GET[vaerk_id]'");
header("location: ../admin/admin.php?page=rediger_vaerk");
?>