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
				$vis_kendis = mysqli_query($db, "SELECT navn, hojde, fodt, fodested FROM skoleprojekter_peoplepictures_kendis WHERE kendis_id='$_GET[id]'");
				while($vis = mysqli_fetch_array($vis_kendis)) {
				?>
				<form action="" method="post">
					<p><label for="navn">Navn</label></p><input type="text" name="navn" id="kendis_felt" value="<?php echo $vis["navn"]; ?>"/>
					<p><label for="hojde">H&oslash;jde</label></p><input type="text" name="hojde" id="kendis_felt" value="<?php echo $vis["hojde"]; ?>"/>
					<p><label for="fodt">F&oslash;dt</label></p><input type="text" name="fodt" id="kendis_felt" value="<?php echo $vis["fodt"]; ?>"/>
					<p><label for="fodested">F&oslash;dested</label></p><input type="text" name="fodested" id="kendis_felt" value="<?php echo $vis["fodested"]; ?>"/>
					<input type="submit" value="Opdater" id="login_button" />
				</form>
				<?php
					if(isset($_POST["navn"]) && isset($_POST["hojde"]) && isset($_POST["fodt"]) && isset($_POST["fodested"])) {
						$navn     = $_POST["navn"];
						$hojde    = $_POST["hojde"];
						$fodt     = $_POST["fodt"];
						$fodested = $_POST["fodested"];
						$opdater  = mysqli_query($db, "UPDATE skoleprojekter_peoplepictures_kendis SET navn='$navn', hojde='$hojde', fodt='$fodt', fodested='$fodested'");
						if($opdater == true) {
							include("ret_header.php");							
						} else {
							echo "<p>Der er sket en fejl</p>";
						}
					}
				?>
				<form action="" method="post" enctype="multipart/form-data" id="upload_formular">
					<p><label for="minfil">Upload et billede</label></p><input type="file" name="minfil" />
					<input type="submit" value="upload" id="login_button" />
				</form>
				<?php
					if(isset($_FILES["minfil"])) {
						$destination = "images/billeder/".time()."_".$_FILES["minfil"]["name"];
						$upload    = copy($_FILES["minfil"]["tmp_name"], $destination);
						$upload_db = mysqli_query($db, "UPDATE skoleprojekter_peoplepictures_kendis SET st_billede='$destination' WHERE kendis_id='$_GET[id]'");						
						if($upload == true && $upload_db == true) {
							include("ret_header.php");
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