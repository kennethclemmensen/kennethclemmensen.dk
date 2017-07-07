<?php
session_start();
if($_SESSION['elev'] == true) {
	header("location: sider/elev.php");
}
if($_SESSION['laerer'] == true) {
	header("location: sider/laerer.php");
}
if(!isset($_GET['page'])) {
	$_GET['page'] = "forside";
}
include("includes/db.php");
include("includes/funktioner.php");
//elevlogin
if(isset($_POST['brugernavn']) && $_POST['brugernavn'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
	$hentElev = mysqli_query($db, "SELECT elev_id, navn, brugernavn, password FROM skoleprojekter_skoleportalen_elever WHERE brugernavn='$_POST[brugernavn]' AND password='$_POST[password]'");
	if(mysqli_num_rows($hentElev) == 1) {
		$data = mysqli_fetch_assoc($hentElev);
		$_SESSION['elev'] = true;
		$_SESSION['navn'] = $data['navn'];
		$_SESSION['elev_id'] = $data['elev_id'];
		header("location: sider/elev.php");
	} else {
		$login_fejl = "ja";
	}
}
if(isset($_POST['laerer_brugernavn']) && $_POST['laerer_brugernavn'] != "" && isset($_POST['laerer_password']) && $_POST['laerer_password'] != "") {
	$hentLaerer = mysqli_query($db, "SELECT brugernavn, password FROM skoleprojekter_skoleportalen_laererer WHERE brugernavn='$_POST[laerer_brugernavn]' AND password='$_POST[laerer_password]'");
	if(mysqli_num_rows($hentLaerer) == 1) {
		$_SESSION['laerer'] = true;
		header("location: sider/laerer.php");
	} else {
		$login_fejl = "ja";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<?php
		//tjekker om skolenavnet er sat er hvad skolenavnet er for at finde det rigtige stylesheet
		if(isset($_GET['skole_navn']) && $_GET['skole_navn'] == "Byskolen") {
			echo "<link rel='stylesheet' type='text/css' href='style/byskolen.css' media='screen' />";
		}
		if(isset($_GET['skole_navn']) && $_GET['skole_navn'] == "Skovskolen") {
			echo "<link rel='stylesheet' type='text/css' href='style/skovskolen.css' media='screen' />";
		}
		if(isset($_GET['skole_navn']) && $_GET['skole_navn'] == "Strandskolen") {
			echo "<link rel='stylesheet' type='text/css' href='style/strandskolen.css' media='screen' />";
		}
		?>
		<title>Skoleportalen<?php if(isset($_GET['skole_navn'])) echo " - ".$_GET['skole_navn']; ?></title>
	</head>
	
	<body>
		<div id="wrap">
			<div id="top">
				<h1>Skoleportalen<?php if(isset($_GET['skole_navn'])) echo " - ".$_GET['skole_navn'] ?></h1>
			</div><!-- slut top -->
			<div id="menu">
				<ul>
					<li><a href="index.php?page=forside" <?php echo style("forside"); ?>>Forside</a></li>
					<li><a href="index.php?page=skolerne" <?php echo style("skolerne"); echo style("skolen"); echo style("laerer"); echo style("klasser"); echo style("artikler"); echo style("klassen"); ?>>Skolerne</a></li>
					<li><a href="index.php?page=kontakt" <?php echo style("kontakt"); ?>>Kontakt</a></li>
					<li><a href="index.php?page=om_portalen" <?php echo style("om_portalen"); ?>>Om portalen</a></li>
				</ul>
			</div><!-- slut menu -->
			<div id="samle">
				<div id="content">
					<?php
					if($_GET['page'] == "forside") {
					?>
					<h1>Forside</h1>
					<p>Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla
					bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla
					bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla
					bla bla bla bla bla bla bla.</p>
					<?php
					}
					if($_GET['page'] == "skolerne") {
						$sql_skole = mysqli_query($db, "SELECT skole_id, navn, billede, beskrivelse FROM skoleprojekter_skoleportalen_skoler ORDER BY navn");
						while($data_skole = mysqli_fetch_assoc($sql_skole)) {
							echo "<h1>".$data_skole['navn']."</h1>";
							echo "<img src='billeder/skoler/".$data_skole['billede']."' alt='".$data_skole['navn']."' />";
							echo "<p>".substr($data_skole['beskrivelse'], 0, 140)."...</p><a href='index.php?page=skolen&amp;skole_id=$data_skole[skole_id]&amp;skole_navn=$data_skole[navn]' class='laes_mere_link'>L&aelig;s mere</a>";
						}
					}
					if($_GET['page'] == "skolen") {
						$sqlSkole = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$_GET[skole_id]'");
						$dataSkole = mysqli_fetch_assoc($sqlSkole);
						?>
						<h1><?php echo $dataSkole['navn']; ?></h1>
						<img src="billeder/skoler/<?php echo $dataSkole['billede']; ?>" alt="<?php echo $dataSkole['navn']; ?>" />
						<p><?php echo $dataSkole['beskrivelse']; ?></p>
						<h2>Kontakt <?php echo $dataSkole['navn']; ?></h2>
						<form action="" method="post">
							<label for="navn">Navn</label>
							<input type="text" name="navn" id="navn" class="textfield" value="<?php if(isset($_POST['navn']) && $_POST['navn'] != "") { echo $_POST['navn']; } ?>" />
							<label for="email">E-mail</label>
							<input type="text" name="email" id="email" class="textfield" value="<?php if(isset($_POST['email']) && $_POST['email'] != "") { echo $_POST['email']; } ?>" />
							<label for="besked">Besked</label>
							<textarea name="besked" id="besked" class="textarea"><?php if(isset($_POST['besked']) && $_POST['besked'] != "") { echo $_POST['besked']; } ?></textarea>
							<input type="submit" value="Send" class="knapper" />
						</form>
						<?php
						if(isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['email']) && $_POST['email'] != "" && isset($_POST['besked']) && $_POST['besked'] != "") {
							/*$sql_email = mysqli_query($db, "SELECT email FROM skoleportalen_skoler WHERE skole_id='$_GET[skole_id]'");
							$data_email = mysqli_fetch_assoc($sql_email);
							$sendMail = mail($data_email['email'], "Kontakt", $_POST['besked'], "From: ".$_POST['email']);
							if($sendMail == true) {
								echo "<p>Tak for din henvendelse</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}*/
						}
					}
					if($_GET['page'] == "laerer") {
						$sqlLaerer = mysqli_query($db, "SELECT navn, billede, brugernavn FROM skoleprojekter_skoleportalen_laererer WHERE skole_id='$_GET[skole_id]' ORDER BY navn");
						if(mysqli_num_rows($sqlLaerer) != 0) {
							echo "<table>\n";
							while($dataLaerer = mysqli_fetch_assoc($sqlLaerer)) {
								echo "<tr><td class='info_celle'><p>Navn: ".$dataLaerer['navn']."</p>\n";
								echo "<p>Brugernavn: ".$dataLaerer['brugernavn']."</p></td>\n";
								echo "<td class='info_celle'><img src='billeder/laerere/".$dataLaerer['billede']."' alt='".$dataLaerer['navn']."' /></td></tr>\n";
							}
							echo "</table>\n";
							$hentSkole = mysqli_query($db, "SELECT navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$_GET[skole_id]'");
							$dataSkole = mysqli_fetch_assoc($hentSkole);
							echo "<a href='javascript: window.history.go(-1)'>Tilbage til ".$dataSkole['navn']."</a>";
						}
					}
					if($_GET['page'] == "klasser") {
						echo "<table>\n";
						$sql_klasser = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_klasser WHERE skole_id='$_GET[skole_id]'");
						while($data_klasser = mysqli_fetch_assoc($sql_klasser)) {
							echo "<tr><td class='info_celle'><p>Navn: ".$data_klasser['navn']."</p>";
							$sqlElever = mysqli_query($db, "SELECT elev_id FROM skoleprojekter_skoleportalen_elever WHERE klasse_id='$data_klasser[klasse_id]'");
							echo "<p>Antal elever: ".mysqli_num_rows($sqlElever)."</p>";
							echo "<a href='index.php?page=klassen&amp;klasse_id=$data_klasser[klasse_id]&amp;skole_navn=$_GET[skole_navn]'>L&aelig;s mere</a></td>\n";
							echo "<td class='info_celle'><img src='billeder/klasser/".$data_klasser['billede']."' alt='".$data_klasser['navn']."' /></td></tr>\n";
						}
						echo "</table>\n";
						$hentSkole = mysqli_query($db, "SELECT navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$_GET[skole_id]'");
						$dataSkole = mysqli_fetch_assoc($hentSkole);
						echo "<a href='javascript: window.history.go(-1)'>Tilbage til ".$dataSkole['navn']."</a>";
					}
					if($_GET['page'] == "klassen") {
						$sql_klassen = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_klasser WHERE klasse_id='$_GET[klasse_id]'");
						$data_klassen = mysqli_fetch_assoc($sql_klassen);
						echo "<h1>".$data_klassen['navn']."</h1>";
						echo "<img src='billeder/klasser/".$data_klassen['billede']."' alt='".$data_klassen['billede']."' />";
						$sql_skole = mysqli_query($db, "SELECT navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$data_klassen[skole_id]'");
						$data_skole = mysqli_fetch_assoc($sql_skole);
						echo "<p>Skole: ".$data_skole['navn']."</p>";
						$sql_elever = mysqli_query($db, "SELECT elev_id FROM skoleprojekter_skoleportalen_elever WHERE klasse_id='$data_klassen[klasse_id]'");
						echo "<p>Antal elever: ".mysqli_num_rows($sql_elever);
						echo "<h2>Beskrivelse</h2>";
						echo "<p>".$data_klassen['beskrivelse']."</p>";
						echo "<a href='javascript: window.history.go(-1)'>Tilbage</a>";
					}
					if($_GET['page'] == "artikler") {
						$sql_artikel = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_artikler WHERE artikel_id='$_GET[artikel_id]'");
						$data_artikel = mysqli_fetch_assoc($sql_artikel);
						echo "<h1>".$data_artikel['overskrift']."</h1>";
						echo "<img src='billeder/artikler/".$data_artikel['billede']."' alt='".$data_artikel['billede']."' />";
						echo "<p>".date("j-m-Y | H:i", $data_artikel['dato'])."</p>";
						echo "<p>".$data_artikel['tekst']."</p>";
						echo "<a href='javascript: window.history.go(-1)'>Tilbage</a>";
					}
					if($_GET['page'] == "kontakt") {
					?>
						<h1>Kontakt</h1>
						<form action="" method="post">
							<label for="navn">Navn</label>
							<input type="text" name="navn" id="navn" class="textfield" value="<?php if(isset($_POST['navn']) && $_POST['navn'] != "") { echo $_POST['navn']; } ?>" />
							<label for="email">E-mail</label>
							<input type="text" name="email" id="email" class="textfield" value="<?php if(isset($_POST['email']) && $_POST['email'] != "") { echo $_POST['email']; } ?>" />
							<label for="besked">Besked</label>
							<textarea name="besked" id="besked" class="textarea"><?php if(isset($_POST['besked']) && $_POST['besked'] != "") { echo $_POST['besked']; } ?></textarea>
							<input type="submit" value="Send" class="knapper" />
						</form>
						<?php
						if(isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['email']) && $_POST['email'] != "" && isset($_POST['besked']) && $_POST['besked'] != "") {
							/*$navn = $_POST['navn'];
							$email = $_POST['email'];
							$besked = $_POST['besked'];
							$sendMail = mail("skoleportalen@mail.dk", $email."vil sp�rge om noget", $besked);
							if($sendMail == true) {
								echo "<p>Tak for din henvendelse</p>";
							} else {
								echo "<p class='fejl'>Der er sket en fejl</p>";
							}*/
						}
					}
					if($_GET['page'] == "om_portalen") {
					?>
					<h1>Om portalen</h1>	
					<p>Her skal der st&aring; noget om portalen.</p>	
					<?php
					}
					?>
				</div><!-- slut indhold -->
				<div id="hojre">
					<?php
					if($_GET['page'] == "forside") {
					?>
					<fieldset>
						<legend>Elevlogin</legend>
						<form action="" method="post">
							<label for="brugernavn">Brugernavn</label>
							<input type="text" name="brugernavn" id="brugernavn" class="textfield" value="<?php if(isset($_POST['brugernavn']) && $_POST['brugernavn'] != "") { echo $_POST['brugernavn']; } ?>" />
							<label for="password">Password</label>
							<input type="password" name="password" id="password" class="textfield" />
							<input type="submit" value="Login" class="knapper" />
						</form>
					</fieldset>
					
					<fieldset>
						<legend>L&aelig;rerlogin</legend>
						<form action="" method="post">
							<label for="laerer_brugernavn">Brugernavn</label>
							<input type="text" name="laerer_brugernavn" id="laerer_brugernavn" class="textfield" value="<?php if(isset($_POST['laerer_brugernavn']) && $_POST['laerer_brugernavn'] != "") { echo $_POST['laerer_brugernavn']; } ?>" />
							<label for="laerer_password">Password</label>
							<input type="password" name="laerer_password" id="laerer_password" class="textfield" />
							<input type="submit" value="Login" class="knapper" />
						</form>
					</fieldset>
					<?php
					}
					if($_GET['page'] == "skolen") {
						$sql_skolen = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$_GET[skole_id]'");
						$data_skolen = mysqli_fetch_assoc($sql_skolen);
						?>
						<h2>Fakta om <?php echo $data_skolen['navn'] ?></h2>
						<p class="info">Adresse: <?php echo $data_skolen['adresse']; ?></p>
						<p class="info">Telefon: <?php echo $data_skolen['telefon']; ?></p>
						<p class="info">E-mail: <a href="mailto:<?php echo $data_skolen['email']; ?>"><?php echo $data_skolen['email']; ?></a></p>
						<?php
						//finder l�rerer
						$sql_laerer = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_laererer WHERE skole_id='$_GET[skole_id]'");
						$data_laerer = mysqli_fetch_assoc($sql_laerer);
						echo "<p class='info'>";
						if(mysqli_num_rows($sql_laerer) != 0) {
							echo "<a href='index.php?page=laerer&amp;skole_id=$data_laerer[skole_id]&amp;skole_navn=$_GET[skole_navn]'>Antal l&aelig;rere: ".mysqli_num_rows($sql_laerer)."</a>";
						} else {
							echo "Antal l&aelig;rere: 0";
						}
						echo "</p>";
						//finder klasser
						$sqlKlasser = mysqli_query($db, "SELECT klasse_id, skole_id FROM skoleprojekter_skoleportalen_klasser WHERE skole_id='$_GET[skole_id]'");
						$dataKlasser = mysqli_fetch_assoc($sqlKlasser);
						echo "<p class='info'>";
						if(mysqli_num_rows($sqlKlasser) != 0) {
							echo "<a href='index.php?page=klasser&amp;skole_id=$dataKlasser[skole_id]&amp;skole_navn=$_GET[skole_navn]'>Antal klasser: ".mysqli_num_rows($sqlKlasser)."</a>";
						} else {
							echo "Antal klasser: 0";
						}
						echo "</p>";
						//finder elever
						$sql_elever = mysqli_query($db, "SELECT elev_id FROM skoleprojekter_skoleportalen_elever WHERE klasse_id='$dataKlasser[klasse_id]'");
						echo "<p class='info'>Antal elever p&aring; skolen: ".mysqli_num_rows($sql_elever).".</p>";
					}
					if($_GET['page'] == "klassen") {
						echo "<h1>Artikler</h1>";
						//henter eleverne fra klassen
						$hent_elever = mysqli_query($db, "SELECT elev_id FROM skoleprojekter_skoleportalen_elever WHERE klasse_id='$_GET[klasse_id]'");
						while($data_elever = mysqli_fetch_assoc($hent_elever)) {
							//henter artiklerne som eleverne fra klassen har skrevet
							$sql_artikler = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_artikler WHERE elev_id='$data_elever[elev_id]'");
							if(mysqli_num_rows($sql_artikler) != 0) {
								while($data_artikler = mysqli_fetch_assoc($sql_artikler)) {
									echo "<h2>".$data_artikler['overskrift']."</h2>";
									echo "<p>".substr($data_artikler['tekst'], 0, 40).".. <a href='index.php?page=artikler&amp;artikel_id=$data_artikler[artikel_id]&amp;skole_navn=$_GET[skole_navn]'>L&aelig;s mere</a></p>";
								}
							}
						}
					}
					?>
				</div><!-- slut hojre -->
			</div><!-- slut samle -->
			<div id="bund">
				<p>&copy; Skoleportalen</p>
			</div><!-- slut bund -->
		</div><!-- slut wrap -->
		<div id="luft"></div><!-- slut bund -->	
		<?php
		if(isset($login_fejl) && $login_fejl == "ja") {
		?>
		<script type="text/javascript">
		alert("Forkert brugernavn eller password!");
		</script>
		<?php
		}
		?>
	</body>
</html>