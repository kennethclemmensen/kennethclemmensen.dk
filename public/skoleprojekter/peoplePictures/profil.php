<?php
include("db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>People Pictures</title>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
				<div id="menu">
					<a href="index.php"><img src="images/logo_index.jpg" /></a>
				</div><!-- slut menu -->
			</div><!-- slut top -->
			<?php
			$vis = mysqli_query($db, "SELECT kendis_id, navn, st_billede, hojde, fodt, fodested FROM skoleprojekter_peoplepictures_kendis WHERE kendis_id='$_GET[id]'");
			while($visInfo = mysqli_fetch_array($vis)) {
			?>
			<div id="profil">
				<h1><?php echo $visInfo["navn"]; ?></h1>
			</div><!-- slut profil -->
			<div id="profil_billede1">
				<?php echo "<a href='profil.php?page=gallery&id=$_GET[id]'><img src=".$visInfo["st_billede"]." /></a>"; ?>
			</div><!-- slut profil_billede1 -->
			<div id="profil_billede2">
			</div><!-- slut profil_billede2 -->
			<div id="container">
			</div><!-- slut container -->
			<div id="samle">
				<div id="profil_news">
					<?php
					if($_GET["page"]=="gallery") {
						echo "<h1>Gallery</h1>";
					}
					if($_GET["page"]=="news") {
						echo "<h1>News</h1>";
					}
					if($_GET["page"]=="movie") {
						echo "<h1>Movie</h1>";
					}
					?>
				</div><!-- slut profil_news -->
				<div id="profil_info">
					<h1>Info</h1>
				</div><!-- slut profil_info -->
				<div id="profil_tekst">
					<?php
					if($_GET["page"]=="gallery") {
						$vis_billeder = mysqli_query($db, "SELECT url FROM skoleprojekter_peoplepictures_billeder WHERE kendis_id='$_GET[id]'");
						while($visBilleder = mysqli_fetch_array($vis_billeder)) {
							echo "<img src='images/billeder/thumb_".$visBilleder["url"]."' /></a>";
						}
					}
					if($_GET["page"]=="news") {
						$vis_nyheder = mysqli_query($db, "SELECT overskrift, tekst, dato FROM skoleprojekter_peoplepictures_nyheder WHERE id='$_GET[news_id]'");
						while($visNyheder = mysqli_fetch_array($vis_nyheder)) {
							echo "<h1>".$visNyheder["overskrift"]."</h1>";
							echo "<h2>".$visNyheder["dato"]."</h2>";
							echo "<p>".$visNyheder["tekst"]."</p>";
						}
					}
					if($_GET["page"]=="movie") {
						$vis_film = mysqli_query($db, "SELECT url, navn FROM skoleprojekter_peoplepictures_film WHERE id='$_GET[movie_id]'");
						while($visFilm = mysqli_fetch_array($vis_film)) {
							echo "<h1>".$visFilm["navn"]."</h1>";
							echo $visFilm["url"];
						}
					}
					?>
				</div><!-- slut profil_tekst -->
				<div id="profil_info2">
					<?php 
					echo "<h1>Navn</h1><p>".$visInfo["navn"]."</p>";
					echo "<h1>F&oslash;dt</h1><p>".$visInfo["fodt"]."</p>";
					echo "<h1>F&oslash;dested</h1><p>".$visInfo["fodested"]."</p>";
					echo "<h1>H&oslash;jde</h1><p>".$visInfo["hojde"]."</p>";
					?>
				</div><!-- slut profil_info2 -->
				<div id="profil_in_the_news">
					<?php echo "<a href='profil.php?page=news&id=$_GET[id]'><h1>".$visInfo["navn"]." in the news</h1></a>"; ?>
				</div><!-- profil_in_the_news -->
				<div id="profil_in_motion">
					<table>
					<?php
					echo "<a href='profil.php?page=movie&id=$_GET[id]'><h1>".$visInfo["navn"]." in motion</h1></a>";
					?>
					</table>
				</div><!-- slut profil_in_motion -->
				<div id="profil_links">
					<ul>
					<?php
					$vis_kendte_nyhed = mysqli_query($db, "SELECT id, overskrift FROM skoleprojekter_peoplepictures_nyheder WHERE kendis_id='$_GET[id]'");
					while($visKendteNyhed = mysqli_fetch_array($vis_kendte_nyhed)) {
						echo "<li><a href='profil.php?page=news&id=$_GET[id]&news_id=$visKendteNyhed[id]'>".$visKendteNyhed["overskrift"]."</a></li>";
					}
					?>
					</ul>
				</div><!-- slut profil_links -->
				<div id="profil_film">
					<table>
					<?php
					$vis_profil_film = mysqli_query($db, "SELECT id, navn FROM skoleprojekter_peoplepictures_film WHERE kendis_id='$_GET[id]'");
					while($visProfilFilm = mysqli_fetch_array($vis_profil_film)) {
						echo "<tr><td><img src='images/movie_picture.jpg' />";
						echo "<a href='profil.php?page=movie&id=$_GET[id]&movie_id=$visProfilFilm[id]'>".$visProfilFilm["navn"]."</a></td></tr>";
					}
					?>
					</table>
				</div><!-- slut profil_film -->
			</div><!-- slut samle -->
			<div id="container_bund">
			</div><!-- slut container_bund -->
			<?php
			} // slut whilel�kke
			?>
			<div id="search">
				<p>Search our database of Galleries:</p>
				<form action="index.php" method="post" name="soeg_formular" onsubmit="return valider_soeg();">
					<input type="text" name="soeg" id="soeg_felt" />
					<input type="submit" value="GO!" id="soeg_knap" />
				</form>
				<p>OR</p> <a href="index.php">View our entire list</a>
			</div><!-- slut soeg -->
		</div><!-- slut wrap -->
		<div id="luft">
		</div><!-- slut luft -->
	</body>
</html>