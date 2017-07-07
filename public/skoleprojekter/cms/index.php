<?php
if(isset($_GET['page'])) {
	$_GET['page'];
} else {
	$_GET['page'] = "forside";	
}
include("includes/db.php");
include("includes/funktioner.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<link rel="stylesheet" href="style/lightbox.css" type="text/css" media="screen" />
		<script type="text/javascript" src="js/prototype.js"></script>
		<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
		<script type="text/javascript" src="js/lightbox.js"></script>
		<title>NETavisen - gratis online nyheder</title>
	</head>
	<body>
		<div id="top">
		</div><!-- slut toppen -->
		<div id="wrap">
			<div id="top">
				<p>NETavisen - gratis online nyheder</p>
			</div><!-- slut top -->
			<div id="indhold_top">
				<div id="menu">
					<ul>
						<li><a href="index.php?page=forside" <?php echo style("forside"); ?>>Forside</a></li>
						<?php
						$sql_menu = "SELECT * FROM skoleprojekter_netavisen_menu WHERE parent='0' ORDER BY menu_id";
						$resultat_menu = mysqli_query($db, $sql_menu);
						while($data_menu = mysqli_fetch_assoc($resultat_menu)) {
							echo "<li><a href='index.php?page=$data_menu[titel]' ".style($data_menu['titel']).">".ucfirst($data_menu['titel'])."</a></li>\n";
						}
						?>
						<li><a href="index.php?page=galleri" <?php echo style("galleri"); ?>>Galleri</a></li>
					</ul>
				</div><!-- slut menu -->
			</div><!-- slut indhold_top -->
			<div id="samle_indhold">
				<div id="venstre">
					<?php
					if($_GET['page'] == "forside" || $_GET['page'] == "galleri" || $_GET['page'] == "artikel") {
					?>
					<ul>
						<li><a href="index.php?page=indland">Indland</a></li>
						<li><a href="index.php?page=udland">Udland</a></li>
						<li><a href="index.php?page=vejret">Vejret</a></li>
						<li><a href="index.php?page=finans">Finans</a></li>
						<li><a href="index.php?page=sport">Sport</a></li>
						<li><a href="index.php?page=galleri">Galleri</a></li>
					</ul>
					<?php
					} else if($_GET['page'] == "galleri_siden") {
						$sql_galleri = "SELECT * FROM skoleprojekter_netavisen_galleri ORDER BY navn";
						$resultat_galleri = mysqli_query($db, $sql_galleri);
						if(mysqli_num_rows($resultat_galleri) != 0) {
							echo "<ul>";
							while($data_galleri = mysqli_fetch_assoc($resultat_galleri)) {
								echo "<li><a href='index.php?page=galleri_siden&amp;galleri_id=$data_galleri[galleri_id]' ".style_galleri($data_galleri['galleri_id']).">".$data_galleri['navn']."</a></li>";
							}
							echo "</ul>";
						}
					} else {
						$sql_vis_kategori = "SELECT * FROM skoleprojekter_netavisen_menu WHERE titel='$_GET[page]'";
						$resultat_vis_kategori = mysqli_query($db, $sql_vis_kategori);
						while($data_vis_kategori = mysqli_fetch_assoc($resultat_vis_kategori)) {
							$sql_underkategori = "SELECT * FROM skoleprojekter_netavisen_menu WHERE parent='$data_vis_kategori[menu_id]'";
							$resultat_underkategori = mysqli_query($db, $sql_underkategori);
							$data_underkategori = mysqli_fetch_assoc($resultat_underkategori);
							if(!is_null($data_underkategori['parent'])) {
								echo getKategori($data_underkategori['parent'])."\n";
							} else {
								echo "<p>Der er ingen underkategorier</p>";
							}
						}
					}
					?>
				</div><!-- slut venstre -->
				<div id="content">
					<?php
					if($_GET['page'] == "forside") {
						echo "<h1>Velkommen</h1>";
					} else if($_GET['page'] == "artikel") {
						$sql_vis_nyhed = "SELECT * FROM skoleprojekter_netavisen_nyhed WHERE nyhed_id='$_GET[artikel_id]'";
						$resultat_vis_nyhed = mysqli_query($db, $sql_vis_nyhed);
						while($data_vis_nyhed = mysqli_fetch_assoc($resultat_vis_nyhed)) {
							echo "<p>Skrevet af ".$data_vis_nyhed['journalist']." ".date("d-m-Y", $data_vis_nyhed['start_dato'])."</p>";
							echo "<h1>".$data_vis_nyhed['overskrift']."</h1>";
							$sql_billede = "SELECT * FROM skoleprojekter_netavisen_billeder WHERE nyhed_id='$data_vis_nyhed[nyhed_id]' LIMIT 1";
							$resultat_billede = mysqli_query($db, $sql_billede);
							if(mysqli_num_rows($resultat_billede) != 0) {
								$data_billede = mysqli_fetch_assoc($resultat_billede);
								echo "<img src='billeder/nyheder/".$data_billede['sti']."' alt='".$data_billede['titel']."' title='".$data_billede['titel']."' />";
							}
							echo "<p>".$data_vis_nyhed['tekst']."</p>";
							echo "<a href='javascript: window.history.go(-1)'>Tilbage til listen</a>";
						}
					} else if($_GET['page'] == "galleri") {
						echo "<h1>Gallerier</h1>";
						echo "<table><tr>";
						$sql_galleri = "SELECT galleri_id, navn, beskrivelse FROM skoleprojekter_netavisen_galleri ORDER BY navn";
						$resultat_galleri = mysqli_query($db, $sql_galleri);
						while($data_galleri = mysqli_fetch_assoc($resultat_galleri)) {
							$sql_billede = "SELECT sti, titel FROM skoleprojekter_netavisen_billeder WHERE galleri_id='$data_galleri[galleri_id]' LIMIT 1";
							$resultat_billede = mysqli_query($db, $sql_billede);
							$data_billede = mysqli_fetch_assoc($resultat_billede);
							echo "<td class='galleri_celle'>";
							echo "<a href='index.php?page=galleri_siden&amp;galleri_id=$data_galleri[galleri_id]' title='".$data_galleri['navn']."'>";
							echo "<img src='billeder/galleri/".$data_billede['sti']."' alt='".$data_galleri['navn']."' title='".$data_galleri['navn']."' class='galleri_billede' /></a>";
							echo "<p>".$data_galleri['beskrivelse']."</p></td>";
						}
						echo "</tr></table>";
					} else if($_GET['page'] == "galleri_siden") {
						$sql_galleri = "SELECT navn, beskrivelse FROM skoleprojekter_netavisen_galleri WHERE galleri_id='$_GET[galleri_id]'";
						$resultat_galleri = mysqli_query($db, $sql_galleri);
						while($data_galleri = mysqli_fetch_assoc($resultat_galleri)) {
							echo "<h1>".$data_galleri['navn']."</h1>";
							echo "<p>".$data_galleri['beskrivelse']."</p>";
							echo "<table><tr>";
							$sql_billede = "SELECT * FROM netavisen_billeder WHERE galleri_id='$_GET[galleri_id]'";
							$resultat_billede = mysqli_query($db, $sql_billede);
							while($data_billede = mysqli_fetch_assoc($resultat_billede)) {
								echo "<td class='galleri_celle'>";
								echo "<a href='billeder/galleri/".$data_billede['sti']."' rel='lightbox[".$data_billede['galleri_id']."]' title='".$data_galleri['navn']."'>";
								echo "<img src='billeder/galleri/".$data_billede['sti']."' alt='".$data_billede['titel']."' title='".$data_billede['titel']."' class='galleri_billede' /></a></td>";	
							}
							echo "</tr></table>";
						}
					} else {
						$sql_overskrift = "SELECT * FROM skoleprojekter_netavisen_menu WHERE titel='$_GET[page]'";
						$resultat_overskrift = mysqli_query($db, $sql_overskrift);
						while($data_overskrift = mysqli_fetch_assoc($resultat_overskrift)) {
							echo "<h1>Nyhedsliste - ".ucfirst($data_overskrift['titel'])."</h1>\n";
							$sql_nyhed = "SELECT * FROM skoleprojekter_netavisen_nyhed WHERE menu_id='$data_overskrift[menu_id]' AND start_dato <= ".time()." AND slut_dato >= ".time();
							$resultat_nyhed = mysqli_query($db, $sql_nyhed);
							if(mysqli_num_rows($resultat_nyhed) != 0) {
								echo "<table>";
								while($data_nyhed = mysqli_fetch_assoc($resultat_nyhed)) {
									echo "<tr><td>";
									echo "<p class='dato'>".date("d-m-Y", $data_nyhed['start_dato'])."</p></td>";
									echo "<td><a href='index.php?page=artikel&amp;artikel_id=$data_nyhed[nyhed_id]' class='artikel_link'>".$data_nyhed['overskrift']."</a>\n";
									echo "</td></tr>";
								}
								echo "</table>";
							}
						}
					}
					?>
				</div><!-- slut indhold -->
				<div id="hojre">
					<a href="http://www.google.dk" target="_blank"><img src="billeder/google.jpg" alt="Annoncer" /></a>
					<a href="http://www.google.dk" target="_blank"><img src="billeder/google.jpg" alt="Annoncer" /></a>
					<a href="http://www.google.dk" target="_blank"><img src="billeder/google.jpg" alt="Annoncer" /></a>
				</div><!-- slut hojre -->
			</div><!-- slut samle_indhold -->
			<div id="luft">
			</div><!-- slut luft -->
		</div><!-- slut wrap -->
		<div id="footer">
			<p>NETavisen - gratis online nyheder | Avisvej 10 5000 Odense C |
			Tlf. 12 34 56 78 | E-mail: <a href="mailto:netavisen@mail.dk">netavisen@mail.dk</a></p>
		</div><!-- slut bunden -->
	</body>
</html>