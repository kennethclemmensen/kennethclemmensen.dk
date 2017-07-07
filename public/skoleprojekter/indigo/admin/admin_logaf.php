<?php
session_start();
if($_SESSION['admin'] == true) {
	unset($_SESSION['admin']);
	header("location: index.php");
}
?>