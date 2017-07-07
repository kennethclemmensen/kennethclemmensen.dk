<?php
session_start();
ob_start();
include('../includes/db.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../style/admin_style.css" media="screen" />
		<title>Komponist Kasper Jarnum - administration</title>
	</head>
	<body>
		<div id="top">
			<div id="top2"></div>
		</div><!-- slut top -->
		<div id="wrap">
			<div id="menu">
			</div><!-- slut menu -->
				<div id="login_indhold">
					<form action="" method="post">
						<p><label for="brugernavn">Brugernavn</label></p>
						<input type="text" name="brugernavn" id="brugernavn" class="textfield" />
						<p><label for="password">Password</label></p>
						<input type="password" name="password" id="password" class="textfield" />
						<input type="submit" value="Login" id="knap" />
					</form>
					<?php
					if(isset($_POST['brugernavn']) && $_POST['brugernavn'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
						$hentAdmin = mysqli_query($db, "SELECT brugernavn, password FROM skoleprojekter_komponist_admin WHERE brugernavn='$_POST[brugernavn]' AND password='$_POST[password]'");
						if(mysqli_num_rows($hentAdmin) == 1) {
							$_SESSION['admin'] = true;
							header("location: admin.php");
						} else {
							echo "<p>Forkert brugernavn eller password</p>";
						}
					}
					?>
				</div><!-- slut indhold -->
			<div id="bund">
				<?php
				ob_flush();
				?>
				<p>&copy; Kasper Jarnum - E-mail <a href="mailto:kasper_jarnum@mail.dk">kasper-jarnum@mail.dk</a></p>
			</div><!-- slut bund -->
		</div><!-- slut wrap -->
	</body>
</html>