<?php
if(isset($_GET["page"])) {
	$_GET["page"];
} else {
	$_GET["page"] = "lister";
}
include("db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Computer - 
		<?php
		if($_GET["page"]=="lister") {
			echo " lister";
		}
		if($_GET["page"]=="ny_computer") {
			echo " tilf&oslash;j en computer ";
		}
		if($_GET["page"]=="sletcomputer") {
			echo " slet en computer";
		}
		if($_GET["page"]=="ny_bruger") {
			echo " tilf&oslash;j en bruger";
		}
		if($_GET["page"]=="sletbruger") {
			echo " slet en bruger";
		}
		if($_GET["page"]=="udlaan") {
			echo " udl&aring;n";
		}
		if($_GET["page"]=="aflevering") {
			echo " aflevering";
		}
		?>
		</title>
		<script language="javascript" type="text/javascript">
		<!--
		function computer() {
			if(document.ny_computer.maerke.value=='' ||
			   document.ny_computer.model.value=='' || 
			   document.ny_computer.nummer.value=='' || 
			   document.ny_computer.mus.value=='') {
				alert("Alle felter skal udfyldes!");
				return false;
			}
		}
		function bruger() {
			if(document.ny_bruger.elevnummer.value=='' || 
			   document.ny_bruger.navn.value=='' || 
			   document.ny_bruger.adresse.value=='' || 
			   document.ny_bruger.postnr.value=='' || 
			   document.ny_bruger.cpr.value=='' || 
			   document.ny_bruger.email.value=='' || 
			   document.ny_bruger.stamklasse.value=='') {
				alert("Alle felter skal udfyldes!");
				return false;
			}
		}
		function aflever_computer() {
			if(document.aflever.aflever_maerke.value=='' || 
			   document.aflever.aflever_model.value=='' || 
			   document.aflever.aflever_nummer.value=='') {
				alert("Alle felter skal udfyldes!");
				return false;
			}
		}
		//-->
		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="menu">
				<a href="index.php?page=lister">Lister</a>
				<a href="index.php?page=ny_computer">Tilf&oslash;j en computer</a>
				<a href="index.php?page=sletcomputer">Slet en computer</a>
				<a href="index.php?page=ny_bruger">Tilf&oslash;j en bruger</a>
				<a href="index.php?page=sletbruger">Slet en bruger</a>
				<a href="index.php?page=udlaan">Udl&aring;n</a>
				<a href="index.php?page=aflevering">Aflevering</a>
			</div>
			<div id="content">
				<?php
				if($_GET["page"]=="lister") {
				?>
				<h1>Listen over computere som ikke er udl&aring;nte</h1>
				<table>
					<tr>
						<td><p>M&aelig;rke</p></td>
						<td><p>Model</p></td>
						<td><p>Nummer</p></td>
						<td><p>Pris</p></td>
					</tr>
					<?php
					$vis_alle = mysqli_query($db, "SELECT maerke, model, nummer, pris FROM skoleprojekter_computer WHERE udlaan='0' ORDER BY maerke");
					while($stor_liste = mysqli_fetch_array($vis_alle)) {
					echo "<tr><td><p>".$stor_liste["maerke"]."</p></td>";
					echo "<td><p>".$stor_liste["model"]."</p></td>";
					echo "<td><p>".$stor_liste["nummer"]."</p></td>";
					echo "<td><p>".$stor_liste["pris"]."</p></td></tr>";
					}
					?>
				</table>
				<h1>Listen over udl&aring;nte computere</h1>
				<table>
					<tr>
						<td><p>M&aelig;rke</p></td>
						<td><p>Model</p></td>
						<td><p>Nummer</p></td>
						<td><p>Udl&aring;nt til</p></td>
						<td><p>Udl&aring;nt den</p></td>
						<td><p>Afleveres den</p></td>
					</tr>
				<?php
					$liste = mysqli_query($db, "SELECT maerke, model, nummer, udlaaneren, dato_udlaan, dato_aflevering FROM skoleprojekter_computer WHERE udlaan='1'");
					while($vis_liste = mysqli_fetch_array($liste)) {
						echo "<tr><td><p>".$vis_liste["maerke"]."</p></td>";					
						echo "<td><p>".$vis_liste["model"]."</p></td>";					
						echo "<td><p>".$vis_liste["nummer"]."</p></td>";					
						echo "<td><p>".$vis_liste["udlaaneren"]."</p></td>";					
						echo "<td><p>".date("j-m-Y", $vis_liste["dato_udlaan"])."</p></td>";					
						echo "<td><p>".date("j-m-Y", $vis_liste["dato_aflevering"])."</p></td></tr>";	
					}
				?>
				</table>
				<h1>Listen over brugere</h1>
				<table>
					<tr>
						<td><p>Elevnummer</p></td>
						<td><p>Navn</p></td>
						<td><p>Adresse</p></td>
						<td><p>Post nr.</p></td>
						<td><p>Cpr nr.</p></td>
						<td><p>Email</p></td>
						<td><p>Stamklasse</p></td>
					</tr>
				<?php
					$bruger = mysqli_query($db, "SELECT elevnummer, navn, adresse, postnr, cpr, email, stamklasse FROM skoleprojekter_computer_bruger ORDER BY elevnummer");
					while($visBruger = mysqli_fetch_array($bruger)) {
						echo "<tr><td><p>".$visBruger["elevnummer"]."</p></td>";
						echo "<td><p>".$visBruger["navn"]."</p></td>";
						echo "<td><p>".$visBruger["adresse"]."</p></td>";
						echo "<td><p>".$visBruger["postnr"]."</p></td>";
						echo "<td><p>".$visBruger["cpr"]."</p></td>";
						echo "<td><p>".$visBruger["email"]."</p></td>";
						echo "<td><p>".$visBruger["stamklasse"]."</p></td></tr>";
					}
				?>
				</table>
				<?php
				}
				if($_GET["page"]=="ny_computer") {
				?>
				<h1>Tilf&oslash;j en computer</h1>
				<form action="" method="post" name="ny_computer" onsubmit="return computer();">
					<p><label for="maerke">M&aelig;rke</label></p><input type="text" name="maerke" id="felt" />
					<p><label for="model">Model</label></p><input type="text" name="model" id="felt" />
					<p><label for="nummer">Nummer</label></p><input type="text" name="nummer" id="felt" />
					<p><label for="pris">Pris</label></p><input type="text" name="pris" id="felt" />
					<p><label for="mus">Mus</label></p>
					<select name="mus" id="rulle_menu">
						<option value="nej">Nej</option>
						<option value="optisk">Optisk</option>
						<option value="almindelig">Almindelig</option>
					</select>
					<input type="submit" value="Tilf&oslash;j" id="knap" /><br/>
				</form>
				<?php
					if(isset($_POST["maerke"], $_POST["model"], $_POST["nummer"], $_POST["pris"], $_POST["mus"])) {
						$maerke = $_POST["maerke"];
						$model  = $_POST["model"];
						$nummer = $_POST["nummer"];
						$pris   = $_POST["pris"];
						$mus    = $_POST["mus"];
						$udlaan = mysqli_query($db, "INSERT INTO skoleprojekter_computer (maerke, model, nummer, pris, mus, udlaan) VALUES ('$maerke', '$model', '$nummer', '$pris', '$mus', '0')");
						if($udlaan == true) {
							echo "<p class='opret'>Computeren ".$maerke." ".$model." er blevet tilf&oslash;jet";
						} else {
							echo "<p class='opret'>Der er sket en fejl</p>";
						}
					}
				}
				if($_GET["page"]=="sletcomputer") {
				?>
				<h1>Slet en computer</h1>
				<table>
					<tr>
						<td><p>M&aelig;rke</p></td>
						<td><p>Model</p></td>
						<td><p>Nummer</p></td>
						<td><p>Pris</p></td>
						<td><p>Mus</p></td>
						<td><p>Slet</p></td>
					</tr>
					<?php
					$slet_computer = mysqli_query($db, "DELETE FROM skoleprojekter_computer WHERE id='$_GET[id]'");
					$vis_computer  = mysqli_query($db, "SELECT id, maerke, model, nummer, pris, mus FROM skoleprojekter_computer ORDER BY nummer");
					while($computer = mysqli_fetch_array($vis_computer)) {
						echo "<tr><td><p>".$computer["maerke"]."</p></td>";
						echo "<td><p>".$computer["model"]."</p></td>";
						echo "<td><p>".$computer["nummer"]."</p></td>";
						echo "<td><p>".$computer["pris"]."</p></td>";
						echo "<td><p>".$computer["mus"]."</p></td>";
						echo "<td><a href='index.php?page=sletcomputer&id=$computer[id]'>Slet</a></td></tr>";
					}
					?>
				</table>
				<?php
				}
				if($_GET["page"]=="ny_bruger") {
				?>
					<h1>Tilf&oslash;j en bruger</h1>
					<form action="" method="post" name="ny_bruger" onsubmit="return bruger();">
						<p><label for="elevnummer">Elevnummer</label></p><input type="text" name="elevnummer" id="felt" />
						<p><label for="navn">Navn</label></p><input type="text" name="navn" id="felt" />
						<p><label for="adresse">Adresse</label></p><input type="text" name="adresse" id="felt" />
						<p><label for="postnr">Post nr. og by</label></p><input type="text" name="postnr" id="felt" />
						<p><label for="cpr">Cpr nr.</label></p><input type="text" name="cpr" id="felt" />
						<p><label for="email">Email</label></p><input type="text" name="email" id="felt" />
						<p><label for="stamklasse">Stamklasse</label></p><input type="text" name="stamklasse" id="felt" />
						<input type="submit" value="Tilf&oslash;j" id="knap" />
					</form>
				<?php
					if(isset($_POST["elevnummer"], $_POST["navn"], $_POST["adresse"], $_POST["postnr"], $_POST["cpr"], $_POST["email"], $_POST["stamklasse"])) {
						$elevnummer = $_POST["elevnummer"];
						$navn       = $_POST["navn"];
						$adresse    = $_POST["adresse"];
						$postnr     = $_POST["postnr"];
						$cpr        = $_POST["cpr"];
						$email      = $_POST["email"];
						$stamklasse = $_POST["stamklasse"];
						$nyBruger = mysqli_query($db, "INSERT INTO skoleprojekter_computer_bruger (elevnummer, navn, adresse, postnr, cpr, email, stamklasse) VALUES ('$elevnummer', '$navn', '$adresse', '$postnr', '$cpr', '$email', '$stamklasse')");
						if($nyBruger == true) {
							echo "<p class='opret'>Brugeren ".$navn." er oprettet";
						} else {
							echo "<p class='opret'>Der er sket en fejl";
						}
					}
				}
				if($_GET["page"]=="sletbruger") {
				?>
				<h1>Slet en bruger</h1>
				<table>
					<tr>
						<td><p>Navn</p></td>
						<td><p>Adresse</p></td>
						<td><p>Post nr. og by</p></td>
						<td><p>Elevnummer</p></td>
						<td><p>Slet</p></td>
					</tr>
					<?php
					$slet_bruger = mysqli_query($db, "DELETE FROM skoleprojekter_computer_bruger WHERE id='$_GET[id]'");
					$vis_bruger  = mysqli_query($db, "SELECT id, navn, adresse, elevnummer, postnr FROM skoleprojekter_computer_bruger ORDER BY navn");
					while($bruger = mysqli_fetch_array($vis_bruger)) {
						echo "<tr><td><p>".$bruger["navn"]."</p></td>";
						echo "<td><p>".$bruger["adresse"]."</p></td>";
						echo "<td><p>".$bruger["postnr"]."</p></td>";
						echo "<td><p>".$bruger["elevnummer"]."</p></td>";
						echo "<td><a href='index.php?page=sletbruger&id=$bruger[id]'>Slet</a></td></tr>";
					}
					?>
				</table>
				<?php
				}
				if($_GET["page"]=="udlaan") {
				?>
				<h1>Udl&aring;n</h1>
				<table>
					<tr>
						<td><p>M&aelig;rke</p></td>
						<td><p>Model</p></td>
						<td><p>Nummer</p></td>
						<td><p>Udl&aring;n</p></td>
					</tr>
				<?php
					$ikke_udlaante = mysqli_query($db, "SELECT id, maerke, model, nummer FROM skoleprojekter_computer WHERE udlaan='0'");
					while($vis_ikke_udlaante = mysqli_fetch_array($ikke_udlaante)) {
						echo "<tr><td><p>".$vis_ikke_udlaante["maerke"]."</p></td>";	
						echo "<td><p>".$vis_ikke_udlaante["model"]."</p></td>";	
						echo "<td><p>".$vis_ikke_udlaante["nummer"]."</p></td>";
						echo "<td><a href='udlaan.php?id=$vis_ikke_udlaante[id]'>Udl&aring;n</a></td></tr>";
					}
				?>
				</table>
				<?php
				}
				if($_GET["page"]=="aflevering") {
				?>
				<h1>Aflevering</h1>
				<form action="" method="post" name="aflever" onsubmit="return aflever_computer();">
					<p><label for="maerke">M&aelig;rke</label></p><input type="text" name="aflever_maerke" id="felt" />
					<p><label for="model">Model</label></p><input type="text" name="aflever_model" id="felt" />
					<p><label for="nummer">Nummer</label></p><input type="text" name="aflever_nummer" id="felt" />
					<input type="submit" value="Aflever" id="knap" />
				</form>
				<?php
					if(isset($_POST["aflever_maerke"], $_POST["aflever_model"], $_POST["aflever_nummer"])) {
						$aflever_maerke = $_POST["aflever_maerke"];
						$aflever_model  = $_POST["aflever_model"];
						$aflever_nummer = $_POST["aflever_nummer"];
						$sql = mysqli_query($db, "SELECT udlaan, nummer FROM skoleprojekter_computer WHERE udlaan='1' AND nummer='$aflever_nummer'");
						if(mysqli_num_rows($sql)==1) {
							$aflever = mysqli_query($db, "UPDATE skoleprojekter_computer SET udlaan='0', udlaaneren='', dato_udlaan='', dato_aflevering='' WHERE maerke='$aflever_maerke' AND model='$aflever_model' AND nummer='$aflever_nummer' AND udlaan='1'");
							if($aflever == true) {
								echo "<p class='opret'>Computeren ".$aflever_maerke." ".$aflever_model." er afleveret</p>";
							} else {
								echo "<p class='opret'>Der er sket en fejl</p>";
							}
						} else {
							echo "<p class='opret'>Der er sket en fejl</p>";
						}
					}
				}
				?>
			</div>
			<div id="bund">
				<?php 
				$bund_dato = date("j-m-Y")." ".date("H:i");
				echo "<p>".$bund_dato."</p>";
				?>
			</div>
		</div>
	</body>
</html>