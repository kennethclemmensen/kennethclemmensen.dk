<?php
include("db.php");
$dato = time();
$dato_aflevering = $dato+(3*24*60*60);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Computer - udl&aring;n</title>
		<script language="javascript" type="text/javascript">
		<!--
		function udlaan() {
			if(document.udlaan_computer.udlaan_maerke.value=='' || 
			   document.udlaan_computer.udlaan_model.value=='' || 
			   document.udlaan_computer.udlaan_nummer.value=='' ||
			   document.udlaan_computer.udlaan_elevnummer.value=='') {
				alert("Alle felter skal udfyldes!");
				return false;
			}
		}
		//-->
		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="menu">
				<a href="index.php?page=lister">Lister</a>
				<a href="index.php?page=ny_computer">Tilf&oslash;j en computer</a>
				<a href="index.php?page=sletcomputer">Slet en computer</a>
				<a href="index.php?page=ny_bruger">Tilf&oslash;j en bruger</a>
				<a href="index.php?page=sletbruger">Slet en bruger</a>
				<a href="index.php?page=udlaan">Udl&aring;n</a>
				<a href="index.php?page=aflevering">Aflevering</a>
			</div>
			<div id="content">
				<?php
				$vis = mysqli_query($db, "SELECT maerke, model, nummer FROM skoleprojekter_computer WHERE id='$_GET[id]'");
				while($vis_info = mysqli_fetch_array($vis)) {
				?>
				<form action="" method="post" name="udlaan_computer" onsubmit="return udlaan();">
					<p><label for="udlaan_maerke">M&aelig;rke</label></p><input type="text" name="udlaan_maerke" value="<?php echo $vis_info["maerke"]; ?>" id="felt" />
					<p><label for="udlaan_model">Model</label></p><input type="text" name="udlaan_model" value="<?php echo $vis_info["model"]; ?>" id="felt" />
					<p><label for="udlaan_nummer">Nummer</label></p><input type="text" name="udlaan_nummer" value="<?php echo $vis_info["nummer"]; ?>" id="felt" />
					<p><label for="udlaan_elevnummer">Elevnummer</label></p><input type="text" name="udlaan_elevnummer" id="felt" />
					<input type="submit" value="Udl&aring;n" id="knap" /> 
				</form>
				<?php
				}
				if(isset($_POST["udlaan_maerke"], $_POST["udlaan_model"], $_POST["udlaan_nummer"], $_POST["udlaan_elevnummer"])) {
					$udlaan_maerke = $_POST["udlaan_maerke"];
					$udlaan_model  = $_POST["udlaan_model"];
					$udlaan_nummer = $_POST["udlaan_nummer"];
					$udlaan_elevnummer = $_POST["udlaan_elevnummer"];
					$udlaan = mysqli_query($db, "UPDATE skoleprojekter_computer SET udlaan='1', udlaaneren='$udlaan_elevnummer', dato_udlaan='$dato', dato_aflevering='$dato_aflevering' WHERE id='$_GET[id]'");	
					if($udlaan == true) {
						echo "<p class='opret'>".$udlaan_maerke." ".$udlaan_model." med nummeret ".$udlaan_nummer." er nu udl&aring;nt til elev ".$udlaan_elevnummer."</p>";	
					} else {
						echo "<p class='opret'>Der er sket en fejl</p>";
					}
				}
				?>
			</div>
			<div id="bund">
				<?php 
				$bund_dato = date("j-m-Y")." ".date("H:i");
				echo "<p>".$bund_dato."</p>";
				?>
			</div>
		</div>
	</body>
</html>