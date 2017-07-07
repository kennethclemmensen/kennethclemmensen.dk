<?php
session_start();
if($_SESSION["admin"]!==true) {
	header("location: admin_login.php");
}
if(isset($_GET["page"])) {
	$_GET["page"];
} else {
	$_GET["page"] = "forside";
}
include("db.php");
include("dktime.php");
include("mytime.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Rejsesiden</title>
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAqHbZgLVEjNi1RhvkmgDVqBS5wOjB-8J2VKGjOJq0ZdwSRnACMRSNtDW01-_Yu0GISi5TXl3X6UAiaQ" type="text/javascript"></script>
		<?php
		$vis_grader = mysqli_query($db, "SELECT langdegrad, breddegrad FROM rejsesiden_admin");
		$dataGrader = mysqli_fetch_array($vis_grader);
		?>
		<script language="javascript">
		function initialize() {
			if(GBrowserIsCompatible()) {
				var map = new GMap2(document.getElementById("map_canvas"));
				map.setCenter(new GLatLng(<?php echo $dataGrader["breddegrad"].",".$dataGrader["langdegrad"]; ?>), 12);
				map.setUIToDefault();
			}
		}
		function valider_position() {
			if(document.position_formular.tidszone.value=="" ||
			document.position_formular.position.value=="") {
				alert("Du skal udfylde begge felter!");
				return false;
			}
		}
		function valider_kort() {
			if(document.kort.langdegrad.value=="" ||
			document.kort.breddegrad.value=="") {
				alert("Du skal udfylde begge felter!");
				return false;
			}
		}
		function valider_opret() {
			if(document.opret.opret_brugernavn.value=="" ||
			document.opret.opret_password.value=="") {
				alert("Du skal udfylde begge felter!");
				return false;
			}
		}
		function valider_rejseplan() {
			if(document.rejseplan.dato.value=="" ||
			document.rejseplan.tekst.value=="") {
				alert("Du skal udfylde begge felter!");
				return false;
			}
		}
		function valider_upload() {
			if(document.upload.minfil.value=="" ||
			document.upload.beskrivelse.value=="" ||
			document.upload.dato.value=="") {
				alert("Alle felter skal udfyldes!");
				return false;
			}
		}
		</script>
		</head>
	<body onload="initialize()" onunload="GUnload()">
		<div id="wrap">
			<div id="top">
			</div>
			<div id="samle">
				<div id="menu">
					<ul>
						<li><a href="admin.php?page=forside">Forside</a></li>
						<li><a href="admin.php?page=lokaltid">Indstil tid, position og kort</a></li>
						<li><a href="admin.php?page=rejseplan">Rejseplan</a></li>
						<li><a href="admin.php?page=ret_rejseplan">Ret din rejseplan</a></li>
						<li><a href="admin.php?page=slet_rejseplan">Slet din rejseplan</a></li>
						<li><a href="admin.php?page=opretbruger">Opret bruger</a></li>
						<li><a href="admin.php?page=sletbruger">Slet bruger</a></li>
						<li><a href="admin.php?page=sletindlaeg">Slet indl&aelig;g</a></li>
						<li><a href="admin.php?page=upload">Upload et billede</a></li>
						<li><a href="admin.php?page=slet_billede">Slet et billede</a></li>
						<li><a href="logaf_admin.php">Log af</a></li>
					</ul>
				</div>
				<div id="content">
					<?php
					if($_GET["page"]=="forside") {
					?>
					<h1>Admin forside</h1>
					<p>Klik p&aring; de forskellige links i menuen til venstre for at v&aelig;lge</p>
					<?php
					}
					if($_GET["page"]=="lokaltid") {
					?>
					<h1>Lokal tid og position</h1>
					<form action="" method="post" name="position_formular" onsubmit="return valider_position();">
						<label for="tidszone"><p class="opret">Tidszone</p></label><input type="text" name="tidszone" id="position" /> 
						<label for="position"><p class='opret'>Position</p></label><input type="text" name="position" id="position" />
						<input type="submit" value="Opdater" id="opdater_knap" />
					</form>
					<?php
						if(isset($_POST["tidszone"]) && isset($_POST["position"])) {
							$position = $_POST["position"];
							$tidszone = $_POST["tidszone"];							
							$opdater  = mysqli_query($db, "UPDATE skoleprojekter_rejsesiden_admin SET position='$position', tidszone='$tidszone'");
							if($opdater == true) {
								echo "<p class='position_opdateret'>Positionen og tidszonen er opdateret</p>";
							} else {
								echo "<p class='position_opdateret'>Der er sket en fejl</p>";
							}
						}
					?>
					<h1>Kort</h1>
					<form action="" method="post" name="kort" onsubmit="return valider_kort();">
						<label for="breddegrad"><p class="opret">Breddegrad</p></label><input type="text" name="breddegrad" id="position" />
						<label for="langdegrad"><p class="opret">L&aelig;ngdegrad</p></label><input type="text" name="langdegrad" id="position" />
						<input type="submit" value="Opdater" id="opdater_knap" />
					</form>
					<div id="map_canvas">		
					</div>
					<?php
						if(isset($_POST["langdegrad"]) && isset($_POST["breddegrad"])) {
							$langdegrad = $_POST["langdegrad"];
							$breddegrad = $_POST["breddegrad"];
							$opdaterGrader = mysqli_query($db, "UPDATE skoleprojekter_rejsesiden_admin SET langdegrad='$langdegrad', breddegrad='$breddegrad'");
							if($opdaterGrader == true) {
								echo "<p class='position_opdateret'>Opdateringen er gemt<p>";
							} else {
								echo "<p class='position_opdateret'>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET["page"]=="rejseplan") {
					?>
					<h1>Rejseplan</h1>
					<?php
						$vis = mysqli_query($db, "SELECT dato, tekst FROM skoleprojekter_rejsesiden_rejseplan ORDER BY dato"); 
						while($visRejseplan = mysqli_fetch_array($vis)) {
							echo "<h2>".$visRejseplan["dato"]."</h2>";
							echo "<p class='rejseplanTekst'>".$visRejseplan["tekst"]."</p>";
						}
					?>
					<form action="" method="post" name="rejseplan" onsubmit="return valider_rejseplan();" id="rejseplan_formular">
					<p>Her kan du tilf&oslash;je noget til din rejseplan.</p>
						<label for="dato"><p class="opret">Dato<p></label><input type="text" name="dato" id="rejseplan_dato" />
						<label for="tekst"><p class="opret">Tekst</p></label><textarea name="tekst" id="rejseplan_tekst"></textarea>
						<input type="submit" value="Gem" id="opretbruger_knap" />
					</form>
					<?php
					}
					if(isset($_POST["dato"]) && isset($_POST["tekst"])) {
						$dato  = $_POST["dato"];
						$tekst = $_POST["tekst"];
						$opret = mysqli_query($db, "INSERT INTO skoleprojekter_rejsesiden_rejseplan (dato, tekst) VALUES ('$dato', '$tekst')");
						if($opret == true) {
							include("header.php");
						} else {
							echo "<p class='er_oprettet'>Der er sket en fejl</p>";
						}
					}
					if($_GET["page"]=="ret_rejseplan") {
					?>
					<h1>Ret din rejseplan</h1>
					<p>Herunder kan du v&aelig;lge det du vil rette i din rejseplan</p>
					<table>
						<tr>
							<td class="dato_celle"><p>Dato</p></td>
							<td class="tekst_celle"><p>Tekst</p></td>
							<td class="dato_celle"><p>Ret</p></td>
						</tr>
					<?php
					$ret = mysqli_query($db, "SELECT id, dato, tekst FROM skoleprojekter_rejsesiden_rejseplan ORDER BY dato");
					while($retRejseplan = mysqli_fetch_array($ret)) {
						echo "<tr><td class='celle'><p>".$retRejseplan["dato"]."</p></td>";
						echo "<td class='indlaeg_td'><p>".$retRejseplan["tekst"]."</p></td>";
						echo "<td class='celle'><a href='ret_rejseplan.php?id=$retRejseplan[id]'>Ret</a></td></tr>";
					}
					?>
					</table>
					<?php	
					}
					if($_GET["page"]=="slet_rejseplan") {
					?>
					<h1>Slet din rejseplan</h1>
					<p>Herunder kan du v&aelig;lge hvad du vil slette</p>
					<table>
						<tr>
							<td class="celle"><p>Dato</p></td>
							<td class="indlaeg_td"><p>Tekst</p></td>
							<td class="celle"><p>Slet</p></td>
						</tr>
					<?php
						$sletRejseplan = mysqli_query($db, "DELETE FROM skoleprojekter_rejsesiden_rejseplan WHERE id='$_GET[id]'");
						$vis_rejseplan = mysqli_query($db, "SELECT id, dato, tekst FROM skoleprojekter_rejsesiden_rejseplan");
						while($rejseplan_slet = mysqli_fetch_array($vis_rejseplan)) {
							echo "<tr><td class='celle'><p>".$rejseplan_slet["dato"]."</p></td>";
							echo "<td class='indlaeg_td'><p>".$rejseplan_slet["tekst"]."</p></td>";
							echo "<td class='celle'><a href='admin.php?page=slet_rejseplan&id=$rejseplan_slet[id]'>Slet</a></td>";
						}
					?>
					</table>
					<?php
					}
					if($_GET["page"]=="opretbruger") {
					?>
					<h1>Opret bruger</h1>
					<form action="" method="post" name="opret" onsubmit="return valider_opret();">
						<label for="opret_brugernavn"><p class="opret">Opret brugernavn</p></label><input type="text" name="opret_brugernavn" id="login_felt" />
						<label for="opret_password"><p class="opret">Opret password</p></label><input type="text" name="opret_password" id="login_felt" />
						<input type="submit" value="Opret" id="opretbruger_knap" />
					</form>
					<?php
						if(isset($_POST["opret_brugernavn"]) && isset($_POST["opret_password"])) {
							$opret_brugernavn = $_POST["opret_brugernavn"];
							$opret_password   = $_POST["opret_password"];
							$opretBruger      = mysqli_query($db, "INSERT INTO skoleprojekter_rejsesiden_login (brugernavn, password) VALUES ('$opret_brugernavn', '$opret_password')");
							if($opretBruger == true) {
								echo "<p class='er_oprettet'>Brugeren ".$opret_brugernavn." blev oprettet</p>";
							} else {
								echo "<p class='er_oprettet'>Brugernavnet er optaget</p>";
							}
						}
					}
					if($_GET["page"]=="sletbruger") {
					?>
					<h1>Slet bruger</h1>
					<table>
						<tr>
							<td class="slet_celle"><p>Brugernavn</p></td>
							<td class="slet_celle"><p>Password</p></td>
							<td class="slet_celle"><p>Slet</p></td>
						</tr>
					<?php
					$sletBruger = mysqli_query($db, "DELETE FROM skoleprojekter_rejsesiden_login WHERE id='$_GET[id]'");
					$visBruger = mysqli_query($db, "SELECT id, brugernavn, password FROM skoleprojekter_rejsesiden_login");
					while($bruger = mysqli_fetch_array($visBruger)) {
						echo "<tr><td class='slet_celle'><p>".$bruger["brugernavn"]."</p></td>";
						echo "<td class='slet_celle'><p>".$bruger["password"]."</p></td>";
						echo "<td class='slet_celle'><a href='admin.php?page=sletbruger&id=$bruger[id]'>Slet</a></td></tr>";
					}
					?>	
					</table>
					<?php
					}
					if($_GET["page"]=="sletindlaeg") {
					?>
					<h1>Slet indl&aelig;g fra webbloggen</h1>
					<table>
						<tr>
							<td class="celle"><p>Dato</p></td>
							<td class="indlaeg_td"><p>Indl&aelig;g</p></td>
							<td class="celle"><p>Skrevet af</p></td>
							<td class="celle"><p>Slet</p></td>
						</tr>
					<?php
						$slet_indlaeg = mysqli_query($db, "DELETE FROM skoleprojekter_rejsesiden_webblog WHERE id='$_GET[id]'");
						$vis_indlaeg  = mysqli_query($db, "SELECT id, dato, skrevet_af, indlaeg FROM skoleprojekter_rejsesiden_webblog");
						while($visIndlaeg = mysqli_fetch_array($vis_indlaeg)) {
							echo "<tr><td class='celle'><p>".$visIndlaeg["dato"]."</p></td>";
							echo "<td class='indlaeg_td'><p>".$visIndlaeg["indlaeg"]."</p></td>";
							echo "<td class='celle'><p>".$visIndlaeg["skrevet_af"]."</p></td>";
							echo "<td class='celle'><a href='admin.php?page=sletindlaeg&id=$visIndlaeg[id]'>Slet</a></td></tr>";
						}
					?>
					</table>
					<?php
					}
					if($_GET["page"]=="upload") {
					?>
					<h1>Upload et billede</h1>
					<form action="" method="post" enctype="multipart/form-data" name="upload" onsubmit="return valider_upload();">
						<label for="minfil"><p>Upload fil</p></label><input type="file" name="minfil" id="upload" />
						<label for="dato"><p>Dato</p></label><input type="text" name="dato" id="dato" />
						<label for="beskrivelse"><p>Beskrivelse</p></label><input type="text" name="beskrivelse" id="beskrivelse" />
						<input type="submit" value="upload" id="upload_knap" />
					</form>
					<?php
						if(isset($_FILES["minfil"]) && isset($_POST["beskrivelse"]) && isset($_POST["dato"])) {
							$beskrivelse = $_POST["beskrivelse"];
							$dato = $_POST["dato"];
							$destination = "billeder/".time().$_FILES["minfil"]["name"];
							$upload = copy($_FILES["minfil"]["tmp_name"], $destination);
							$upload_db = mysqli_query($db, "INSERT INTO skoleprojekter_rejsesiden_billeder (url, beskrivelse, dato) VALUES ('$destination', '$beskrivelse', '$dato')");
							if($upload == true && $upload_db == true) {
								echo "<p class='opret'>Billedet blev uploadet</p>";
							} else {
								echo "<p class='opret'>Billedet blev ikke uploadet</p>";
							}
						}
					}
					if($_GET["page"]=="slet_billede") {
					?>
					<h1>Slet et billede</h1>
					<table>
						<tr>
							<td><p>URL</p></td>
							<td class="slet_billede_beskrivelse"><p>Beskrivelse</p></td>
							<td class="slet_billede_dato"><p>Dato</p></td>
							<td class="slet_billede_slet"><p>Slet</p></td>
						</tr>
					<?php
						$sletBillede = mysqli_query($db, "DELETE FROM skoleprojekter_rejsesiden_billeder WHERE id='$_GET[id]'");
						$visBillede  = mysqli_query($db, "SELECT id, url, beskrivelse, dato FROM skoleprojekter_rejsesiden_billeder");
						while($slet_billedet = mysqli_fetch_array($visBillede)) {
							echo "<tr><td><p>".$slet_billedet["url"]."</p></td>";
							echo "<td class='slet_billede_beskrivelse'><p>".$slet_billedet["beskrivelse"]."</p></td>";
							echo "<td class='slet_billede_dato'><p>".$slet_billedet["dato"]."</p></td>";
							echo "<td class='slet_billede_slet'><a href='admin.php?page=slet_billede&id=$slet_billedet[id]'>Slet</a></td></tr>";
						}
					?>
					</table>
					<?php
					}
					?>
				</div>
			</div>
			<div id="samle_bund">
				<div id="bund1">
					<p>
					<script language="javascript">
					dkTime();
					</script>
					</p>
				</div>
				<div id="bund2">
					<p>
					<script type="text/javascript">	
					myTime();
					</script>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>