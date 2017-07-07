<?php
session_start();
ob_start();
include("../includes/db.php");
if($_SESSION['admin'] == true) {
	header("location: admin.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../style/admin_style.css" media="screen" />
		<title>CMK m&oslash;bler - administration</title>
		<script type="text/javascript">
		function skjul_tekst(felt, tekst) {
			if(felt.value == tekst) {
				felt.value = "";
			}
		}
		function vis_tekst(felt, tekst) {
			if(felt.value == "") {
				felt.value = tekst;
			}
		}
		</script>
	</head>
	<body>
		<div id="top">
		</div><!-- slut top -->
		<div id="login">
			<div id="login_form">
				<form action="" method="post" name="login_form">
					<input type="text" name="brugernavn" value="Brugernavn" onfocus='skjul_tekst(this, "Brugernavn")' onblur='vis_tekst(this, "Brugernavn")' />
					<input type="password" name="password" value="Password" onfocus='skjul_tekst(this, "Password")' onblur='vis_tekst(this, "Password")' />
					<input type="submit" value="Login" id="knap" />
				</form>
				<?php
				//tjekker at felterne ikke er tomme eller standardv�rdierne st�r i dem
				if(isset($_POST['brugernavn']) && $_POST['brugernavn'] != "" && $_POST['brugernavn'] != "Brugernavn" &&
				isset($_POST['password']) && $_POST['password'] != "" && $_POST['password'] != "Password") {
					$hentAdmin = mysqli_query($db, "SELECT brugernavn, password FROM skoleprojekter_cmk_admin WHERE brugernavn='$_POST[brugernavn]' AND password='$_POST[password]'");
					if(mysqli_num_rows($hentAdmin) == 1) {
						$_SESSION['admin'] = true;
						header("location: admin.php");
					} else {
						echo "<p>Dit brugernavn eller password er ikke korrekt!</p>";
					}
				}
				?>
			</div>
			<h1>Velkommen til CMK m&oslash;bler</h1>
			<h2>log ind for at fors&aelig;tte</h2>
		</div>
	</body>
</html>