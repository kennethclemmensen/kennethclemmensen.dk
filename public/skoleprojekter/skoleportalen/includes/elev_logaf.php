<?php
session_start();
if(isset($_SESSION['elev']) && $_SESSION['elev'] == true) {
	unset($_SESSION['elev']);
	unset($_SESSION['navn']);
	unset($_SESSION['elev_id']);
	header("location: ../index.php");
}
?>