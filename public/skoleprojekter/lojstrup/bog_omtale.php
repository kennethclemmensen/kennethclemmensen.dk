<?php
if(isset($_GET['page'])) {
	$_GET['page'];
} else {
	$_GET['page'] = "forside";
}
include("db.php");
include("includes/funktioner.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>L&oslash;jstrup bibliotek - b&oslash;ger til b&oslash;rn, unge og voksne</title>
		<script type="text/javascript">
		function valider_kommentar_formular() {
			if(document.kommentar_formular.navn.value=="" ||
			document.kommentar_formular.kommentar.value=="") {
				alert("Du skal udfylde begge felter for at skrive en kommentar!");
				return false;
			}
		}
		function valider_stjerne_formular() {
			if(!document.stjerne_formular.stjerne.checked) {
				alert("Du skal v�lge en stjerne for at bed�mme bogen!");
				return false;
			}
		}
		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
			</div><!-- slut top -->
			<div id="samle">
				<div id="samle_menu">
					<h1>MENU</h1>
					<ul>
						<li><a href="index.php?page=forside">FORSIDEN</a></li>
						<li><a href="index.php?page=nye_boger">NYE B&Oslash;GER</a></li>
						<?php
						$vis_type = mysqli_query($db, "SELECT type FROM skoleprojekter_lojstrup_bog WHERE bog_id='$_GET[bog_id]'");
						while($visType = mysqli_fetch_array($vis_type)) {
						?>
							<li class="undermenu"
							<?php
							if($visType['type'] == "voksne") {						
								echo "style='color: orange;'";
							}
							?>><a 
							<?php
							if($visType['type'] == "voksne") {						
								echo "style='color: orange;'";
							}
							?>
							href="index.php?page=voksne">VOKSNE</a></li>
							<li class="undermenu"
							<?php
							if($visType['type'] == "unge") {						
								echo "style='color: orange;'";
							}
							?>><a 
							<?php
							if($visType['type'] == "unge") {						
								echo "style='color: orange;'";
							}
							?>
							href="index.php?page=unge">UNGE</a></li>
							<li class="undermenu"
							<?php
							if($visType['type'] == "born") {						
								echo "style='color: orange;'";
							}
							?>
							><a href="index.php?page=born"
							<?php
							if($visType['type'] == "born") {						
								echo "style='color: orange;'";
							}
							?>
							>B&Oslash;RN</a></li>
						<?php
						}
						?>
						<li><a href="index.php?page=arrangementer">ARRANGEMENTER</a></li>
						<li><a href="index.php?page=reglement">REGLEMENT</a></li>
						<li><a href="index.php?page=kontakt">KONTAKT</a></li>
						<li><a href="index.php?page=soeg">S&Oslash;G</a></li>
					</ul>
					<h1>&Aring;BNINGSTIDER</h1>
					<?php
					$vis_tider = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_aabning");
					while($visTider = mysqli_fetch_array($vis_tider)) { 
					?>
						<p class="dage">Mandag - onsdag</p>
						<p class="tid"><?php echo $visTider['mandag_onsdag_tid']; ?></p>
						<p class="dage">Torsdag</p>
						<p class="tid"><?php echo $visTider['torsdag_tid']; ?></p>
						<p class="dage">Fredag</p>
						<p class="tid"><?php echo $visTider['fredag_tid']; ?></p>
						<p class="dage">L&oslash;rdag</p>
						<p class="tid"><?php echo $visTider['lordag_tid']; ?></p>
					<?php
					}
					?>
					<h1>OM OS</h1>
					<?php
					$om_os = mysqli_query($db, "SELECT om FROM skoleprojekter_lojstrup_kontakt");
					while($omOs = mysqli_fetch_array($om_os)) {
						echo "<p>".$omOs['om']."</p>";
					}
					?>
				</div><!-- slut samle_menu -->
				<div id="omtale_indhold">	
					<?php
					$vis_bog = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_bog WHERE bog_id='$_GET[bog_id]'");
					while($visBog = mysqli_fetch_array($vis_bog)) {
					?>
					<table>
						<tr>
							<td>
								<img src="admin/<?php echo $visBog['billede']; ?>" width="133px" height="176px" alt="<?php echo $visBog['titel']; ?>" title="<?php echo $visBog['titel']; ?>" />
							</td>
							<td class="tekst_celle_hojre">
								<h2><?php echo $visBog['titel']; ?></h2>
								<p>Forfatter <?php echo $visBog['forfatter']; ?></p>
								<p>ISBN <?php echo $visBog['isbn']; ?></p>
								<p>Forlag <?php echo $visBog['forlag']; ?></p>
								<p>Antal sider <?php echo $visBog['sider']; ?></p>
								<p>Udgivelsesdato <?php echo $visBog['udgivelsesdato']; ?></p>
								<?php vis_stjerne($_GET['bog_id']); ?>
								<p class="textarea"></p>
							</td>
						</tr>
						</table>
						<table>
						<tr>
							<td>
								<p><?php echo $visBog['omtale']; ?></p>
							</td>
						</tr>
					</table>
					<form action="" method="post" class="omtale_formular" name="stjerne_formular" onsubmit="return valider_stjerne_formular();">
						<table>
							<tr>
								<td class="stjerne_tekst">
									<p class="tekst">Din bed�mmelse</p>
								</td>
								<td class="stjerne">
									<img src="billeder/extra/star.jpg" /><br/>
									<input type="radio" name="stjerne" value="1" />
								</td>
								<td class="stjerne">
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" /><br/>
									<input type="radio" name="stjerne" value="2" />
								</td>
								<td class="stjerne">
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" /><br/>
									<input type="radio" name="stjerne" value="3" />
								</td>
								<td class="stjerne">
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" /><br/>
									<input type="radio" name="stjerne" value="4" />
								</td>
								<td class="stjerne">
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" />
									<img src="billeder/extra/star.jpg" /><br/>
									<input type="radio" name="stjerne" value="5" />
									
								</td>
								<td class="knap_celle">
									<input type="submit" name="stjerne_knap" value="SEND" id="omtale_knap" />
								</td>
							</tr>
						</table>
					</form>
					<?php
					if(isset($_POST['stjerne']) && !empty($_POST['stjerne'])) {
						$stjerne = $_POST['stjerne'];
						$vis_stjerne = mysqli_query($db, "INSERT INTO skoleprojekter_lojstrup_afstemning_bog (bog_id, stjerne) VALUES ('$_GET[bog_id]', '$stjerne')");
						if($vis_stjerne == true) {
							echo "<p>Din stemme er gemt</p>";
						} else {
							echo "<p>Der er sket en fejl</p>";
						}
					}
					?>					
					<form action="" method="post" class="omtale_formular" name="kommentar_formular" onsubmit="return valider_kommentar_formular();">
						<table>
							<tr>
								<td>
									<p>Skriv dit navn:</p>
								</td>
								<td>
									<input type="text" name="navn" id="kommentar_navn" />
								</td>
							</tr>
							
							<tr>
								<td>
									<p>Skriv din kommentar:</p>
								</td>
								<td>
									<textarea name="kommentar" id="kommentar"></textarea>
								</td>
								<td>
									<input type="submit" value="SEND" id="omtale_knap2" />
								</td>
							</tr>
						</table>
					</form>
						<?php
						if(isset($_POST['navn']) && isset($_POST['kommentar']) && !empty($_POST['navn']) && !empty($_POST['kommentar'])) {
							$navn = $_POST['navn'];
							$kommentar = $_POST['kommentar'];
							$dato = date("j-m-Y");
							$opretKommentar = mysqli_query($db, "INSERT INTO skoleprojekter_lojstrup_kommentar (bog_id, arrangement_id, navn, dato, kommentar, godkendt) VALUES ('$_GET[bog_id]', '', '$navn', '$dato', '$kommentar', '0')");
							if($opretKommentar == true) {
								echo "<p>Kommentaren er tilf&oslash;jet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
						?>
					<table>
					<?php
					$vis_godkendt_kommentar = mysqli_query($db, "SELECT navn, dato, kommentar FROM skoleprojekter_lojstrup_kommentar WHERE bog_id='$_GET[bog_id]' AND godkendt='1'");
					while($visGodkendtKommentar = mysqli_fetch_array($vis_godkendt_kommentar)) {
					?>
					<tr>
						<td>
							<h3><?php echo $visGodkendtKommentar['navn']; ?></h3><h3><?php echo $visGodkendtKommentar['dato']; ?></h3>
						</td>
					</tr>
					<tr>
						<td>
							<p class="kommentar"><?php echo $visGodkendtKommentar['kommentar']; ?></p>
						</td>
					</tr>
					<?php
					}
					?>
				</table>
				<?php
				}
				?>			
				</div><!-- slut indhold -->
				<div id="samle_hojre">
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
					codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" 
					width="110" height="459" id="lojstrup"> 
					<param name="movie"  
					value="includes/lojstrup.swf" /> 
					<param name="quality" value="high" /> 
					<param name="bgcolor" value="#ffffff" /> 
					<embed src="includes/lojstrup.swf" quality="high" bgcolor="#ffffff"
					width="110" height="459" 
					name="lojstrup" align="" type="application/x-shockwave-flash" 
					pluginspage="http://www.macromedia.com/go/getflashplayer"> 
					</embed> 
					</object> 
				</div><!-- slut samle_hojre -->
				<div class="clear">
				</div><!-- slut clear -->
			</div><!-- slut samle -->
			<div id="bund">
				<?php
				$vis_bund_info = mysqli_query($db, "SELECT adresse, postnr, telefon, email FROM skoleprojekter_lojstrup_kontakt");
				while($visBundInfo = mysqli_fetch_array($vis_bund_info)) { 
					echo "<p>L&oslash;jstrup Bibliotek ::".$visBundInfo['adresse']." :: ".$visBundInfo['postnr']." :: ".$visBundInfo['telefon']." :: ".$visBundInfo['email']."</p>";
				}
				?>
			</div><!-- slut bund -->
		</div><!-- slut wrap -->
	</body>
</html>