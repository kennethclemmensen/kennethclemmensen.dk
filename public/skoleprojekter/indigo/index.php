<?php
if(!isset($_GET['page'])) {
	$_GET['page'] = "forside";
}
include("includes/db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Indigo</title>
		<script type="text/javascript">
		<!--
		function vis_billede(billede_id) {
			document.getElementById("udsmykning_2").innerHTML = "<img src='billeder/udsmykninger/" + billede_id + "' alt='Udsmykningsbillede' />";
		}
		//-->
		</script>
	</head>
	
	<body>
		<div id="wrap">
			<div id="top">
				<h1>Malerier Indigo Richards</h1>
			</div><!-- slut top -->
			<div id="menu">
				|<a href="index.php?page=forside">Forside</a>
				|<a href="index.php?page=nyheder">Nyheder</a>
				|<a href="index.php?page=malerier">Malerier</a>
				|<a href="index.php?page=kontakt">Kontakt</a>
				|<a href="index.php?page=udsmykninger">Udsmykninger</a>
				|<a href="index.php?page=cv">CV</a>
				|<a href="index.php?page=vaerksted">V&aelig;rksted</a>
				|<a href="index.php?page=in_english">In english</a>|
			</div><!-- slut menu -->
			<div id="content">
				<?php
				if($_GET['page'] == "forside") {
					echo "<img src='billeder/forside_billede.jpg' alt='forside billede' id='forside_billede' />";
				}
				if($_GET['page'] == "nyheder") {
					echo "<h1>Nyheder</h1>";
					$sql_nyhed = mysqli_query($db, "SELECT nyhed FROM skoleprojekter_indigo_nyheder ORDER BY dato DESC");
					while($data_nyhed = mysqli_fetch_assoc($sql_nyhed)) {
						echo "<p>".$data_nyhed['nyhed']."</p>";
					}
				}
				if($_GET['page'] == "malerier") {
					echo "<h1>Malerier</h1>";
					$sql_gallerier = mysqli_query($db, "SELECT galleri_id, navn FROM skoleprojekter_indigo_galleri ORDER BY navn");
					while($data_gallerier = mysqli_fetch_assoc($sql_gallerier)) {
						echo "<p>".$data_gallerier['navn']."</p>";
						$sql_billeder = mysqli_query($db, "SELECT sti, titel FROM skoleprojekter_indigo_billeder WHERE galleri_id='$data_gallerier[galleri_id]'");
						while($data_billeder = mysqli_fetch_assoc($sql_billeder)) {
							echo "<img src='billeder/galleri_billeder/".$data_billeder['sti']."' alt='".$data_billeder['titel']."' />";
						}
					}
				}
				if($_GET['page'] == "kontakt") {
					echo "<h1>Kontakt</h1>";
					$sqlKontakt = mysqli_query($db, "SELECT tekst FROM skoleprojekter_indigo_kontakt");
					$dataKontakt = mysqli_fetch_assoc($sqlKontakt);
					echo "<p>".$dataKontakt['tekst']."</p>";
				
					echo "<img src='billeder/kontakt_billede.jpg' alt='Kontakt billede' class='kontakt_billeder' />";
					echo "<img src='billeder/kontakt_billede_2.jpg' alt='Kontakt billede' class='kontakt_billeder' />";

				}
				if($_GET['page'] == "udsmykninger") {
				?>
				<h1>Udsmykninger</h1>
				<div id="udsmykning">
					<?php
					//hvis der er et billede til udsmykningen
					$sqlUdsmykning = mysqli_query($db, "SELECT udsmykning_id, udsmykning FROM skoleprojekter_indigo_udsmykning ORDER BY udsmykning");
					while($dataUdsmykning = mysqli_fetch_assoc($sqlUdsmykning)) {
						$sql_billede = mysqli_query($db, "SELECT sti, titel FROM skoleprojekter_indigo_billeder WHERE udsmykning_id='$dataUdsmykning[udsmykning_id]'");
						if(mysqli_num_rows($sql_billede) != 0) {
							echo "<p>".$dataUdsmykning['udsmykning']."</p>";
							while($data_billede = mysqli_fetch_assoc($sql_billede)) {
								$billedet =  $data_billede['sti'];
								echo "<img src='billeder/udsmykninger/".$billedet."' alt='".$data_billede['titel']."' onmouseover='vis_billede(\"$billedet\")' />";
							}
						}
					}
					?>
				</div><!-- slut udsmykning -->
				<div id="udsmykning_2">
					<img src="billeder/skorsten/skorsten_hoj.jpg" id="billede" alt="H&oslash;j skorsten" />
				</div><!-- slut udsmykning_2 -->
				<div id="udsmykning_3">
					<p>Andre udsmykninger</p>
					<?php
					//hvis der ikke er et billede til udsmykningen
					$sql_udsmykning = mysqli_query($db, "SELECT udsmykning_id, udsmykning FROM skoleprojekter_indigo_udsmykning ORDER BY udsmykning");
					while($data_udsmykning = mysqli_fetch_assoc($sql_udsmykning)) {
						$sql_billede = mysqli_query($db, "SELECT sti, titel FROM skoleprojekter_indigo_billeder WHERE udsmykning_id='$data_udsmykning[udsmykning_id]'");
						if(mysqli_num_rows($sql_billede) == 0) {
							echo "<p>".$data_udsmykning['udsmykning']."</p>";
						}
					}
					echo "</div>";
				}
				if($_GET['page'] == "cv") {
				echo "<h1>CV</h1>";
				$sqlKategori = mysqli_query($db, "SELECT kategori_id, navn FROM skoleprojekter_indigo_cv_kategori ORDER BY navn");
				while($dataKategori = mysqli_fetch_assoc($sqlKategori)) {
					echo "<h2>".$dataKategori['navn']."</h2>";
					$sqlCV = mysqli_query($db, "SELECT tekst FROM skoleprojekter_indigo_cv WHERE kategori_id='$dataKategori[kategori_id]'");
					if(mysqli_num_rows($sqlCV) != 0) {
						echo "<ul>";
						while($dataCV = mysqli_fetch_assoc($sqlCV)) {
							echo "<li>".$dataCV['tekst']."</li>";		
						}
						echo "</ul>";
					}
				}
				
				//<h2>Udstillinger, Seperat</h2>
				
				/*<ul>
					<li>"Appearance" Galleri Die Werkstatt, K&oslash;benhavn 1996</li>
					<li>"Fluer" D.F.K.U. Brandts Kl&aelig;defabrik, Odense 1998</li>
					<li>Cafe�Biografen, Odense 2000</li>
					<li>Comwell Galleri, Kolding v. Gallerie Rasmus 2001</li>
					<li>"Der er Serveret" Gallerie Rasmus, Kolding 2001</li>
					<li>Tv 2 Kunstforening 2002</li>
					<li>Gallerie Rasmus, Odense 2005</li>
					<li>Gallerie Rasmus, K&oslash;benhavn 2006</li>
				</ul>*/
				}
				if($_GET['page'] == "vaerksted") {
				?>
				<h1>V&aelig;rksted</h1>
				<?php
				}
				if($_GET['page'] == "in_english") {
				?>
				<h1>In english</h1>
				<p>Something engelsk are going to st&aring; her.</p>
				<?php
				}
				?>
			</div><!-- slut indhold -->
			<div id="clear"></div><!-- slut clear -->
		</div><!-- slut wrap -->
	</body>
</html>