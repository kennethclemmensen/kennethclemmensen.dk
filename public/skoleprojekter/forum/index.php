<?php
session_start();
ob_start();
if(!isset($_GET['page'])) {
	$_GET['page'] = "forside";
}
include("includes/db.php");
include("includes/funktioner.php");
include("includes/class.bruger.php");
$bruger = new bruger($db, "skoleprojekter_forum_bruger");
include("includes/class.billede.php");
$billede = new billede("billeder/profilbilleder/");
include("includes/class.validering.php");
$valider = new validering();
/*if(isset($_SESSION['brugernavn'])) {
	//finder brugeren
	$findBruger = mysqli_query($db, "SELECT sidste_login FROM forum_bruger WHERE email='$_SESSION[brugernavn]'");
	$dataFindBruger = mysqli_fetch_assoc($findBruger);
	//hvis brugeren ikke har klikket inden for 1800 sekunder bliver brugeren logget ud
	if($dataFindBruger['sidste_login'] > time() - 1800) {
		header("location: includes/logaf.php");
	}
}*/
//login
if(isset($_POST['login_email']) && $_POST['login_email'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
	$hentBruger = $bruger->log_ind($db, $_POST['login_email'], $_POST['password']);
	if($hentBruger == true) {
	?>
	<script type="text/javascript">
	alert("Velkommen <?php echo $_SESSION['brugernavn']; ?>\nDu er nu logget ind");
	</script>
	<?php	
	} else {
	?>
	<script type="text/javascript">
	alert("Du blev ikke logget ind");
	</script>
	<?php
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<link rel="shorcut icon" href="billeder/favicon.ico" />
		<title>Forum</title>
		<script type="text/javascript">
		function valider_indlaeg() {
			if(document.form_indlaeg.overskrift.value == "" ||
			document.form_indlaeg.tekst.value == "") {
				alert("Begge felter skal udfyldes!");
				return false;
			}
		}
		function valider_kommentar() {
			if(document.kommentar_form.kommentar.value == "") {
				alert("Kommentarfeltet skal udfyldes!");
				return false;
			}
		}
		function valider_soeg() {
			if(document.soeg_form.soeg.value == "" ||
			document.soeg_form.soeg_kategori.value == "vaelg_kategori") {
				alert("Feltet skal udfyldes og der skal v�lges en kategori!");
				return false;
			}
		}
		</script>
	</head>
	
	<body>
		<div id="top">
			<h1>Forum</h1>
		</div><!-- slut toppen -->
		<div id="wrap">
			<div id="menu">
				<a href="index.php?page=forside" <?php echo style("forside"); ?>>Forside</a>
				<a href="index.php?page=indlaeg" <?php echo style("indlaeg"); echo style("indlaegget"); ?>>Indl&aelig;g</a>
				<a href="index.php?page=nyheder" <?php echo style("nyheder"); ?>>Nyheder</a>
				<a href="index.php?page=medlems_liste" <?php echo style("medlems_liste"); ?>>Medlemsliste</a>
				<a href="index.php?page=soeg" <?php echo style("soeg"); ?>>S&oslash;g</a>
				<?php
				if($_SESSION['bruger'] == true) {
					echo "<a href='index.php?page=konto' ".style("konto").">Konto</a>";
					echo "<a href='includes/logaf.php'>Log af</a>";
				}
				?>
			</div><!-- slut menu -->
			<div id="samle">
				<div id="content">
					<?php
					if($_GET['page'] == "forside") {
						if($_SESSION['bruger'] == true) {
							$sqlNavn = mysqli_query($db, "SELECT navn FROM skoleprojekter_forum_bruger WHERE email='$_SESSION[brugernavn]'");
							$dataNavn = mysqli_fetch_assoc($sqlNavn);
							echo "<h1>Velkommen ".$dataNavn['navn']."</h1>";
						} else {
							echo "<h1>Velkommen</h1>";
						}
						?>
						<p>Velkommen til Forum</p>
					<?php
					}
					if($_GET['page'] == "indlaeg") {
						$sqlKategori = mysqli_query($db, "SELECT kategori_id, navn FROM skoleprojekter_forum_kategorier ORDER BY navn");
						while($dataKategori = mysqli_fetch_assoc($sqlKategori)) {
							echo "<h1>".$dataKategori['navn']."</h1>";
							$sqlIndlaeg = mysqli_query($db, "SELECT indlaeg_id, overskrift, tekst FROM skoleprojekter_forum_indlaeg WHERE kategori_id='$dataKategori[kategori_id]' ORDER BY dato DESC");
							while($dataIndlaeg = mysqli_fetch_assoc($sqlIndlaeg)) {
								echo "<h2>".$dataIndlaeg['overskrift']."</h2>";
								echo "<p>".substr($dataIndlaeg['tekst'], 0, 50)."... <a href='index.php?page=indlaegget&amp;indlaeg_id=$dataIndlaeg[indlaeg_id]'>L&aelig;s mere</a></p>";
							}
						}
						//hvis om man er logget ind f�r formularen bliver vist
						if($_SESSION['bruger'] == true) {
						?>
						<form action="" method="post" name="form_indlaeg" onsubmit="return valider_indlaeg()">
							<p><label for="kategori">V&aelig;lg kategori</label></p>
							<select name="kategori" id="kategori" class="select">
							<?php
							$sqlKategori = mysqli_query($db, "SELECT kategori_id, navn FROM skoleprojekter_forum_kategorier ORDER BY navn");
							while($dataKategori = mysqli_fetch_assoc($sqlKategori)) {
								echo "<option value='$dataKategori[kategori_id]'>".$dataKategori[navn]."</option>\n";
							}
							?>
							</select>
							<p><label for="overskrift">Overskrift</label></p>
							<input type="text" name="overskrift" id="overskrift" class="textfield" />
							<p><label for="tekst">Tekst</label></p>
							<textarea name="tekst" id="tekst" class="textarea"></textarea>
							<input type="submit" value="Opret" class="knapper" />
						</form>
						<?php
							if(isset($_POST['overskrift']) && $_POST['overskrift'] != "" && isset($_POST['tekst']) && $_POST['tekst'] != "") {
								$opretIndlaeg = mysqli_query($db, "INSERT INTO skoleprojekter_forum_indlaeg (kategori_id, overskrift, dato, tekst, email) VALUES ('$_POST[kategori]', '$_POST[overskrift]',
								'".time()."', '$_POST[tekst]', '$_SESSION[brugernavn]')");
								if($opretIndlaeg == true) {
									echo "<p>Indl&aelig;get er oprettet</p>";
								} else {
									echo "<p>Der er sket en fejl</p>";
								}
							}
						}
					}
					if($_GET['page'] == "indlaegget") {
						//viser indl�gget
						$sqlIndlaeg = mysqli_query($db, "SELECT indlaeg_id, overskrift, dato, tekst, email FROM skoleprojekter_forum_indlaeg WHERE indlaeg_id='$_GET[indlaeg_id]'");
						while($dataIndlaeg = mysqli_fetch_assoc($sqlIndlaeg)) {
							$sqlBruger = mysqli_query($db, "SELECT email, navn, tagline FROM skoleprojekter_forum_bruger WHERE email='$dataIndlaeg[email]'");
							$dataBruger = mysqli_fetch_assoc($sqlBruger);
							echo "<h1>".$dataIndlaeg['overskrift']."</h1>";
							$sqlProfilbillede = mysqli_query($db, "SELECT sti, titel FROM skoleprojekter_forum_billeder WHERE bruger='$dataBruger[email]'");
							if(mysqli_num_rows($sqlProfilbillede) != 0) {
								$dataProfilbillede = mysqli_fetch_assoc($sqlProfilbillede);
								echo "<img src='billeder/profilbilleder/".$dataProfilbillede['sti']."' alt='".$dataProfilbillede['titel']."' class='profilbillede' />";
							} else {
								echo "<img src='billeder/profilbilleder/intet_billede.jpg' alt='Intet billede' class='profilbillede' />";
							}
							echo "<p class='indlaeg_tekst'>Skrevet af ";
							if(mysqli_num_rows($sqlBruger) != 0) {
								echo $dataBruger['navn']."</p>";
							} else {
								echo "?</p>";
							}
							if($dataBruger['tagline'] !== 0) {
								echo "<p>".$dataBruger['tagline']."</p>";
							}
							echo "<p class='indlaeg_tekst'>Dato: ".date("j-m-Y", $dataIndlaeg['dato'])." kl. ".date("H:i", $dataIndlaeg['dato'])."</p>";
							echo "<p>".$dataIndlaeg['tekst']."</p>";
							echo "<div id='kommentar'>\n<h2>Kommentarer</h2>\n<table>";
							//opretter en ny kommentar hvis feltet er udfyldt
							if(isset($_POST['kommentar']) && $_POST['kommentar'] != "") {
								$nyKommentar = mysqli_query($db, "INSERT INTO skoleprojekter_forum_kommentar (indlaeg_id, email, dato, kommentar) VALUES ('$_GET[indlaeg_id]', '$_SESSION[brugernavn]', '".time()."', '$_POST[kommentar]')");
								if($nyKommentar == true) {
									$tilfojKommentar = "ja";
								} else {
									$tilfojKommentar = "nej";
								}
							}
							//viser kommentarene til indl�gget
							$sqlKommentar = mysqli_query($db, "SELECT email, dato, kommentar FROM skoleprojekter_forum_kommentar WHERE indlaeg_id='$dataIndlaeg[indlaeg_id]' ORDER BY dato DESC");
							if(mysqli_num_rows($sqlKommentar) != 0) {
								while($dataKommentar = mysqli_fetch_assoc($sqlKommentar)) {
									$sql = mysqli_query($db, "SELECT sti, titel FROM skoleprojekter_forum_billeder WHERE bruger='$dataKommentar[email]'");
									if(mysqli_num_rows($sql) != 0) {
										$data = mysqli_fetch_assoc($sql);
										echo "<tr><td><img src='billeder/profilbilleder/".$data['sti']."' alt='".$data['titel']."' class='profilbillede' /></td>";
									} else {
										echo "<tr><td><img src='billeder/profilbilleder/intet_billede.jpg' alt='Intet billede' class='profilbillede' /></td>";
									}
									$bruger_kommentar = mysqli_query($db, "SELECT navn FROM skoleprojekter_forum_bruger WHERE email='$dataKommentar[email]'");
									$data_kommentar = mysqli_fetch_assoc($bruger_kommentar);
									if(mysqli_num_rows($bruger_kommentar) != 0) {
										echo "<td><p>".$data_kommentar['navn'];
									} else {
										echo "<td><p>?";
									}
									echo " den ".date("j-m-Y", $dataKommentar['dato'])." kl. ".date("H:i", $dataKommentar['dato'])."</p>";
									echo "<p>".$dataKommentar['kommentar']."</p></td></tr>";
								}
							} else {
								echo "<p>Der er ingen kommentarer til indl&aelig;gget</p>";
							}
							echo "</table>\n</div>";
						}
						//hvis man er logget ind kan man skrive en kommentar
						if($_SESSION['bruger'] == true) {
						?>
						<form action="" method="post" name="kommentar_form" onsubmit="return valider_kommentar()">
							<p><label for="skriv_kommentar">Skriv en kommentar</label></p>
							<textarea name="kommentar" id="skriv_kommentar" class="textarea"><?php if(isset($_POST['kommentar']) && $_POST['kommentar'] != "") echo $_POST['kommentar']; ?></textarea>
							<input type="submit" value="Tilf&oslash;j" class="knapper" />
						</form>
						<?php
							if($tilfojKommentar == "nej") {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "nyheder") {
						$sqlNyhed = mysqli_query($db, "SELECT overskrift, dato, tekst FROM skoleprojekter_forum_nyheder ORDER BY dato DESC");
						while($dataNyhed = mysqli_fetch_assoc($sqlNyhed)) {
							echo "<h2>".$dataNyhed['overskrift']."</h2>";
							echo "<p>".date("j-m-Y", $dataNyhed['dato'])."</p>";
							echo "<p>".$dataNyhed['tekst']."</p>";
						}
					}
					if($_GET['page'] == "medlems_liste") {
					?>
					<table>
						<tr>
							<td class="liste_celle"><h2>E-mail</h2></td>
							<td class="liste_celle"><h2>Navn</h2></td>
							<td class="liste_celle"><h2>Logget ind</h2></td>
							<td class="liste_celle"><h2>Sidste login</h2></td>
						</tr>		
						<?php
						$sqlListe = mysqli_query($db, "SELECT email, navn, logget_ind, sidste_login FROM skoleprojekter_forum_bruger ORDER BY navn");
						while($dataListe = mysqli_fetch_assoc($sqlListe)) {
							echo "<tr>\n";
							echo "<td class='liste_celle'><p>".$dataListe['email']."</p></td>";
							echo "<td class='liste_celle'><p>".$dataListe['navn']."</p></td>\n";
							echo "<td class='liste_celle'><p>";
							//tjekker om brugeren er logget ind
							if($dataListe['logget_ind'] == 1) {
								echo "Ja";
							} else {
								echo "Nej";
							}
							echo "</p></td>";
							echo "<td class='liste_celle'><p>".date("d-m-Y H:i", $dataListe['sidste_login'])."</p></td>\n";
							echo "</tr>\n";
						}
						echo "</table>\n";
					}
					if($_GET['page'] == "soeg") {
					?>
					<h1>S&oslash;g</h1>
					<form action="" method="post" name="soeg_form" onsubmit="return valider_soeg()">
						<p><label for="soeg">S&oslash;g</p>
						<input type="text" name="soeg" id="search" class="textfield" />
						<p><label for="soeg_kategori">V&aelig;lg kategori</label></p>
						<select name="soeg_kategori" id="soeg_kategori" class="select">
							<option value="vaelg_kategori">V&aelig;lg kategori</option>
							<option value="bruger">Bruger</option>
							<option value="indlaeg">Indl&aelig;g</option>
						</select>
						<input type="submit" value="S&oslash;g" class="knapper" />
					</form>
					<?php
						if(isset($_POST['soeg']) && $_POST['soeg'] != "" && $_POST['soeg_kategori'] != "vaelg_kategori") {
							include("includes/soeg.php");
						}
					}
					if($_GET['page'] == "opret_bruger") {
					?>
					<h1>Opret en bruger</h1>
					<form action="" method="post">
						<p><label for="opret_email">E-mail</label></p>
						<input type="text" name="opret_email" id="opret_email" class="textfield" />
						<p><label for="navn">Navn</label></p>
						<input type="text" name="navn" id="navn" class="textfield" />
						<p><label for="password">Password</label></p>
						<input type="password" name="password" id="password" class="textfield" />
						<input type="submit" value="Opret" class="knapper" />
					</form>
					<?php
						if(isset($_POST['opret_email']) && $_POST['opret_email'] != "" && isset($_POST['navn']) && $_POST['navn'] != ""
						&& isset($_POST['password']) && $_POST['password'] != "") {
							if($valider->mail($_POST['opret_email'], "E-mailadressen er ikke gyldig")) {						
								$opretBruger = $bruger->opret($db, $_POST['opret_email'], $_POST['password'], $_POST['navn']);
								if($opretBruger == true) {
									echo "<p>Brugeren er oprettet</p>";
								} else {
									echo "<p>Brugernavnet er optaget!</p>";
								}
							} else {
								echo "<p>E-mailadressen er ikke gyldig</p>";
							}
						}
					}
					if($_SESSION['bruger'] == true) {
						if($_GET['page'] == "konto") {
						?>
						<h1>Ret password</h1>
						<form action="" method="post">
							<p><label for="ret_password">Nyt password</label></p>
							<input type="password" name="ret_password" id="ret_password" class="textfield" />
							<input type="submit" value="Opdater" class="knapper" />
						</form>
						<?php
						if(isset($_POST['ret_password']) && $_POST['ret_password'] != "") {
							$opdaterPassword = mysqli_query($db, "UPDATE skoleprojekter_forum_bruger SET password='$_POST[ret_password]' WHERE email='$_SESSION[brugernavn]'");
							if($opdaterPassword == true) {
								echo "<p>Dit password er nu &aelig;ndret</p>";
							} else {
								echo "<p>Dit password blev ikke &aelig;ndret</p>";
							}
						}
						if(isset($_POST['tagline']) && $_POST['tagline'] != "") {
							$opretTagline = mysqli_query($db, "UPDATE skoleprojekter_forum_bruger SET tagline='$_POST[tagline]' WHERE email='$_SESSION[brugernavn]'");
							if($opretTagline == true) {
								$ny_tagline = "ja";
							} else {
								$ny_tagline = "nej";
							}
						}
						?>
						<h1>Tagline</h1>
						<p>Skriv dit tagline</p>
						<form action="" method="post">
							<p><label for="tagline">Tagline</label></p>
							<?php
							$sqlTagline = mysqli_query($db, "SELECT tagline FROM skoleprojekter_forum_bruger WHERE email='$_SESSION[brugernavn]'");
							while($dataTagline = mysqli_fetch_assoc($sqlTagline)) {
								if($dataTagline['tagline'] !== 0) {
									echo "<input type='text' name='tagline' id='tagline' class='felter' value='$dataTagline[tagline]' />";
								} else {
									echo "<input type='text' name='tagline' id='tagline' class='felter' />";
								}
							}
							?>
							<input type="submit" value="Gem" class="knapper" />
						</form>
						<?php
							if($ny_tagline == "nej") {
								echo "<p>Der er sket en fejl</p>";
							}
						?>
						
						<h1>Upload profilbillede</h1>
						<p>Upload et profilbillede. Formatet skal v&aelig;re jpg, gif eller png.</p>
						<form action="" method="post" enctype="multipart/form-data">
							<p><label for="profilbillede">V&aelig;lg profilbillede</label></p>
							<input type="file" name="profilbillede" id="profileimage" class="textfield" />
							<input type="submit" value="Upload" class="knapper" />
						</form>
						<?php
						if(isset($_FILES['profilbillede']) && $_FILES['profilbillede'] != "") {
							$profilbillede = $billede->setBillede($_FILES['profilbillede']);
							$profilbillede = strtolower($profilbillede);
							if(substr($profilbillede, -3) == "jpg" || substr($profilbillede, -4) == "jpeg" || substr($profilbillede, -3) == "gif" || substr($profilbillede, -3) == "png") {
								//tjekker om brugeren allerede har et profilbillede. Hvis han har det bliver billedet opdateret. Ellers bliver der uploadet et nyt billede 
								$tjekBillede = mysqli_query($db, "SELECT bruger, sti FROM skoleprojekter_forum_billeder WHERE bruger='$_SESSION[brugernavn]'");
								if(mysqli_num_rows($tjekBillede) == 1) {
									//opdaterer tabellen med det nye billede
									$upload_db = mysqli_query($db, "UPDATE skoleprojekter_forum_billeder SET bruger='$_SESSION[brugernavn]', sti='$profilbillede', titel='$profilbillede' WHERE bruger='$_SESSION[brugernavn]'");
									$dataBillede = mysqli_fetch_assoc($tjekBillede);
									//sletter billedet fra serveren
									unlink("billeder/profilbilleder/".$dataBillede['sti']);
								} else {
									$upload_db = mysqli_query($db, "INSERT INTO skoleprojekter_forum_billeder (bruger, sti, titel) VALUES ('$_SESSION[brugernavn]', '$profilbillede', '$profilbillede')");
								}
								$upload = $billede->upload();
								if($upload == true && $upload_db == true) {
									echo "<p>Billedet blev uploadet</p>";
								} else {
									echo "<p>Billedet blev ikke uploadet</p>";
								}
							} else {
								echo "<p>Filformatet er ugyldigt</p>";
							}
						}
						?>
						
						<h1>Slet din bruger</h1>
						<p>Skriv dit password for at slette din bruger</p>
						<form action="" method="post">
							<p><label for="slet_password">Password</p>
							<input type="password" name="slet_password" id="slet_password" class="textfield" />
							<input type="submit" value="Slet" class="knapper" />
						</form>
						<?php
							if(isset($_POST['slet_password']) && $_POST['slet_password'] != "") {
								if($_POST['slet_password'] == $_SESSION['kodeord']) {
									$sqlBrugerId = mysqli_query($db, "SELECT bruger_id FROM skoleprojekter_forum_bruger WHERE email='$_SESSION[brugernavn]'");
									$dataBrugerId = mysqli_fetch_assoc($sqlBrugerId);
									echo "<p>Klik p&aring; linket for at slette din bruger</p>";
									echo "<a href='includes/slet_bruger.php?bruger_id=$dataBrugerId[bruger_id]'>Slet min bruger</a>";
								} else {
									echo "<p>Du har skrevet et forkert password</p>";
								}
							}
						}
					}
					?>
				</div><!-- slut indhold -->
				<div id="hojre">
					<?php
					if(!$_SESSION['bruger'] == true) {
					?>
					<h2>Login</h2>
					<form action="" method="post">
						<p><label for="login_email">E-mail</label></p>
						<input type="text" name="login_email" id="login_email" class="login_felter" />
						<p><label for="password">Password</label></p>
						<input type="password" name="password" id="password" class="login_felter" />
						<input type="submit" value="Login" id="login_button" />
						<a href="index.php?page=opret_bruger" id="opret_link">Opret bruger</a>
					</form>
					<?php
					}
					?>
										
					<h2>Brugere online</h2>
					<?php
					echo brugere_online("logget_ind", "skoleprojekter_forum_bruger", "logget_ind", 1);
					if($_SESSION['bruger'] == true) {
						echo "<p>Du er logget ind som <b>".$_SESSION['brugernavn']."</b></p>";
					}
					?>
					
					<h2>Nyeste indl&aelig;g</h2>
					<?php
					$visIndlaeg = mysqli_query($db, "SELECT indlaeg_id, overskrift FROM skoleprojekter_forum_indlaeg ORDER BY dato DESC LIMIT 5");
					while($indlaeg = mysqli_fetch_assoc($visIndlaeg)) {
						echo "<a href='index.php?page=indlaegget&amp;indlaeg_id=$indlaeg[indlaeg_id]' class='indlaeg_link'>".$indlaeg['overskrift']."</a>";
					}
					if($_SESSION['bruger'] == true) {
					?>
					<h2>Nyhedsbrev</h2>
					<form action="" method="post">
						<p><label for="email">E-mail</label></p>
						<input type="text" name="email" id="email" />
						<input type="submit" name="tilmeld" value="Tilmeld" class="nyhedsbrev_knap" />
						<input type="submit" name="frameld" value="Frameld" class="nyhedsbrev_knap" />
					</form>
						<?php
						if(isset($_POST['email']) && $_POST['email'] != "") {
							if($valider->mail($_POST['email'], "Adressen er ikke gyldig")) {
								if(isset($_POST['tilmeld'])) {
									$tilmeld = mysqli_query($db, "INSERT INTO skoleprojekter_forum_nyhedsbrev (modtager) VALUES ('$_POST[email]')");
									if($tilmeld == true) {
										echo "<p id='nyhedsbrev'>Du er nu tilmeldt nyhedsbrevet</p>";
									} else {
										echo "<p id='nyhedsbrev'>Der er sket en fejl</p>";
									}
								}
								if(isset($_POST['frameld'])) {
									$frameld = mysqli_query($db, "DELETE FROM skoleprojekter_forum_nyhedsbrev WHERE modtager='$_POST[email]'");
									if($frameld == true) {
										echo "<p id='nyhedsbrev'>Du er nu frameldt nyhedsbrevet</p>";
									} else {
										echo "<p id='nyhedsbrev'>Der er sket en fejl</p>";
									}
								}
							} else {
								echo "<p id='nyhedsbrev'>Adressen er ikke gyldig</p>";
							}
						}
					}
					ob_flush();
					?>
				</div><!-- slut hojre -->
			</div><!-- slut samle -->
			<div id="clear"></div><!-- slut clear -->
		</div><!-- slut wrap -->
	</body>
</html>