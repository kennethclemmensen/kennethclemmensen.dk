<?php
session_start();
unset($_SESSION['journalist']);
unset($_SESSION['journalist_navn']);
header("location: login.php");
?>