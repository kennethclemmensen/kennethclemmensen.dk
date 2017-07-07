<?php
session_start();
if($_SESSION["bruger"]==true) {
	header("location: bruger.php");
}
if(isset($_GET["page"])) {
	$_GET["page"];
} else {
	$_GET["page"] = "forside";
}
include("db.php");
include("mytime.php");
include("dktime.php");
// bruger login
if(isset($_POST["brugernavn"], $_POST["password"])) {
	$brugernavn = $_POST["brugernavn"];
	$password   = $_POST["password"];
	$hentBruger = mysqli_query($db, "SELECT brugernavn, password FROM skoleprojekter_rejsesiden_login WHERE brugernavn='$brugernavn' AND password='$password'");
	if(mysqli_num_rows($hentBruger)==1) {
		$_SESSION["bruger"] = true;
		$_SESSION["brugernavn"] = $brugernavn;
		header("location: bruger.php");
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
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAqHbZgLVEjNi1RhvkmgDVqBS5wOjB-8J2VKGjOJq0ZdwSRnACMRSNtDW01-_Yu0GISi5TXl3X6UAiaQ" type="text/javascript"></script>
		<?php
		$vis_grader = mysqli_query($db, "SELECT langdegrad, breddegrad FROM skoleprojekter_rejsesiden_admin");
		$dataGrader = mysqli_fetch_array($vis_grader);
		?>
		<script language="javascript">
		function initialize() {
			if(GBrowserIsCompatible()) {
				var map = new GMap2(document.getElementById("map_canvas"));
				map.setCenter(new GLatLng(<?php echo $dataGrader["breddegrad"].",".$dataGrader["langdegrad"]; ?>), 12);
				map.setUIToDefault();
			}
		}
		function valider_login() {
			if(document.login.brugernavn.value=="" ||
			document.login.password.value=="") {
				alert("Begge felter skal udfyldes!");
				return false;
			}
		}
		</script>
	</head>
	<body onload="initialize()" onunload="GUnload()">
		<div id="wrap">
			<div id="top">
			</div>
			<div id="samle">
				<div id="menu">
					<ul>
						<li><a href="index.php?page=forside">Forside</a></li>
						<li><a href="index.php?page=rejseplan">Rejseplan</a></li>
						<li><a href="index.php?page=galleri">Galleri</a></li>
					</ul>
					<form action="" method="post" name="login" onsubmit="return valider_login();" id="login_formular">
						<h1>Login til webblog</h1>
						<label for="brugernavn"><p>Brugernavn</p></label><input type="text" name="brugernavn" id="login_felt" />
						<label for="password"><p>Password</p></label><input type="password" name="password" id="login_felt" />
						<input type="submit" value="Login" id="login_button" />
					</form>
				</div>
				<div id="content">
					<?php
					if($_GET["page"]=="forside") {
					?>
					<h1>Forside</h1>
					<p>Min position er
					<div id="map_canvas">		
					</div>					
					<?php
					}
					if($_GET["page"]=="rejseplan") {
					?>
					<h1>Rejseplan</h1>
					<?php
						$vis = mysqli_query($db, "SELECT dato, tekst FROM skoleprojekter_rejsesiden_rejseplan ORDER BY dato"); 
						while($visRejseplan = mysqli_fetch_array($vis)) {
							echo "<h2>".$visRejseplan["dato"]."</h2>";
							echo "<p class='rejseplanTekst'>".$visRejseplan["tekst"]."</p>";
						}
					}
					if($_GET["page"]=="galleri") {
					?>
					<h1>Galleri</h1>
					<?php
						$billede = mysqli_query($db, "SELECT url, beskrivelse, dato FROM skoleprojekter_rejsesiden_billeder");
						while($vis_billede = mysqli_fetch_array($billede)) {
							echo "<img src='".$vis_billede["url"]."' />";
							echo "<p class='galleri_dato'>".$vis_billede["dato"]."</p>";
							echo "<p class='galleri_beskrivelse'>".$vis_billede["beskrivelse"]."</p>";
						}
					}
					?>
				</div>
			</div>
			<div id="samle_bund">
				<div id="bund1">
					<p>
					<script language="javascript">
					dkTime();
					</script>
					</p>
				</div>
				<div id="bund2">
					<p>
					<script type="text/javascript">	
					myTime();
					</script>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>