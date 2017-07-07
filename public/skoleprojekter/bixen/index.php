<?php
session_start();
if($_SESSION["forhandler"] == true){
	header("location: forhandler.php");
}
if($_SESSION["admin"] == true) {
	header("location: administrator.php");
}
if(isset($_GET["page"])) {
	$_GET["page"];
} else {
	$_GET["page"] = "forside";
}
//forhandler login
if($_POST["forhandler_brugernavn"]!=="") {
	include("db.php");
	$forhandler_brugernavn = $_POST["forhandler_brugernavn"];
	$forhandler_password = $_POST["forhandler_password"];
	$hentForhandler = mysqli_query($db, "SELECT id, brugernavn, password FROM skoleprojekter_bixen_forhandler WHERE brugernavn='$forhandler_brugernavn' AND password='$forhandler_password'");
	if(mysqli_num_rows($hentForhandler)==1) {
		$_SESSION["forhandler"] = true;
		$_SESSION["forhandler_navn"] = $forhandler_brugernavn;
		header("location: forhandler.php");
	}
}
//administrator login
if($_POST["admin_brugernavn"]!=="") {
	include("db.php");
	$admin_brugernavn = $_POST["admin_brugernavn"];
	$admin_password = $_POST["admin_password"];
	$hentAdmin = mysqli_query($db, "SELECT id, brugernavn, password FROM skoleprojekter_bixen_admin WHERE brugernavn='$admin_brugernavn' AND password='$admin_password'");
	if(mysqli_num_rows($hentAdmin)==1) {
		$_SESSION["admin"] = true;
		header("location: administrator.php");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>BIXEN</title>
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
						<li><a id="links" href="index.php?page=forside">Forside</a></li>
						<li><a id="links" href="index.php?page=personbiler">Personbiler</a></li>
						<li><a id="links" href="index.php?page=lastvogne">Last- & varevogne</a></li>
						<li><a id="links" href="index.php?page=vrag">Vrag</a></li>
						<li><a id="links" href="index.php?page=login">Forhandler login</a></li>
						<li><a id="links" href="index.php?page=admin">Administrator login</a></li>
					</ul>
				</div>
				<div id="content">
					<?php
					if($_GET["page"]=="forside") {
						echo "<h1>BIXEN</h1>";
						$personbiler = array("audi80.jpg", "bmw320.jpg", "fordKa.jpg", "fordSierra.jpg", "golf.jpg", "mazda323.jpg",
						"mercedes190.jpg", "opelAstra.jpg", "toyotaCarina.jpg");
						$tal = rand(0, 8);
						$varevogne = array("fordTransit.jpg", "mazdaB2000.jpg", "mazdaE2200.jpg", "mercedesSprinter.jpg", "transporter.jpg");
						$tal2 = rand(0, 4);
						$vrag = array("fordEscort.jpg", "fordGranada.jpg", "fordSierra.jpg", "fordSierra2.jpg", "hondaCivic.jpg", "mercedes300.jpg");
						$tal3 = rand(0, 5);
						echo "<table><tr>";
						echo "<td><a href='index.php?page=personbiler'><img src='images/personbiler/".$personbiler[$tal]."' /></a><h2>Personbiler</h2></td>";
						echo "<td><a href='index.php?page=lastvogne'><img src='images/varevogne/".$varevogne[$tal2]."' /></a><h2>Varevogne</h2></td>";
						echo "<td><a href='index.php?page=vrag'><img src='images/vrag/".$vrag[$tal3]."' /></a><h2>Vrag</h2></td></tr></table>";
					}
					if($_GET["page"]=="personbiler") {
					?>
					<h1>Personbiler</h1>
					<p>Her er listen over personbiler</p>
					<table class="tabel">
						<tr>
							<td class="celle"><p class="top"><p>M&aelig;rke</p></td>
							<td class="celle"><p class="top"><p>Model</p></td>
							<td class="celle"><p class="top"><p>D&oslash;re</p></td>
							<td class="celle"><p class="top"><p>Pris</p></td>
						<td class="celle"><p class="top"><p>Kommentarer</p></td>
						</tr>
					<?php
					include("db.php");
					$vis_personbiler = mysqli_query($db, "SELECT id, maerke, model, doere, pris FROM skoleprojekter_bixen_biler WHERE type='personbil' ORDER BY pris DESC");
					while($data = mysqli_fetch_array($vis_personbiler)) {
						$vis_antal = mysqli_query($db, "SELECT bil_id, kommentar FROM skoleprojekter_bixen_kommentar WHERE bil_id='$data[id]' AND godkendt='1'");
						$antal = mysqli_num_rows($vis_antal);	
						echo "<tr><td class='celle'><p>".$data["maerke"]."</p></td>";
						echo "<td class='celle'><p>".$data["model"]."</p></td>";
						echo "<td class='celle'><p>".$data["doere"]."</p></td>";
						echo "<td class='celle'><p>".$data["pris"]." kr</p></td>";
						echo "<td class='celle'><a href='kommentar.php?id=$data[id]'>".$antal;
						if($antal > 1) {
							echo " kommentarer";
						} elseif ($antal == 0) {
							echo " kommentarer";
						} else {
							echo " kommentar";
						}
						echo "</a></td></tr>";
					}
					?>
					</table>
					<?php
					}
					if($_GET["page"]=="lastvogne") {
					?>	
					<h1>Last- & varevogne</h1>
					<p>Her er listen over lastvogne</p>
					<table class="tabel">
						<tr>
							<td class="celle"><p class="top"><p>M&aelig;rke</p></td>
							<td class="celle"><p class="top"><p>Model</p></td>
							<td class="celle"><p class="top"><p>D&oslash;re</p></td>
							<td class="celle"><p class="top"><p>Pris</p></td>
						<td class="celle"><p class="top"><p>Kommentarer</p></td>
						</tr>
					<?php
					include("db.php");
					$vis_lastvogn = mysqli_query($db, "SELECT id, maerke, model, doere, pris FROM skoleprojekter_bixen_biler WHERE type='lastvogn' ORDER BY pris DESC");
					while($lastvogn_data = mysqli_fetch_array($vis_lastvogn)) {
							$vis_antal = mysqli_query($db, "SELECT bil_id, kommentar FROM skoleprojekter_bixen_kommentar WHERE bil_id='$lastvogn_data[id]' AND godkendt='1'");
							$antal = mysqli_num_rows($vis_antal);
							echo "<tr><td class='celle'><p>".$lastvogn_data["maerke"]."</p></td>";
							echo "<td class='celle'><p>".$lastvogn_data["model"]."</p></td>";
							echo "<td class='celle'><p>".$lastvogn_data["doere"]."</p></td>";
							echo "<td class='celle'><p>".$lastvogn_data["pris"]." kr</p></td>";
							echo "<td class='celle'><a href='kommentar.php?id=$lastvogn_data[id]'>".$antal;
							if($antal > 1) {
								echo " kommentarer";
							} elseif ($antal == 0) {
								echo " kommentarer";
							} else {
								echo " kommentar";
							}
							echo "</a></td></tr>";
						}
					?>
					</table>
					<?php
					}
					if($_GET["page"]=="vrag") {
					?>
					<h1>Vrag</h1>
					<p>Her er listen over vrag</p>
					<table class="tabel">
						<tr>
							<td class="celle"><p class="top"><p>M&aelig;rke</p></td>
							<td class="celle"><p class="top"><p>Model</p></td>
							<td class="celle"><p class="top"><p>D&oslash;re</p></td>
							<td class="celle"><p class="top"><p>Pris</p></td>
							<td class="celle"><p class="top"><p>Kommentarer</p></td>
						</tr>
					<?php
					include("db.php");
					$vis_vrag = mysqli_query($db, "SELECT id, maerke, model, doere, pris FROM skoleprojekter_bixen_biler WHERE type='vrag' ORDER BY pris DESC");
					while($vrag_data = mysqli_fetch_array($vis_vrag)) {
						$vis_antal = mysqli_query($db, "SELECT bil_id, kommentar FROM skoleprojekter_bixen_kommentar WHERE bil_id='$vrag_data[id]' AND godkendt='1'");
						$antal = mysqli_num_rows($vis_antal);
						echo "<tr><td class='celle'><p>".$vrag_data["maerke"]."</p></td>";
						echo "<td class='celle'><p>".$vrag_data["model"]."</p></td>";
						echo "<td class='celle'><p>".$vrag_data["doere"]."</p></td>";
						echo "<td class='celle'><p>".$vrag_data["pris"]." kr</p></td>";
						echo "<td class='celle'><a href='kommentar.php?id=$vrag_data[id]'>".$antal;
						if($antal > 1) {
							echo " kommentarer";
						} elseif ($antal == 0) {
							echo " kommentarer";
						} else {
							echo " kommentar";
						}
						echo "</a></td></tr>";
					}
					?>
					</table>
					<?php
					}
					if($_GET["page"]=="login") {
					?>
					<h1>Forhandler login</h1>
					<form action="" method="post">
						<p><label for="forhandler_brugernavn">Brugernavn</label></p><input type="text" name="forhandler_brugernavn" id="felt" />
						<p><label for="forhandler_password">Password</label></p><input type="password" name="forhandler_password" id="felt" />
						<input type="submit" value="Login" id="knap" />
					</form>
					<?php
					}
					if($_GET["page"]=="admin") {
					?>
					<h1>Administrator login</h1>
					<form action="" method="post">
						<p><label for="admin_brugernavn">Brugernavn</label></p><input type="text" name="admin_brugernavn" id="felt" />
						<p><label for="adminpassword">Password</label></p><input type="password" name="admin_password" id="felt" />
						<input type="submit" value="Login" id="knap" />
					</form>
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