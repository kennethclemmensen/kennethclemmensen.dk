<?php
//tjekker om $_GET['page'] er sat. Hvis den ikke er det er siden forside
if(isset($_GET['page'])) {
	$_GET['page'];
} else {
	$_GET['page'] = "forside";
}
include("includes/db.php");
include("includes/funktioner.php");
include("includes/class.validering.php");
$valider = new validering();
session_start();
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>
		<?php
		//tjekker om siden er forside
		if($_GET['page'] == "forside") {
			echo "CMK M&oslash;bler";
		}
		//tjekker om siden er nyheds_arkiv
		if($_GET['page'] == "nyheds_arkiv") {
			echo "CMK M&oslash;bler - Nyheds arkiv";		
		}
		//tjekker om siden er mobler
		if($_GET['page'] == "mobler" || $_GET['page'] == "resultat" || $_GET['page'] == "beskrivelse") {
			echo "CMK M&oslash;bler - M&oslash;bler";
		}
		//tjekker om siden er kontakt
		if($_GET['page'] == "kontakt") {
			echo "CMK M&oslash;bler - Kontakt";
		}
		?>
		</title>
		<script type="text/javascript">
		function valider_kontakt() {
			if(document.kontakt_form.navn.value == "" ||
			document.kontakt_form.adresse.value == "" ||
			document.kontakt_form.telefon.value == "" ||
			document.kontakt_form.email.value == "" ||
			document.kontakt_form.kommentar.value == "") {
				alert("Alle felter skal udfyldes!");
				return false;
			}
		}
		</script>
	</head>
	<body>
		<div id="top">
		</div><!-- slut top -->
		<div id="menu">
			<a href="index.php?page=forside" id="forside_link" <?php echo style("forside"); ?>>Forside</a>
			<a href="index.php?page=nyheds_arkiv" <?php echo style("nyheds_arkiv"); ?>>Nyheds arkiv</a>
			<a href="index.php?page=mobler" <?php echo style("mobler"); echo style("beskrivelse"); ?>>M&oslash;bler</a>
			<a href="index.php?page=kontakt" <?php echo style("kontakt"); ?>>Kontakt</a>
		</div><!-- slut menu -->
		<div id="wrap">
			<div id="venstre">
				<?php
				if($_GET['page'] == "forside") {
					echo "<h1 class='overskrift'>Forsiden</h1>";
				}
				if($_GET['page'] == "nyheds_arkiv") {
					echo "<h1 class='overskrift'>Nyheds arkiv</h1>";
				}
				if($_GET['page'] == "mobler" || $_GET['page'] == "resultat" || $_GET['page'] == "beskrivelse") {
					echo "<h1 class='overskrift'>M&oslash;bler</h1>";
				}
				if($_GET['page'] == "kontakt") {
					echo "<h1 class='overskrift'>Kontakt</h1>";
				}
				$sql_info = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_mobel ORDER BY rand() LIMIT 1");
				while($data_info = mysqli_fetch_assoc($sql_info)) {
					$billede = mysqli_query($db, "SELECT sti, titel FROM skoleprojekter_cmk_billeder WHERE mobel_id='$data_info[mobel_id]' LIMIT 1");
					$vis_billede = mysqli_fetch_assoc($billede);
					echo "<img src='billeder/mobler/".$vis_billede['sti']."' alt='".$vis_billede['titel']."' />";
					$serie = mysqli_query($db, "SELECT navn FROM skoleprojekter_cmk_modelserie WHERE serie_id='$data_info[serie_id]'") or die(mysqli_error($db));
					$vis_serie = mysqli_fetch_assoc($serie);
					echo "<p><b>M&oslash;belserie</b></p>";
					echo "<p>".$vis_serie['navn']."</p>";
					$designer = mysqli_query($db, "SELECT navn FROM skoleprojekter_cmk_designer WHERE designer_id='$data_info[designer_id]'");
					$vis_designer = mysqli_fetch_assoc($designer);
					echo "<p><b>Designer</b></p>";
					echo "<p>".$vis_designer['navn']."</p>";
					echo "<p><b>Design &aring;r</b></p>";
					echo "<p>".$data_info['design_aar']."</p>";
					echo "<p><b>".Pris."</b></p>";
					echo "<p>".$data_info['pris']." kr</p>";
					echo "<a href='index.php?page=beskrivelse&amp;mobel_id=$data_info[mobel_id]'>mere info</a>";
				}
				?>
				<h1 class="overskrift" id="nyhedsbrev">Tilmeld nyhedsbrev</h1>
				<form action="" method="post" name="mail_form" id="mail_form">
					<input type="text" name="email" id="email" value="E-mail" onfocus="if (this.value=='E-mail'){this.value=''}" onblur="if (this.value==''){this.value='E-mail'}" />
					<input type="submit" name="tilmeld" class="email_knap" value="Tilmeld" /> 
					<input type="submit" name="frameld" class="email_knap" value="Frameld" /> 
				</form>
				<?php
				if(isset($_POST['email']) && $_POST['email'] != "") {
					if($valider->mail($_POST['email'], "Emailadressen er ikke gyldig")) {
						if(isset($_POST['tilmeld'])) {
							$tilmeld = mysqli_query($db, "INSERT INTO skoleprojekter_cmk_nyhedsbrev (modtager) VALUES ('$_POST[email]')");
							if($tilmeld == true) {
								echo "<p class='fejl'>Du er nu tilmeldt</p>";
							} else {
								echo "<p class='fejl'>Der er sket en fejl</p>";
							}
						}
						if(isset($_POST['frameld'])) {
							$frameld = mysqli_query($db, "DELETE FROM skoleprojekter_cmk_nyhedsbrev WHERE modtager='$_POST[email]'");
							if($frameld == true) {
								echo "<p class='fejl'>Du er nu frameldt</p>";
							} else {
								echo "<p class='fejl'>Der er sket en fejl</p>";
							}
						}
					} else {
						echo "<p class='fejl'>Emailadressen er ikke gyldig</p>";
					}
				}
				$sql_kontakt = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_kontakt");
				while($data_kontakt = mysqli_fetch_assoc($sql_kontakt)) {
				?>
				<h1 class="overskrift">Kontakt oplysninger</h1>
				<p><b>CMK</b> m&oslash;bler</p>
				<p><?php echo $data_kontakt['adresse']; ?></p>
				<p><?php echo $data_kontakt['postnr_by']; ?></p>
				
				<p id="telefon"><b>Telefon</b></p>
				<p><?php echo $data_kontakt['telefon']; ?></p>
				
				<p><b>Telefax</b></p>
				<p><?php echo $data_kontakt['telefax']; ?></p>
				<?php
				}
				?>
			</div><!-- slut venstre -->
			<div id="content">
				<?php
				//tjekker om det er forsiden
				if($_GET['page'] == "forside") {
					$sql_nyhed = mysqli_query($db, "SELECT overskrift, tekst FROM skoleprojekter_cmk_nyheder ORDER BY dato DESC LIMIT 3");
					while($data_nyhed = mysqli_fetch_assoc($sql_nyhed)) {
						echo "<div class=\"nyheder\">";
						echo "<h1>".$data_nyhed['overskrift']."</h1>";
						echo "<p>".$data_nyhed['tekst']."</p>";
						echo "</div>";
					}
				}
				//tjekker om siden er nyheds_arkiv
				if($_GET['page'] == "nyheds_arkiv") {
					$nyhed = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_nyheder ORDER BY dato DESC");
					while($data = mysqli_fetch_assoc($nyhed)) {
					?>
					<table class="nyheds_arkiv">
						<tr>
							<td class="overskrift_celle">
								<h1><?php echo $data['overskrift']; ?></h1>
							</td>
							<td class="dato_celle">
								<?php
								$dato = date("j-n-Y", $data['dato']);
								$dato = explode("-", $dato);
								$dag = $dato[0];
								$maaneder = array("", "januar", "februar", "marts", "april", "maj", "juni", "juli", "august", "september", "oktober", "november", "december");
								$maaned = $maaneder[$dato[1]];
								$aar = $dato[2];
								$tid = "den ".$dag.". ".$maaned." ".$aar;
								echo "<p>".$tid."</p>";
								?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<p class="nyhed_tekst"><?php echo $data['tekst']; ?></p>
							</td>
						</tr>
						<tr>
							<td class="navn_celle" colspan="2">
								<p class="navn"><?php echo $data['forfatter']; ?></p>
							</td>
						</tr>
					</table>
				<?php
					}
				}
				//tjekker om siden er mobler
				if($_GET['page'] == "mobler") {
					if(isset($_POST['vare_nr']) && $_POST['vare_nr'] != "") {
						$sql_soeg = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_mobel WHERE varenummer LIKE '%".$_POST['vare_nr']."%' ORDER BY varenummer ");
						if(mysqli_num_rows($sql_soeg) != 0) {
							//hvis $soeg_resultat er ja bliver formularen ikke vist
							$soeg_resultat = "ja";
							while($data_soeg = mysqli_fetch_assoc($sql_soeg)) {
								/*echo $data_soeg['mobel_id']."<br/>";
								$resultat[] = $data_soeg['mobel_id'];*/
								$billede_resultat = mysqli_query($db, "SELECT sti, titel FROM skoleprojekter_cmk_billeder WHERE mobel_id='$data_soeg[mobel_id]' LIMIT 1");
								$data_billede = mysqli_fetch_assoc($billede_resultat);
							?>
								<div class="resultat_soeg">
								<a href="index.php?page=beskrivelse&amp;mobel_id=<?php echo $data_soeg['mobel_id']; ?>">
								<table>
									<tr>
										<td class="resultat_celle">
											<img src="billeder/mobler/<?php echo $data_billede['sti']; ?>" alt="<?php echo $data_billede['titel']; ?>" />
										</td>
										<td>
											<h1 class="resultat_overskrift"><?php echo $data_soeg['navn']; ?></h1>
											<?php
											if(strlen($data_soeg['beskrivelse']) > 187) {
												echo "<p>".substr($data_soeg['beskrivelse'], 0, 183)." ...</p>";
											} else {
												echo "<p>".$data_soeg['beskrivelse']."</p>";
											}
											?>
										</td>
									</tr>
								</table>
								</a>
							</div>
							<?php
							}
						} else {
							echo "<p id='ingen_resultater'>Der er desv&aelig;rre ikke nogen emner der matcher dine kriterier.<br/>
							Vi anbefaler at du udvider din s&oslash;gning og pr&oslash;ver igen.</p>";
						}
					}
					if(isset($_POST['avanceret_soeg'])) {
						include("includes/avanceret_soeg.php");
					}
					if(isset($soeg_resultat) && $soeg_resultat == "ja") {
						echo "";
					} else {
					?>
					<form action="" method="post">
						<table>
							<tr>
								<td class="soeg_celle">
									<p>Vare nr.</p>
								</td>
								<td>
									<input type="text" name="vare_nr" value="<?php echo $_POST['vare_nr']; ?>" id="soeg_felt" />
								</td>
								<td>
									<input type="submit" id="soeg_knap" value="S&oslash;g" />
								</td>
							</tr>
						</table>
					</form>
					<form action="" method="post">
						<table id="avanceret_soeg">
							<tr>
								<td class="soeg_celle">
									<p>M&oslash;belserie:</p>
								</td>
								<td colspan="2">
									<input type="checkbox" name="mobelserie" value="Sofa" class="soeg_check" /><p class="soeg_tekst">Sofa</p>
									<input type="checkbox" name="mobelserie" value="Sofabord" class="soeg_check2" /><p class="soeg_tekst">Sofabord</p>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="2">
									<input type="checkbox" name="mobelserie" value="Spisestol" class="soeg_check" /><p class="soeg_tekst">Spisestol</p>
									<input type="checkbox" name="mobelserie" value="Spisebord" class="soeg_check3" /><p class="soeg_tekst">Spisebord</p>
								</td>
							</tr>
							<tr>
								<td class="soeg_celle">
									<p>Designer:</p>
								</td>
								<td colspan="2">
									<select name="designer" id="designer">
										<option value="alle">Alle</option>
										<?php
										$alle_designere = mysqli_query($db, "SELECT designer_id, navn FROM skoleprojekter_cmk_designer ORDER BY navn");
										while($data_alle_designere = mysqli_fetch_assoc($alle_designere)) {
											echo "<option value='$data_alle_designere[designer_id]'";
											echo ">".$data_alle_designere['navn']."</option>";
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="soeg_celle">
									<p>Design &aring;r:</p>
								</td>
								<td>
									<p class="min">min</p><input type="text" class="tal" name="min_ar" value="1999" />
								</td>
								<td>
									<p class="min">max</p><input type="text" class="tal" name="max_ar" value="2006" />
								</td>
							</tr>
							<tr>
								<td class="soeg_celle">
									<p>Pris:</p>
								</td>
								<td>
									<p class="min">min</p><input type="text" class="tal" name="min_pris" value="0" />
								</td>
								<td>
									<p class="min">max</p><input type="text" class="tal" name="max_pris" value="10000" />
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<input type="submit" name="avanceret_soeg" id="soeg_knap2" value="S&oslash;g" />
								</td>
							</tr>
						</table>
					</form>
					<?php
					}
				}
				//tjekker om siden er beskrivelse
				if($_GET['page'] == "beskrivelse") {
					$sql_mobel = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_mobel WHERE mobel_id='$_GET[mobel_id]'");
					while($data_mobel = mysqli_fetch_assoc($sql_mobel)) {
					?>
					<div id="beskrivelse">
						<table>
							<tr id="beskrivelse_rakke">
								<td>
									<h1 class="beskrivelse_overskrift"><?php echo $data_mobel['navn']; ?></h1>
									<?php
									$sql_top_billde = mysqli_query($db, "SELECT billede_id, sti, titel FROM skoleprojekter_cmk_billeder WHERE mobel_id='$data_mobel[mobel_id]' LIMIT 1");
									while($data_top_billede = mysqli_fetch_assoc($sql_top_billde)) {
											echo "<img src='billeder/mobler/".$data_top_billede['sti']."' alt='".$data_top_billede['titel']."' />";
									?>
								</td>
								<td>
									<p><?php echo $data_mobel['beskrivelse']; ?></p>
								</td>
							</tr>
							<tr id="beskrivelse_rakke2">
								<td>
									<h1 class="beskrivelse_overskrift">Andre varianter</h1>
									<?php
									$sql_billede = mysqli_query($db, "SELECT billede_id, sti, titel FROM skoleprojekter_cmk_billeder WHERE mobel_id='$data_mobel[mobel_id]' AND billede_id != '$data_top_billede[billede_id]'");
									while($data_billede = mysqli_fetch_assoc($sql_billede)) {
									?>
									<a href="billeder/mobler/<?php echo $data_billede['sti']; ?>" target="_blank"><img src="billeder/mobler/<?php echo $data_billede['sti']; ?>" alt="<?php echo $data_billede['titel']; ?>" /></a>
									<?php
										}
									}
									?>
								</td>
								<td>
									<p><b>Pris:</b> <?php echo $data_mobel['pris']; ?> kr<br/>
									<b>Designer:</b> 
									<?php 
									$sql_designer = mysqli_query($db, "SELECT navn FROM skoleprojekter_cmk_designer WHERE designer_id='$data_mobel[designer_id]'");
									while($data_designer = mysqli_fetch_assoc($sql_designer)) {
										echo $data_designer['navn'];
									}
									?><br/>
									<b>M&oslash;belserie:</b> 
									<?php
									$sql_serie = mysqli_query($db, "SELECT navn FROM skoleprojekter_cmk_mobelserie WHERE serie_id='$data_mobel[serie_id]'");
									while($data_serie = mysqli_fetch_assoc($sql_serie)) {
										echo $data_serie['navn'];
									}
									?>
									<br/>
									<b>Design &aring;r:</b> <?php echo $data_mobel['design_aar']; ?><br/>
									<b>Varenr:</b> <?php echo $data_mobel['varenummer']; ?></p>
								</td>
							</tr>
						</table>
					</div><!-- slut beskrivelse -->
					<?php
					}
				}
				//tjekker om siden er kontakt
				if($_GET['page'] == "kontakt") {
					$sql_aabning = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_aabningstider");
					while($data_aabning = mysqli_fetch_assoc($sql_aabning)) {
					?>
					<p><b>&Aring;bningstider:</b><br/>
					Mandag-Torsdag fra <?php echo $data_aabning['mandag']; ?><br/>
					Fredag fra <?php echo $data_aabning['fredag']; ?><br/>
					L&oslash;rdag fra <?php echo $data_aabning['lordag']; ?></p>
					<?php 
					}
					?>
				
				<form action="" method="post" id="kontakt_form" name="kontakt_form" onsubmit="valider_kontakt()">
					<table>
						<tr>
							<td class="kontakt_celle">
								<p>Navn</p>
							</td>
							<td>
								<input type="text" name="navn" class="kontakt_felt" value="<?php echo $_POST['navn']; ?>" />
							</td>
						</tr>
						<tr>
							<td class="kontakt_celle">
								<p>Adresse</p>
							</td>
							<td>
								<input type="text" name="adresse" class="kontakt_felt" value="<?php echo $_POST['adresse']; ?>" />
							</td>
						</tr>
						<tr>
							<td class="kontakt_celle">
								<p>Telefon</p>
							</td>
							<td>
								<input type="text" name="telefon" class="kontakt_felt" value="<?php echo $_POST['telefon']; ?>" />
							</td>
						</tr>
						<tr>
							<td class="kontakt_celle">
								<p>E-mail</p>
							</td>
							<td>
								<input type="text" name="email" class="kontakt_felt" value="<?php echo $_POST['email']; ?>" />
							</td>
						</tr>
						<tr>
							<td class="kontakt_celle">
								<p>Kommentar</p>
							</td>
							<td>
								<textarea name="kommentar" id="kontakt_kommentar"><?php echo $_POST['kommentar']; ?></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="submit" value="Send" id="kontakt_knap" />
							</td>
						</tr>
					</table>
				</form>
				<?php
					if(isset($_POST['navn']) && $_POST['navn'] && isset($_POST['adresse']) && $_POST['adresse'] != "" &&
					isset($_POST['telefon']) && $_POST['telefon'] != "" && isset($_POST['email']) && $_POST['email'] != "" &&
					isset($_POST['kommentar']) && $_POST['kommentar'] != "") {
						if(is_numeric($_POST['telefon'])) {
							if($valider->mail($_POST['email'], "Emailadressen er ugyldig")) {
								$besked = "Navn: ".$_POST['navn']."\n";
								$besked.= "Adresse: ".$_POST['adresse']."\n";
								$besked.= "Telefon: ".$_POST['telefon']."\n";
								$besked.= "E-mail: ".$_POST['email']."\n";
								$besked.= "Kommentar: ".$_POST['kommentar'];
								$emne = "Kontakt";
								$sql = mysqli_query($db, "SELECT email FROM skoleprojekter_cmk_kontakt");
								$modtager = mysqli_fetch_assoc($sql);
								$send_mail = mail($modtager['email'], $emne, $besked);
								if($send_mail == true) {
									echo "<p class='fejl'>Tak for din henvendelse</p>";
								} else {
									echo "<p class='fejl'>Der er sket en fejl</p>";
								}
							} else {
								echo "<p class='fejl'>Emailadressen er ugyldigt</p>";
							}
						} else {
							echo "<p class='fejl'>Telefonnummeret er ugyldigt</p>";
						}
					}
				}
				ob_flush();
				?> 
			</div><!-- slut indhold -->
		</div><!-- slut wrap -->
		<div id="clear">
		</div><!-- slut clear -->
	</body>
</html>