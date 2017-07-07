<?php
session_start();
if($_SESSION['admin'] !== true) {
	header("location: admin_login.php");
}
if(isset($_GET['page'])) {
	$_GET['page'];
} else {
	$_GET['page'] = "forside";
}
include("../db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" href="../style/style.css" media="screen" />
		<title>L&oslash;jstrup bibliotek - b&oslash;ger til b&oslash;rn, unge og voksne</title>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
			</div><!-- slut top -->
			<div id="samle">
				<div id="samle_menu">
					<h1>MENU</h1>
					<ul>
						<li><a href="admin.php?page=forside">Forside</a></li>
						<li><a href="admin.php?page=rediger_aabning">Rediger &aring;bningstider</a></li>
						<li><a href="admin.php?page=rediger_kontakt">Rediger kontaktoplysninger</a></li>
						<li><a href="admin.php?page=ny_bog">Ny bog</a></li>
						<li><a href="admin.php?page=ret_bog">Ret bog</a></li>
						<li><a href="admin.php?page=slet_bog">Slet bog</a></li>
						<li><a href="admin.php?page=ny_arrangement">Nyt arrangement</a></li>
						<li><a href="admin.php?page=ret_arrangement">Ret arrangement</a></li>
						<li><a href="admin.php?page=slet_arrangement">Slet arrangement</a></li>
						<li><a href="admin.php?page=godkend_kommentar">Godkend kommentar</a></li>
						<li><a href="admin.php?page=upload">Upload billeder</a></li>
						<li><a href="admin.php?page=slet_billede">Slet billeder</a></li>
						<li><a href="admin_logaf.php">Log af</a></li>
					</ul>
					
					<h1>&Aring;BNINGSTIDER</h1>
					<p class="dage">Mandag - onsdag</p>
					<p class="tid">9.30 - 17.30</p>
					<p class="dage">Torsdag</p>
					<p class="tid">12.30 - 20.00</p>
					<p class="dage">Fredag</p>
					<p class="tid">9.30 - 15.30</p>
					<p class="dage">L&oslash;rdag</p>
					<p class="tid">8.30 - 13.00</p>
					
					<h1>OM OS</h1>
					<p>L&oslash;jstrup bibliotek er et lille lokalt bibliotek. Udover almindeligt bogudl&aring;n, fokuserer vi p&aring;
					afholdelse af forskellige kulturelle arrangementer.</p>
				</div><!-- slut samle_menu -->
				<div id="admin_indhold">
					<?php
					if($_GET['page'] == "forside") {
						
						if(isset($_POST['layout_knap'])) {
							$sql_layout = "UPDATE skoleprojekter_lojstrup_forside SET layout='$_POST[layout]'";
							$result_sql = mysqli_query($db, $sql_layout) or die (mysqli_error());
						}
					?>
					<h1>Velkommen admin</h1>
					<p>V&aelig;lg hvad der skal vises p&aring; forsiden.</p>
					<form action="" method="post">
						<select name="layout">
							<option value="3boger">3 b&oslash;ger</option>
							<option value="3arrangementer">3 arrangementer</option>
							<option value="1bog2arrangementer">1 bog og 2 arrangementer</option>
							<option value="2boger1arrangement">2 b&oslash;ger og 1 arrangement</option>
						</select>
						<input type="submit" name="layout_knap" value="V&aelig;lg" class="ret_knap" />
					</form>
					<?php
					}
					if($_GET['page'] == "rediger_aabning") {
						$ret_tider = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_aabning");
						while($retTider = mysqli_fetch_array($ret_tider)) {
					?>
					<h1>Ret &aring;bningstider</h1>
					<form action="" method="post">
						<p><label for="mandag">Mandag - onsdag</label></p><input type="text" name="mandag_onsdag" value="<?php echo $retTider['mandag_onsdag_tid']; ?>" />
						<p><label for="torsdag">Torsdag</label></p><input type="text" name="torsdag" value="<?php echo $retTider['torsdag_tid']; ?>" />
						<p><label for="fredag">Fredag</label></p><input type="text" name="fredag" value="<?php echo $retTider['fredag_tid']; ?>" />
						<p><label for="lordag">L&oslash;rdag</label></p><input type="text" name="lordag" value="<?php echo $retTider['lordag_tid']; ?>" />
						<input type="submit" name="opdater_knap" value="Gem" class="ret_knap" />
					<?php
							if(isset($_POST['opdater_knap'])) {
								$mandag_onsdag_tid = $_POST['mandag_onsdag'];
								$torsdag_tid = $_POST['torsdag'];
								$fredag_tid = $_POST['fredag'];
								$lordag_tid = $_POST['lordag'];
								$opdaterTider = mysqli_query($db, "UPDATE skoleprojekter_lojstrup_aabning SET mandag_onsdag_tid='$mandag_onsdag_tid', torsdag_tid='$torsdag_tid', fredag_tid='$fredag_tid', lordag_tid='$lordag_tid'");
								if($opdaterTider == true) {
									echo "<p>&Aring;bningstider er nu opdateret</p>";
								} else {
									echo "<p>Der er sket en fejl</p>";
								}
							}
						}
					}
					if($_GET['page'] == "rediger_kontakt") {
						$ret_oplysning = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_kontakt");
						while($retOplysning = mysqli_fetch_array($ret_oplysning)) {
					?>
					<h1>Rediger kontaktoplysninger og &aring;bningstider</h1>
					<form action="" method="post">
						<p><label for="adresse">Adresse</label></p><input type="text" name="adresse" class="ret_felt" value="<?php echo $retOplysning['adresse']; ?>" />
						<p><label for="postnr">Postnummer og by</label></p><input type="text" name="postnr" class="ret_felt" value="<?php echo $retOplysning['postnr']; ?>" />
						<p><label for="telefon">Telefon</label></p><input type="text" name="telefon" class="ret_felt" value="<?php echo $retOplysning['telefon']; ?>" />
						<p><label for="fax">Fax</label></p><input type="text" name="fax" class="ret_felt" value="<?php echo $retOplysning['fax']; ?>" />
						<p><label for="email">E-mail</label></p><input type="text" name="email" class="ret_felt" value="<?php echo $retOplysning['email']; ?>" />
						<p><label for="om">Om os</label></p><textarea name="om" class="ret_boks"><?php echo $retOplysning['om']; ?></textarea>
						<p><label for="reglement">Reglement</label></p><textarea name="reglement" class="ret_boks"><?php echo $retOplysning['reglement']; ?></textarea>
						<input type="submit" name="submit" value="Gem" class="ret_knap" />
					</form>
					<?php
						}
						if(isset($_POST['submit'])) {
							$adresse = $_POST['adresse'];
							$postnr = $_POST['postnr'];
							$telefon = $_POST['telefon'];
							$fax = $_POST['fax'];
							$email = $_POST['email'];
							$om = $_POST['om'];
							$reglement = $_POST['reglement'];
							$opdater = mysqli_query($db, "UPDATE skoleprojekter_lojstrup_kontakt SET adresse='$adresse', postnr='$postnr', telefon='$telefon', fax='$fax', email='$email', om='$om', reglement='$reglement'");
							if($opdater == true) {
								echo "<p>Rettelserne er gemt</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "ny_bog") {
					?>
					<h1>Tilf&oslash;j en ny bog</h1>
					<form action="" method="post" enctype="multipart/form-data">
						<p><label for="titel">Titel</label></p><input type="text" name="titel" class="ret_felt" />
						<p><label for="forfatter">Forfatter</label></p><input type="text" name="forfatter" class="ret_felt" />
						<p><label for="isbn">ISBN-nr</label></p><input type="text" name="isbn" class="ret_felt" />
						<p><label for="forlag">Forlag</label></p><input type="text" name="forlag" class="ret_felt" />
						<p><label for="sider">Antal sider</label></p><input type="text" name="sider" class="ret_felt" />
						<p><label for="dato">Udgivelsesdato</label></p><input type="text" name="dato" class="ret_felt" />
						<p><label for="kort_omtale">Kort omtale</label></p><textarea name="kort_omtale" class="ret_boks"></textarea>
						<p><label for="omtale">Omtale</label></p><textarea name="omtale" class="ret_boks"></textarea>
						<p><label for="fil">Billede</label></p><input type="file" name="fil" />
						<p><label for="type">V&aelig;lg type</label></p>
						<select name="type">
							<option value="born">B&oslash;rn</option>
							<option value="unge">Unge</option>
							<option value="voksne">Voksne</option>
						</select>
						<input type="submit" name="bog_knap" value="Tilf&oslash;j" class="ret_knap" />
					</form>
					<?php
						if(isset($_POST['bog_knap'])) {
							$tilfoj_dato = date("j-m-Y");
							$title = $_POST['titel'];
							$forfatter = $_POST['forfatter'];
							$isbn = $_POST['isbn'];
							$forlag = $_POST['forlag'];
							$sider = $_POST['sider'];
							$dato = $_POST['dato'];
							$kort_omtale = $_POST['kort_omtale'];
							$omtale = $_POST['omtale'];
							$destination = "img/".time().$_FILES['fil']['name'];
							$upload_billede = copy($_FILES['fil']['tmp_name'], $destination);
							$type = $_POST['type'];
							$ny_bog = mysqli_query($db, "INSERT INTO skoleprojekter_lojstrup_bog (tilfoj_dato, titel, forfatter, isbn, forlag, sider, udgivelsesdato, kort_omtale, omtale, billede, type) VALUES ('$tilfoj_dato', '$title', '$forfatter', '$isbn', '$forlag', '$sider', '$dato', '$kort_omtale', '$omtale', '$destination', '$type')");
							if($ny_bog == true && $upload_billede == true) {
								echo "<p>Bogen ".$title." er tilf&oslash;jet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "ret_bog") {
					?>
					<h1>Ret en bog</h1>
					<table>
						<tr>
							<td class="edit_cell"><p>Titel</p></td>
							<td class="edit_cell"><p>Forfatter</p></td>
							<td class="edit_cell"><p>ISBN-nr</p></td>
							<td><p>Ret</p></td>
						</tr>
					<?php	
						$ret_bog = mysqli_query($db, "SELECT bog_id, titel, forfatter, isbn FROM skoleprojekter_lojstrup_bog ORDER BY titel");
						while($retBog = mysqli_fetch_array($ret_bog)) {
							echo "<tr><td class='ret_celle'><p>".$retBog['titel']."</p></td>";
							echo "<td class='ret_celle'><p>".$retBog['forfatter']."</p></td>";
							echo "<td class='ret_celle'><p>".$retBog['isbn']."</p></td>";
							echo "<td><p><a href='admin.php?page=ret_bog_side&bog_id=$retBog[bog_id]'>Ret</a></p></td></tr>";
						}
						echo "</table>";
					}
					if($_GET['page'] == "ret_bog_side") {
						$vis_ret_bog = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_bog WHERE bog_id='$_GET[bog_id]'");
						while($visRetBog = mysqli_fetch_array($vis_ret_bog)) {
						?>
						<form action="" method="post">
							<p><label for="ret_titel">Titel</label></p><input type="text" name="ret_titel" class="ret_felt" value="<?php echo $visRetBog['titel']; ?>" />
							<p><label for="ret_forfatter">Forfatter</label></p><input type="text" name="ret_forfatter" class="ret_felt" value="<?php echo $visRetBog['forfatter']; ?>" />
							<p><label for="ret_isbn">ISBN-nr</label></p><input type="text" name="ret_isbn" class="ret_felt" value="<?php echo $visRetBog['isbn']; ?>" />
							<p><label for="ret_forlag">Forlag</label></p><input type="text" name="ret_forlag" class="ret_felt" value="<?php echo $visRetBog['forfatter']; ?>" />
							<p><label for="ret_sider">Sider</label></p><input type="text" name="ret_sider" class="ret_felt" value="<?php echo $visRetBog['sider']; ?>" />
							<p><label for="ret_dato">Udgivelsesdato</label></p><input type="text" name="ret_dato" class="ret_felt" value="<?php echo $visRetBog['udgivelsesdato']; ?>" />
							<p><label for="ret_kort_omtale">Kort omtale</label></p><textarea name="ret_kort_omtale" class="ret_boks"><?php echo $visRetBog['kort_omtale']; ?></textarea>
							<p><label for="ret_omtale">Omtale</label></p><textarea name="ret_omtale" class="ret_boks"><?php echo $visRetBog['omtale']; ?></textarea>
							<p><label for="ret_type">V&aelig;lg type</label></p>
							<select name="ret_type">
								<option value="born">B&oslash;rn</option>
								<option value="unge">Unge</option>
								<option value="voksne">Voksne</option>
							</select>
							<input type="submit" name="opdater_bog" value="Gem" class="ret_knap" />
						</form>
						<?php
							if(isset($_POST['opdater_bog'])) {
								$ret_titel = $_POST['ret_titel'];
								$ret_forfatter = $_POST['ret_forfatter'];
								$ret_isbn = $_POST['ret_isbn'];
								$ret_forlag = $_POST['ret_forlag'];
								$ret_sider = $_POST['ret_sider'];
								$ret_dato = $_POST['ret_dato'];
								$ret_kort_omtale = $_POST['ret_kort_omtale'];
								$ret_omtale = $_POST['ret_omtale'];
								$ret_kategori = $_POST['ret_type'];
								$opdater_bog = mysqli_query($db, "UPDATE skoleprojekter_lojstrup_bog SET titel='$ret_titel', forfatter='$ret_forfatter', isbn='$ret_isbn', forlag='$ret_forlag', sider='$ret_sider', udgivelsesdato='$ret_dato', kort_omtale='$ret_kort_omtale', omtale='$ret_omtale', type='$ret_kategori' WHERE bog_id='$_GET[bog_id]'");
								if($opdater_bog == true) {
									echo "<p>Oplysningerne er gemt</p>";
								} else {
									echo "<p>Der er sket en fejl</p>";
								}
							}
						}
					}
					if($_GET['page'] == "slet_bog") {
					?>
					<h1>Slet en bog</h1>
					<table>
						<tr>
							<td class="edit_cell"><p>Titel</p></td>
							<td class="edit_cell"><p>Forfatter</p></td>
							<td class="edit_cell"><p>ISBN-nr</p></td>
							<td class="edit_cell"><p>Slet</p></td>
						</tr>
						<?php
						$slet_bog = mysqli_query($db, "DELETE FROM skoleprojekter_lojstrup_bog WHERE bog_id='$_GET[bog_id]'");
						$vis_bog = mysqli_query($db, "SELECT bog_id, titel, forfatter, isbn FROM skoleprojekter_lojstrup_bog ORDER BY titel");
						while($visBog = mysqli_fetch_array($vis_bog)) {
							echo "<tr><td class='ret_celle'><p>".$visBog['titel']."</p></td>";
							echo "<td class='ret_celle'><p>".$visBog['forfatter']."</p></td>";
							echo "<td class='ret_celle'><p>".$visBog['isbn']."</p></td>";
							echo "<td class='ret_celle'><p><a href='admin.php?page=slet_bog&bog_id=$visBog[bog_id]'>Slet</a></p></td></tr>";
						}
						?>
					</table>
					<?php
					}
					if($_GET['page'] == "ny_arrangement") {
					?>
					<h1>Tilf&oslash;j et arrangement</h1>
					<form action="" method="post" enctype="multipart/form-data">
						<p><label for="titel">Titel</label></p><input type="text" name="titel" class="ret_felt" />
						<p><label for="taler">Foredragsholder / taler</label></p><input type="text" name="taler" class="ret_felt" />
						<p><label for="sted">Sted</label></p><input type="text" name="sted" class="ret_felt" />
						<p><label for="tid">Tidspunkt</label></p><input type="text" name="tid" class="ret_felt" />
						<p><label for="entre">Entr�</label></p><input type="text" name="entre" class="ret_felt" />
						<p><label for="tekst">Omtale</label></p><textarea name="tekst" class="ret_boks"></textarea>
						<p><label for="minfil">Billede</label></p><input type="file" name="minfil" />
						<input type="submit" name="arrangement_knap" value="Opret" class="ret_knap" />
					</form>
					<?php
						if(isset($_POST['arrangement_knap'])) {
							$title = $_POST['titel'];
							$taler = $_POST['taler'];
							$sted = $_POST['sted'];
							$tid = $_POST['tid'];
							$entre = $_POST['entre'];
							$tekst = $_POST['tekst'];
							$destination = "img/".time().$_FILES["minfil"]["name"];
							$upload = copy($_FILES["minfil"]["tmp_name"], $destination);
							$opretArrangement = mysqli_query($db, "INSERT INTO skoleprojekter_lojstrup_arrangement (titel, taler, sted, dato, entre, tekst, billede) VALUES ('$title', '$taler', '$sted', '$tid', '$entre', '$tekst', '$destination')");
							if($opretArrangement == true && $upload == true) {
								echo "<p>Arrangementet ".$title." er oprettet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "ret_arrangement") {
					?>
					<h1>Ret et arrangement</h1>
					<table>
						<tr>
							<td class="edit_cell"><p>Titel</p></td>
							<td class="edit_cell"><p>Taler</p></td>
							<td class="edit_cell"><p>Dato</p></td>
							<td class="edit_cell"><p>Ret</p></td>
						</tr>
						<?php
						$vis_arrangement = mysqli_query($db, "SELECT arrangement_id, titel, taler, dato FROM skoleprojekter_lojstrup_arrangement ORDER BY dato");
						while($visArrangement = mysqli_fetch_array($vis_arrangement)) {
							echo "<tr><td class='ret_celle'><p>".$visArrangement['titel']."</p></td>";
							echo "<td class='ret_celle'><p>".$visArrangement['taler']."</p></td>";
							echo "<td class='ret_celle'><p>".$visArrangement['dato']."</p></td>";
							echo "<td class='ret_celle'><p><a href='admin.php?page=ret_Arrangement&arrangement_id=$visArrangement[arrangement_id]'>Ret</a></p></td></tr>";
						}
						?>
					</table>
					<?php
					}
					if($_GET['page'] == "ret_Arrangement") {
						$ret_arrangement = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_arrangement WHERE arrangement_id='$_GET[arrangement_id]'");
						while($retArrangement = mysqli_fetch_array($ret_arrangement)) {
					?>
						<form action="" method="post">
							<p><label for="ret_titel">Titel</label></p><input type="text" name="ret_titel" class="ret_felt" value="<?php echo $retArrangement['titel']; ?>" />
							<p><label for="ret_taler">Taler</label></p><input type="text" name="ret_taler" class="ret_felt" value="<?php echo $retArrangement['taler']; ?>" />
							<p><label for="ret_sted">Sted</label></p><input type="text" name="ret_sted" class="ret_felt" value="<?php echo $retArrangement['sted']; ?>" />
							<p><label for="ret_dato">Dato</label></p><input type="text" name="ret_dato" class="ret_felt" value="<?php echo $retArrangement['dato']; ?>" />
							<p><label for="ret_entre">Entr�</label></p><input type="text" name="ret_entre" class="ret_felt" value="<?php echo $retArrangement['entre']; ?>" />
							<p><label for="ret_kort_tekst">Kort omtale</label></p><textarea name="ret_kort_tekst" class="ret_boks"><?php echo $retArrangement['kort_tekst']; ?></textarea>
							<p><label for="ret_tekst">Omtale</label></p><textarea name="ret_tekst" class="ret_boks"><?php echo $retArrangement['tekst']; ?></textarea>
							<input type="submit" name="ret_arrangement_knap" value="Ret" class="ret_knap" />
						</form>
					<?php
							if(isset($_POST['ret_arrangement_knap'])) {
								$ret_titel = $_POST['ret_titel'];
								$ret_taler = $_POST['ret_taler'];
								$ret_sted = $_POST['ret_sted'];
								$ret_dato = $_POST['ret_dato'];
								$ret_entre = $_POST['ret_entre'];
								$ret_kort_tekst = $_POST['ret_kort_tekst'];
								$ret_tekst = $_POST['ret_tekst'];
								$opdaterArrangement = mysqli_query($db, "UPDATE skoleprojekter_lojstrup_arrangement SET titel='$ret_titel', taler='$ret_taler', sted='$ret_sted', dato='$ret_dato', entre='$ret_entre', kort_tekst='$ret_kort_tekst', tekst='$ret_tekst' WHERE arrangement_id='$_GET[arrangement_id]'");
								if($opdaterArrangement == true) {
									echo "<p>Arrangementet ".$ret_titel." er opdateret</p>";
								} else {
									echo "<p>Der er sket en fejl</p>";
								}
							}
						}
					}
					if($_GET['page'] == "slet_arrangement") {
					?>
					<h1>Slet et arrangement</h1>
					<table>
						<tr>
							<td class="edit_cell"><p>Titel</p></td>
							<td class="edit_cell"><p>Taler</p></td>
							<td class="edit_cell"><p>Dato</p></td>
							<td class="edit_cell"><p>Slet</p></td>
						</tr>
						<?php
						$sletArrangement = mysqli_query($db, "DELETE FROM skoleprojekter_lojstrup_arrangement WHERE arrangement_id='$_GET[arrangement_id]'");
						$vis_arrangementer = mysqli_query($db, "SELECT arrangement_id, titel, taler, dato FROM skoleprojekter_lojstrup_arrangement ORDER BY dato");
						while($visArrangementer = mysqli_fetch_array($vis_arrangementer)) {
							echo "<tr><td class='ret_celle'><p>".$visArrangementer['titel']."</p></td>";
							echo "<td class='ret_celle'><p>".$visArrangementer['taler']."</p></td>";
							echo "<td class='ret_celle'><p>".$visArrangementer['dato']."</p></td>";
							echo "<td class='ret_celle'><p><a href='admin.php?page=slet_arrangement&arrangement_id=$visArrangementer[arrangement_id]'>Slet</a></p></td></tr>";
						}
						?>
					</table>
					<?php
					}
					if($_GET['page'] == "godkend_kommentar") {
					?>
					<h1>Godkend en kommentar</h1>
					<table>
						<tr>
							<td class="edit_cell"><p>Navn</p></td>
							<td class="edit_cell"><p>Kommentar</p></td>
							<td class="edit_cell"><p>Godkend</p></td>
							<td class="edit_cell"><p>Slet</p></td>
						</tr>
						<?php
						$godkend = mysqli_query($db, "UPDATE skoleprojekter_lojstrup_kommentar SET godkendt='1' WHERE kommentar_id='$_GET[kommentar_id]'");
						$vis_kommentar = mysqli_query($db, "SELECT kommentar_id, navn, kommentar FROM skoleprojekter_lojstrup_kommentar WHERE godkendt='0'");
						while($visKommentar = mysqli_fetch_array($vis_kommentar)) {
							echo "<tr><td class='ret_celle'><p>".$visKommentar['navn']."</p></td>";
							echo "<td class='ret_celle'><p>".$visKommentar['kommentar']."</p></td>";
							echo "<td class='ret_celle'><p><a href='admin.php?page=godkend_kommentar&kommentar_id=$visKommentar[kommentar_id]'>Godkend</a></p></td>";
							echo "<td class='ret_celle'><p><a href='slet_kommentar.php?kommentar_id=$visKommentar[kommentar_id]'>Slet</a></p></td></tr>";
						}
						?>
					</table>
					<?php
					}
					if($_GET['page'] == "upload") {
					?>
					<h1>Upload billeder til reglementsiden</h1>
					<form action="" method="post" enctype="multipart/form-data" id="upload">
						<input type="file" name="billede" />
						<input type="submit" value="Upload" id="upload_knap" />
					</form>
					<?php
						if(isset($_FILES['billede'])) {
							$destination = "img/".time().$_FILES["billede"]["name"];
							$upload = copy($_FILES["billede"]["tmp_name"], $destination);
							$dato = date("j-m-Y");
							$upload_db = mysqli_query($db, "INSERT INTO skoleprojekter_lojstrup_billeder (url, dato) VALUES ('$destination', '$dato')");
							if($upload == true && $upload_db == true) {
								echo "<p>Billedet blev uploadet!";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "slet_billede") {
					?>
					<h1>Slet billeder</h1>
					<table>
						<tr>
							<td class="edit_cell"><p>Billedet</p></td>
							<td class="edit_cell"><p>Slet</p></td>
						</tr>
						<?php
						$slet_billede = mysqli_query($db, "DELETE FROM skoleprojekter_lojstrup_billeder WHERE billede_id='$_GET[billede_id]'");
						$vis_billede = mysqli_query($db, "SELECT * FROM skoleprojekter_lojstrup_billeder ORDER BY dato DESC");
						while($visBilleder = mysqli_fetch_array($vis_billede)) {
							echo "<tr><td class='ret_celle'><img src='".$visBilleder['url']."' /></td>";
							echo "<td class='ret_celle'><p><a href='admin.php?page=slet_billede&billede_id=$visBilleder[billede_id]'>Slet</a></p></td></tr>";
						}
						echo "</table>";			
					}
					?>
				</div><!-- slut indhold -->
				<div id="samle_hojre">
					<img src="../billeder/extra/flash_dummy.jpg" alt="Flash Dummy" title="Flash Dummy" />
				</div><!-- slut samle_hojre -->
				<div class="clear">
				</div><!-- slut clear -->
			</div><!-- slut samle -->
			<div id="bund">
				<p>L&oslash;jstrup Bibliotek :: Hovedgaden 37 :: 4728 L&oslash;jstrup :: 6791 2801 :: info@loejstrup-bib.dk</p>
			</div><!-- slut bund -->
		</div><!-- slut wrap -->
	</body>
</html>