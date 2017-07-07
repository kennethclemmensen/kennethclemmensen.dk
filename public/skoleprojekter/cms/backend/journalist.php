<?php
session_start();
ob_start();
if($_SESSION['journalist'] != true) {
	header("location: login.php");
}
if(isset($_GET['page'])) {
	$_GET['page'];
} else {
	$_GET['page'] = "opret_nyhed";
}
include("../includes/db.php");
include("../includes/class.billede.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>NETavisen - journalist</title>
		<link rel="stylesheet" type="text/css" href="style/backend_style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="style/datepicker.css" media="screen" />
		<script type="text/javascript" src="../js/jquery-1.4.1.min.js"></script>
		<script type="text/javascript" src="../js/jquery-ui-1.7.2.custom.min.js"></script>
		<script type="text/javascript" src="../js/jquery.ui.datepicker-da.js"></script>
		<script type="text/javascript">
		function valider_nyhed() {
			if(document.nyhed_formular.journalist.value == "" ||
			document.nyhed_formular.overskrift.value == "" ||
			document.nyhed_formular.tekst.value == "" ||
			document.nyhed_formular.start_date.value == "" ||
			document.nyhed_formular.slut_date.value == "" ||
			document.nyhed_formular.kategori.value == "valg_kategori") {
				alert("Alle felter skal udfyldes og der skal v�lges en kategori!");
				return false;
			}
		}
		function valider_ret() {
			if(document.ret_form.ret_password.value == "") {
				alert("Du skal udfylde feltet!");
				return false;
			}
		}
		function valider_galleri() {
			if(document.galleri_form.galleri_navn.value == "" ||
			document.galleri_form.galleri.beskrivelse.value == "") {
				alert("Begge felter skal udfyldes for at oprette et galleri!");
				return false;
			}
		}
		function valider_upload() {
			if(document.billede_formular.billede.value == "" ||
			document.billede_formular.billede_titel.value == "") {
				alert("Alle felter skal udfyldes");
				return false;
			}
		}
		$(document).ready(function() {
			$("#datepicker").datepicker();
			$("#datepicker").datepicker({ altFormat: 'dd-mm-yy' });
			$("#datepicker").datepicker('option', 'altFormat', 'dd-mm-yy');
			$("#datepicker2").datepicker();
			
		});
		function skift_element(id, state) {
			//opretter variablen e som indeholder funktionen getElementById
			var e = document.getElementById(id);
			//tjekker om state er 0. Hvis den er 0 bliver den ikke vist
			if(state == 0) {
				disp = "none";
			} else { //ellers bliver den vist
				disp = "block";
			}		
			e.style.display = disp;
		}
		//opretter funktionen soeg_valg_onchange
		function soeg_valg_onchange() {
			//opretter variablen e som indeholder funktionen getElementById som har id'et fra <select>
			var e = document.getElementById("til_galleri");
			if(e.checked == true){
				skift_element ("galleri_valg", 1);
				skift_element ("nyhed_valg",  0);
			} else {
				skift_element ("galleri_valg", 0);
				skift_element ("nyhed_valg",  1);
			}
		}
		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
				<h1>Journalist <?php echo $_SESSION['journalist_navn']; ?></h1>
			</div>
			<div id="menu">
				<a href="journalist.php?page=opret_nyhed">Opret nyhed</a>
				<a href="journalist.php?page=ret_nyhed">Ret nyhed</a>
				<a href="journalist.php?page=slet_nyhed">Slet nyhed</a>
				<a href="journalist.php?page=opret_galleri">Opret galleri</a>
				<a href="journalist.php?page=slet_galleri">Slet galleri</a>
				<a href="journalist.php?page=upload_billeder">Upload billeder</a>
				<a href="journalist.php?page=slet_billeder">Slet billeder</a>
				<a href="journalist.php?page=ret_password">Ret password</a>
				<a href="journalist_logaf.php">Log af</a>
			</div>
			<div id="content">
			<?php
			if($_GET['page'] == "opret_nyhed") {
			?>
			<h1>Opret en nyhed</h1>
			<form action="" method="post" name="nyhed_formular" onsubmit="valider_nyhed()">
				<p><label for="journalist">Journalist navn</label></p>
				<input type="text" name="journalist" id="journalist" readonly="readonly" class="nyhed_felt" value="<?php echo $_SESSION['journalist_navn']; ?>" />
				<p><label for="overskrift">Overskrift</p>
				<input type="text" name="overskrift" id="overskrift" class="nyhed_felt" value="<?php echo $_POST['overskrift']; ?>" />
				<p><label for="tekst">Tekst</label></p>
				<textarea name="tekst" id="tekst" class="nyhed_tekst"><?php echo $_POST['tekst']; ?></textarea>
				<br/>
				<p><label for="datepicker">V&aelig;lg startdato</label></p>
				<input type="text" readonly="readonly" name="start_date" id="datepicker" class="nyhed_felt" value="<?php echo $_POST['start_date']; ?>" />
				<p><label for="datepicker2">V&aelig;lg en slutdato</label></p>
				<input type="text" name="slut_date" readonly="readonly" id="datepicker2" class="nyhed_felt" value="<?php echo $_POST['slut_date']; ?>" />
				<p><label for="kategori">Kategori</label></p>
				<select name="kategori" id="kategori">
					<option value="valg_kategori">V&aelig;lg kategori</option>
					<?php
					$sql_muligheder = "SELECT menu_id, titel, parent FROM skoleprojekter_netavisen_menu ORDER BY menu_id";
					$resultat_muligheder = mysqli_query($db, $sql_muligheder);
					while($data = mysqli_fetch_assoc($resultat_muligheder)) {
						echo "<option value='".$data['menu_id']."'>".ucfirst($data['titel'])."</option>";	
					}
					?>
				</select>
				<input type="submit" name="opret_knap" value="Opret" class="nyhed_knap" />
			</form>
			<?php
				if(isset($_POST['opret_knap']) && $_POST['kategori'] != "valg_kategori") {
					if($_POST['start_date'] < $_POST['slut_date']) {
						$journalist = $_POST['journalist'];
						$overskrift = $_POST['overskrift'];
						$tekst = $_POST['tekst'];
						$start_dato = $_POST['start_date'];
						$slut_dato = $_POST['slut_date'];
						$start_array = explode("-", $start_dato);
						$slut_array = explode("-", $slut_dato);
						$start_dato = mktime(0, 0, 0, $start_array[1], $start_array[0], $start_array[2]);
						$slut_dato = mktime(0, 0, 0, $slut_array[1], $slut_array[0], $slut_array[2]);
						$kategori = $_POST['kategori'];
						$sql_nyhed = "INSERT INTO skoleprojekter_netavisen_nyhed (journalist, overskrift, tekst, start_dato, slut_dato, menu_id) VALUES ('$journalist', '$overskrift', '$tekst', '$start_dato', '$slut_dato', '$kategori')";
						$resultat_nyhed = mysqli_query($db, $sql_nyhed) or die (mysqli_error($db));
						if($resultat_nyhed == true) {
							echo "<p>Nyheden <b>".$overskrift."</b> er oprettet</p>";
						} else {
							echo "<p>Der er sket en fejl</p>";	
						}
					} else {
					?>
					<script type="text/javascript">
						alert("Der er fejl i datoerne!");
					</script>
					<?php
					}
				}
			}
			if($_GET['page'] == "ret_nyhed") {
			?>
			<h1>Ret en nyhed</h1>
			<?php
				$sql_nyhed = "SELECT nyhed_id, overskrift FROM skoleprojekter_netavisen_nyhed ORDER BY overskrift";
				$resultat_nyhed = mysqli_query($db, $sql_nyhed);
				while($data_nyhed = mysqli_fetch_assoc($resultat_nyhed)) {
					echo "<a href='journalist.php?page=rettet_nyhed&amp;nyhed_id=$data_nyhed[nyhed_id]'>".$data_nyhed['overskrift']."</a><br/>";
				}
			}
			if($_GET['page'] == "rettet_nyhed") {
				$ret_nyhed = "SELECT * FROM skoleprojekter_netavisen_nyhed WHERE nyhed_id='$_GET[nyhed_id]'";
				$resultat_ret_nyhed = mysqli_query($db, $ret_nyhed);
				while($data_ret = mysqli_fetch_assoc($resultat_ret_nyhed)) {
				?>
				<form action="" method="post">
				<p><label for="ret_journalist">Journalist navn</label></p>
				<input type="text" name="ret_journalist" id="ret_journalist" readonly="readonly" class="nyhed_felt" value="<?php echo $_SESSION['journalist_navn']; ?>" />
				<p><label for="ret_overskrift">Overskrift</label></p>
				<input type="text" name="ret_overskrift" id="ret_overskrift" class="nyhed_felt" value="<?php echo $data_ret['overskrift']; ?>" />
				<p><label for="ret_tekst">Tekst</label></p>
				<textarea name="ret_tekst" id="ret_tekst" class="nyhed_tekst"><?php echo $data_ret['tekst']; ?></textarea>
				<br/>
				<p><label for="datepicker">V&aelig;lg startdato</label></p>
				<input type="text" readonly="readonly" name="ret_start_date" id="datepicker" class="nyhed_felt" value="<?php echo date("j-m-Y", $data_ret['start_dato']); ?>" />
				<p><label for="datepicker2">V&aelig;lg en slutdato</label></p>
				<input type="text" name="ret_slut_date" readonly="readonly" id="datepicker2" class="nyhed_felt" value="<?php echo date("j-m-Y", $data_ret['slut_dato']); ?>" />
				<p><label for="ret_kategori">Kategori</label></p>
				<select name="ret_kategori" id="ret_kategori" class="nyhed_felt">
					<option value="valg_kategori">V&aelig;lg kategori</option>
					<?php
					$sql_muligheder = "SELECT menu_id, titel, parent FROM skoleprojekter_netavisen_menu ORDER BY menu_id";
					$resultat_muligheder = mysqli_query($db, $sql_muligheder);
					while($data = mysqli_fetch_assoc($resultat_muligheder)) {
						echo "<option value='".$data['menu_id']."'>".ucfirst($data['titel'])."</option>";	
					}
					?>
				</select>
				<input type="submit" name="ret_knap" value="Ret" class="nyhed_knap" />
				<?php
				}
				if(isset($_POST['ret_knap']) && $_POST['ret_kategori'] != "valg_kategori" && $_POST['ret_start_date'] < $_POST['ret_slut_date']) {
					if($_POST['ret_start_date'] < $_POST['ret_slut_date']) {
						$start_dato = $_POST['ret_start_date'];
						$slut_dato = $_POST['ret_slut_date'];
						$start_array = explode("-", $start_dato);
						$slut_array = explode("-", $slut_dato);
						$start_dato = mktime(0, 0, 0, $start_array[1], $start_array[0], $start_array[2]);
						$slut_dato = mktime(0, 0, 0, $slut_array[1], $slut_array[0], $slut_array[2]);
						$sql_opdater = "UPDATE skoleprojekter_netavisen_nyhed SET journalist='$_POST[ret_journalist]', overskrift='$_POST[ret_overskrift]', tekst='$_POST[ret_tekst]', start_dato='$start_dato', slut_dato='$slut_dato', menu_id='$_POST[ret_kategori]' WHERE nyhed_id='$_GET[nyhed_id]'";
						$resultat_opdater = mysqli_query($db, $sql_opdater);
						if($resultat_opdater == true) {
							header("location: journalist.php?page=ret_nyhed");
						} else {
							echo "<p>Der er sket en fejl</p>";
						}
					} else {
						echo "<p>Der er fejl i datoerne</p>";
					}
				}
			}
			if($_GET['page'] == "slet_nyhed") {
				$sql = "SELECT nyhed_id, journalist, overskrift FROM skoleprojekter_netavisen_nyhed ORDER BY overskrift";
				$resultat = mysqli_query($db, $sql);
				while($row = mysqli_fetch_assoc($resultat)) {
					echo "<p>".$row['journalist']."</p>";
					echo "<p>".$row['overskrift']."</p>";
					echo "<a href='slet_nyhed.php?nyhed_id=$row[nyhed_id]' class='slet_nyhed_link'>Slet</a>";
				}
			}
			if($_GET['page'] == "opret_galleri") {
			?>
			<h1>Opret et galleri</h1>
			<form action="" method="post" name="galleri_form" onsubmit="valider_galleri()">
				<p><label for="galleri_felt">Galleri navn</label></p>
				<input type="text" name="galleri_navn" id="galleri_felt" value="<?php echo $_POST['galleri_navn']; ?>" />
				<p><label for="galleri_beskrivelse">Beskrivelse</label></p>
				<textarea name="beskrivelse" id="galleri_beskrivelse"><?php echo $_POST['beskrivelse']; ?></textarea>
				<input type="submit" value="Opret" class="nyhed_knap" />
			</form>
			<?php
				if(isset($_POST['galleri_navn']) && $_POST['galleri_navn'] != "" && isset($_POST['beskrivelse']) && $_POST['beskrivelse'] != "") {
					$galleri_navn = $_POST['galleri_navn'];
					$beskrivelse = $_POST['beskrivelse'];
					$sql_galleri = "INSERT INTO skoleprojekter_netavisen_galleri (navn, beskrivelse) VALUES ('$galleri_navn', '$beskrivelse')";
					$resultat_galleri = mysqli_query($db, $sql_galleri);
					if($resultat_galleri == true) {
						echo "<p>Galleriet ".$galleri_navn." er oprettet</p>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "slet_galleri") {
				echo "<h1>Slet galleri</h1>";
				$sql_galleri = "SELECT galleri_id, navn FROM skoleprojekter_netavisen_galleri ORDER BY navn";
				$resultat_galleri = mysqli_query($db, $sql_galleri);
				while($data_galleri = mysqli_fetch_assoc($resultat_galleri)) {
					echo "<p>".$data_galleri['navn']."</p>";
					echo "<a href='slet_galleri.php?galleri_id=$data_galleri[galleri_id]' class='slet_nyhed_link'>Slet</a>";
				}
			}
			if($_GET['page'] == "upload_billeder") {
			?>
			<h1>Upload billeder</h1>
			<form action="" method="post" name="billede_formular" enctype="multipart/form-data" onsubmit="valider_upload()">
				<p><label for="billede">Upload et billede</label></p>
				<input type="file" name="billede" id="billede" /><br/>
				<p><label for="billede_titel">Titel</label></p>
				<input type="text" name="billede_titel" id="billede_titel" class="nyhed_felt" value="<?php echo $_POST['billede_titel'];  ?>" />
				<p><label for="til_galleri">Billede til galleri</label></p>
				<input type="checkbox" name="galleri_billede" id="til_galleri" onchange="soeg_valg_onchange()" />
				<p><label for="nyhed_valg">V&aelig;lg nyheden eller galleriet</label></p>
				
				<select name="nyhed_valg" id="nyhed_valg">
				<?php
				$sql = "SELECT nyhed_id, overskrift FROM skoleprojekter_netavisen_nyhed ORDER BY nyhed_id";
				$resultat = mysqli_query($db, $sql);
				while($data = mysqli_fetch_assoc($resultat)) {
					echo "<option value='".$data['nyhed_id']."'>".$data['overskrift']."</option>\n";
				}
				?>
				</select>
				
				<select name="galleri_valg" id="galleri_valg">
				<?php
				$sql = "SELECT galleri_id, navn FROM skoleprojekter_netavisen_galleri ORDER BY navn";
				$resultat = mysqli_query($db, $sql);
				while($data = mysqli_fetch_assoc($resultat)) {
					echo "<option value='".$data['galleri_id']."'>".$data['navn']."</option>\n";
				}
				?>
				</select>
				<input type="submit" value="Upload" name="knap" class="nyhed_knap" />
			</form>
			<?php
				if(isset($_FILES['billede']) && $_FILES['billede'] != "" && isset($_POST['billede_titel']) && $_POST['billede_titel'] != "") {
					$title = $_POST['billede_titel'];
					$valg = $_POST['billede_valg'];
					$nyhed_id = $_POST['nyhed_valg'];
					$galleri_id = $_POST['galleri_valg'];
					if(isset($_POST['galleri_billede'])) {
						//hvis billedet er til et galleri
						$billede = new billede("../billeder/galleri/");
					} else {
						//hvis billedet er til en nyhed
						$billede = new billede("../billeder/nyheder/");
					}
					$billede->setBillede($_FILES['billede']);
					$filnavn = $billede->upload();
					if(isset($_POST['galleri_billede'])) {
						//hvis billedet er til et galleri
						$sql_billede = "INSERT INTO skoleprojekter_netavisen_billeder (sti, titel, nyhed_id, galleri_id) VALUES ('$filnavn', '$title', '0', '$galleri_id')";
					} else {
						//hvis billedet er til en nyhed
						$sql_billede = "INSERT INTO skoleprojekter_netavisen_billeder (sti, titel, nyhed_id, galleri_id) VALUES ('$filnavn', '$title', '$nyhed_id', '0')";
					}
					$resultat_billede = mysqli_query($db, $sql_billede);
					if($resultat_billede == true && $filnavn == true) {
						echo "<p>Billedet blev uploadet</p>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "slet_billeder") {
				echo "<h1>Slet billeder</h1>";
				$sql_slet = "SELECT billede_id, sti, titel, nyhed_id, galleri_id FROM skoleprojekter_netavisen_billeder ORDER BY titel";
				$resultat_slet = mysqli_query($db, $sql_slet);
				while($data_slet = mysqli_fetch_assoc($resultat_slet)) {
					if($data_slet['nyhed_id'] != 0) {
						echo "<img src='../billeder/nyheder/".$data_slet['sti']."' alt='".$data_slet['titel']."' />";
						echo "<a href='slet_billede.php?billede_id=$data_slet[billede_id]' class='slet_nyhed_link'>Slet</a>";
					}
					if($data_slet['galleri_id'] != 0) {
						echo "<img src='../billeder/galleri/".$data_slet['sti']."' alt='".$data_slet['titel']."' />";
						echo "<a href='slet_billede.php?billede_id=$data_slet[billede_id]' class='slet_nyhed_link'>Slet</a>";
					}					
				}
			}
			if($_GET['page'] == "ret_password") {
			?>
			<h1>Ret dit password</h1>
			<form action="" method="post" name="ret_form" onsubmit="valider_ret()">
				<p><label for="ret">Skriv det &oslash;nskede password</label></p>
				<input type="password" name="ret_password" id="ret" class="input" />
				<input type="submit" value="Ret" class="redaktor_knap" />
			</form>
			<?php
				if(isset($_POST['ret_password']) && $_POST['ret_password'] != "") {
					$ret_password = $_POST['ret_password'];
					$sql_ret = "UPDATE skoleprojekter_netavisen_journalist SET password='$ret_password' WHERE navn='$_SESSION[journalist_navn]'";
					$resultat_ret = mysqli_query($db, $sql_ret) or die (mysqli_error($db));
					if($resultat_ret == true) {
						echo "<p>Dit password er nu &aelig;ndret!</p>";
					} else {
						echo "<p>Der er sket en fejl!</p>";
					}
				}
			}
			ob_flush();
			?>
			</div>
			<div id="clear"></div>
		</div>
	</body>
</html>