<?php
session_start();
//tjekker om der er logget ind
if($_SESSION['admin'] != true) {
	header("location: index.php");
}
//tjekker om $_GET['page'] er sat. Hvis den ikke er det er $_GET['page'] forside
if(isset($_GET['page'])) {
	$_GET['page'];
} else {
	$_GET['page'] = "forside";
}
include("../includes/db.php");
include("../includes/funktioner.php");
include("../includes/class.billede.php");
$billede = new billede("../billeder/mobler/");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../style/admin_style.css" media="screen" />
		<title>CMK m&oslash;bler - administration</title>
		<script type="text/javascript">
		function valider_nyhedsbrev() {
			if(document.nyhedsbrev_form.emne.value == "" ||
			document.nyhedsbrev_form.nyhedsbrev.value == "") {
				alert("Begge felter skal udfyldes!");
				return false;
			}
		}
		function valider_nyhed() {
			if(document.nyhed_form.forfatter.value == "" ||
			document.nyhed_form.overskrift.value == "" ||
			document.nyhed_form.tekst.value == "") {
				alert("Alle felter skal udfyldes!");
				return false;
			}
		}
		function valider_mobel() {
			if(document.mobel_form.designer.value == "designer" ||
			document.mobel_form.navn.value == "" ||
			document.mobel_form.design_aar.value == "" ||
			document.mobel_form.pris.value == "" ||
			document.mobel_form.beskrivelse.value == "" ||
			document.mobel_form.serie.value == "serie") {
				alert("Alle felter skal udfyldes og der skal v�lges en designer og m�belserie!");
				return false
			}
		}
		function valider_mobelserie() {
			if(document.mobelserie_form.navn.value == "") {
				alert("Feltet skal udfyldes!");
				return false;
			}
		}
		function valider_designer() {
			if(document.designer_form.navn.value == "") {
				alert("Feltet skal udfyldes!");
				return false;
			}
		}
		</script>
	</head>
	<body>
		<div id="top">
		</div><!-- slut top -->
		<div id="menu">
			<a href="admin.php?page=forside" id="forside_link" <?php echo style("forside"); ?>>Forside</a>
			<a href="admin.php?page=ret_abningstider" <?php echo style("ret_abningstider"); ?>>Ret &aring;bningstider</a>
			<a href="admin.php?page=nyhedsbrev" <?php echo style("nyhedsbrev"); ?>>Nyhedsbrev</a>
			<a href="admin.php?page=kontakt" <?php echo style("kontakt"); ?>>Kontakt</a>
			<a href="admin.php?page=opret_nyhed" <?php echo style("opret_nyhed"); ?>>Opret nyheder</a>
			<a href="admin.php?page=rediger_nyhed" <?php echo style("rediger_nyhed"); ?>>Rediger nyheder</a>
			<a href="admin.php?page=upload_billeder" <?php echo style("upload_billeder"); ?>>Upload billeder</a>
			<a href="admin.php?page=slet_billeder" <?php echo style("slet_billeder"); ?>>Slet billeder</a>
		</div>
		<div id="menu2">
			<a href="admin.php?page=opret_mobel" id="forside_link" <?php echo style("opret_mobel"); ?>>Opret m&oslash;bel</a>
			<a href="admin.php?page=rediger_mobel" <?php echo style("rediger_mobel"); ?>>Rediger m&oslash;bel</a>
			<a href="admin.php?page=opret_mobelserie" <?php echo style("opret_mobelserie"); ?>>Opret m&oslash;belserie</a>
			<a href="admin.php?page=rediger_mobelserie" <?php echo style("rediger_mobelserie"); ?>>Rediger m&oslash;belserie</a>
			<a href="admin.php?page=opret_designer" <?php echo style("opret_designer"); ?>>Opret designer</a>
			<a href="admin.php?page=rediger_designer" <?php echo style("rediger_designer"); ?>>Rediger designer</a>
			<a href="../includes/logaf.php">Log af</a>
		</div><!-- slut menu -->
		<div id="container">
			<?php 
			if($_GET['page'] == "forside") {
				echo "<h1>Forside</h1>";
			}
			if($_GET['page'] == "ret_abningstider") {
				echo "<h1>Ret &aring;bningstiderne</h1>";
			}
			if($_GET['page'] == "nyhedsbrev") {
				echo "<h1>Skriv nyhedsbrevet</h1>";
			}
			if($_GET['page'] == "kontakt") {
				echo "<h1>Ret kontaktoplysninger</h1>";
			}
			if($_GET['page'] == "opret_nyhed") {
				echo "<h1>Opret en nyhed</h1>";
			}
			if($_GET['page'] == "rediger_nyhed") {
				echo "<h1>Ret eller slet en nyhed</h1>";
			}
			if($_GET['page'] == "ret_nyhed") {
				echo "<h1>Ret nyheden</h1>";
			}
			if($_GET['page'] == "upload_billeder") {
				echo "<h1>Upload billeder</h1>";
			}
			if($_GET['page'] == "slet_billeder") {
				echo "<h1>Slet billeder</h1>";
			}
			if($_GET['page'] == "opret_mobel") {
				echo "<h1>Opret et m&oslash;bel</h1>";
			}
			if($_GET['page'] == "rediger_mobel") {
				echo "<h1>Ret eller slet et m&oslash;bel</h1>";
			}
			if($_GET['page'] == "ret_mobel") {
				echo "<h1>Ret m&oslash;blet</h1>";
			}
			if($_GET['page'] == "opret_mobelserie") {
				echo "<h1>Opret en m&oslash;belserie</h1>";
			}
			if($_GET['page'] == "rediger_mobelserie") {
				echo "<h1>Ret eller slet en m&oslash;belserie</h1>";
			}
			if($_GET['page'] == "ret_mobelserie") {
				echo "<h1>Ret m&oslash;belserie</h1>";
			}
			if($_GET['page'] == "opret_designer") {
				echo "<h1>Opret en designer</h1>";
			}
			if($_GET['page'] == "ret_designer") {
				echo "<h1>Ret designeren</h1>";
			}
			if($_GET['page'] == "rediger_designer") {
				echo "<h1>Ret eller slet en designer</h1>";
			}
			?>
		</div><!-- slut container -->
		<div id="content">
			<?php 
			if($_GET['page'] == "forside") {
			?>
			<p>Velkommen til administrationen p&aring; CMK m&oslash;bler</p>
			<p>V&aelig;lg et link i menuen for at komme igang</p>
			<?php
			}
			if($_GET['page'] == "ret_abningstider") {
				$sql_tider = mysqli_query($db, "SELECT mandag, fredag, lordag FROM skoleprojekter_cmk_aabningstider");
				while($data_tider = mysqli_fetch_assoc($sql_tider)) {
				?>
				<form action="" method="post">
					<p><label for="mandag">Mandag-Torsdag</label></p><input type="text" name="mandag" id="mandag" class="textfield" value="<?php echo $data_tider['mandag']; ?>" />
					<p><label for="fredag">Fredag</label><input type="text" name="fredag" id="fredag" class="textfield" value="<?php echo $data_tider['fredag']; ?>" />
					<p><label for="lordag">L&oslash;rdag</label><input type="text" name="lordag" id="lordag" class="textfield" value="<?php echo $data_tider['lordag']; ?>" />
					<input type="submit" name="ret_knap" value="Ret" class="knapper" />
				</form>
			<?php
					if(isset($_POST['ret_knap'])) {
						$opdater_tid = mysqli_query($db, "UPDATE skoleprojekter_cmk_aabningstider SET mandag='$_POST[mandag]', fredag='$_POST[fredag]', lordag='$_POST[lordag]'");
						if($opdater_tid == true) {
							echo "<p>&Aring;bningstiderne er &aelig;ndret</p>";
						} else {
							echo "<p>Der er sket en fejl</p>";
						}
					}
				}
			}
			if($_GET['page'] == "nyhedsbrev") {
			?>
			<form action="" method="post" name="nyhedsbrev_form" onsubmit="valider_nyhedsbrev()">
				<p><label for="emne">Emne</label></p><input type="text" name="emne" id="emne" class="textfield" value="<?php echo $_POST['emne']; ?>" />
				<p><label for="nyhedsbrev">Besked</label></p><textarea name="nyhedsbrev" id="nyhedsbrev" class="textarea"><?php echo $_POST['nyhedsbrev']; ?></textarea>
				<input type="submit" value="Send" class="knapper" />
			</form>
			<?php
				if(isset($_POST['emne']) && $_POST['emne'] != "" && isset($_POST['nyhedsbrev']) && $_POST['nyhedsbrev'] != "") {
					$emne = $_POST['emne'];
					$nyhedsbrev = $_POST['nyhedsbrev'];
					$sql_modtager = mysqli_query($db, "SELECT modtager FROM skoleprojekter_cmk_nyhedsbrev");
					while($modtager = mysqli_fetch_assoc($sql_modtager)) {
						$modtagere[] = $modtager['modtager'];  
					}
					$modtagere = implode(",", $modtagere);
					$sendNyhedsbrev = mail($modtagere, $emne, $nyhedsbrev);
					if($sendNyhedsbrev == true) {
						echo "<p>Nyhedsbrevet blev sendt</p>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "kontakt") {
				$sql_kontakt = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_kontakt");
				while($data_kontakt = mysqli_fetch_assoc($sql_kontakt)) {
			?>
				<form action="" method="post">
					<p><label for="adresse">Adresse</label></p><input type="text" name="adresse" id="adresse" class="textfield" value="<?php echo $data_kontakt['adresse']; ?>" />
					<p><label for="postnr">Post nr. og by</label></p><input type="text" name="postnr" id="postnr" class="textfield" value="<?php echo $data_kontakt['postnr_by']; ?>" />
					<p><label for="telefon">Telefon</label></p><input type="text" name="telefon" id="telefon" class="textfield" value="<?php echo $data_kontakt['telefon']; ?>" />
					<p><label for="telefax">Telefax</label></p><input type="text" name="telefax" id="telefax" class="textfield" value="<?php echo $data_kontakt['telefax']; ?>" />
					<p><label for="email">E-mailadressen til kontaktformularen</label></p><input type="text" name="email" id="email" class="textfield" value="<?php echo $data_kontakt['email']; ?>" />
					<input type="submit" name="ret_kontakt" value="Ret" class="knapper" />
				</form>
			<?php
				}
				if(isset($_POST['ret_kontakt'])) {
					$opdater_kontakt = mysqli_query($db, "UPDATE skoleprojekter_cmk_kontakt SET adresse='$_POST[adresse]', postnr_by='$_POST[postnr]',
					telefon='$_POST[telefon]', telefax='$_POST[telefax]', email='$_POST[email]'");
					if($opdater_kontakt == true) {
						echo "<p>Kontaktoplysningerne er &aelig;ndret</p>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "opret_nyhed") {
			?>
			<form action="" method="post" name="nyhed_form" onsubmit="valider_nyhed()">
				<p><label for="forfatter">Forfatter</label></p><input type="text" name="forfatter" id="forfatter" class="textfield" value="<?php echo $_POST['forfatter'] ?>" />
				<p><label for="overskrift">Overskrift</label></p><input type="text" name="overskrift" id="overskrift" class="textfield" value="<?php echo $_POST['overskrift'] ?>" />
				<p><label for="tekst">Nyhedens tekst</label></p><textarea name="tekst" id="tekst" class="textarea"><?php echo $_POST['tekst']; ?></textarea>
				<input type="submit" value="Opret" class="knapper" />
			</form>
			<?php
				if(isset($_POST['forfatter']) && $_POST['forfatter'] != "" && isset($_POST['overskrift']) && $_POST['overskrift'] != "" &&
				isset($_POST['tekst']) && $_POST['tekst'] != "") {
					$dag = date("d");
					$dato = time();
					$sql_nyhed = mysqli_query($db, "INSERT INTO skoleprojekter_cmk_nyheder (overskrift, dato, tekst, forfatter) VALUES ('$_POST[overskrift]', '$dato', '$_POST[tekst]', '$_POST[forfatter]')");
					if($sql_nyhed == true) {
						echo "<p>Nyheden ".$_POST['overskrift']." er oprettet </p>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "rediger_nyhed") {
			?>
			<table>
				<tr>
					<td class="edit_cell">
						<h2>Overskrift</h2>
					</td>
					<td class="edit_cell">
						<h2>Dato</h2>
					</td>
					<td class="edit_cell">
						<h2>Ret</h2>
					</td>
					<td class="edit_cell">
						<h2>Slet</h2>
					</td>
				</tr>
				<?php
				$ret_nyhed = mysqli_query($db, "SELECT nyhed_id, overskrift, dato FROM skoleprojekter_cmk_nyheder ORDER BY dato DESC");
				while($data_nyhed = mysqli_fetch_assoc($ret_nyhed)) {
				?>
				<tr>
					<td>
						<p><?php echo $data_nyhed['overskrift']; ?></p>
					</td>
					<td>
						<p><?php echo date("j-m-Y", $data_nyhed['dato']); ?></p>
					</td>
					<td>
						<a href="admin.php?page=ret_nyhed&amp;<?php echo "nyhed_id=$data_nyhed[nyhed_id]"; ?>"><input type="submit" name="ret" value="Ret" class="knapper" /></a>
					</td>
					<td>
						<a href="../includes/slet_nyhed.php?nyhed_id=<?php echo $data_nyhed['nyhed_id']; ?>"><input type="submit" name="slet" value="Slet" class="knapper" /></a>
					</td>
				</tr>
				<?php
				}
				echo "</table>";
			}
			if($_GET['page'] == "ret_nyhed") {
				$sql_ret_nyhed = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_nyheder WHERE nyhed_id='$_GET[nyhed_id]'");
				while($data_ret_nyhed = mysqli_fetch_assoc($sql_ret_nyhed)) {	
				?>
				<form action="" method="post">
					<p><label for="ret_forfatter">Forfatter</label></p><input type="text" name="ret_forfatter" id="ret_forfatter" class="textfield" value="<?php echo $data_ret_nyhed['forfatter']; ?>" />
					<p><label for="ret_overskrift">Overskrift</label></p><input type="text" name="ret_overskrift" id="ret_overskrift" class="textfield" value="<?php echo $data_ret_nyhed['overskrift']; ?>" />
					<p><label for="ret_tekst">Nyhedens tekst</label></p><textarea name="ret_tekst" id="ret_tekst" class="textarea"><?php echo $data_ret_nyhed['tekst']; ?></textarea>
					<input type="submit" name="ret_nyhed" value="Ret" class="knapper" />
				</form>
				<?php
				}
				if(isset($_POST['ret_nyhed'])) {
					$dato = time();
					$opdater_nyhed = mysqli_query($db, "UPDATE skoleprojekter_cmk_nyheder SET overskrift='$_POST[ret_overskrift]', dato='$dato', tekst='$_POST[ret_tekst]', forfatter='$_POST[ret_forfatter]' WHERE nyhed_id='$_GET[nyhed_id]'");
					if($opdater_nyhed == true) {
						echo "<p>Nyheden er rettet</p>";
						echo "<a href='admin.php?page=rediger_nyhed'>Tilbage</a>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "upload_billeder") {
			?>
			<form action="" method="post" enctype="multipart/form-data">
				<p><label for="titel">Titel</label></p><input type="text" name="titel" id="titel" class="textfield" value="<?php echo $_POST['titel'];?>" />
				<p><label for="billede">Billede</label></p><input type="file" name="billede" id="billede" />
				<p><label for="mobel">M&oslash;blet</label></p>
				<select name="mobel" class="select">
					<?php
					$sql_mobel = mysqli_query($db, "SELECT mobel_id, navn FROM cmk_mobel ORDER BY navn");
					while($dataMobel = mysqli_fetch_assoc($sql_mobel)) {
						echo "<option value='$dataMobel[mobel_id]'>$dataMobel[navn]</option>";
					}
					?>
				</select>
				<input type="submit" value="Upload" class="knapper" />
			</form>
			<?php
				if(isset($_POST['titel']) && $_POST['titel'] != "" && isset($_FILES['billede']) && $_FILES['billede'] != "") {
					$billede->setBillede($_FILES['billede']);
					$upload = $billede->upload();
					$upload_db = mysqli_query($db, "INSERT INTO skoleprojekter_cmk_billeder (mobel_id, sti, titel) VALUES ('$_POST[mobel]', '$upload', '$_POST[titel]')");
					if($upload == true && $upload_db == true) {
						echo "<p>Billedet blev uploadet</p>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "slet_billeder") {
			?>
			<table>
				<tr>
					<td class="edit_cell">
						<h2>Titel</h2>
					</td>
					<td class="edit_cell">
						<h2>Sti</h2>
					</td>
					<td class="edit_cell">
						<h2>Slet</h2>
					</td>
				</tr>
			<?php
				$sql_billeder = mysqli_query($db, "SELECT billede_id, sti, titel FROM skoleprojekter_cmk_billeder ORDER BY titel");
				while($data_billeder = mysqli_fetch_assoc($sql_billeder)) {
					echo "<tr>\n";
					echo "<td>".$data_billeder['titel']."</td>\n";
					echo "<td>".$data_billeder['sti']."</td>\n";
					echo "<td><a href='../includes/slet_billede.php?billede_id=$data_billeder[billede_id]'>Slet</a></td>";
					echo "</tr>";
				}
			}
			if($_GET['page'] == "opret_mobel") {
			?>
			<form action="" method="post" name="mobel_form" onsubmit="valider_mobel()">
				<p><label for="navn">Navn</label></p><input type="text" name="navn" id="navn" class="textfield" value="<?php echo $_POST['navn']; ?>" />
				<p><label for="vare_nr">Varenummer</label></p><input type="text" name="vare_nr" id="vare_nr" class="textfield" value="<?php echo $_POST['vare_nr'] ?>" />
				<p><label for="designer">Designer</label></p>
				<select name="designer" id="designer" class="select">
					<option value="designer">V&aelig;lg designer</option>
					<?php
					$designer = mysqli_query($db, "SELECT designer_id, navn FROM skoleprojekter_cmk_designer ORDER BY navn");
					while($data = mysqli_fetch_assoc($designer)) {
						echo "<option value='$data[designer_id]'";
						if($_POST['designer'] == $data['designer_id']) {
							echo " selected='selected'";
						}
						echo ">$data[navn]</option>";
					}
					?>
				</select>
				<p><label for="design_aar">Design &aring;r</label></p><input type="text" name="design_aar" id="design_aar" class="textfield" value="<?php echo $_POST['design_aar']; ?>" />
				<p><label for="pris">Pris</label></p><input type="text" name="pris" id="pris" class="textfield" value="<?php echo $_POST['pris']; ?>" />
				<p><label for="beskrivelse">Beskrivelse</label></p><textarea name="beskrivelse" id="beskrivelse" class="textarea"><?php echo $_POST['beskrivelse']; ?></textarea>
				<p><label for="serie">Serie</label></p>
				<select name="serie" id="serie" class="select">
					<option value="serie">V&aelig;lg serie</option>
					<?php
					$serie = mysqli_query($db, "SELECT serie_id, navn FROM skoleprojekter_cmk_mobelserie ORDER BY navn");
					while($data_serie = mysqli_fetch_assoc($serie)) {
						echo "<option value='$data_serie[serie_id]'";
						if($_POST['serie'] == $data_serie['serie_id']) {
							echo " selected='selected'";
						}
						echo ">$data_serie[navn]</option>";
					}
					?>
				</select>
				<input type="submit" value="Opret" class="knapper" />
			</form>
			<?php
				if($_POST['designer'] != "designer" && isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['vare_nr']) && $_POST['vare_nr'] != "" 
				&& isset($_POST['design_aar']) && $_POST['design_aar'] != "" && isset($_POST['pris']) && $_POST['pris'] != "" && isset($_POST['beskrivelse']) && $_POST['beskrivelse'] != "" && $_POST['serie'] != "serie") {
					if(is_numeric($_POST['design_aar'])) {
						$designer = $_POST['designer'];
						$navn = $_POST['navn'];
						$vare_nr = $_POST['vare_nr'];
						$design_aar = $_POST['design_aar'];
						$pris = $_POST['pris'];
						$beskrivelse = $_POST['beskrivelse'];
						$serie = $_POST['serie'];
						$sql_mobel = mysqli_query($db, "INSERT INTO skoleprojekter_cmk_mobel (designer_id, navn, varenummer, design_aar, pris, beskrivelse, serie_id) VALUES ('$designer', '$navn', '$vare_nr', '$design_aar', '$pris', '$beskrivelse', '$serie')");
						if($sql_mobel == true) {
							echo "<p>M&oslash;blet er oprettet</p>";
						} else {
							echo "<p>Der er sket en fejl</p>";
						}
					} else {
						echo "<p>Design &aring;ret skal v&aelig;re et tal</p>";
					}
				}
			}
			if($_GET['page'] == "rediger_mobel") {
			?>
			<table>
				<tr>
					<td class="edit_cell">
						<h2>Navn</h2>
					</td>
					<td class="edit_cell">
						<h2>Ret</h2>
					</td>
					<td class="edit_cell">
						<h2>Slet</h2>
					</td>
				</tr>
				<?php
				$sql = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_mobel ORDER BY design_aar DESC");
				while($dataMobel = mysqli_fetch_assoc($sql)) {
				?>
				<tr>
					<td>
						<p><?php echo $dataMobel['navn']; ?></p>
					</td>
					<td>
						<a href="admin.php?page=ret_mobel&amp;mobel_id=<?php echo $dataMobel['mobel_id']; ?>"><input type="submit" name="ret" value="Ret" class="knapper" /></a>
					</td>
					<td>
						<a href="../includes/slet_mobel.php?mobel_id=<?php echo $dataMobel['mobel_id']; ?>"><input type="submit" name="slet" value="Slet" class="knapper" /></a>
					</td>
				</tr>
			<?php
				}
				echo "</table>"; 
			}
			if($_GET['page'] == "ret_mobel") {
				$sql_mobel = mysqli_query($db, "SELECT * FROM skoleprojekter_cmk_mobel WHERE mobel_id='$_GET[mobel_id]'");
				while($data_mobel = mysqli_fetch_assoc($sql_mobel)) {
				?>	
				<form action="" method="post">
					<p><label for="ret_navn">Navn</label></p><input type="text" name="ret_navn" id="ret_navn" class="textfield" value="<?php echo $data_mobel['navn']; ?>" />
					<p><label for="ret_vare_nr">Varenummer</label></p><input type="text" name="ret_vare_nr" id="ret_vare_nr" class="textfield" value="<?php echo $data_mobel['varenummer']; ?>" />
					<p><label for="ret_designer">Designer</label></p>
					<select name="ret_designer" id="ret_designer" class="select">
						<?php
						$designer = mysqli_query($db, "SELECT designer_id, navn FROM skoleprojekter_cmk_designer ORDER BY navn");
						while($data = mysqli_fetch_assoc($designer)) {
							echo "<option value='$data[designer_id]'";
							if($data['designer_id'] == $data_mobel['designer_id']) {
								echo " selected='selected'";
							}
							echo ">$data[navn]</option>";
						}
						?>
					</select>
					<p><label for="ret_design_aar">Design &aring;r</label></p><input type="text" name="ret_design_aar" id="ret_design_aar" class="textfield" value="<?php echo $data_mobel['design_aar']; ?>" />
					<p><label for="ret_pris">Pris</label></p><input type="text" name="ret_pris" id="ret_pris" class="textfield" value="<?php echo $data_mobel['pris']; ?>" />
					<p><label for="ret_beskrivelse">Beskrivelse</label></p><textarea name="ret_beskrivelse" id="ret_beskrivelse" class="textarea"><?php echo $data_mobel['beskrivelse']; ?></textarea>
					<p><label for="ret_serie">Serie</label></p>
					<select name="ret_serie" id="ret_serie" class="select">
						<?php
						$serie = mysqli_query($db, "SELECT serie_id, navn FROM skoleprojekter_cmk_mobelserie ORDER BY navn");
						while($data_serie = mysqli_fetch_assoc($serie)) {
							echo "<option value='$data_serie[serie_id]'";
							if($data_serie['serie_id'] == $data_mobel['serie_id']) {
								echo " selected='selected'";
							}
							echo ">$data_serie[navn]</option>";
						}
						?>
					</select>
					<input type="submit" name="ret_mobel" value="Ret" class="knapper" />
				</form>
			<?php
				}
				if(isset($_POST['ret_mobel'])) {
					$opdater_mobel = mysqli_query($db, "UPDATE skoleprojekter_cmk_mobel SET designer_id='$_POST[ret_designer]', navn='$_POST[ret_navn]', varenummer='$_POST[ret_vare_nr]', design_aar='$_POST[ret_design_aar]', pris='$_POST[ret_pris]', beskrivelse='$_POST[ret_beskrivelse]', serie_id='$_POST[ret_serie]' WHERE mobel_id='$_GET[mobel_id]'");
					if($opdater_mobel == true) {
						echo "<p>M&oslash;blet er rettet</p>";
						echo "<a href='admin.php?page=rediger_mobel'>Tilbage</a>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "opret_mobelserie") {
			?>
			<form action="" method="post" name="mobelserie_form" onsubmit="valider_mobelserie()">
				<p><label for="navn">Navn</label></p><input type="text" name="navn" id="navn" class="textfield" />
				<input type="submit" value="Opret" class="knapper" />
			</form>
			<?php
				if(isset($_POST['navn']) && $_POST['navn'] != "") {
					$sql_serie = mysqli_query($db, "INSERT INTO skoleprojekter_cmk_mobelserie (navn) VALUES ('$_POST[navn]')");
					if($sql_serie == true) {
						echo "<p>M&oslash;belerien ".$_POST['navn']." er oprettet</p>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "rediger_mobelserie") {
			?>
			<table>
				<tr>
					<td class="edit_cell">
						<h2>Navn</h2>
					</td>
					<td class="edit_cell">
						<h2>Ret</h2>
					</td>
					<td class="edit_cell">
						<h2>Slet</h2>
					</td>
				</tr>
				<?php
				$serie = mysqli_query($db, "SELECT serie_id, navn FROM skoleprojekter_cmk_mobelserie WHERE navn != 'Sofa' AND navn != 'Sofabord' AND navn != 'Spisebord' AND navn != 'Spisestol' ORDER BY navn");
				while($data_serie = mysqli_fetch_assoc($serie)) {
				?>
				<tr>
					<td>
						<p><?php echo $data_serie['navn']; ?></p>
					</td>
					<td>
						<a href="admin.php?page=ret_mobelserie&amp;serie_id=<?php echo $data_serie['serie_id']; ?>"><input type="submit" name="ret" value="Ret" class="knapper" /></a>
					</td>
					<td>
						<a href="../includes/slet_serie.php?serie_id=<?php echo $data_serie['serie_id']; ?>"><input type="submit" name="slet" value="Slet" class="knapper" /></a>
					</td>
				</tr>
				<?php
				}
				echo "</table>";
			}
			if($_GET['page'] == "ret_mobelserie") {
				$serie = mysqli_query($db, "SELECT navn FROM skoleprojekter_cmk_mobelserie WHERE serie_id='$_GET[serie_id]'");
				while($data_serie = mysqli_fetch_assoc($serie)) {
				?>
				<form action="" method="post">
					<p><label for="ret_navn">Navn</label></p><input type="text" name="ret_navn" id="ret_navn" class="textfield" value="<?php echo $data_serie['navn']; ?>" />
					<input type="submit" name="ret_serie" value="Ret" class="knapper" />
				</form>
				<?php	
				}
				if(isset($_POST['ret_serie'])) {
					$opdater_serie = mysqli_query($db, "UPDATE skoleprojekter_cmk_mobelserie SET navn='$_POST[ret_navn]' WHERE serie_id='$_GET[serie_id]'");
					if($opdater_serie == true) {
						echo "<p>M&oslash;belserien er rettet</p>";
						echo "<a href='admin.php?page=rediger_mobelserie'>Tilbage</a>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "opret_designer") {
			?>
			<form action="" method="post" name="designer_form" onsubmit="valider_designer()">
				<p><label for="navn">Navn</label></p><input type="text" name="navn" id="navn" class="textfield" />
				<input type="submit" value="Opret" class="knapper" />
			</form>
			<?php
				if(isset($_POST['navn']) && $_POST['navn'] != "") {
					$sql_designer = mysqli_query($db, "INSERT INTO skoleprojekter_cmk_designer (navn) VALUES ('$_POST[navn]')");
					if($sql_designer == true) {
						echo "<p>Designeren ".$_POST['navn']." er oprettet</p>";
					}
				}
			}
			if($_GET['page'] == "rediger_designer") {
			?>
			<table>
				<tr>
					<td class="edit_cell">
						<h2>Navn</h2>
					</td>
					<td class="edit_cell">
						<h2>Ret</h2>
					</td>
					<td class="edit_cell">
						<h2>Slet</h2>
					</td>
				</tr>
				<?php
				$sql_designer = mysqli_query($db, "SELECT designer_id, navn FROM skoleprojekter_cmk_designer ORDER BY navn");
				while($data_designer = mysqli_fetch_assoc($sql_designer)) {
				?>
				<tr>
					<td>
						<p><?php echo $data_designer['navn']; ?></p>
					</td>
					<td>
						<a href="admin.php?page=ret_designer&amp;designer_id=<?php echo $data_designer['designer_id']; ?>"><input type="submit" name="ret" value="Ret" class="knapper" /></a>
					</td>
					<td>
						<a href="../includes/slet_designer.php?designer_id=<?php echo $data_designer['designer_id']; ?>"><input type="submit" name="slet" value="Slet" class="knapper" /></a>
					</td>
				</tr>
				<?php
				}
				echo "</table>";
			}
			if($_GET['page'] == "ret_designer") {
				$ret_designer = mysqli_query($db, "SELECT navn FROM skoleprojekter_cmk_designer WHERE designer_id='$_GET[designer_id]'");
				while($data_designer = mysqli_fetch_assoc($ret_designer)) {
				?>
				<form action="" method="post">
					<p><label for="ret_navn">Navn</label></p><input type="text" name="ret_navn" id="ret_navn" class="textfield" value="<?php echo $data_designer['navn']; ?>" />
					<input type="submit" name="ret_designer" value="Ret" class="knapper" />
				</form>
				<?php
					if(isset($_POST['ret_designer'])) {
						$opdater_designer = mysqli_query($db, "UPDATE skoleprojekter_cmk_designer SET navn='$_POST[ret_navn]' WHERE designer_id='$_GET[designer_id]'");
						if($opdater_designer == true) {
							echo "<p>Designeren er rettet</p>";
							echo "<a href='admin.php?page=rediger_designer'>Tilbage</a>";
						}
					}
				}
			}
			?>
		</div><!-- slut indhold -->
	</body>
</html>