<?php
if(!isset($_GET['page'])) {
	$_GET['page'] = "forside";
}
include("includes/db.php");
include("includes/funktioner.php");
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Komponist Kasper Jarnum</title>
	</head>
	<body>
		<div id="top">
			<div id="top2"></div><!-- slut top2 -->
		</div><!-- slut top -->
		<div id="wrap">
			<div id="menu">
				<a href="index.php?page=forside" <?php echo style("forside"); ?>>Forside</a>
				<a href="index.php?page=biografi" <?php echo style("biografi"); ?>>Biografi</a>
				<a href="index.php?page=varkliste" <?php echo style("varkliste"); ?>>V&aelig;rkliste</a>
				<a href="index.php?page=tourneplan" <?php echo style("tourneplan"); ?>>Tourn�plan</a>
				<a href="index.php?page=links" <?php echo style("links"); ?>>Links</a>
				<a href="index.php?page=anmeldelser" <?php echo style("anmeldelser"); ?>>Anmeldelser</a>
				<a href="index.php?page=varkbestilling" <?php echo style("varkbestilling"); ?>>V&aelig;rkbestilling</a>
			</div><!-- slut menu -->
			<div id="samle">
				<div id="content">
					<?php
					if($_GET['page'] == "forside") {
					?>
					<h1>Velkommen</h1>
					<p>Velkommen til siden.</p>
					<?php
					}
					if($_GET['page'] == "biografi") {
						$sqlBiografi = mysqli_query($db, "SELECT tekst FROM skoleprojekter_komponist_biografi");
						$dataBiografi = mysqli_fetch_assoc($sqlBiografi);
						echo "<h1>Biografi</h1>";
						echo "<p>".$dataBiografi['tekst']."</p>";
					}
					if($_GET['page'] == "varkliste") {
					?>
					<h1>V&aelig;rkliste</h1>
					<?php
						$sql_vaerk = mysqli_query($db, "SELECT overskrift, beskrivelse FROM skoleprojekter_komponist_vaerkliste ORDER BY overskrift");
						while($data_vaerk = mysqli_fetch_assoc($sql_vaerk)) {
							echo "<h2>".$data_vaerk['overskrift']."</h2>";
							echo "<p>".$data_vaerk['beskrivelse']."</p>";
						}
					}
					if($_GET['page'] == "tourneplan") {
					?>
					<h1>Tourn�plan</h1>
					<p>En plan til min tourn�</p>
					<table>
						<tr>
							<td class="tour_celle_sted"><p><b>Sted</b></p></td>
							<td class="tour_celle"><p><b>Dato</b></p></td>
							<td class="tour_celle"><p><b>Tid</b></p></td>
						</tr>
						<?php
						$sql_tour = mysqli_query($db, "SELECT sted, dato, tid FROM skoleprojekter_komponist_tour ORDER BY dato");
						while($data_tour = mysqli_fetch_assoc($sql_tour)) {
						?>
						<tr>
							<td class="tour_celle_sted"><p><?php echo $data_tour['sted']; ?></p></td>
							<td class="tour_celle"><p><?php echo date("d-m-Y", $data_tour['dato']); ?></p></td>
							<td class="tour_celle"><p><?php echo $data_tour['tid']; ?></p></td>
						</tr>
						<?php
						}
						echo "</table>";
					}
					if($_GET['page'] == "links") {
					?>
					<h1>Links til cd-k&oslash;b</h1>
					<ul>
						<?php
						$sql_link = mysqli_query($db, "SELECT http, titel FROM skoleprojekter_komponist_links ORDER BY titel");
						while($data_link = mysqli_fetch_assoc($sql_link)) {
							echo "<li><a href='".$data_link['http']."' target='_blank'>".$data_link['titel']."</a></li>";
						}
						?>
					</ul>
					<?php	
					}
					if($_GET['page'] == "anmeldelser") {
					?>
					<h1>Anmeldelser</h1>
					<?php
					$pr_side = 3;
					$sql = mysqli_query($db, "SELECT anmelder, stjerner, tekst FROM skoleprojekter_komponist_anmeldelser WHERE godkendt='1' ORDER BY stjerner DESC");
					$antal = mysqli_num_rows($sql);
					if(isset($_GET['visfra']) && is_numeric($_GET['visfra']) && $_GET['visfra'] < $antal) {
						$vis_fra = $_GET['visfra'];
					} else {
						$vis_fra = 0;
					}
					$sql = mysqli_query($db, "SELECT anmelder, stjerner, tekst FROM skoleprojekter_komponist_anmeldelser WHERE godkendt='1' ORDER BY stjerner DESC LIMIT $vis_fra, $pr_side");
					while($data = mysqli_fetch_array($sql)) {
					    echo "<h2>".$data['anmelder']."</h2>";
						for($i = 1; $i <= $data['stjerner']; $i++) {
							echo "<img src='billeder/stjerne.gif' alt='Stjerne' />";
						}
						echo "<p>".$data['tekst']."</p>";
					}
					if($vis_fra > 0) {
					    $back = $vis_fra - $pr_side;
					    echo "<a href='index.php?page=anmeldelser&amp;visfra=$back'>Forrige</a> ";
					}
					if($vis_fra < $antal - $pr_side) {
					    $next = $vis_fra + $pr_side;
					    echo " <a href='index.php?page=anmeldelser&amp;visfra=$next'>N&aelig;ste</a>";
					}
					?>
					<form action="" method="post">
						<p><label for="anmelder">Anmelder</label></p>
						<input type="text" name="anmelder" id="anmelder" class="textfield" />
						<p><label for="stjerner">Stjerner</label></p>
						<select name="stjerner" id="stjerner">
						<?php
						for($i = 1; $i <= 6; $i++) {
							echo "<option value='".$i."'>".$i."</option>";
						}
						?>
						</select>
						<p><label for="tekst_anmeldelse">Tekst</label></p>
						<textarea name="tekst_anmeldelse" id="tekst_anmeldelse"></textarea>
						<input type="submit" value="Gem" id="knap" />
					</form>
					<?php
						if(isset($_POST['anmelder']) && $_POST['anmelder'] != "" && isset($_POST['tekst_anmeldelse']) && $_POST['tekst_anmeldelse']) {
							$opret_anmeldelse = mysqli_query($db, "INSERT INTO skoleprojekter_komponist_anmeldelser (anmelder, stjerner, tekst, godkendt) VALUES ('$_POST[anmelder]', '$_POST[stjerner]', '$_POST[tekst_anmeldelse]', '0')");
							if($opret_anmeldelse == true) {
								echo "<p>Anmeldelsen er oprettet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "varkbestilling") {
					?>
					<h1>V&aelig;rkbestilling</h1>
					<form action="" method="post">
						<div class="vaerk">
							<p><label for="minutter">Minutter</label></p>
							<select name="minutter" id="minutter">
								<?php
								$sql_minutter = mysqli_query($db, "SELECT minutter FROM skoleprojekter_komponist_priser ORDER BY minutter");
								while($data_minutter = mysqli_fetch_assoc($sql_minutter)) {
									echo "<option value='".$data_minutter['minutter']."'";
									if($data_minutter['minutter'] == $_POST['minutter']) {
										echo " selected='selected'";
									}
									echo ">".$data_minutter['minutter']."</option>";
								}
								?>
							</select>
						</div>
						<div class="vaerk">
							<p><label for="kategorier">Instrumenter</label></p>
							<select name="kategorier" id="kategorier">
								<option value="kategori_a">Solo</option>
								<option value="kategori_b">2-3</option>
								<option value="kategori_c">4-5</option>
								<option value="kategori_d">6-8</option>
								<option value="kategori_e">9-12</option>
								<option value="kategori_f">13-19</option>
								<option value="kategori_g">Over 20</option>
								<option value="kategori_h">Lille Kammeropera</option>
								<option value="kategori_i">Kammeropera</option>
								<option value="kategori_j">Store Opera</option>
							</select>
						</div>
						<input type="submit" name="udregn" id="udregn" value="Udregn pris" />
					</form>
					<?php
						if(isset($_POST['udregn'])) {
							$minutter = $_POST['minutter'];
							$kategorier = $_POST['kategorier'];
							$pris = mysqli_query($db, "SELECT ".$kategorier." FROM skoleprojekter_komponist_priser WHERE minutter='$minutter'");
							$data_pris = mysqli_fetch_assoc($pris);
							if($data_pris != 0) {
								$resultat = $data_pris[$kategorier];
							} else {
								$resultat = 0;
							}
							if($resultat == 0) {
								echo "<p>Det kan jeg ikke klare desv&aelig;rre :(</p>";
							}
						}
						if(isset($resultat) && $resultat != 0) {
						?>
						<form action="" method="post">
							<p><label for="resultat">Pris</label></p>
							<input type="text" name="resultat" readonly="readonly" value="<?php echo $resultat."kr"; ?>" id="resultat" />
							<p><label for="navn">Navn</label></p>
							<input type="text" name="navn" id="navn" class="bestilling" />
							<p><label for="adresse">Adresse</label></p>
							<input type="text" name="adresse" id="adresse" class="bestilling" />
							<p><label for="sted">Opf&oslash;relsessted</label></p>
							<input type="text" name="sted" id="sted" class="bestilling" />
							<input type="submit" value="Send" id="bestilling_knap" />
						</form>
					<?php
						}
						if(isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['adresse']) && $_POST['adresse'] != ""
						&& isset($_POST['sted']) && $_POST['sted'] != "") {
							/*$modtager = "kasper_jarnum@mail.dk";
							$emne = "V&aelig;rkbestilling";
							$besked = $_POST['navn']."\n";	
							$besked.= $_POST['adresse']."\n";	
							$besked.= $_POST['sted'];
							$besked.= "Minutter: ".$_POST['minutter'];
							$besked.= "Instrumenter: ".$_POST['kategorier'];
							$sendMail = mail($modtager, $emne, $besked);
							if($sendMail == true) {
								echo "<p>Du vil h&aoslash;re n&aelig;rmere</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}*/
						}
					}
					ob_flush();
					?>
				</div><!-- slut indhold -->
				<div id="hojre">
					<h2>Kasper Jarnum</h2>
					<img src="billeder/kj2.jpg" alt="Kasper Jarnum" title="Kasper Jarnum" />
					<h3>F&oslash;dt: Ja</h3>
					<h3>Alder: Over 20</h3>
					<h3>Andet info: N&aelig;h</h3>
				</div><!-- slut hojre -->
			</div><!-- slut samle -->
			<div id="bund">
				<p>&copy; Kasper Jarnum - E-mail <a href="mailto:kasper_jarnum@mail.dk">kasper-jarnum@mail.dk</a></p>
			</div><!-- slut bund -->
		</div><!-- slut wrap -->
	</body>
</html>