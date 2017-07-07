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
include("../includes/funktioner.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../style/style.css" media="screen" />
		<link rel="shorcut icon" href="../billeder/favicon.ico" />
		<title>Forum - administration</title>
	</head>
	
	<body>
		<div id="top">
		</div><!-- slut toppen -->
		<div id="wrap">
			<div id="top"></div><!-- slut top -->
			<div id="admin_menu">
				<a href="admin.php?page=forside" <?php echo style("forside"); ?>>Forside</a>
				<a href="admin.php?page=opret_kategori" <?php echo style("opret_kategori"); ?>>Opret kategori</a>
				<a href="admin.php?page=ret_kategori" <?php echo style("ret_kategori"); ?>>Ret kategori</a>
				<a href="admin.php?page=opret_nyhed" <?php echo style("opret_nyhed"); ?>>Opret nyhed</a>
				<a href="admin.php?page=ret_nyhed" <?php echo style("ret_nyhed"); ?>>Ret nyhed</a>
				<a href="admin.php?page=nyhedsbrev" <?php echo style("nyhedsbrev"); ?>>Nyhedsbrev</a>
				<a href="admin.php?page=mail" <?php echo style("mail"); ?>>Send mail</a>
				<a href="admin.php?page=slet_bruger" <?php echo style("slet_bruger"); ?>>Slet bruger</a>
				<a href="admin.php?page=slet_kommentar" <?php echo style("slet_kommentar"); ?>>Slet kommentar</a>
				<a href="admin.php?page=slet_indlaeg" <?php echo style("slet_indlaeg"); ?>>Slet indl&aelig;g</a>
				<a href="admin_logaf.php">Log af</a>
			</div><!-- slut menu -->
			<div id="samle">
				<div id="content">
					<?php
					if($_GET['page'] == "forside") {
					?>
					<h1>Velkommen admin</h1>
					<?php
					}
					if($_GET['page'] == "opret_kategori") {
					?>
					<h1>Opret en kategori</h1>
					<form action="" method="post">
						<p><label for="navn">Navn</label></p>
						<input type="text" name="navn" id="navn" class="textfield" />
						<input type="submit" value="Opret" class="knapper" />
					</form>
					<?php
						if(isset($_POST['navn']) && $_POST['navn'] != "") {
							$sqlKategori = mysqli_query($db, "INSERT INTO skoleprojekter_forum_kategorier (navn) VALUES ('$_POST[navn]')");
							if($sqlKategori == true) {
								echo "<p>Kategorien ".$_POST['navn']." blev oprettet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "ret_kategori") {
					?>
					<h1>Ret eller slet en kategori</h1>
					<table>
						<tr>
							<td class="edit_cell"><h2>Navn</h2></td>
							<td class="edit_cell"><h2>Ret</h2></td>
							<td class="edit_cell"><h2>Slet</h2></td>
						</tr>
						<?php
						$retKategori = mysqli_query($db, "SELECT kategori_id, navn FROM skoleprojekter_forum_kategorier ORDER BY navn");
						while($dataKategori = mysqli_fetch_assoc($retKategori)) {
							echo "<tr>\n<td class='ret_celle'><p>".$dataKategori['navn']."</p></td>";
							echo "<td class='ret_celle'><a href='admin.php?page=rettet_kategori&amp;kategori_id=$dataKategori[kategori_id]'>Ret</a></td>";
							echo "<td class='ret_celle'><a href='../includes/slet_kategori.php?kategori_id=$dataKategori[kategori_id]'>Slet</a></td>\n</tr>\n";
						}
						echo "</table>";
					}
					if($_GET['page'] == "rettet_kategori") {
						$sqlKategori = mysqli_query($db, "SELECT kategori_id, navn FROM skoleprojekter_forum_kategorier WHERE kategori_id='$_GET[kategori_id]'");
						while($dataKategori = mysqli_fetch_assoc($sqlKategori)) {
						?>
						<form action="" method="post">
							<p><label for="ret_navn">Navn</label></p>
							<input type="text" name="ret_navn" id="ret_navn" class="textfield" value="<?php echo $dataKategori['navn']; ?>" />
							<input type="submit" name="ret_kategori" value="Ret" class="knapper" />
						</form>
						<?php
						}
						if(isset($_POST['ret_kategori'])) {
							$retKategori = mysqli_query($db, "UPDATE skoleprojekter_forum_kategorier SET navn='$_POST[ret_navn]' WHERE kategori_id='$_GET[kategori_id]'");
							if($retKategori == true) {
								header("location: admin.php?page=ret_kategori");
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "opret_nyhed") {
					?>
					<h1>Opret en nyhed</h1>
					<form action="" method="post">
						<p><label for="overskrift">Overskrift</label></p>
						<input type="text" name="overskrift" id="overskrift" class="textfield" />
						<p><label for="tekst">Tekst</label></p>
						<textarea name="tekst" id="tekst" class="textarea"></textarea>
						<input type="submit" value="Opret" class="knapper" />
					</form>
					<?php
						if(isset($_POST['overskrift']) && $_POST['overskrift'] != "" && isset($_POST['tekst']) && $_POST['tekst'] != "") {
							$opretNyhed = mysqli_query($db, "INSERT INTO skoleprojekter_forum_nyheder (overskrift, dato, tekst) VALUES ('$_POST[overskrift]', '".time()."', '$_POST[tekst]')");
							if($opretNyhed == true) {
								echo "<p>Nyheden ".$_POST['overskrift']." er oprettet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "ret_nyhed") {
					?>
					<h1>Ret eller slet en nyhed</h1>
					<table>
						<tr>
							<td class="edit_cell"><h2>Overskrift</h2></td>
							<td class="edit_cell"><h2>Ret</h2></td>
							<td class="edit_cell"><h2>Slet</h2></td>
						</tr>
						<?php
						$sletNyhed = mysqli_query($db, "DELETE FROM skoleprojekter_forum_nyheder WHERE nyhed_id='$_GET[nyhed_id]'");
						$sqlNyhed = mysqli_query($db, "SELECT nyhed_id, overskrift FROM skoleprojekter_forum_nyheder ORDER BY overskrift");
						while($dataNyhed = mysqli_fetch_assoc($sqlNyhed)) {
							echo "<tr>\n";
							echo "<td class='ret_celle'><p>".$dataNyhed['overskrift']."</p></td>";
							echo "<td class='ret_celle'><a href='admin.php?page=rettet_nyhed&amp;nyhed_id=$dataNyhed[nyhed_id]'>Ret</a></td>";
							echo "<td class='ret_celle'><a href='admin.php?page=ret_nyhed&amp;nyhed_id=$dataNyhed[nyhed_id]'>Slet</a></td>";
							echo "</tr>\n";
						}
						echo "</table>";
					}
					if($_GET['page'] == "rettet_nyhed") {
						$sqlNyhed = mysqli_query($db, "SELECT nyhed_id, overskrift, tekst FROM skoleprojekter_forum_nyheder WHERE nyhed_id='$_GET[nyhed_id]'");
						while($dataNyhed = mysqli_fetch_assoc($sqlNyhed)) {
						?>
						<form action="" method="post">
							<p><label for="ret_overskrift">Overskrift</label></p>
							<input type="text" name="ret_overskrift" id="ret_overskrift" class="textfield" value="<?php echo $dataNyhed['overskrift']; ?>" />
							<p><label for="ret_tekst">Tekst</label></p>
							<textarea name="ret_tekst" id="ret_tekst" class="textarea"><?php echo $dataNyhed['tekst']; ?></textarea>
							<input type="submit" name="ret_nyhed" value="Ret" class="knapper" />
						</form>
						<?php
						}
						if(isset($_POST['ret_nyhed'])) {
							$retNyhed = mysqli_query($db, "UPDATE skoleprojekter_forum_nyheder SET overskrift='$_POST[ret_overskrift]', tekst='$_POST[ret_tekst]' WHERE nyhed_id='$_GET[nyhed_id]'");
							if($retNyhed == true) {
								header("location: admin.php?page=ret_nyhed");
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "nyhedsbrev") {
					?>
					<h1>Skriv et nyhedsbrev</h1>
					<form action="" method="post">
						<p><label for="nyhedsbrev">Nyhedsbrev</label></p>
						<textarea name="nyhedsbrev" id="nyhedsbrev" class="textarea"></textarea>
						<input type="submit" value="Send" class="knapper" />
					</form>
					<?php
						if(isset($_POST['nyhedsbrev']) && $_POST['nyhedsbrev'] != "") {
							$sql_modtager = mysqli_query($db, "SELECT modtager FROM skoleprojekter_forum_nyhedsbrev");
		                    while($modtager = mysqli_fetch_assoc($sql_modtager)) {
		                        $modtagere[] = $modtager['modtager'];  
		                    }
		                    $modtagere = implode(",", $modtagere);
		                    $sendNyhedsbrev = mail($modtagere, "Nyhedsbrev", $_POST['nyhedsbrev']);
		                    if($sendNyhedsbrev == true) {
		                        echo "<p>Nyhedsbrevet blev sendt</p>";
		                    } else {
		                        echo "<p>Der er sket en fejl</p>";
		                    }
						}
					}
					if($_GET['page'] == "mail") {
					?>
					<h1>Send mails</h1>
					<form action="" method="post">
						<p><label for="modtager">Modtager</label></p>
						<select name="modtager" id="modtager" class="select">
							<?php
							$sqlModtager = mysqli_query($db, "SELECT email FROM skoleprojekter_forum_bruger ORDER BY email");
							while($dataModtager = mysqli_fetch_assoc($sqlModtager)) {
								echo "<option value='".$dataModtager['email']."'>".$dataModtager['email']."</option>";
							}
							?>
						</select>
						<p><label for="emne">Emne</label></p>
						<input type="text" name="emne" id="emne" class="textfield" />
						<p><label for="besked">Besked</label></p>
						<textarea name="besked" id="besked" class="textarea"></textarea>
						<input type="submit" value="Send" class="knapper" />
					</form>
					<?php
						if(isset($_POST['emne']) && $_POST['emne'] != "" && isset($_POST['besked']) && $_POST['besked'] != "") {
							$sendMail = mail($_POST['modtager'], $_POST['emne'], $_POST['besked'], "Reply-to: kenneth-clemmensen@hotmail.com");
							if($sendMail == true) {
								echo "<p>Mailen blev sendt</p>";
							} else {
								echo "<p>Mailen blev ikke sendt</p>";
							}
						}
					}
					if($_GET['page'] == "slet_bruger") {
					?>
					<h1>Slet en bruger</h1>
					<table>
						<tr>
							<td class="edit_cell"><h2>Navn</h2></td>
							<td class="edit_cell"><h2>E-mail</h2></td>
							<td class="edit_cell"><h2>Online</h2></td>
							<td class="edit_cell"><h2>Slet</h2></td>
						</tr>
						<?php
						$sql_bruger = mysqli_query($db, "SELECT bruger_id, email, navn, logget_ind FROM skoleprojekter_forum_bruger ORDER BY navn");
						while($data_bruger = mysqli_fetch_assoc($sql_bruger)) {
							echo "<tr><td class='ret_celle'><p>".$data_bruger['navn']."</p></td>";
							echo "<td class='ret_celle'><p>".$data_bruger['email']."</p></td>";
							echo "<td class='ret_celle'><p>";
							if($data_bruger['logget_ind'] == 1) {
								echo "Ja";
							} else {
								echo "Nej";
							}
							echo "</p></td>";
							echo "<td class='ret_celle'><a href='../includes/slet_bruger.php?bruger_id=$data_bruger[bruger_id]'>Slet</a></td></tr>";
						}
						?>
					</table>
					<?php
					}
					if($_GET['page'] == "slet_kommentar") {
					?>
					<h1>Slet en kommentar</h1>
					<table>
						<tr>
							<td class="edit_cell"><p>Email</p></td>
							<td class="edit_cell"><p>Kommentar</p></td>
							<td class="edit_cell"><p>Slet</p></td>
						</tr>
						<?php
						//sletter kommentaren
						$sletKommentar = mysqli_query($db, "DELETE FROM skoleprojekter_forum_kommentar WHERE kommentar_id='$_GET[kommentar_id]'");
						$sqlKommentar = mysqli_query($db, "SELECT kommentar_id, email, kommentar FROM skoleprojekter_forum_kommentar ORDER BY kommentar");
						while($dataKommentar = mysqli_fetch_assoc($sqlKommentar)) {
							echo "<tr><td class='ret_celle'><p>".$dataKommentar['email']."</p></td>";
							echo "<td class='ret_celle'><p>".$dataKommentar['kommentar']."</p></td>";
							echo "<td class='ret_celle'><a href='admin.php?page=slet_kommentar&amp;kommentar_id=$dataKommentar[kommentar_id]'>Slet</a></td></tr>\n";
						}
					echo "</table>";
					}
					if($_GET['page'] == "slet_indlaeg") {
					?>
					<h1>Slet indl&aelig;g</h1>
					<table>
						<tr>
							<td class="edit_cell"><p>Overskrift</p></td>
							<td class="edit_cell"><p>Slet</p></td>
						</tr>
						<?php
						$sqlIndlaeg = mysqli_query($db, "SELECT indlaeg_id, overskrift FROM skoleprojekter_forum_indlaeg ORDER BY overskrift");
						while($dataIndlaeg = mysqli_fetch_assoc($sqlIndlaeg)) {
							echo "<tr><td class='ret_celle'><p>".$dataIndlaeg['overskrift']."</p></td>";
							echo "<td class='ret_celle'><a href='../includes/slet_indlaeg.php?indlaeg_id=$dataIndlaeg[indlaeg_id]'>Slet</a></td></tr>";
						}
					echo "</table>";
					}
					ob_flush();
					?>
				</div><!-- slut indhold -->
				<div id="hojre">
			
				</div><!-- slut hojre -->
			</div><!-- slut samle -->
		</div><!-- slut wrap -->
	</body>
</html>