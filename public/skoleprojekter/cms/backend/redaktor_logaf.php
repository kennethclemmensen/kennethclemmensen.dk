<?php
session_start();
unset($_SESSION['redaktor']);
unset($_SESSION['redaktor_navn']);
header("location: login.php");
?>