<?php
session_start();
if($_SESSION["forhandler"]!==true) {
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
		<title>BIXEN - Forhandler</title>
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
						<li><a id="links" href="forhandler.php?page=forside">Forside</a></li>
						<li><a id="links" href="forhandler.php?page=forhandler_personbil">Personbil</a></li>
						<li><a id="links" href="forhandler.php?page=forhandler_lastvogn">Lastvogn</a></li>
						<li><a id="links" href="forhandler.php?page=forhandler_vrag">Vrag</a></li>
						<li><a id="links" href="logaf.php">Log af</a></li>
					</ul>
				</div>
				<div id="content">
				<?php
				if($_GET["page"]=="forside") {
				?>
				<h1>Forhandler</h1>
				<p>Her kan du som forhandler tilf&oslash;je kommentare til bilerne. Priserne vises uden moms og med 10% rabat.</p>
				<?php
				}
				if($_GET["page"]=="forhandler_personbil") {
				?>
				<h1>Personbil</h1>
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
					$forhandler_personbil = mysqli_query($db, "SELECT id, maerke, model, doere, pris FROM skoleprojekter_bixen_biler WHERE type='personbil' ORDER BY pris DESC");
					while($personbil_data = mysqli_fetch_array($forhandler_personbil)) {
						$pris = $personbil_data["pris"];
						$pris_rabat_moms = $pris*30/100;
						$forhandlerpris = $pris-$pris_rabat_moms;
						$vis_antal = mysqli_query($db, "SELECT bil_id, kommentar FROM skoleprojekter_bixen_kommentar WHERE bil_id='$personbil_data[id]' AND godkendt='1'");
						$antal = mysqli_num_rows($vis_antal);
						echo "<td class='celle'><p>".$personbil_data["maerke"]."</p></td>";
						echo "<td class='celle'><p>".$personbil_data["model"]."</p></td>";
						echo "<td class='celle'><p>".$personbil_data["doere"]."</p></td>";
						echo "<td class='celle'><p>".$forhandlerpris." kr</p></td>";
						echo "<td class='celle'><a href='kommentar.php?id=$personbil_data[id]'>".$antal;
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
				if($_GET["page"]=="forhandler_lastvogn") {
				?>
				<h1>Lastvogn</h1>
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
					$forhandler_lastvogn = mysqli_query($db, "SELECT id, maerke, model, doere, pris FROM skoleprojekter_bixen_biler WHERE type='lastvogn' ORDER BY pris DESC");
					while($lastvogn_data = mysqli_fetch_array($forhandler_lastvogn)) {
						$pris = $lastvogn_data["pris"];
						$pris_rabat_moms = $pris*30/100;
						$forhandlerpris = $pris-$pris_rabat_moms;	
						$vis_antal = mysqli_query($db, "SELECT bil_id, kommentar FROM skoleprojekter_bixen_kommentar WHERE bil_id='$lastvogn_data[id]' AND godkendt='1'");
						$antal = mysqli_num_rows($vis_antal);
						echo "<td class='celle'><p>".$lastvogn_data["maerke"]."</p></td>";
						echo "<td class='celle'><p>".$lastvogn_data["model"]."</p></td>";
						echo "<td class='celle'><p>".$lastvogn_data["doere"]."</p></td>";
						echo "<td class='celle'><p>".$forhandlerpris." kr</p></td>";
						echo "<td class='celle'><a href='kommentar.php?id=$lastvogn_data[id]'>".$antal;
						if($antal > 1) {
							echo " kommentarer</a>";
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
				if($_GET["page"]=="forhandler_vrag") {
				?>
				<h1>Vrag</h1>
				<table class="tabel">
					<tr>
						<td class="celle"><p class="top"><p>M&aelig;rke</p></td>
						<td class="celle"><p class="top"><p>Model</p></td>
						<td class="celle"><p class="top"><p>D&oslash;re</p></td>
						<td class="celle"><p class="top"><p>Pris</p></td>
						<td class="celle"><p class="top"><p>Kommentare</p></td>
					</tr>
				<?php	
					include("db.php");
					$forhandler_vrag = mysqli_query($db, "SELECT id, maerke, model, doere, pris FROM skoleprojekter_bixen_biler WHERE type='vrag' ORDER BY pris DESC");
					while($vrag_data = mysqli_fetch_array($forhandler_vrag)) {
						$pris = $vrag_data["pris"];
						$pris_rabat_moms = $pris*30/100;
						$forhandlerpris = $pris-$pris_rabat_moms;
						$vis_antal = mysqli_query($db, "SELECT bil_id, kommentar FROM skoleprojekter_bixen_kommentar WHERE bil_id='$vrag_data[id]' AND godkendt='1'");
						$antal = mysqli_num_rows($vis_antal);
						echo "<td class='celle'><p>".$vrag_data["maerke"]."</p></td>";
						echo "<td class='celle'><p>".$vrag_data["model"]."</p></td>";
						echo "<td class='celle'><p>".$vrag_data["doere"]."</p></td>";
						echo "<td class='celle'><p>".$forhandlerpris." kr</p></td>";
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
				?>
				</div>
			</div>
			<div id="bund">
				<p>BIXEN - Munkebjergvej 130 - 5000 Odense M - Tlf. 12345678 - &Aring;bningstider: Man-fre 09.00-16.00</p>
			</div>
		</div>
	</body>
</html>