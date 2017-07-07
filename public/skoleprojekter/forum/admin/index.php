<?php
session_start();
if($_SESSION['admin'] == true) {
	header("location: admin.php");
}
include("../includes/db.php");
include("../includes/funktioner.php");
if(isset($_POST['brugernavn']) && $_POST['brugernavn'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
	$hentAdmin = mysqli_query($db, "SELECT brugernavn, password FROM skoleprojekter_forum_admin WHERE brugernavn='$_POST[brugernavn]' AND password='$_POST[password]'");
	if(mysqli_num_rows($hentAdmin) == 1) {
		$_SESSION['admin'] = true;
		header("location: admin.php");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../style/style.css" media="screen" />
		<link rel="shorcut icon" href="../billeder/favicon.ico" />
		<title>Forum - administration</title>
	</head>
	
	<body>
		<div id="top">
		</div><!-- slut toppen -->
		<div id="wrap">
			<div id="top"></div><!-- slut top -->
			<div id="login_div">
				<form action="" method="post">
					<label for="brugernavn">Brugernavn: </label>
					<input type="text" name="brugernavn" id="brugernavn" class="login_felter" />
					<label for="password">Password: </label>
					<input type="password" name="password" id="password" class="login_felter" />
					<input type="submit" value="Login" id="knap" />
				</form>
			</div><!-- slut login_div -->
		</div><!-- slut wrap -->
	</body>
</html>