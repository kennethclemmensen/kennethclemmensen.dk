<?php
session_start();
if($_SESSION['admin'] == true) {
	header("location: admin.php");
}
include("../db.php");
if(isset($_POST['brugernavn']) && isset($_POST['password'])) {
	$brugernavn = $_POST['brugernavn'];
	$password = $_POST['password'];
	$hentAdmin = mysqli_query($db, "SELECT brugernavn, password FROM skoleprojekter_lojstrup_admin WHERE brugernavn='$brugernavn' AND password='$password'");
	if(mysqli_num_rows($hentAdmin) == 1) {
		header("location: admin.php");
		$_SESSION['admin'] = true;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" href="../style/style.css" media="screen" />
		<title>L&oslash;jstrup bibliotek - b&oslash;ger til b&oslash;rn, unge og voksne</title>
	</head>
	<body>
		<div id="admin_login">
			<form action="" method="post">
				<p><label for="brugernavn">Brugernavn</label></p><input type="text" name="brugernavn" id="admin_felt" />
				<p><label for="password">Password</label></p><input type="password" name="password" id="admin_felt" />
				<input type="submit" value="Login" id="admin_knap" />
			</form>
		</div>
	</body>
</html>