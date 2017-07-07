<?php
include("db.php");
session_start();
mysqli_query($db, "UPDATE skoleprojekter_forum_bruger SET logget_ind='0' WHERE email='$_SESSION[brugernavn]'");
unset($_SESSION['bruger']);
unset($_SESSION['brugernavn']);
unset($_SESSION['kodeord']);
header("location: ../index.php");
?>