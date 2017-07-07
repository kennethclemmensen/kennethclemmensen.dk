<?php
session_start();
include("db.php");
if($_SESSION["admin"]!==true) {
	header("location: admin_login.php");
}
if(isset($_GET["page"])) {
	$_GET["page"];
} else {
	$_GET["page"] = "forside";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/admin_style.css" media="screen" />
		<title>People Pictures</title>
		<script language="javascript">
		function valider_kendis() {
			if(document.kendis_formular.navn.value=="" ||
			document.kendis_formular.biografi.value=="" ||
			document.kendis_formular.hojde.value=="" ||
			document.kendis_formular.fodt.value=="" ||
			document.kendis_formular.fodested.value=="") {
				alert("Alle felter skal udfyldes");
				return false;
			}
		}
		function nyhed_valider() {
			if(document.nyhed_formular.nyhed_overskrift.value=="" ||
			document.nyhed_formular.nyhed_tekst.value=="" ||
			document.nyhed_formular.kendis_id.value=="") {
				alert("Alle felterne skal udfyldes for at skrive en nyhed");
				return false;
			}
		}
		function valider_upload() {
			if(document.upload.minfil.value=="" ||
			document.upload.kendis_id.value=="") {
				alert("Du skal v�lge et billede");
				return false;
			}
		}
		function valider_film() {
			if(document.film_formular.film.value=="" ||
			document.film_formular.navn.value=="" ||
			document.film_formular.embed.value=="") {
				alert("Alle felter skal udfyldes");
				return false;
			}
		}
		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
			</div>
			<div id="samle">
				<div id="menu">
					<ul>
						<li><a href="admin.php?page=forside">Forside</a></li>
						<li><a href="admin.php?page=opretKendis">Opret Kendis</a></li>
						<li><a href="admin.php?page=retKendis">Ret Kendis</a></li>
						<li><a href="admin.php?page=sletKendis">Slet Kendis</a></li>
						<li><a href="admin.php?page=opretNyhed">Opret nyhed</a></li>
						<li><a href="admin.php?page=redigerNyhed">Rediger nyhed</a></li>
						<li><a href="admin.php?page=sletNyhed">Slet nyhed</a></li>
						<li><a href="admin.php?page=uploadBillede">Upload billeder</a></li>
						<li><a href="admin.php?page=sletBillede">Slet billeder</a></li>
						<li><a href="admin.php?page=uploadFilm">Upload film</a></li>
						<li><a href="admin.php?page=sletFilm">Slet film</a></li>
						<li><a href="logaf.php">Log af</a></li>
					</ul>
				</div>
				<div id="content">
					<?php
					if($_GET["page"]=="forside") {
					?>
					<h1>Velkommen</h1>					
					<?php
					}
					if($_GET["page"]=="opretKendis") {
					?>
					<h1>Opret en kendis</h1>
					<form action="" method="post" name="kendis_formular" onsubmit="return valider_kendis();">
						<p><label for="navn">Navn</label></p><input type="text" name="navn" id="kendis_felt" />
						<p><label for="hojde">H&oslash;jde</label></p><input type="text" name="hojde" id="kendis_felt" />
						<p><label for="fodt">F&oslash;dt</label></p><input type="text" name="fodt" id="kendis_felt" />
						<p><label for="fodested">F&oslash;dested</label></p><input type="text" name="fodested" id="kendis_felt" />
						<input type="submit" value="Opret" id="login_button" />
					</form>
					<?php
					if(isset($_POST["navn"]) && isset($_POST["hojde"]) && isset($_POST["fodt"]) && isset($_POST["fodested"])) {
						$navn        = $_POST["navn"];
						$hojde       = $_POST["hojde"];
						$fodt        = $_POST["fodt"];
						$fodested    = $_POST["fodested"];
						$opretKendis = mysqli_query($db, "INSERT INTO skoleprojekter_peoplepictures_kendis (navn, hojde, fodt, fodested) VALUES ('$navn', '$hojde', '$fodt', '$fodested')");
						if($opretKendis == true) {
							echo "<p>".$navn." er oprettet i databasen</p>";
						} else {
							echo "<p>Der er sket en fejl</p>";
						}
					}
					}
					if($_GET["page"]=="retKendis") {
					?>
					<h1>Ret en kendis</h1>
					<table>
						<tr>
							<td class="kendis_celle"><p>Navn</p></td>
							<td class="kendis_celle"><p>H&oslash;jde</p></td>
							<td class="kendis_celle"><p>F&oslash;dt</p></td>
							<td class="kendis_celle"><p>F&oslash;dested</p></td>
							<td class="kendis_celle"><p>Ret</p></td>
						</tr>
					<?php
					$retKendis = mysqli_query($db, "SELECT kendis_id, navn, hojde, fodt, fodested FROM skoleprojekter_peoplepictures_kendis ORDER BY navn");
					while($ret_kendis = mysqli_fetch_array($retKendis)) {
						echo "<tr><td class='kendis_celle'><p>".$ret_kendis["navn"]."</p></td>";
						echo "<td class='kendis_celle'><p>".$ret_kendis["hojde"]."</p></td>";
						echo "<td class='kendis_celle'><p>".$ret_kendis["fodt"]."</p></td>";
						echo "<td class='kendis_celle'><p>".$ret_kendis["fodested"]."</p></td>";
						echo "<td class='kendis_celle'><a href='ret_kendis.php?id=$ret_kendis[kendis_id]'>Ret</a></td></tr>";
					}
					?>
					</table>
					<?php
					}
					if($_GET["page"]=="sletKendis") {
					?>
					<h1>Slet en kendis</h1>
					<table>
						<tr>
							<td class="kendis_celle"><p>Navn</p></td>
							<td class="kendis_celle"><p>F&oslash;dt</p></td>
							<td class="kendis_celle"><p>F&oslash;dested</p></td>
							<td class="kendis_celle"><p>Slet</p></td>
						</tr>
					<?php
					$sletKendis = mysqli_query($db, "DELETE FROM skoleprojekter_peoplepictures_kendis WHERE kendis_id='$_GET[id]'");
					$visKendis  = mysqli_query($db, "SELECT kendis_id, navn, fodt, fodested FROM skoleprojekter_peoplepictures_kendis ORDER BY navn");
					while($kendis = mysqli_fetch_array($visKendis)) {
						echo "<tr><td class='kendis_celle'><p>".$kendis["navn"]."<p></td>";
						echo "<td class='kendis_celle'><p>".$kendis["fodt"]."<p></td>";
						echo "<td class='kendis_celle'><p>".$kendis["fodested"]."<p></td>";
						echo "<td class='kendis_celle'><a href='admin.php?page=sletKendis&id=$kendis[kendis_id]'>Slet</a></td></tr>";
					}
					?>
					</table>
					<?php
					}
					if($_GET["page"]=="opretNyhed") {
					?>
					<h1>Opret en nyhed</h1>
					<form action="" method="post" name="nyhed_formular" onsubmit="return nyhed_valider();">
						<p><label for="overskrift">Overskrift</label></p><input type="text" name="overskrift" id="nyhed_overskrift" />
						<p><label for="tekst">Tekst</label></p><textarea name="tekst" id="nyhed_tekst"></textarea>
						<select name="kendis_id" id="rullemenu">
							<?php
							$vis_kendis_id = mysqli_query($db, "SELECT kendis_id, navn FROM skoleprojekter_peoplepictures_kendis");
							while($visKendisId = mysqli_fetch_array($vis_kendis_id)) {
								echo "<option value='".$visKendisId["kendis_id"]."'>".$visKendisId["navn"]."</option>";
							}
							?>
						</select>
						<input type="submit" value="Gem" id="login_button" />
					</form>
					<?php
					if(isset($_POST["overskrift"]) && isset($_POST["tekst"]) && isset($_POST["kendis_id"])) {
						$overskrift = $_POST["overskrift"];
						$tekst      = $_POST["tekst"];
						$dato       = date("j-m-Y");
						$kendis_id  = $_POST["kendis_id"];
						$ny_nyhed   = mysqli_query($db, "INSERT INTO skoleprojekter_peoplepictures_nyheder (overskrift, tekst, dato, kendis_id) VALUES ('$overskrift', '$tekst', '$dato', '$kendis_id')") or die(mysqli_error($db));
						if($ny_nyhed == true) {
							echo "<p>Nyheden er oprettet og gemt i databasen</p>";
						} else {
							echo "<p>Der er sket en fejl</p>";
						}
					}
					}
					if($_GET["page"]=="redigerNyhed") {
					?>
					<h1>Rediger en nyhed</h1>
					<table>
						<tr>
							<td class="kendis_celle"><p>Overskrift</p></td>
							<td class="kendis_celle"><p>Tekst</p></td>
							<td class="kendis_celle"><p>Dato</p></td>
							<td class="kendis_celle"><p>Ret</p></td>
						</tr>
					<?php
					$ret_nyhed = mysqli_query($db, "SELECT id, overskrift, tekst, dato FROM skoleprojekter_peoplepictures_nyheder ORDER BY dato DESC");
					while($retNyhed = mysqli_fetch_array($ret_nyhed)) {
						echo "<tr><td class='kendis_celle'><p>".$retNyhed["overskrift"]."</p></td>";
						echo "<td class='kendis_celle'><p>".$retNyhed["tekst"]."</p></td>";
						echo "<td class='kendis_celle'><p>".$retNyhed["dato"]."</p></td>";
						echo "<td class='kendis_celle'><a href='ret_nyhed.php?id=$retNyhed[id]'>Ret</a></td></tr>";
					}
					?>
					</table>
					<?php
					}
					if($_GET["page"]=="sletNyhed") {
					?>
					<h1>Slet en nyhed</h1>
					<table>
						<tr>
							<td class="nyhed_overskrift"><p>Overskrift</p></td>
							<td class="nyhed_tekst"><p>Nyhed</p></td>
							<td class="nyhed_dato"><p>Dato</p></td>
							<td><p>Slet</p></td>
						</tr>
					<?php
					$sletNyhed = mysqli_query($db, "DELETE FROM skoleprojekter_peoplepictures_nyheder WHERE id='$_GET[id]'");
					$visNyhed  = mysqli_query($db, "SELECT id, overskrift, tekst, dato FROM skoleprojekter_peoplepictures_nyheder ORDER BY dato DESC");
					while($nyhed = mysqli_fetch_array($visNyhed)) {
						echo "<tr><td><p>".$nyhed["overskrift"]."</p></td>";
						echo "<td><p>".$nyhed["tekst"]."</p></td>";
						echo "<td><p>".$nyhed["dato"]."</p></td>";
						echo "<td><a href='admin.php?page=sletNyhed&id=$nyhed[id]'>Slet</a></td></tr>";
					}
					?>
					</table>					
					<?php
					}
					if($_GET["page"]=="uploadBillede") {
					?>
					<h1>Upload billeder</h1>
					<form action="" method="post" enctype="multipart/form-data" name="upload" onsubmit="return valider_upload();">
						<input type="file" name="minfil" />
						<input type="submit" value="upload" id="login_button" />
						<select name="kendis_id" id="rullemenu">
							<?php
							$vis_kendis_id = mysqli_query($db, "SELECT kendis_id, navn FROM skoleprojekter_peoplepictures_kendis");
							while($visKendisId = mysqli_fetch_array($vis_kendis_id)) {
								echo "<option value='".$visKendisId["kendis_id"]."'>".$visKendisId["navn"]."</option>";
							}
							?>
						</select>
					</form>
					<?php
					if(isset($_FILES["minfil"]) && isset($_POST["kendis_id"])) {
						/*$destination = "images/billeder/".time()."_".$_FILES["minfil"]["name"];
						$upload      = copy($_FILES["minfil"]["tmp_name"], $destination);
						*/
						include('class.billede.php');
						$mitBillede = new billede("images/billeder/");

						//echo $mitBillede->form();
						$mitBillede->setBillede($_FILES['minfil']);
						$nytBillede = $mitBillede->upload();

						if($nytBillede != ""){
							$mitBillede->lavProportionalThumb($nytBillede,120,120);
							//$mitBillede->lavThumb($nytBillede,120,120);
							echo $mitBillede->visBillede("thumb_".$nytBillede);
						}
						$kendis_id   = $_POST["kendis_id"];
						$upload_db   = mysqli_query($db, "INSERT INTO skoleprojekter_peoplepictures_billeder (url, kendis_id) VALUES ('$nytBillede', '$kendis_id')");
						if($nytBillede && $upload_db == true) {
							echo "<p>Filen blev uploadet</p>";
						} else {
							echo "<p>Der er sket en fejl</p>";
						}
					}
					}
					if($_GET["page"]=="sletBillede") {
					?>
					<h1>Slet billeder</h1>
					<table>
						<tr>
							<td><p>Billedenavn</p></td>
							<td class="slet_celle"><p>Slet</p></td>
						</tr>
					<?php
					$sletBillede = mysqli_query($db, "DELETE FROM skoleprojekter_peoplepictures_billeder WHERE id='$_GET[id]'");
					$vis_billede = mysqli_query($db, "SELECT id, url FROM skoleprojekter_peoplepictures_billeder");
					while($visBillede = mysqli_fetch_array($vis_billede)) {
						echo "<tr><td><p>".$visBillede["url"]."</p></td>";
						echo "<td class='slet_celle'><a href='admin.php?page=sletBillede&id=$visBillede[id]'>Slet</a></td></tr>";
					}
					?>	
					</table>
					<?php
					}
					if($_GET["page"]=="uploadFilm") {
					?>
					<h1>Upload film</h1>
					<form action="" method="post" name="film_formular" onsubmit="return valider_film();">
						<p><label for="embed">Embedkoden fra youtube</label></p><input type="text" name="embed" id="kendis_felt" />
						<p><label for="navn">Navn</label></p><input type="text" name="navn" id="kendis_felt" />
						<select name="film" id="rullemenu">
							<?php
							$vis_kendis_id = mysqli_query($db, "SELECT kendis_id, navn FROM skoleprojekter_peoplepictures_kendis");
							while($visKendisId = mysqli_fetch_array($vis_kendis_id)) {
								echo "<option value='".$visKendisId["kendis_id"]."'>".$visKendisId["navn"]."</option>";
							}
							?>							
						</select>
						<input type="submit" value="Opret" id="login_button" />
					</form>
					<?php
						if(isset($_POST["embed"]) && isset($_POST["film"])) {
							$embed  = $_POST["embed"];
							$navn   = $_POST["navn"];
							$film   = $_POST["film"];
							$nyFilm = mysqli_query($db, "INSERT INTO skoleprojekter_peoplepictures_film (url, navn, kendis_id) VALUES ('$embed', '$navn', '$film')");
							if($nyFilm == true) {
								echo "<p>Filmen blev uploadet</p>";
							} else {
								echo "<p>Der er sket en fejl</p>";
							}
						}
					}
					if($_GET["page"]=="sletFilm") {
					?>
					<h1>Slet film</h1>
					<table>
						<tr>
							<td><p>Film</p></td>
							<td class="navn_celle"><p>Navn</p></td>
							<td><p>Slet</p></td>
						</tr>
						<?php
						$sletFilm = mysqli_query($db, "DELETE FROM skoleprojekter_peoplepictures_film WHERE id='$_GET[id]'");
						$vis_film = mysqli_query($db, "SELECT id, url, navn FROM skoleprojekter_peoplepictures_film");
						while($visFilm = mysqli_fetch_array($vis_film)) {
							echo "<tr><td>".$visFilm["url"]."</p></td>";
							echo "<td class='navn_celle'><p>".$visFilm["navn"]."</p></td>";
							echo "<td><a href='admin.php?page=sletFilm&id=$visFilm[id]'>Slet</a></td></tr>";
						}
						?>
					</table>
					<?php
					}
					?>
				</div>
			</div>
			<div id="bund">
			</div>
		</div>
	</body>
</html>