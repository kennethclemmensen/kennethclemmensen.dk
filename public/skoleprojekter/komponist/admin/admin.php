<?php
session_start();
if($_SESSION['admin'] != true) {
	header('location: index.php');
}
if(!isset($_GET['page'])) {
	$_GET['page'] = "opret_link";
}
ob_start();
include("../includes/db.php");
include("../includes/funktioner.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../style/admin_style.css" media="screen" />
		<title>Komponist Kasper Jarnum - administration</title>
	</head>
	<body>
		<div id="top">
			<div id="top2"></div>
		</div><!-- slut top -->
		<div id="wrap">
			<div id="menu">
				<a href="admin.php?page=opret_link" <?php echo style("opret_link"); ?>>Opret links</a>
				<a href="admin.php?page=rediger_link" <?php echo style("rediger_link"); echo style("ret_link"); ?>>Rediger links</a>
				<a href="admin.php?page=opret_tour" <?php echo style("opret_tour"); ?>>Opret tourplan</a>
				<a href="admin.php?page=rediger_tour" <?php echo style("rediger_tour"); echo style("ret_tour"); ?>>Rediger tourplan</a>
				<a href="admin.php?page=rediger_biografi" <?php echo style("rediger_biografi"); ?>>Rediger biografi</a>
				<a href="admin.php?page=godkend_anmeldelse" <?php echo style("godkend_anmeldelse"); ?>>Godkend anmeldelse</a>
				<a href="admin.php?page=opret_vaerk" <?php echo style("opret_vaerk"); ?>>Opret v&aelig;rk</a>
				<a href="admin.php?page=rediger_vaerk" <?php echo style("rediger_vaerk"); echo style("ret_vaerk"); ?>>Rediger v&aelig;rk</a>
				<a href="../includes/logaf.php">Log af</a>
			</div><!-- slut menu -->
			<div id="samle">
				<div id="content">
					<?php
					if($_GET['page'] == "opret_link") {
					?>
					<h1>Opret link</h1>
					<form action="" method="post">
						<p><label for="http">Http</label></p>
						<input type="text" name="http" id="http" class="textfield" />
						<p><label for="titel">Titel</label></p>
						<input type="text" name="titel" id="titel" class="textfield" />
						<input type="submit" value="Opret" class="knapper" />
					</form>
					<?php	
						if(isset($_POST['http']) && $_POST['http'] != "" && isset($_POST['titel']) && $_POST['titel'] != "") {
							$opretLink = mysqli_query($db, "INSERT INTO skoleprojekter_komponist_links (http, titel) VALUES ('$_POST[http]', '$_POST[titel]')");
							if($opretLink == true) {
								echo "<p>Linket er oprettet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == 'rediger_link') {
					?>
					<h1>Rediger link</h1>
					<table>
						<tr>
							<td class="rediger_celle"><h2>Http</h2></td>
							<td class="rediger_celle"><h2>Titel</h2></td>
							<td class="rediger_celle"><h2>Rediger</h2></td>
							<td class="rediger_celle"><h2>Slet</h2></td>
						</tr>
						<?php
						$sletLink = mysqli_query($db, "DELETE FROM skoleprojekter_komponist_links WHERE link_id='$_GET[link_id]'");
						$sqlLink = mysqli_query($db, "SELECT link_id, http, titel FROM skoleprojekter_komponist_links ORDER BY titel");
						while($dataLink = mysqli_fetch_assoc($sqlLink)) {
							echo "<tr><td class='rediger_celle'><p>".$dataLink['http']."</p></td>";
							echo "<td class='rediger_celle'><p>".$dataLink['titel']."</p></td>";
							echo "<td class='rediger_celle'><a href='admin.php?page=ret_link&amp;link_id=".$dataLink['link_id']."'>Rediger</a></td>";
							echo "<td class='rediger_celle'><a href='admin.php?page=rediger_link&amp;link_id=".$dataLink['link_id']."'>Slet</a></td></tr>";
						}
						echo "</table>";
					}
					if($_GET['page'] == "ret_link") {
						$link = mysqli_query($db, "SELECT http, titel FROM skoleprojekter_komponist_links WHERE link_id='$_GET[link_id]'");
						while($ret_link = mysqli_fetch_assoc($link)) {
						?>
						<form action="" method="post">
							<p><label for="ret_http">Http</label></p>
							<input type="text" name="ret_http" id="ret_http" class="textfield" value="<?php echo $ret_link['http']; ?>" />
							<p><label for="titel">Titel</label></p>
							<input type="text" name="ret_titel" id="ret_titel" class="textfield" value="<?php echo $ret_link['titel']; ?>" />
							<input type="submit" name="ret" value="Ret" class="knapper" />
						</form>
						<?php
						}
						if(isset($_POST['ret'])) {
							$retLink = mysqli_query($db, "UPDATE skoleprojekter_komponist_links SET http='$_POST[ret_http]', titel='$_POST[ret_titel]'");
							if($retLink = true) {
								header("location: admin.php?page=rediger_link");
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "opret_tour") {
					?>
					<h1>Opret tour</h1>
					<form action="" method="post">
						<p><label for="sted">Sted</label></p>
						<input type="text" name="sted" id="sted" class="textfield" />
						<p><label for="dato">Dato</label></p>
						<input type="text" name="dato" id="dato" class="textfield" value="dd-mm-&aring;&aring;&aring;&aring;" />
						<p><label for="tid">Tid</label></p>
						<input type="text" name="tid" id="tid" class="textfield" />
						<input type="submit" value="Opret" class="knapper" />
					</form>
					<?php
						if(isset($_POST['sted']) && $_POST['sted'] != "" && isset($_POST['dato']) && $_POST['dato'] != "" && isset($_POST['tid']) && $_POST['tid'] != "") {
							$dato = explode("-", $_POST['dato']);
							$dato = mktime(0, 0, 0, $dato[1], $dato[0], $dato[2]);
							$opretTour = mysqli_query($db, "INSERT INTO skoleprojekter_komponist_tour (sted, dato, tid) VALUES ('$_POST[sted]', '$dato', '$_POST[tid]')");
							if($opretTour == true) {
								echo "<p>Touren er oprettet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "rediger_tour") {
					?>
					<h1>Rediger tour</h1>
					<table>
						<tr>
							<td class="rediger_celle"><h2>Sted</h2></td>
							<td class="rediger_celle"><h2>Dato</h2></td>
							<td class="rediger_celle"><h2>Rediger</h2></td>
							<td class="rediger_celle"><h2>Slet</h2></td>
						</tr>
					<?php
						$slet_tour = mysqli_query($db, "DELETE FROM skoleprojekter_komponist_tour WHERE tour_id='$_GET[tour_id]'");
						$ret_tour = mysqli_query($db, "SELECT tour_id, sted, dato FROM skoleprojekter_komponist_tour ORDER BY dato");
						while($data_tour = mysqli_fetch_assoc($ret_tour)) {
							echo "<tr><td class='rediger_celle'><p>".$data_tour['sted']."</p></td>\n";
							echo "<td class='rediger_celle'><p>".date("d-m-Y", $data_tour['dato'])."</p></td>";
							echo "<td class='rediger_celle'><a href='admin.php?page=ret_tour&amp;tour_id=".$data_tour['tour_id']."'>Rediger</a></td>";
							echo "<td class='rediger_celle'><a href='admin.php?page=rediger_tour&amp;tour_id=".$data_tour['tour_id']."'>Slet</a></td></tr>";
						}
						echo "</table>";
					}
					if($_GET['page'] == "ret_tour") {
						$tour = mysqli_query($db, "SELECT sted, dato, tid FROM skoleprojekter_komponist_tour WHERE tour_id='$_GET[tour_id]'");
						while($dataTour = mysqli_fetch_assoc($tour)) {
						?>
						<form action="" method="post">
						<p><label for="ret_sted">Sted</label></p>
						<input type="text" name="ret_sted" id="ret_sted" class="textfield" value="<?php echo $dataTour['sted']; ?>" />
						<p><label for="ret_dato">Dato</label></p>
						<input type="text" name="ret_dato" id="ret_dato" class="textfield" value="<?php echo date("d-m-Y", $dataTour['dato']); ?>" />
						<p><label for="tidret_">Tid</label></p>
						<input type="text" name="ret_tid" id="ret_tid" class="textfield" value="<?php echo $dataTour['tid']; ?>" />
						<input type="submit" value="Ret" class="knapper" />
					</form>
						<?php
						}
						if(isset($_POST['ret_sted']) && $_POST['ret_sted'] != "" && isset($_POST['ret_dato']) && $_POST['ret_dato'] != "" && isset($_POST['ret_tid']) && $_POST['ret_tid'] != "") {
							$ret_dato = explode("-", $_POST['ret_dato']);
							$ret_dato = mktime(0, 0, 0, $ret_dato[1], $ret_dato[0], $ret_dato[2]);
							$retTour = mysqli_query($db, "UPDATE skoleprojekter_komponist_tour SET sted='$_POST[ret_sted]', dato='$ret_dato', tid='$_POST[ret_tid]' WHERE tour_id='$_GET[tour_id]'");
							if($retTour == true) {
								header("location: admin.php?page=rediger_tour");
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "rediger_biografi") {
					?>
					<h1>Rediger biografi</h1>
					<form action="" method="post">
						<p><label for="biografi">Biografi</label></p>
						<?php
						$sql_biografi = mysqli_query($db, "SELECT tekst FROM skoleprojekter_komponist_biografi");
						$biografi = mysqli_fetch_assoc($sql_biografi);
						if(preg_match("[<br />]", $biografi['tekst'])) {
							$biografi['tekst'] = str_replace("<br />", "", $biografi['tekst']);
						}
						?>
						<textarea name="biografi" id="biografi"><?php echo $biografi['tekst']; ?></textarea>
						<input type="submit" name="opdater" value="Opdater" class="knapper" />
					</form>
					<?php
						if(isset($_POST['opdater'])) {
							$biografi = nl2br($_POST['biografi']);
							$opdaterBiografi = mysqli_query($db, "UPDATE skoleprojekter_komponist_biografi SET tekst='$biografi'");
							if($opdaterBiografi == true) {
								echo "<p>Biografien er opdateret</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "godkend_anmeldelse") {
					?>
					<h1>Godkend anmeldelser</h1>
					<table>
						<tr>
							<td class="rediger_celle"><h2>Anmelder</h2></td>
							<td class="rediger_celle"><h2>Stjerner</h2></td>
							<td class="rediger_celle"><h2>Anmeldelse</h2></td>
							<td class="rediger_celle"><h2>Godkend</h2></td>
							<td class="rediger_celle"><h2>Slet</h2></td>
						</tr>
					<?php
						$godkend = mysqli_query($db, "UPDATE skoleprojekter_komponist_anmeldelser SET godkendt='1' WHERE anmeldelse_id='$_GET[anmeldelse_id]'");
						$sql_anmeldelser = mysqli_query($db, "SELECT * FROM skoleprojekter_komponist_anmeldelser WHERE godkendt='0' ORDER BY stjerner DESC");
						while($data_anmeldelser = mysqli_fetch_assoc($sql_anmeldelser)) {
						?>
						<tr>
							<td class="rediger_celle"><p><?php echo $data_anmeldelser['anmelder']; ?></p></td>
							<td class="rediger_celle"><p><?php echo $data_anmeldelser['stjerner']; ?></p></td>
							<td class="rediger_celle"><p><?php echo $data_anmeldelser['tekst']; ?></p></td>
							<td class="rediger_celle"><a href="admin.php?page=godkend_anmeldelse&amp;anmeldelse_id=<?php echo $data_anmeldelser['anmeldelse_id']; ?>">Godkend</a></td>
							<td class="rediger_celle"><a href="../includes/slet_anmeldelse.php?anmeldelse_id=<?php echo $data_anmeldelser['anmeldelse_id'] ?>">Slet</a></td>
						</tr> 
						<?php
						}
						echo "</table>";
					}
					if($_GET['page'] == "opret_vaerk") {
					?>
					<h1>Opret v&aelig;rk</h1>
					<form action="" method="post">
						<p><label for="overskrift">Overskrift</label></p>
						<input type="text" name="overskrift" id="overskrift" class="textfield" />
						<p><label for="beskrivelse">Beskrivelse</label></p>
						<textarea name="beskrivelse" id="beskrivelse"></textarea>
						<input type="submit" value="Gem" class="knapper" />
					</form>
					<?php
						if(isset($_POST['overskrift']) && $_POST['overskrift'] != "" && isset($_POST['beskrivelse']) && $_POST['beskrivelse'] != "") {
							$opret_vaerk = mysqli_query($db, "INSERT INTO skoleprojekter_komponist_vaerkliste (overskrift, beskrivelse) VALUES ('$_POST[overskrift]', '$_POST[beskrivelse]')");
							if($opret_vaerk == true) {
								echo "<p>V&aelig;rket er oprettet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET['page'] == "rediger_vaerk") {
					?>
					<h1>Rediger v&aelig;rk</h1>
					<table>
						<tr>
							<td class="rediger_celle"><h2>Overskrift</h2></td>
							<td class="rediger_celle"><h2>Beskrivelse</h2></td>
							<td class="rediger_celle"><h2>Ret</h2></td>
							<td class="rediger_celle"><h2>Slet</h2></td>
						</tr>
						<?php
						$sql_vaerk = mysqli_query($db, "SELECT * FROM skoleprojekter_komponist_vaerkliste ORDER BY overskrift");
						while($data_vaerk = mysqli_fetch_assoc($sql_vaerk)) {
						?>
						<tr>
							<td class="rediger_celle"><p><?php echo $data_vaerk['overskrift']; ?></p></td>
							<td class="rediger_celle"><p><?php echo $data_vaerk['beskrivelse']; ?></p></td>
							<td class="rediger_celle"><a href="admin.php?page=ret_vaerk&amp;vaerk_id=<?php echo $data_vaerk['vaerk_id']; ?>">Ret</a></td>
							<td class="rediger_celle"><a href="../includes/slet_vaerk.php?vaerk_id=<?php echo $data_vaerk['vaerk_id']; ?>">Slet</a></td>
						</tr>
						<?php
						}
						echo "</table>";
					}
					if($_GET['page'] == "ret_vaerk") {
						$ret = mysqli_query($db, "SELECT * FROM skoleprojekter_komponist_vaerkliste WHERE vaerk_id='$_GET[vaerk_id]'");
						while($vaerk = mysqli_fetch_assoc($ret)) {
						?>
						<form action="" method="post">
							<p><label for="overskrift">Overskrift</label></p>
							<input type="text" name="overskrift" id="overskrift" class="textfield" value="<?php echo $vaerk['overskrift']; ?>" />
							<p><label for="beskrivelse">Beskrivelse</label></p>
							<textarea name="beskrivelse" id="beskrivelse"><?php echo $vaerk['beskrivelse']; ?></textarea>
							<input type="submit" name="ret_knappen" value="Gem" class="knapper" />
						</form>
						<?php
						}
						if(isset($_POST['ret_knappen'])) {
							$ret_vaerk = mysqli_query($db, "UPDATE skoleprojekter_komponist_vaerkliste SET overskrift='$_POST[overskrift]', beskrivelse='$_POST[beskrivelse]' WHERE vaerk_id='$_GET[vaerk_id]'");
							if($ret_vaerk == true) {
								header("location: admin.php?page=rediger_vaerk");
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					ob_flush();
					?>
				</div><!-- slut indhold -->
				<div id="hojre">
					<h2>Kasper Jarnum</h2>
					<img src="../billeder/kj2.jpg" alt="Kasper Jarnum" title="Kasper Jarnum" />
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