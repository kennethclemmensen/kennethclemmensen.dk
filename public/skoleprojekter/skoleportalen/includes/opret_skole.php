<?php
session_start();
//tjekker om man er logget ind
if($_SESSION['admin'] == true) {
	include("db.php");
	include("class.billede.php");
	$billede = new billede("../billeder/skoler/");
	$billedet = $billede->setBillede($_FILES['billede']);
	//tjekker om det er et billede
	if(substr($billedet, -3) == "jpg" || substr($billedet, -4) == "jpeg" || substr($billedet, -3) == "gif" || substr($billedet, -3) == "png") {
		//tjekker om det er et gyldigt telefonnummer
		if(is_numeric($_POST['telefon']) && strlen($_POST['telefon']) == 8) {
			//opretter skolen
			$opret_skole = mysqli_query($db, "INSERT INTO skoleprojekter_skoleportalen_skoler (navn, style, billede, beskrivelse, email, adresse, telefon) VALUES ('$_POST[navn]', '$_POST[style]', '$billedet', '$_POST[beskrivelse]', '$_POST[email]', '$_POST[adresse]', '$_POST[telefon]')");
			//hvis skolen bliver oprettet kan der uploades et billede
			if($opret_skole == true) {
				$upload = $billede->upload();
			}
			if($opret_skole == true && $upload == true) {
				echo "<p>Skolen ".$_POST['navn']." er oprettet</p>";
			} else {
				echo "<p class='fejl'>Skolen blev ikke oprettet</p>";
			}
		} else {
			echo "<p class='fejl'>Telefonnummeret er ikke gyldigt</p>";
		}
	} else {
		echo "<p class='fejl'>Filformatet er ikke gyldigt. Det skal v&aelig;re jpg, gif eller png</p>";
		echo "<p class='fejl'>Husk at tjekke om der er valgt et billede</p>";
	}
}
?>