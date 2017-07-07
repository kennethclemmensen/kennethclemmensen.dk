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
		function valider_kontakt_formular() {
			if(document.kontakt_formular.navn.value=="" ||
			document.kontakt_formular.adresse.value=="" ||
			document.kontakt_formular.postnr.value=="" ||
			document.kontakt_formular.telefon.value=="" ||
			document.kontakt_formular.email.value=="" ||
			document.kontakt_formular.besked.value=="") {
				alert("Du skal udfylde alle felter!");
				return false;
			}
		}
		function valider_soeg_formular() {
			if(document.soeg_formular.soeg.value=="") {
				alert("Du skal v�lge et s�geord!");
				return false;
			}
		}
		function valider_avanceret_soeg_formular() {
			if(document.avanceret_soeg_formular.soeg.value=="") {
				alert("Du skal v�lge et s�geord!");
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
					<?php
					include("includes/menu.php");
					?>
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
					if($_GET['page'] !== "kontakt") {
						echo "<h1>OM OS</h1>";
						$om_os = mysqli_query($db, "SELECT om FROM skoleprojekter_lojstrup_kontakt");
						while($omOs = mysqli_fetch_array($om_os)) {
							echo "<p>".$omOs['om']."</p>";
						}
					}
					?>
				</div><!-- slut samle_menu -->
				<div id="content">
					<?php
					if($_GET['page'] == "forside") {
						$sql_layout = "SELECT layout FROM skoleprojekter_lojstrup_forside";
						$result_layout = mysqli_query($db, $sql_layout);
						$row_layout = mysqli_fetch_array($result_layout);
						$layout = $row_layout['layout'];
						if($layout == "3boger") {
							$sql_bog = "SELECT * FROM skoleprojekter_lojstrup_bog ORDER BY tilfoj_dato DESC LIMIT 3";
							$result_bog = mysqli_query($db, $sql_bog);
							$side = "venstre";
							while($row_bog = mysqli_fetch_assoc($result_bog)) {
								if($side == "venstre") {
									vis_bog_venstre($row_bog);
									$side = "hojre";
									echo "<hr/>";
								} elseif($side == "hojre") {
									vis_bog_hojre($row_bog);
									$side = "venstre";
									echo "<hr/>";
								} 
							}
						} elseif ($layout == "3arrangementer") {
							$sql_arrangement = "SELECT * FROM skoleprojekter_lojstrup_arrangement ORDER BY dato DESC LIMIT 3";
							$result_arrangement = mysqli_query($db, $sql_arrangement);
							$side = "venstre";
							while($row_arrangement = mysqli_fetch_assoc($result_arrangement)) {
								if($side == "venstre") {
									vis_arrangement_venstre($row_arrangement);
									$side = "hojre";
									echo "<hr/>";
								} elseif($side == "hojre") {
									vis_arrangement_hojre($row_arrangement);
									$side = "venstre";
									echo "<hr/>";
								} 
							}
						} elseif ($layout == "1bog2arrangementer") {
							$sql_arrangement = "SELECT * FROM skoleprojekter_lojstrup_arrangement ORDER BY dato DESC LIMIT 2";
							$result_arrangement = mysqli_query($db, $sql_arrangement);
							$row_arrangement = mysqli_fetch_assoc($result_arrangement);
								vis_arrangement_venstre($row_arrangement);
								echo "<hr/>";
								
							$sql_bog = "SELECT * FROM skoleprojekter_lojstrup_bog ORDER BY tilfoj_dato DESC LIMIT 1";
							$result_bog = mysqli_query($db, $sql_bog);
							while($row_bog = mysqli_fetch_assoc($result_bog)) {
								vis_bog_hojre($row_bog);
								echo "<hr/>";
							}
							$row_arrangement = mysqli_fetch_assoc($result_arrangement);
								vis_arrangement_venstre($row_arrangement);
						} elseif ($layout == "2boger1arrangement") {
							$sql_bog = "SELECT * FROM skoleprojekter_lojstrup_bog ORDER BY tilfoj_dato DESC LIMIT 2";
							$result_bog = mysqli_query($db, $sql_bog);
							$row_bog = mysqli_fetch_assoc($result_bog);
								vis_bog_venstre($row_bog);
								echo "<hr/>";
								
							$sql_arrangement = "SELECT * FROM skoleprojekter_lojstrup_arrangement ORDER BY dato DESC LIMIT 1";
							$result_arrangement = mysqli_query($db, $sql_arrangement);
							while($row_arrangement = mysqli_fetch_assoc($result_arrangement)) {
								vis_arrangement_hojre($row_arrangement);
								echo "<hr/>";
							}
							$row_bog = mysqli_fetch_assoc($result_bog);
								vis_bog_venstre($row_bog);
						}
					}
					if($_GET['page'] == "nye_boger") {
						$vis_nye_boger = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_bog ORDER BY tilfoj_dato DESC LIMIT 4");
						$side = "h�jre";
						while($visNyeBoger = mysqli_fetch_array($vis_nye_boger)) {
							if($side == "h�jre") {
					?>
							<table>
								<tr>
									<td>
										<img src="admin/<?php echo $visNyeBoger['billede']; ?>" width="98px" height="118px" alt="<?php echo $visNyeBoger['titel']; ?>" title="<?php echo $visNyeBoger['titel']; ?>" />
									</td>
									<td class="tekst_celle_hojre">
										<h1><?php echo $visNyeBoger['titel']; ?></h1>
										<p>Forfatter <?php echo $visNyeBoger['forfatter']; ?></p>
										<p><?php echo $visNyeBoger['kort_omtale']; ?></p>
										<?php
										echo "<a href='bog_omtale.php?bog_id=$visNyeBoger[bog_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
								</tr>
							</table>
							<?php
							$side = "venstre";
							} else {
							?>
							<hr/>
							
							<table>
								<tr>
									<td class="tekst_celle_venstre">
										<h1><?php echo $visNyeBoger['titel']; ?></h1>
										<p>Forfatter <?php echo $visNyeBoger['forfatter']; ?></p>
										<p><?php echo $visNyeBoger['kort_omtale']; ?></p>
										<?php
										echo "<a href='bog_omtale.php?bog_id=$visNyeBoger[bog_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
									<td>
										<img src="admin/<?php echo $visNyeBoger['billede']; ?>" width="98px" height="118px" alt="<?php echo $visNyeBoger['titel']; ?>" title="<?php echo $visNyeBoger['titel']; ?>" />
									</td>							
								</tr>
							</table>
							<hr/>
						<?php
						$side = "h�jre";
							}
						}
					}
					if($_GET['page'] == "born") {
						$vis_nye_boger = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_bog WHERE type='born' ORDER BY tilfoj_dato DESC LIMIT 4");
						$side = "h�jre";
						while($visNyeBoger = mysqli_fetch_array($vis_nye_boger)) {
							if($side == "h�jre") {
					?>
							<table>
								<tr>
									<td>
										<img src="admin/<?php echo $visNyeBoger['billede']; ?>" width="98px" height="118px" alt="<?php echo $visNyeBoger['titel']; ?>" title="<?php echo $visNyeBoger['titel']; ?>" />
									</td>
									<td class="tekst_celle_hojre">
										<h1><?php echo $visNyeBoger['titel']; ?></h1>
										<p>Forfatter <?php echo $visNyeBoger['forfatter']; ?></p>
										<p><?php echo $visNyeBoger['kort_omtale']; ?></p>
										<?php
										echo "<a href='bog_omtale.php?bog_id=$visNyeBoger[bog_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
								</tr>
							</table>
							<?php
							$side = "venstre";
							} else {
							?>
							<hr/>
							
							<table>
								<tr>
									<td class="tekst_celle_venstre">
										<h1><?php echo $visNyeBoger['titel']; ?></h1>
										<p>Forfatter <?php echo $visNyeBoger['forfatter']; ?></p>
										<p><?php echo $visNyeBoger['kort_omtale']; ?></p>
										<?php
										echo "<a href='bog_omtale.php?bog_id=$visNyeBoger[bog_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
									<td>
										<img src="admin/<?php echo $visNyeBoger['billede']; ?>" width="98px" height="118px" alt="Verdens historie gennem tiderne" title="Verdens historie gennem tiderne" />
									</td>							
								</tr>
							</table>
							<hr/>
						<?php
							$side = "h�jre";
							}
						}
					}
					if($_GET['page'] == "unge") {
						$vis_nye_boger = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_bog WHERE type='unge'ORDER BY tilfoj_dato DESC LIMIT 4");
						$side = "h�jre";
						while($visNyeBoger = mysqli_fetch_array($vis_nye_boger)) {
							if($side == "h�jre") {
						?>
							<table>
								<tr>
									<td>
										<img src="admin/<?php echo $visNyeBoger['billede']; ?>" width="98px" height="118px" alt="<?php echo $visNyeBoger['titel']; ?>" title="<?php echo $visNyeBoger['titel']; ?>" />
									</td>
									<td class="tekst_celle_hojre">
										<h1><?php echo $visNyeBoger['titel']; ?></h1>
										<p>Forfatter <?php echo $visNyeBoger['forfatter']; ?></p>
										<p><?php echo $visNyeBoger['kort_omtale']; ?></p>
										<?php
										echo "<a href='bog_omtale.php?bog_id=$visNyeBoger[bog_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
								</tr>
							</table>
							<?php
							$side = "venstre";
							} else {
							?>
							<hr/>
							
							<table>
								<tr>
									<td class="tekst_celle_venstre">
										<h1><?php echo $visNyeBoger['titel']; ?></h1>
										<p>Forfatter <?php echo $visNyeBoger['forfatter']; ?></p>
										<p><?php echo $visNyeBoger['kort_omtale']; ?></p>
										<?php
										echo "<a href='bog_omtale.php?bog_id=$visNyeBoger[bog_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
									<td>
										<img src="admin/<?php echo $visNyeBoger['billede']; ?>" width="98px" height="118px" alt="Verdens historie gennem tiderne" title="Verdens historie gennem tiderne" />
									</td>							
								</tr>
							</table>
							<hr/>
						<?php
						$side = "h�jre";
							}
						}
					}
					if($_GET['page'] == "voksne") {
						$vis_nye_boger = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_bog WHERE type='voksne' ORDER BY tilfoj_dato DESC LIMIT 4");
						$side = "h�jre";
						while($visNyeBoger = mysqli_fetch_array($vis_nye_boger)) {
							if($side == "h�jre") {
					?>
							<table>
								<tr>
									<td>
										<img src="admin/<?php echo $visNyeBoger['billede']; ?>" width="98px" height="118px" alt="<?php echo $visNyeBoger['titel']; ?>" title="<?php echo $visNyeBoger['titel']; ?>" />
									</td>
									<td class="tekst_celle_hojre">
										<h1><?php echo $visNyeBoger['titel']; ?></h1>
										<p>Forfatter <?php echo $visNyeBoger['forfatter']; ?></p>
										<p><?php echo $visNyeBoger['kort_omtale']; ?></p>
										<?php
										echo "<a href='bog_omtale.php?bog_id=$visNyeBoger[bog_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
								</tr>
							</table>
							<?php
							$side = "venstre";
							} else {
							?>
							<hr/>
							
							<table>
								<tr>
									<td class="tekst_celle_venstre">
										<h1><?php echo $visNyeBoger['titel']; ?></h1>
										<p>Forfatter <?php echo $visNyeBoger['forfatter']; ?></p>
										<p><?php echo $visNyeBoger['kort_omtale']; ?></p>
										<?php
										echo "<a href='bog_omtale.php?bog_id=$visNyeBoger[bog_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
									<td>
										<img src="admin/<?php echo $visNyeBoger['billede']; ?>" width="98px" height="118px" alt="Verdens historie gennem tiderne" title="Verdens historie gennem tiderne" />
									</td>							
								</tr>
							</table>
							<hr/>
						<?php
						$side = "h�jre";
							}
						}
					}
					if($_GET['page'] == "arrangementer") {
						$vis_nye_arrangementer = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_arrangement ORDER BY dato DESC LIMIT 4");
						$side = "h�jre";
						while($visNyeArrangementer = mysqli_fetch_array($vis_nye_arrangementer)) {
							if($side == "h�jre") {
					?>
							<table>
								<tr>
									<td>
										<img src="admin/<?php echo $visNyeArrangementer['billede']; ?>" width="98px" height="118px" alt="<?php echo $visNyeArrangementer['titel']; ?>" title="<?php echo $visNyeArrangementer['titel']; ?>" />
									</td>
									<td class="tekst_celle_hojre">
										<h1><?php echo $visNyeArrangementer['titel']; ?></h1>
										<p>Taler <?php echo $visNyeArrangementer['taler']; ?></p>
										<p>Sted <?php echo $visNyeArrangementer['sted']; ?></p>
										<p>Dato <?php echo $visNyeArrangementer['dato']; ?></p>
										<p>Omtale <?php echo $visNyeArrangementer['kort_tekst']; ?></p>
										<?php
										echo "<a href='arrangement_omtale.php?arrangement_id=$visNyeArrangementer[arrangement_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
								</tr>
							</table>
							<?php
							$side = "venstre";
							} else {
							?>
							<hr/>
							
							<table>
								<tr>
									<td class="tekst_celle_venstre">
										<h1><?php echo $visNyeArrangementer['titel']; ?></h1>
										<p>Taler <?php echo $visNyeArrangementer['taler']; ?></p>
										<p>Sted <?php echo $visNyeArrangementer['sted']; ?></p>
										<p>Dato <?php echo $visNyeArrangementer['dato']; ?></p>
										<p>Omtale <?php echo $visNyeArrangementer['kort_tekst']; ?></p>
										<?php
										echo "<a href='arrangement_omtale.php?arrangement_id=$visNyeArrangementer[arrangement_id]'>:: l&aelig;s mere ::</a>";
										?>
									</td>
									<td>
										<img src="admin/<?php echo $visNyeArrangementer['billede']; ?>" width="98px" height="118px" alt="<?php echo $visNyeArrangementer['titel']; ?>" title="<?php echo $visNyeArrangementer['titel']; ?>" />
									</td>							
								</tr>
							</table>
							<hr/>
						<?php
						$side = "h�jre";
							}
						}
					}
					if($_GET['page'] == "reglement") {
					?>
					<h1>Reglement</h1>
					<?php
						$vis_reglement = mysqli_query($db, "SELECT skoleprojekter_reglement FROM lojstrup_kontakt");
						while($visReglement = mysqli_fetch_array($vis_reglement)) {
							echo "<p>".$visReglement['reglement']."</p>";
						}
						$vis_billeder = mysqli_query($db, "SELECT url FROM skoleprojekter_lojstrup_billeder ORDER BY dato LIMIT 7");
						while($visBilleder = mysqli_fetch_array($vis_billeder)) {
							echo "<img src='admin/".$visBilleder['url']."' />";
						}
					}
					if($_GET['page'] == "kontakt") {
					?>
					<table class="tabel">
						<tr>
							<td>
								<?php
								$vis_info = mysqli_query($db, "SELECT adresse, postnr, telefon, fax, email FROM skoleprojekter_lojstrup_kontakt");
								while($visInfo = mysqli_fetch_array($vis_info)) {
								?>
								<h1>Om biblioteket</h1>
								<p>L&oslash;jstrup Biblioteket</p>
								<p><?php echo $visInfo['adresse']; ?></p>
								<p><?php echo $visInfo['postnr']; ?></p>
								<table class="kontakt">
									<tr>
										<td>
											<p>Telefon:</p>
										</td>
										<td class="nummer">
											<p class="info"><?php echo $visInfo['telefon']; ?></p>
										</td>
									</tr>
									<tr>
										<td>
											<p>Fax:</p>
										</td>
										<td class="nummer">
											<p><?php echo $visInfo['fax']; ?></p>
										</td>
									</tr>
									<tr>
										<td>
											<p class="info">Email:</p>
										</td>
										<td class="nummer">
											<p><?php echo $visInfo['email']; ?></p>
										</td>										
									</tr>
									<?php
									}
									?>
								</table>
							</td>
							<td class="kort">
								<img src="billeder/extra/kort.jpg" alt="Kort" title="Kort" />
							</td>
						</tr>
					</table>
					<hr/>
					
					<h1>Kontakt</h1>
					<p>Du kan kontakte Biblioteket her.</p>
					<p>(Alle felter skal udfyldes)</p>
					<form action="" method="post" id="kontakt_formular" name="kontakt_formular" onsubmit="return valider_kontakt_formular();">
						<table>
							<tr>
								<td class="tekst_celle">
								</td>
								<td>
									<input type="text" name="navn" class="kontakt_felt" />
									<p class="kontakt_tekst"><label for="navn">Navn:</label></p>
								</td>
							</tr>
							<tr>
								<td class="tekst_celle">
								</td>
								<td>
									<input type="text" name="adresse" class="kontakt_felt" />
									<p class="kontakt_tekst"><label for="adresse">Adresse:</label></p>
								</td>
							</tr>
							<tr>
								<td class="tekst_celle">
								</td>
								<td>
									<input type="text" name="postnr" class="kontakt_felt" />
									<p class="kontakt_tekst"><label for="postnr">Postnr. & By:</label></p>
								</td>
							</tr>
							<tr>
								<td class="tekst_celle">
								</td>
								<td>
									<input type="text" name="telefon" class="kontakt_felt" />
									<p class="kontakt_tekst"><label for="telefon">Telefon:</label></p>
								</td>
							</tr>
							<tr>
								<td class="tekst_celle">
								</td>
								<td>	
									<input type="text" name="email" class="kontakt_felt" />
									<p class="kontakt_tekst"><label for="email">Email:</label></p>
								</td>
							</tr>
							<tr>
								<td>
								</td>
								<td class="besked_celle">
									<p class="besked_tekst"><label for="besked">Besked</label></p>
									<textarea name="besked" id="besked"></textarea>
								</td>
							</tr>
							<tr>
								<td>									
								</td>
								<td>
									<input type="submit" name="kontakt_submit" value="Send" class="kontakt_knap" />
									<input type="reset" value="Slet" class="kontakt_knap" />
								</td>
							</tr>
						</table>					
					</form>
					<?php
						/*if(isset($_POST['kontakt_submit'])) {
							$navn = $_POST['navn'];
							$adresse = $_POST['adresse'];
							$postnr = $_POST['postnr'];
							$telefon = $_POST['telefon'];
							$email = $_POST['email'];
							$besked = $_POST['besked'];
							$mail = $navn."<br/>".$adresse."<br/>".$postnr."<br/>".$telefon."<br/>".$email."<br/>".$besked;
							$sendMail = mail("kenneth.clemmensen@gmail.com", $navn, $mail);
							if($sendMail == true) {
								echo "<p class='fejl'>Mailen blev sendt</p>";
							} else {
								echo "<p class='fejl'>Der er sket en fejl</p>";
							}
						}*/
					}
					if($_GET['page'] == "soeg") {
						include("includes/soeg.php");					
					}
					if($_GET['page'] == "avanceret_soeg") {
						include("includes/avanceret_soeg.php");
					}
					?>
				</div><!-- slut indhold -->
				<div id="samle_hojre">
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
					codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" 
					width="110" height="459" id="lojstrup"> 
					<param name="movie" value="includes/lojstrup.swf" /> 
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