<?php
include("db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>People Pictures</title>
		<script language="javascript">
		function valider_soeg() {
			if(document.soeg_formular.soeg.value=="") {
				alert("Du skal skrive noget i s�gefeltet");
				return false;
			}
		}
		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
			</div><!-- slut top -->
			<?php
			if(isset($_POST["soeg"])) {
				$soeg     = $_POST["soeg"];
				$visKendte = mysqli_query($db, 'SELECT kendis_id, navn, st_billede FROM skoleprojekter_peoplepictures_kendis WHERE navn LIKE "%'.$_POST['soeg'].'%"');
			} else{
				$visKendte = mysqli_query($db, "SELECT kendis_id, navn, st_billede FROM skoleprojekter_peoplepictures_kendis");
			}
			$antal=mysqli_num_rows($visKendte);
			if($antal>0) {
				while($vis_kendte = mysqli_fetch_array($visKendte)) {
			?>
			<div id="profil">
				<?php echo "<a href='profil.php?page=news&id=$vis_kendte[kendis_id]'><h1>".$vis_kendte["navn"]."</h1></a>"; ?>
			</div><!-- slut profil -->
			<div id="profil_billede1">
				<?php echo "<a href='profil.php?page=news&id=$vis_kendte[kendis_id]'><img src='$vis_kendte[st_billede]' /></a>"; ?>
			</div><!-- slut profilbillede1 -->
			<div id="profil_billede2">
			</div><!-- slut profilbillede2 -->
			<div id="container">
			</div><!-- slut container -->
			<?php
				}
			} else{
				echo "<p>Ingen fundet!</p>";
			}

			?>
			<div id="search">
				<p>Search our database of Galleries:</p>
				<form action="" method="post" name="soeg_formular" onsubmit="return valider_soeg();">
					<input type="text" name="soeg" id="soeg_felt" />
					<input type="submit" value="GO!" id="soeg_knap" />
				</form>
				<p>OR</p> <a href="index.php">View our entire list</a>
				<?php
				/*
				if(isset($_POST["soeg"])) {
				$soeg     = $_POST["soeg"];
				$soegning = mysqli_query($db, 'SELECT kendis_id FROM peoplepictures_billeder WHERE kendis_id LIKE "%'.$_POST['soeg'].'%"');
				if(mysqli_num_rows($soegning)!==0) {
				while($resultat = mysqli_fetch_array($soegning)) {
				echo "<img src='".$resultat["kendis_id"]."' />";
				}
				} else {
				echo "<p>Der blev ikke fundet noget</p>";
				}
				}
				*/
				?>
			</div><!-- slut soeg -->
		</div><!-- slut wrap -->
		<div id="luft">
		</div><!-- slut luft -->
	</body>
</html>