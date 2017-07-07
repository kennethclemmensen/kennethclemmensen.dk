<?php
session_start();
include("../includes/db.php");
if(isset($_POST['brugernavn']) && $_POST['brugernavn'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
	$hentAdmin = mysqli_query($db, "SELECT brugernavn, password FROM skoleprojekter_indigo_admin WHERE brugernavn='$_POST[brugernavn]' AND password='$_POST[password]'");
	if(mysqli_num_rows($hentAdmin) == 1) {
		$_SESSION['admin'] = true;
		header("location: admin.php");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../style/style.css" media="screen" />
		<title>Indigo - administration</title>
	</head>
	
	<body>
		<div id="wrap">
			<div id="top">
				<h1>Indigo - administration</h1>
			</div><!-- slut top -->
			<div id="content">
				<form action="" method="post">
					<p><label for="brugernavn">Brugernavn</label></p>
					<input type="text" name="brugernavn" id="brugernavn" class="textfield" />
					<p><label for="password">Password</label></p>
					<input type="password" name="password" id="password" class="textfield" />
					<input type="submit" value="Login" class="knapper" />
				</form>
			</div><!-- slut indhold -->
		</div><!-- slut wrap -->
	</body>
</html>			