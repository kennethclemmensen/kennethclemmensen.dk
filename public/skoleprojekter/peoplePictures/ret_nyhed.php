<?php
session_start();
include("db.php");
if($_SESSION["admin"]!==true) {
	header("location: admin_login.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" href="style/admin_style.css" media="screen" />
		<title>People Pictures</title>
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
				$visNyhed = mysqli_query($db, "SELECT overskrift, tekst, dato FROM skoleprojekter_peoplepictures_nyheder WHERE id='$_GET[id]'");
				while($vis_nyhed = mysqli_fetch_array($visNyhed)) {
				?>
				<form action="" method="post">
					<p><label for="navn">Overskrift</label></p><input type="text" name="overskrift" id="kendis_felt" value="<?php echo $vis_nyhed["overskrift"]; ?>"/>
					<p><label for="tekst">Tekst</label></p><input type="text" name="tekst" id="kendis_biografi" value="<?php echo $vis_nyhed["tekst"]; ?>"/>
					<p><label for="dato">Dato</label></p><input type="text" name="dato" id="kendis_felt" value="<?php echo $vis_nyhed["dato"]; ?>"/>
					<select name="kendis_id" id="rullemenu">
						<?php
						$vis_kendis_id = mysqli_query($db, "SELECT kendis_id, navn FROM skoleprojekter_peoplepictures_kendis");
						while($visKendisId = mysqli_fetch_array($vis_kendis_id)) {
							echo "<option value='".$visKendisId["kendis_id"]."'>".$visKendisId["navn"]."</option>";
						}
						?>
					</select>
					<input type="submit" value="Opdater" id="login_button" />
				</form>
				<?php
					if(isset($_POST["overskrift"]) && isset($_POST["tekst"]) && isset($_POST["dato"]) && isset($_POST["kendis_id"])) {
						$overskrift   = $_POST["overskrift"];
						$tekst        = $_POST["tekst"];
						$dato         = $_POST["dato"];
						$kendis_id    = $_POST["kendis_id"];
						$opdaterNyhed = mysqli_query($db, "UPDATE skoleprojekter_peoplepictures_nyheder SET overskrift='$overskrift', tekst='$tekst', dato='$dato', kendis_id='$kendis_id' WHERE id='$_GET[id]'");
						if($opdaterNyhed == true) {
							include("nyhed_header.php");							
						} else {
							echo "<p>Der er sket en fejl</p>";
						}
					}
				}
				?>
				</div>
			</div>
			<div id="bund">
			</div>
		</div>
	</body>
</html>