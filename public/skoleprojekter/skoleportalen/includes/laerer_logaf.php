<?php
session_start();
if($_SESSION['laerer'] == true) {
	unset($_SESSION['laerer']);
	header("location: ../index.php");
}
?>