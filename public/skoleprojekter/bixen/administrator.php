<?php
session_start();
if($_SESSION["admin"]!==true) {
	header("location: index.php");
}
if(isset($_GET["page"])) {
	$_GET["page"];
} else {
	$_GET["page"] = "forside";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>BIXEN - Administrator</title>
	</head>
	<body>
		<div id="wrap">
			<div id="samle_top">
				<div id="samle_top2">
					<div id="top1">
						<img src="images/top_billede.jpg" />
					</div>
					<div id="top2">
						<h1>Vrag | Personbiler | Last- & varevogne</h1>
					</div>
				</div>
				<div id="top3">
				</div>
			</div>
			<div id="samle">
				<div id="menu">
					<ul>
						<li><a id="links" href="administrator.php?page=forside">Forside</a></li>
						<li><a id="links" href="administrator.php?page=godkend_kommentarer">Godkend kommentarer</a></li>
						<li><a id="links" href="administrator.php?page=nybil">Tilf&oslash;j en ny bil</a></li>
						<li><a id="links" href="administrator.php?page=sletbil">Slet en bil</a></li>
						<li><a id="links" href="administrator.php?page=opretforhandler">Opret forhandler</a></li>
						<li><a id="links" href="administrator.php?page=sletforhandler">Slet forhandler</a></li>
						<li><a id="links" href="logaf.php">Log af</a></li>
					</ul>
				</div>
				<div id="content">
				<?php
				if($_GET["page"]=="forside") {
				?> 
				<h1>Administrator</h1>
				<p>Her kan du som administrator tilf&oslash;je og slette biler og forhandlere. Du kan ogs� godkende de kommentare
				forhandlerne kommer med.</p>
				<?php
				}
				if($_GET["page"]=="godkend_kommentarer") {
				?>
				<h1>Godkend kommentar</h1>
				<p>Her kan du som administrator godkende de kommentarer som er skrevet til bilerne</p>
				<table class="tabel">
					<tr>
						<td class="celle"><p class="top">Godkend</p></td>
						<td class="celle"><p class="top">Kommentar</p></td>
						<td class="celle"><p class="top">Dato</p></td>
						<td class="celle"><p class="top">Skrevet af</p></td>
						<td class="celle"><p class="top">Bil</p></td>
						<td class="celle"><p class="top">Slet</p></td>
					</tr>
				<?php
				include("db.php");
				$godkendtKommentar = mysqli_query($db, "UPDATE skoleprojekter_bixen_kommentar SET godkendt='1' WHERE id='$_GET[id]' AND godkendt='0'");
				$ikke_godkendt_kommentar = mysqli_query($db, "SELECT id, bil_id, kommentar, dato, navn, godkendt FROM skoleprojekter_bixen_kommentar WHERE godkendt='0'");
				while($godkend_kommentar = mysqli_fetch_array($ikke_godkendt_kommentar)) {
					echo "<tr><td class='celle'><p><a href='administrator.php?page=godkend_kommentarer&id=$godkend_kommentar[id]'>Godkend</a></td>";
					echo "<td class='celle'><p>".$godkend_kommentar["kommentar"]."</p></td>";
					echo "<td class='celle'><p>".$godkend_kommentar["dato"]."</p></td>";
					echo "<td class='celle'><p>".$godkend_kommentar["navn"]."</p></td>";
					$bil = mysqli_query($db, "SELECT id, maerke, model FROM bixen_biler WHERE id='$godkend_kommentar[bil_id]'");
					while($vis_bil=mysqli_fetch_array($bil)) {
					echo "<td class='celle'><p>".$vis_bil["maerke"]." ".$vis_bil["model"]."</p></td>";
					}
					echo $godkend_kommentar["bil_id"]; 	
					echo "<td class='celle'><p><a href='slet_ikke_godkendt_kommentar.php?id=$godkend_kommentar[id]'>Slet</a></p></td></tr>";
				}
				?>
				</table>
				<?php
				}
				if($_GET["page"]=="nybil") {
				?>
				<h1>Tilf&oslash;j en ny bil til listen</h1>
				<form action="" method="post">
					<p>V&aelig;lg kategori</p>
					<select name="type"> 
						<option value="personbil">Personbil</option> 
						<option value="lastvogn">Last- og varevogne</option>
						<option value="vrag">Vrag</option> 
					</select>
					<p>Skriv m&aelig;rke</p>
					<input type="text" name="maerke" id="felt" />
					<p>Skriv model</p>
					<input type="text" name="model" id="felt" />
					<p>Skriv prisen</p>
					<input type="text" name="pris" id="felt" />
					<p>V&aelig;lg d&oslash;re</p>
					<select name="doere">
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
					</select>
					<input type="submit" value="Tilf&oslash;j" id="knap" />
				</form>	
				<?php
					if(isset($_POST["type"], $_POST["model"], $_POST["pris"], $_POST["doere"])) {
						include("db.php");
						$type = $_POST["type"];
						$maerke = $_POST["maerke"];
						$model = $_POST["model"];
						$pris = $_POST["pris"];
						$doere = $_POST["doere"];
						$nyBil = mysqli_query($db, "INSERT INTO skoleprojekter_bixen_biler (type, maerke, model, pris, doere) VALUES ('$type', '$maerke', '$model', '$pris', '$doere')");
						if($nyBil == true) {
							echo "<p>Bilen ".$maerke." ".$model." er gemt i databasen</p>";
						} else {
							echo "<p>Bilen ".$maerke." ".$model." blev ikke gemt i databasen</p>";
						}
					}	
				}
				if($_GET["page"]=="sletbil") {
				?>
				<h1>Slet en bil fra listen</h1>
				<h2>Personbil</h2>
				<table class="tabel">
					<tr>
						<td class="celle"><p class="top"><p>M&aelig;rke</p></td>
						<td class="celle"><p class="top"><p>Model</p></td>
						<td class="celle"><p class="top"><p>D&oslash;re</p></td>
						<td class="celle"><p class="top"><p>Pris</p></td>
						<td class="celle"><p class="top"><p>Slet</p></td>
					</tr>
				<?php
				include("db.php");
				$sletPersonbil = mysqli_query($db, "DELETE FROM bixen_biler WHERE id='$_GET[id]'");
				$vis_personbiler = mysqli_query($db, "SELECT id, maerke, model, doere, pris FROM skoleprojekter_bixen_biler WHERE type='personbil' ORDER BY pris DESC");
				while($personbil_data = mysqli_fetch_array($vis_personbiler)) {
						echo "<tr><td class='celle'><p>".$personbil_data["maerke"]."</p></td>";
						echo "<td class='celle'><p>".$personbil_data["model"]."</p></td>";
						echo "<td class='celle'><p>".$personbil_data["doere"]."</p></td>";
						echo "<td class='celle'><p>".$personbil_data["pris"]." kr</p></td>";
						echo "<td class='celle'><p><a href='administrator.php?page=sletbil&id=$personbil_data[id]'>Slet</a></p></td></tr>";
				}
				?>
				</table>
				<h2>Last- og varevogne</h2>
				<table class="tabel">
					<tr>
						<td class="celle"><p class="top"><p>M&aelig;rke</p></td>
						<td class="celle"><p class="top"><p>Model</p></td>
						<td class="celle"><p class="top"><p>D&oslash;re</p></td>
						<td class="celle"><p class="top"><p>Pris</p></td>
						<td class="celle"><p class="top"><p>Slet</p></td>
					</tr>
				<?php
				$sletLastvogn = mysqli_query($db, "DELETE FROM bixen_biler WHERE id='$_GET[id]'");
				$vis_lastvogn = mysqli_query($db, "SELECT id, maerke, model, doere, pris FROM skoleprojekter_bixen_biler WHERE type='lastvogn' ORDER BY pris DESC");
				while($lastvogn_data = mysqli_fetch_array($vis_lastvogn)) {
						echo "<tr><td class='celle'><p>".$lastvogn_data["maerke"]."</p></td>";
						echo "<td class='celle'><p>".$lastvogn_data["model"]."</p></td>";
						echo "<td class='celle'><p>".$lastvogn_data["doere"]."</p></td>";
						echo "<td class='celle'><p>".$lastvogn_data["pris"]." kr</p></td>";
						echo "<td class='celle'><p><a href='administrator.php?page=sletbil&id=$lastvogn_data[id]'>Slet</a></p></td></tr>";
				}
				?>
				</table>
				<h2>Vrag</h2>
				<table class="tabel">
					<tr>
						<td class="celle"><p class="top"><p>M&aelig;rke</p></td>
						<td class="celle"><p class="top"><p>Model</p></td>
						<td class="celle"><p class="top"><p>D&oslash;re</p></td>
						<td class="celle"><p class="top"><p>Pris</p></td>
						<td class="celle"><p class="top"><p>Slet</p></td>
					</tr>
				<?php
				$sletVrag = mysqli_query($db, "DELETE FROM bixen_vrag WHERE id='$_GET[id]'");
				$vis_vrag = mysqli_query($db, "SELECT id, maerke, model, doere, pris FROM skoleprojekter_bixen_biler WHERE type='vrag' ORDER BY pris DESC");
				while($vrag_data = mysqli_fetch_array($vis_vrag)) {
						echo "<tr><td class='celle'><p>".$vrag_data["maerke"]."</p></td>";
						echo "<td class='celle'><p>".$vrag_data["model"]."</p></td>";
						echo "<td class='celle'><p>".$vrag_data["doere"]."</p></td>";
						echo "<td class='celle'><p>".$vrag_data["pris"]." kr</p></td>";
						echo "<td class='celle'><p><a href='administrator.php?page=sletbil&id=$vrag_data[id]'>Slet</a></p></td></tr>";
				}
				?>
					</table>
				<?php
				}
				if($_GET["page"]=="opretforhandler") {
				?>
				<h1>Opret en ny forhandler</h1>
				<form action="" method="post">
					<p><label for="forhandler_brugernavn">Brugernavn</label></p><input type="text" name="forhandler_brugernavn" id="felt" />
					<p><label for="forhandler_password">Password</label></p><input type="text" name="forhandler_password" id="felt" />
					<input type="submit" value="Opret" id="knap" />
				</form>
				<?php
					if(isset($_POST["forhandler_brugernavn"], $_POST["forhandler_password"])) {
						include("db.php");
						$forhandler_brugernavn = $_POST["forhandler_brugernavn"];
						$forhandler_password = $_POST["forhandler_password"];
						$opretForhandler = mysqli_query($db, "INSERT INTO skoleprojekter_bixen_forhandler (brugernavn, password) VALUES ('$forhandler_brugernavn', '$forhandler_password')");
						if($opretForhandler == true) {
							echo "<p>Forhandleren ".$forhandler_brugernavn." er oprettet!</p>";
						} else {
							echo "<p>Forhandleren er optaget!</p>";
						}
					}
				}
				if($_GET["page"]=="sletforhandler") {
				?>
				<h1>Slet forhandler</h1>
				<table class="tabel">
					<tr>
						<td class="celle"><p class="top">Brugernavn</p></td>
						<td class="celle"><p class="top">Adgangskode</p></td>
						<td class="celle"><p class="top">Slet</p></td>
					</tr>
				<?php
				include("db.php");
				$sletForhandler = mysqli_query($db, "DELETE FROM bixen_forhandler WHERE id='$_GET[id]'");
				$forhandler_data = mysqli_query($db, "SELECT id, brugernavn, password FROM skoleprojekter_bixen_forhandler ORDER BY brugernavn DESC");
				while($forhandler_liste = mysqli_fetch_array($forhandler_data)) {
					echo "<tr><td class='celle'><p>".$forhandler_liste["brugernavn"]."</p></td>
					<td class='celle'><p>".$forhandler_liste["password"]."</p></td>
					<td class='celle'><p><a href='administrator.php?page=sletforhandler&id=$forhandler_liste[id]'>Slet</a></p></td></tr>";
				}
				?>
				</table>
				<?php
				}
				?>
				</div>
			</div>
			<div id="bund">
				<p>BIXEN - Munkebjergvej 130 - 5000 Odense M - Tlf. 12345678 - &Aring;bningstider: Man-fre 09.00-16.00</p>
			</div>
		</div>
	</body>
</html>