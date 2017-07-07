<?php
session_start();
include("db.php");
if($_SESSION["admin"]==true) {
	header("location: admin.php");
}
if(isset($_POST["admin_brugernavn"], $_POST["admin_password"])) {
	$admin_brugernavn = $_POST["admin_brugernavn"];
	$admin_password   = $_POST["admin_password"];
	$hentAdmin        = mysqli_query($db, "SELECT brugernavn, password FROM skoleprojekter_rejsesiden_admin WHERE brugernavn='$admin_brugernavn' AND password='$admin_password'");
	if(mysqli_num_rows($hentAdmin)==1) {
		$_SESSION["admin"] = true;
		header("location: admin.php");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Rejsesiden</title>
	</head>
	<body>
		<div id="admin_formular">
			<form action="" method="post">
				<label for="admin_brugernavn"><p>Brugernavn</p></label><input type="text" name="admin_brugernavn" id="felt" />
				<label for="admin_brugernavn"><p>Password</p></label><input type="password" name="admin_password" id="felt" />
				<input type="submit" value="Login" id="admin_knap" />
			</form>
		</div>
	</body>
</html>