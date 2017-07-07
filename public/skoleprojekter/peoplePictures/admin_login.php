<?php
include("db.php");
session_start();
if($_SESSION["admin"]==true) {
	header("location: admin.php");
}
if(isset($_POST["brugernavn"], $_POST["password"])) {
	$brugernavn = $_POST["brugernavn"];
	$password   = $_POST["password"];
	$hentBruger = mysqli_query($db, "SELECT brugernavn, password FROM skoleprojekter_peoplepictures_admin WHERE brugernavn='$brugernavn' AND password='$password'");
	if(mysqli_num_rows($hentBruger)==1) {
		header("location: admin.php");
		$_SESSION["admin"] = true;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/admin_style.css" media="screen" />
		<title>People Pictures - login</title>
		<script language="javascript">
		function valider_admin() {
			if(document.login.brugernavn.value=="" ||
			document.login.brugernavn.value=="") {
				alert("Begge felter skal udfyldes f�r du kan logge ind");
				return false;
			}
		}
		</script>
	</head>
	<body>
		<div id="admin_login">
			<form action="" method="post" name="login" onsubmit="return valider_admin();">
				<p><label for="brugernavn">Brugernavn</label></p><input type="text" name="brugernavn" id="login_felt" />
				<p><label for="password">Password</label></p><input type="password" name="password" id="login_felt" />
				<input type="submit" value="Login" id="login_button" />
			</form>
		</div>
	</body>
</html>