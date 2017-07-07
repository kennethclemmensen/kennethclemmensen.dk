<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['elev'] == true) {
	include("db.php");
	include("class.billede.php");
	$billede = new billede("../billeder/artikler/");
	$billedet = $billede->setBillede($_FILES['billede']);
	//tjekker om der er valgt et billede
	if(substr($billedet, -3) == "jpg" || substr($billedet, -4) == "jpeg" || substr($billedet, -3) == "gif" || substr($billedet, -3) == "png") {
		//hvis man er logget ind som elev
		if($_SESSION['elev'] == true) {
			$opret_artikel = mysqli_query($db, "INSERT INTO skoleprojekter_skoleportalen_artikler (elev_id, overskrift, dato, tekst, billede, billede_titel) VALUES ('$_SESSION[elev_id]', '$_POST[overskrift]', '".time()."', '$_POST[tekst]', '$billedet', '$_POST[titel]')") or die (mysqli_error($db));
		//hvis man er logget ind som admin	
		} else {
			$opret_artikel = mysqli_query($db, "INSERT INTO skoleprojekter_skoleportalen_artikler (elev_id, overskrift, dato, tekst, billede, billede_titel) VALUES ('0', '$_POST[overskrift]', '".time()."', '$_POST[tekst]', '$billedet', '$_POST[titel]')") or die (mysqli_error($db));
		}
		if($opret_artikel == true) {
			$upload = $billede->upload();
		}
		if($opret_artikel == true && $upload == true) {
			echo "<p>Artiklen ".$_POST['overskrift']." er oprettet</p>";
		} else {
			echo "<p class='fejl'>Artiklen blev ikke oprettet</p>";
		}
	} else {
		echo "<p class='fejl'>Filformatet er ikke gyldigt. Det skal v&aelig;re jpg, gif eller png</p>";
		echo "<p class='fejl'>Husk at tjekke om der er valgt et billede</p>";
	}
}
?>