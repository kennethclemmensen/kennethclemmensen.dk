<?php
session_start();
include("db.php");
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
						<?php
						if($_SESSION["forhandler"] == true) {
						?>
						<li><a id="links" href="forhandler.php?page=forside">Forside</a></li>
						<li><a id="links" href="forhandler.php?page=forhandler_personbil">Personbil</a></li>
						<li><a id="links" href="forhandler.php?page=forhandler_lastvogn">Lastvogn</a></li>
						<li><a id="links" href="forhandler.php?page=forhandler_vrag">Vrag</a></li>
						<li><a id="links" href="logaf.php">Log af</a></li>		
						<?php
						} else {
						?>
						<li><a id="links" href="index.php?page=forside">Forside</a></li>
						<li><a id="links" href="index.php?page=personbiler">Personbiler</a></li>
						<li><a id="links" href="index.php?page=lastvogne">Last- & varevogne</a></li>
						<li><a id="links" href="index.php?page=vrag">Vrag</a></li>
						<li><a id="links" href="index.php?page=login">Forhandler login</a></li>
						<li><a id="links" href="index.php?page=admin">Administrator login</a></li>
						<?php
						}
						?>
					</ul>
				</div>
				<div id="content">
					<h1>Kommentare</h1>
					<table class="tabel">
						<tr>
							<td class="topcelle_kommentar"><p class="top">Bilen</p></td>
						</tr>
						<tr>
							<td class="celle_kommentar">
							<?php
							$id = $_GET["id"];							
							$sql = mysqli_query($db, "SELECT maerke, model FROM skoleprojekter_bixen_biler WHERE id='$id'");
							while($bil = mysqli_fetch_array($sql)) {
								echo "<p>".$bil["maerke"]." ".$bil["model"]."</p>";
							}
							?>
							</td>
						</tr>	
						<tr>
							<td class="celle_kommentar"><p class="top">Kommentar</p></td>
						</tr>						
						<?php
						$gammelKommentar = mysqli_query($db, "SELECT bil_id, kommentar, navn, dato, godkendt FROM skoleprojekter_bixen_kommentar WHERE bil_id='$id' AND godkendt='1' ORDER BY dato DESC");
						while($data_kommentar = mysqli_fetch_array($gammelKommentar)) {
							echo "<tr><td class='celle_kommentar'><p>Skrevet af <b>";
							echo $data_kommentar["navn"]."</b> den ".$data_kommentar["dato"]."<br/>".$data_kommentar["kommentar"];
							echo "</p></td></tr>";  
						}							
						?>
						<tr>
							<td class="celle_kommentar">
								<?php
								if($_SESSION["forhandler"] == true) {
								?>	
								<form action="" method="post">
									<textarea name="kommentar" id="kommentar"></textarea>
									<input type="submit" value="Tilf&oslash;j" id="knap" />
								</form>
								<?php
								}
								?>
							</td>
						</tr>
						<tr>
							<td>
								<?php
								if(isset($_POST["kommentar"])) {
									$kommentar = $_POST["kommentar"];
									$navn = $_SESSION["forhandler_navn"];
									$dato = date("j-n-Y")." kl ".date("H:i");
									$opretKommentar = mysqli_query($db, "INSERT INTO skoleprojekter_bixen_kommentar (bil_id, kommentar, navn, dato, godkendt) VALUES ('$id', '$kommentar', '$navn', '$dato', '0')");
									if($opretKommentar == true) {
										echo "<p>Kommentaren er tilf&oslash;jet</p>";
									} else {
										echo "<p>Kommentaren blev ikke tilf&oslash;jet</p>";
									}
								}
								?>
							</td>
						</tr>						
					</table>
				</div>
			</div>
			<div id="bund">
				<p>BIXEN - Munkebjergvej 130 - 5000 Odense M - Tlf. 12345678 - &Aring;bningstider: Man-fre 09.00-16.00</p>
			</div>
		</div>
	</body>
</html>