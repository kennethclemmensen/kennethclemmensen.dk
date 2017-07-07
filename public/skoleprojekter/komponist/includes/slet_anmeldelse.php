<?php
include("db.php");
$slet = mysqli_query($db, "DELETE FROM skoleprojekter_komponist_anmeldelser WHERE anmeldelse_id='$_GET[anmeldelse_id]'");
header("location: ../admin/admin.php?page=godkend_anmeldelse");
?>