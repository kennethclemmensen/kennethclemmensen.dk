<?php
session_start();
ob_start();
if($_SESSION['admin'] != true) {
	header("location: index.php");
}
if(!isset($_GET['page'])) {
	$_GET['page'] = "forside";
}
include("../includes/db.php");
include("../includes/class.billede.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../style/style.css" media="screen" />
		<title>Indigo</title>
		<script type="text/javascript">
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
        function valg_onchange() {
            //opretter variablen e som indeholder funktionen getElementById der har id'et fra checkboxen
            var e = document.getElementById("udsmykning_billede");
            /*hvis der er sat et hak i checkboxen kalder den funktionen skift_element hvor argumentet
            state bliver sat til 1 ved det element der skal vises og 0 ved det element der ikke skal vises
            
            ellers bliver det sat omvendt i else
            */
            if(e.checked == true){
                skift_element("udsmykning_valg", 1);
                skift_element("galleri",  0);
                skift_element("overskrift1",  0);
                skift_element("overskrift2",  1);
            } else {
                skift_element("udsmykning_valg",  0);
                skift_element("galleri", 1);
                skift_element("overskrift1", 1);
                skift_element("overskrift2",  0);
            }
        }
        </script>		
	</head>
	
	<body>
		<div id="wrap">
			<div id="top">
				<h1>Malerier Indigo Richards</h1>
			</div><!-- slut top -->
			<div id="menu">
				|<a href="admin.php?page=forside">Forside</a>
				|<a href="admin.php?page=nyhed">Opret nyhed</a>
				|<a href="admin.php?page=ret_nyhed">Ret nyhed</a>
				|<a href="admin.php?page=cv_kategori">Opret cv-kategori</a>
				|<a href="admin.php?page=ret_cv_kategori">Ret cv-kategori</a>
				|<a href="admin.php?page=cv">Opret CV</a>
				|<a href="admin.php?page=ret_cv">Ret CV</a>
				|<a href="admin.php?page=ret_kontakt">Kontakt</a>|<br/>
				|<a href="admin.php?page=opret_galleri">Opret galleri</a>
				|<a href="admin.php?page=ret_galleri">Ret galleri</a>
				|<a href="admin.php?page=upload_billeder">Upload billeder</a>
				|<a href="admin.php?page=slet_billeder">Slet billeder</a>
				|<a href="admin.php?page=opret_udsmykning">Opret udsmykning</a>
				|<a href="admin.php?page=ret_udsmykning">Ret udsmykning</a>|
				<a href="admin_logaf.php">Log af</a>|
			</div><!-- slut menu -->
			<div id="content">
			<?php
			if($_GET['page'] == "forside") {
			?>
			<h1>Velkommen</h1>
			<p>Klik p&aring; linksne i menuen for at komme igang.</p>
			<?php
			}
			if($_GET['page'] == "nyhed") {
			?>
			<h1>Opret en nyhed</h1>
			<p>Her kan du oprette en nyhed.</p>
			<form action="" method="post">
				<p><label for="nyhed">Nyhed</label></p>
				<input type="text" name="nyhed" id="nyhed" class="textfield" />
				<input type="submit" value="Opret" class="knapper" />
			</form>
			<?php
				if(isset($_POST['nyhed']) && $_POST['nyhed'] != "") {
					$opretNyhed = mysqli_query($db, "INSERT INTO skoleprojekter_indigo_nyheder (nyhed, dato) VALUES ('$_POST[nyhed]', '".time()."')");
					if($opretNyhed == true) {
						echo "<p>Nyheden er oprettet</p>";
					} else {
						echo "<p>Nyhed blev ikke oprettet</p>";
					}
				}
			}
			if($_GET['page'] == "ret_nyhed") {
			?>
			<h1>Ret eller slet en nyhed</h1>
			<p>Klik p&aring; ret eller slet ud for den nyhed du vil rette elle slette.</p>
			<table>
				<tr>
					<td class="edit_cell"><p>Nyhed</p></td>
					<td class="edit_cell"><p>Dato</p></td>
					<td class="edit_cell"><p>Ret</p></td>
					<td class="edit_cell"><p>Slet</p></td>
				</tr>
				<?php
				$sletNyhed = mysqli_query($db, "DELETE FROM skoleprojekter_indigo_nyheder WHERE nyhed_id='$_GET[nyhed_id]'");
				$sqlNyhed = mysqli_query($db, "SELECT nyhed_id, nyhed, dato FROM skoleprojekter_indigo_nyheder ORDER BY nyhed");
				while($dataNyhed = mysqli_fetch_assoc($sqlNyhed)) {
					echo "<tr><td class='ret_celle'><p>".$dataNyhed['nyhed']."</p></td>\n";
					echo "<td class='ret_celle'><p>".date("j-m-Y", $dataNyhed['dato'])."</p></td>\n";
					echo "<td class='ret_celle'><a href='admin.php?page=rettet_nyhed&amp;nyhed_id=$dataNyhed[nyhed_id]'><input type='submit' value='Ret' class='knapper' /></a></td>\n";
					echo "<td class='ret_celle'><a href='admin.php?page=ret_nyhed&amp;nyhed_id=$dataNyhed[nyhed_id]'><input type='submit' value='Slet' class='knapper' /></a></td></tr>\n";
				}
				echo "</table>";
			}
			if($_GET['page'] == "rettet_nyhed") {
				$retNyhed = mysqli_query($db, "SELECT nyhed FROM skoleprojekter_indigo_nyheder WHERE nyhed_id='$_GET[nyhed_id]'");
				while($data_ret_nyhed = mysqli_fetch_assoc($retNyhed)) {
				?>
				<form action="" method="post">
					<p><label for="ret_nyhed">Ret nyheden</label></p>
					<input type="text" name="ret_nyhed" id="ret_nyhed" class="textfield" value="<?php echo $data_ret_nyhed['nyhed']; ?>" />
					<input type="submit" value="Ret" name="ret_knap" class="knapper" />
				</form>
				<?php
				}
				if(isset($_POST['ret_knap'])) {
					$opdaterNyhed = mysqli_query($db, "UPDATE skoleprojekter_indigo_nyheder SET nyhed='$_POST[ret_nyhed]', dato='".time()."' WHERE nyhed_id='$_GET[nyhed_id]'");
					if($opdaterNyhed == true) {
						header("location: admin.php?page=ret_nyhed");
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "cv_kategori") {
			?>
			<h1>Opret en cv-kategori</h1>
			<p>Her kan du oprette en kategori til dit CV.</p>
			<form action="" method="post">
				<p><label for="navn">Navn p&aring; kategorien</label></p>
				<input type="text" name="navn" id="navn" class="textfield" />
				<input type="submit" value="Opret" class="knapper" />
			</form>
			<?php
				if(isset($_POST['navn']) && $_POST['navn'] != "") {
					$sqlKategori = mysqli_query($db, "INSERT INTO skoleprojekter_indigo_cv_kategori (navn) VALUES ('$_POST[navn]')");
					if($sqlKategori == true) {
						echo "<p>Kategorien ".$_POST['navn']." er oprettet</p>";
					} else {
						echo "<p>Kategorien blev ikke oprettet</p>";
					}
				}
			}
			if($_GET['page'] == "ret_cv_kategori") {
			?>
			<h1>Ret en cv-kategori</h1>
			<p>P&aring; denne side kan du rette eller slette en cv-kategori. Hvis der er noget i kategorien bliver det ogs&aring; slettet.</p>
			<table>
				<tr>
					<td class="edit_cell"><p>Navn</p></td>
					<td class="edit_cell"><p>Ret</p></td>
					<td class="edit_cell"><p>Slet</p></td>
				</tr>
				<?php
				$sql_ret_kategori = mysqli_query($db, "SELECT kategori_id, navn FROM skoleprojekter_indigo_cv_kategori ORDER BY navn");
				while($data_ret_kategori = mysqli_fetch_assoc($sql_ret_kategori)) {
					echo "<tr><td class='ret_celle'><p>".$data_ret_kategori['navn']."</p></td>";
					echo "<td class='ret_celle'><a href='admin.php?page=rettet_kategori&kategori_id=$data_ret_kategori[kategori_id]'><input type='submit' value='Ret' class='knapper' /></a></td>";
					echo "<td class='ret_celle'><a href='../includes/slet_cv_kategori.php?kategori_id=$data_ret_kategori[kategori_id]'><input type='submit' value='Slet' class='knapper' /></a></td></tr>";
				}
				echo "</table>";
			}
			if($_GET['page'] == "rettet_kategori") {
				$sqlRetKategori = mysqli_query($db, "SELECT navn FROM skoleprojekter_indigo_cv_kategori WHERE kategori_id='$_GET[kategori_id]'");
				while($dataRetKategori = mysqli_fetch_assoc($sqlRetKategori)) {
				?>
				<form action="" method="post">
					<p><label for="ret_navn">Navn</label></p>
					<input type="text" name="ret_navn" id="ret_navn" class="textfield" value="<?php echo $dataRetKategori['navn']; ?>" />
					<input type="submit" name="ret_knap" value="Ret" class="knapper" />
				</form>
				<?php
					if(isset($_POST['ret_knap'])) {
						$ret_cv_kategori = mysqli_query($db, "UPDATE skoleprojekter_indigo_cv_kategori SET navn='$_POST[ret_navn]' WHERE kategori_id='$_GET[kategori_id]'");
						if($ret_cv_kategori == true) {
							header("location: admin.php?page=ret_cv_kategori");
						} else {
							echo "<p>Kategorien blev ikke opdateret</p>";
						}
					}
				}
			}
			if($_GET['page'] == "cv") {
			?>
			<h1>Opret CV</h1>
			<p>Her kan du oprette et CV.</p>
			<form action="" method="post">
				<p><label for="cv_kategori">V&aelig;lg kategori</label></p>
				<select name="cv_kategori" id="cv_kategori" class="select">
					<?php
					$sql_kategori = mysqli_query($db, "SELECT kategori_id, navn FROM skoleprojekter_indigo_cv_kategori ORDER BY navn");
					while($data_kategori = mysqli_fetch_assoc($sql_kategori)) {
						echo "<option value='$data_kategori[kategori_id]'>".$data_kategori['navn']."</option>";
					}
					?>
				</select>
				<p><label for="cv">CV</label></p>
				<input type="text" name="cv" id="cv" class="textfield" />
				<input type="submit" value="Opret" class="knapper" />
			</form>
			<?php
				if(isset($_POST['cv']) && $_POST['cv'] != "") {
					$sqlCV = mysqli_query($db, "INSERT INTO skoleprojekter_indigo_cv (kategori_id, tekst) VALUES ('$_POST[cv_kategori]', '$_POST[cv]')");
					if($sqlCV == true) {
						echo "<p>CV�et er oprettet</p>";
					} else {
						echo "<p>CV�et blev ikke oprettet</p>";
					}
				}
			}
			if($_GET['page'] == "ret_cv") {
			?>
			<h1>Ret dit CV</h1>
			<p>P&aring; denne side kan du rette eller slette dit CV.</p>
			<table>
				<tr>
					<td class="edit_cell"><p>CV</p></td>
					<td class="edit_cell"><p>Kategori</p></td>
					<td class="edit_cell"><p>Ret</p></td>
					<td class="edit_cell"><p>Slet</p></td>
				</tr>
				<?php
				$slet_cv = mysqli_query($db, "DELETE FROM skoleprojekter_indigo_cv WHERE cv_id='$_GET[cv_id]'");
				$sql_cv = mysqli_query($db, "SELECT cv_id, kategori_id, tekst FROM skoleprojekter_indigo_cv ORDER BY tekst");
				while($data_cv = mysqli_fetch_assoc($sql_cv)) {
					$sql_kategori = mysqli_query($db, "SELECT navn FROM skoleprojekter_indigo_cv_kategori WHERE kategori_id='$data_cv[kategori_id]'");
					$data_kategori = mysqli_fetch_assoc($sql_kategori);
					echo "<tr><td class='ret_celle'><p>".$data_cv['tekst']."</p></td>";
					echo "<td class='ret_celle'><p>".$data_kategori['navn']."</p></td>";
					echo "<td class='ret_celle'><a href='admin.php?page=rettet_cv&amp;cv_id=$data_cv[cv_id]'><input type='submit' value='Ret' class='knapper' /></a></td>";
					echo "<td class='ret_celle'><a href='admin.php?page=ret_cv&amp;cv_id=$data_cv[cv_id]'><input type='submit' value='Slet' class='knapper' /></a></td></tr>";
				}
				echo "</table>";
			}
			if($_GET['page'] == "rettet_cv") {
				$ret_cv = mysqli_query($db, "SELECT kategori_id, tekst FROM skoleprojekter_indigo_cv WHERE cv_id='$_GET[cv_id]'");
				while($data_ret_cv = mysqli_fetch_assoc($ret_cv)) {
				?>
				<form action="" method="post">
					<p><label for="ret_cv_kategori">Ret kategorien</label></p>
					<select name="ret_cv_kategori" id="ret_cv_kategori" class="select">
						<?php
						$kategori = mysqli_query($db, "SELECT kategori_id, navn FROM skoleprojekter_indigo_cv_kategori ORDER BY navn");
						while($data = mysqli_fetch_assoc($kategori)) {
							echo "<option value='".$data['kategori_id']."'>".$data['navn']."</option>";
						}
						?>
					</select>
					<p><label for="ret_cv">Ret CV</label></p>
					<input type="text" name="ret_cv" id="ret_cv" class="textfield" value="<?php echo $data_ret_cv['tekst']; ?>" />
					<input type="submit" name="ret_cv_knap" value="Ret" class="knapper" />
				</form>
				<?php
				}
				if(isset($_POST['ret_cv_knap'])) {
					$sqlRetCV = mysqli_query($db, "UPDATE skoleprojekter_indigo_cv SET kategori_id='$_POST[ret_cv_kategori]', tekst='$_POST[ret_cv]' WHERE cv_id='$_GET[cv_id]'");
					if($sqlRetCV == true) {
						header("location: admin.php?page=ret_cv");
					} else {
						echo "<p>CV�et blev ikke opdateret</p>";
					}
				}
			}
			if($_GET['page'] == "ret_kontakt") {
				$sql_kontakt = mysqli_query($db, "SELECT tekst FROM skoleprojekter_indigo_kontakt");
				$data_kontakt = mysqli_fetch_assoc($sql_kontakt);
				if(preg_match("<br />", $data_kontakt['tekst'])) {
					$data_kontakt['tekst'] = str_replace("<br />", "", $data_kontakt['tekst']);
				}
				?>
				<h1>Rediger kontakt</h1>
				<p>P&aring; denne side kan du redigerer kontaktinfomationerne</p>
				<form action='' method='post'>
					<p><label for="kontakt">Rediger kontakt</label></p>
					<textarea name="kontakt" id='kontakt'><?php if(isset($_POST['kontakt'])) { echo $_POST['kontakt']; } else { echo $data_kontakt['tekst']; } ?></textarea>
					<input type="submit" name="kontakt_knap" value="Ret" class="knapper" />
				</form>
			<?php
				if(isset($_POST['kontakt_knap'])) {
					$kontakt = nl2br($_POST['kontakt']);
					$opdaterKontakt = mysqli_query($db, "UPDATE skoleprojekter_indigo_kontakt SET tekst='$kontakt'");
					if($opdaterKontakt == true) {
						echo "<p>Kontaktsiden er opdateret</p>";
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "opret_galleri") {
			?>
			<h1>Opret galleri</h1>
			<p>P&aring; denne side kan du oprette et galleri.</p>
			<form action="" method="post">
				<p><label for="galleri_navn">Navn</label></p>
				<input type="text" name="galleri_navn" id="galleri_navn" class="textfield" />
				<input type="submit" value="Opret" class="knapper" />
			</form>
			<?php
				if(isset($_POST['galleri_navn']) && $_POST['galleri_navn'] != "") {
					$sqlGalleri = mysqli_query($db, "INSERT INTO skoleprojekter_indigo_galleri (navn) VALUES ('$_POST[galleri_navn]')");
					if($sqlGalleri == true) {
						echo "<p>Galleriet ".$_POST['galleri_navn']." er oprettet</p>";
					} else {
						echo "<p>Galleriet eksisterer allerede</p>";
					}
				}
			}
			if($_GET['page'] == "ret_galleri") {
			?>
			<h1>Rediger gallerier</h1>
			<p>P&aring; denne side kan du rette eller slette gallerier.</p>
			<table>
				<tr>
					<td class="edit_cell"><p>Navn</p></td>
					<td class="edit_cell"><p>Ret</p></td>
					<td class="edit_cell"><p>Slet</p></td>
				</tr>
				<?php
				$sql_ret_galleri = mysqli_query($db, "SELECT galleri_id, navn FROM skoleprojekter_indigo_galleri ORDER BY navn");
				while($data_ret_galleri = mysqli_fetch_assoc($sql_ret_galleri)) {
					echo "<tr><td class='ret_celle'><p>".$data_ret_galleri['navn']."</p></td>";
					echo "<td class='ret_celle'><a href='admin.php?page=rettet_galleri&amp;galleri_id=$data_ret_galleri[galleri_id]'><input type='submit' value='Ret' class='knapper' /></a></td>";
					echo "<td class='ret_celle'><a href='../includes/slet_galleri.php?galleri_id=$data_ret_galleri[galleri_id]'><input type='submit' value='Slet' class='knapper' /></a></td>";
				}
				echo "</table>";
			}
			if($_GET['page'] == "rettet_galleri") {
				$sqlRetGalleri = mysqli_query($db, "SELECT navn FROM skoleprojekter_indigo_galleri WHERE galleri_id='$_GET[galleri_id]'");
				$dataRetGalleri = mysqli_fetch_assoc($sqlRetGalleri);
				?>
				<form action="" method="post">
					<p><label for="ret_galleri_navn">Navn</label></p>
					<input type="text" name="ret_galleri_navn" id="ret_galleri_navn" class="textfield" value="<?php echo $dataRetGalleri['navn']; ?>" />
					<input type="submit" name="ret_galleri_knap" value="Ret" class="knapper" />
				</form>
				<?php
				if(isset($_POST['ret_galleri_knap'])) {
					$retNavn = mysqli_query($db, "UPDATE skoleprojekter_indigo_galleri SET navn='$_POST[ret_galleri_navn]' WHERE galleri_id='$_GET[galleri_id]'");
					if($retNavn == true) {
						header("location: admin.php?page=ret_galleri");
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			if($_GET['page'] == "upload_billeder") {
			?>
			<h1>Upload billeder</h1>
			<p>P&aring; denne side kan du uploade billeder. De skal v&aelig;re i formatet jpg, gif eller png.</p>
			<form action="" method="post" enctype="multipart/form-data">
				<p><label for="fil">Billedet</label></p>
				<input type="file" name="fil" id="fil" />
				<p><label for="titel">Titel</label></p>
				<input type="text" name="titel" id="titel" class="textfield" />
				<p><input type="checkbox" name="udsmykning_billede" id="udsmykning_billede" onchange="valg_onchange()" /><label for="udsmykning_billede">&nbsp;Billede til udsmykning</label></p>
				<p id="overskrift1"><label for="galleri">V&aelig;lg galleri</label></p>
				<select name="galleri" id="galleri" class="select">
					<?php
					$vaelg_galleri = mysqli_query($db, "SELECT galleri_id, navn FROM skoleprojekter_indigo_galleri ORDER BY navn");
					while($data_vaelg_galleri = mysqli_fetch_assoc($vaelg_galleri)) {
						echo "<option value='".$data_vaelg_galleri['galleri_id']."'>".$data_vaelg_galleri['navn']."</option>";
					}
					?>
				</select>
				<p id="overskrift2"><label for="udsmykning_valg">V&aelig;lg udsmykning</label></p>
				<select name="udsmykning_valg" id="udsmykning_valg" class="select">
					<?php
					$vaelg_udsmykning = mysqli_query($db, "SELECT udsmykning_id, udsmykning FROM skoleprojekter_indigo_udsmykning ORDER BY udsmykning");
					while($data_vaelg_udsmykning = mysqli_fetch_assoc($vaelg_udsmykning)) {
						echo "<option value='".$data_vaelg_udsmykning['udsmykning_id']."'>".$data_vaelg_udsmykning['udsmykning']."</option>";
					}
					?>
				</select>
				<input type="submit" value="Upload" class="knapper" />
			</form>
			<?php
				if(isset($_FILES['fil']) && $_FILES['fil'] != "" && isset($_POST['titel']) && $_POST['titel'] != "") {
					/*echo "<pre>";
					print_r($_FILES);
					echo "<pre>";*/
					$fil = $_FILES['fil'];
					$filnavn = time()."_".$fil['name'];
					$filnavn = strtolower($filnavn);
					if(substr($filnavn, -3) == "jpg" || substr($filnavn, -4) == "jpeg" || substr($filnavn, -3) == "gif" || substr($filnavn, -3) == "png") {
						//tjekker om det er et billede til udsmykning
						if(isset($_POST['udsmykning_billede'])) {
							//echo "Udsmykningbillede";
							$billede = new billede("../billeder/udsmykninger/");
							$billede->setBillede($fil);
							$upload = $billede->upload();
							$upload_db = mysqli_query($db, "INSERT INTO skoleprojekter_indigo_billeder (galleri_id, udsmykning_id, sti, titel) VALUES ('0', '$_POST[udsmykning_valg]', '$filnavn', '$_POST[titel]')");
						} else {
							//echo "Galleribillede";
							//ellers er det et billede til et galleri
							$billede = new billede("../billeder/galleri_billeder/");
							$billede->setBillede($fil);
							$upload = $billede->upload();
							$upload_db = mysqli_query($db, "INSERT INTO skoleprojekter_indigo_billeder (galleri_id, udsmykning_id, sti, titel) VALUES ('$_POST[galleri]', '0', '$filnavn', '$_POST[titel]')");
						}
						if($upload == true && $upload_db == true) {
							echo "<p>Billedet blev uploadet</p>";
						} else {
							echo "<p>Billedet blev IKKE uploadet</p>";
						}
					} else {
						echo "<p>Filformatet er ikke gyldigt</p>";
					}
				}
			}
			if($_GET['page'] == "slet_billeder") {
			?>
			<h1>Slet billeder</h1>
			<p>P&aring; denne side kan du slette billeder.</p>
			<table>
				<tr>
					<td class="edit_cell"><p>Billedet</p></td>
					<td class="edit_cell"><p>Navn</p></td>
					<td class="edit_cell"><p>Slet</p></td>
				</tr>
				<?php
				$sql_slet_billede = mysqli_query($db, "SELECT billede_id, galleri_id, sti, titel FROM skoleprojekter_indigo_billeder ORDER BY titel");
				while($data_slet_billede = mysqli_fetch_assoc($sql_slet_billede)) {
					echo "<tr><td class='ret_celle'>";
					//hvis det er et galleribillede
					if($data_slet_billede['galleri_id'] != 0) {
						echo "<img src='../billeder/galleri_billeder/".$data_slet_billede['sti']."' alt='".$data_slet_billede['titel']."' />";
					} else {
						//hvis det er et udsmykningsbillede
						echo "<img src='../billeder/udsmykninger/".$data_slet_billede['sti']."' alt='".$data_slet_billede['titel']."' />";				
					}
					echo "</td>";
					echo "<td class='ret_celle'><p>".$data_slet_billede['titel']."</p></td>";
					echo "<td class='ret_celle'><a href='../includes/slet_billede.php?billede_id=$data_slet_billede[billede_id]'><input type='submit' value='Slet' class='knapper' /></a></td></tr>";
				}
				echo "</table>";
			}
			if($_GET['page'] == "opret_udsmykning") {
			?>
			<h1>Opret udsmykning</h1>
			<p>P&aring; denne side kan du oprette en udsmykning.</p>
			<form action="" method="post">
				<p><label for="udsmykning_navn">Udsmykning</label></p>
				<input type="text" name="udsmykning_navn" id="udsmykning_navn" class="textfield" />
				<input type="submit" value="Opret" class="knapper" />
			</form>
			<?php
				if(isset($_POST['udsmykning_navn']) && $_POST['udsmykning_navn'] != "") {
					$sqlUdsmykning = mysqli_query($db, "INSERT INTO skoleprojekter_indigo_udsmykning (udsmykning) VALUES ('$_POST[udsmykning_navn]')");
					if($sqlUdsmykning == true) {
						echo "<p>Udsmykningen ".$_POST['udsmykning_navn']." er oprettet</p>";
					} else {
						echo "<p>Udsmykningen blev ikke oprettet</p>";
					}
				}
			}
			if($_GET['page'] == "ret_udsmykning") {
			?>
			<h1>Rediger udsmykning</h1>
			<p>Her kan du rette eller slette en udsmykning.</p>
			<table>
				<tr>
					<td class="edit_cell"><p>Udsmykning</p></td>
					<td class="edit_cell"><p>Ret</p></td>
					<td class="edit_cell"><p>Slet</p></td>
				</tr>
				<?php
				$sql_udsmykning = mysqli_query($db, "SELECT udsmykning_id, udsmykning FROM skoleprojekter_indigo_udsmykning ORDER BY udsmykning");
				while($data_udsmykning = mysqli_fetch_assoc($sql_udsmykning)) {
					echo "<tr><td class='ret_celle'><p>".$data_udsmykning['udsmykning']."</p></td>";
					echo "<td class='ret_celle'><a href='admin.php?page=rettet_udsmykning&amp;udsmykning_id=$data_udsmykning[udsmykning_id]'><input type='submit' value='Ret' class='knapper' /></a></td>";
					echo "<td class='ret_celle'><a href='../includes/slet_udsmykning.php?udsmykning_id=$data_udsmykning[udsmykning_id]'><input type='submit' value='Slet' class='knapper' /></a></td></tr>";
				}
				echo "</table>";
			}
			if($_GET['page'] == "rettet_udsmykning") {
				$sql_ret_udsmykning = mysqli_query($db, "SELECT udsmykning FROM skoleprojekter_indigo_udsmykning WHERE udsmykning_id='$_GET[udsmykning_id]'");
				while($data_ret_udsmykning = mysqli_fetch_assoc($sql_ret_udsmykning)) {
				?>
				<form action="" method="post">
					<p><label for="ret_udsmykning">Udsmykning</label></p>
					<input type="text" name="ret_udsmykning" id="ret_udsmykning" class="textfield" value="<?php echo $data_ret_udsmykning['udsmykning']; ?>" />
					<input type="submit" name="udsmykning_knap" value="Ret" class="knapper" />
				</form>
				<?php
				}
				if(isset($_POST['udsmykning_knap'])) {
					$ret_udsmykning = mysqli_query($db, "UPDATE skoleprojekter_indigo_udsmykning SET udsmykning='$_POST[ret_udsmykning]' WHERE udsmykning_id='$_GET[udsmykning_id]'");
					if($ret_udsmykning == true) {
						header("location: admin.php?page=ret_udsmykning");
					} else {
						echo "<p>Der er sket en fejl</p>";
					}
				}
			}
			ob_flush();
			?>
			</div><!-- slut indhold -->
			<div id="clear"></div><!-- slut clear -->
		</div><!-- slut wrap -->
	</body>
</html>