<?php
session_start();
if($_SESSION['journalist'] == true) {
	header("location: journalist.php");
}
if($_SESSION['redaktor'] == true) {
	header("location: redaktor.php");
}
include("../includes/db.php");
if(isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
	$navn = $_POST['navn'];
	$password = $_POST['password'];
	if($_POST['login_som'] == "journalist") {
		$sql = "SELECT navn, password FROM skoleprojekter_netavisen_journalist WHERE navn='$navn' AND password='$password'";
		$resultat = mysqli_query($db, $sql) or die (mysqli_error($db));
		if(mysqli_num_rows($resultat) == 1) {
			$_SESSION['journalist'] = true;
			$_SESSION['journalist_navn'] = $navn;
			header("location: journalist.php");
		}
	} else if ($_POST['login_som'] == "redaktor") {
		$sql = "SELECT navn, password FROM skoleprojekter_netavisen_redaktor WHERE navn='$navn' AND password='$password'";
		$resultat = mysqli_query($db, $sql) or die (mysqli_error($db));
		if(mysqli_num_rows($resultat) == 1) {
			$_SESSION['redaktor'] = true;
			$_SESSION['redaktor_navn'] = $navn;
			header("location: redaktor.php");
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/backend_style.css" media="screen" />
		<title>NETavisen - login</title>
		<script type="text/javascript">
		function valider_login() {
			if(document.login_form.navn.value == "" ||
			document.login_form.password.value == "") {
				alert("Du skal skrive dit brugernavn og password for at logget ind!");
				return false;
			}
		}
		</script>
	</head>
	<body>
		<div id="login">
			<h1>Login</h1>
			<form action="" method="post" name="login_form" onsubmit="valider_login()">
				<table>
					<tr>
						<td class="login_td">
							<p><label for="navn">Navn</label></p>
							<input type="text" name="navn" id="navn" class="felt" value="<?php echo $_POST['navn']; ?>" />
						</td>
						<td>
							<p><label for="password">Password</label></p>
							<input type="password" name="password" id="password" class="felt" />
						</td>
					</tr>
					<tr>
						<td class="login_td">
							<p>Login som</p>
							<select name="login_som" class="valg">
								<option value="journalist">Journalist</option>
								<option value="redaktor">Redakt&oslash;r</option>
							</select>
						</td>
						<td>
							<input type="submit" value="Login" id="knap" />
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>