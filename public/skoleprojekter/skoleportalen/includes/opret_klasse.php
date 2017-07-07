<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['laerer'] == true) {
	include("db.php");
	include("class.billede.php");
	$billede = new billede("../billeder/klasser/");
	$billedet = $billede->setBillede($_FILES['billede']);
	if(substr($billedet, -3) == "jpg" || substr($billedet, -4) == "jpeg" || substr($billedet, -3) == "gif" || substr($billedet, -3) == "png") {
		$opret_klasse = mysqli_query($db, "INSERT INTO skoleprojekter_skoleportalen_klasser (skole_id, navn, beskrivelse, billede) VALUES ('$_POST[skole]', '$_POST[navn]', '$_POST[beskrivelse]', '$billedet')");
		if($opret_klasse == true) {
			$upload = $billede->upload();
		}
		if($opret_klasse == true && $upload) {
			echo "<p>Klassen ".$_POST['navn']." er oprettet</p>";
		} else {
			echo "<p class='fejl'>Klassen blev ikke oprettet</p>";
		}
	} else {
		echo "<p class='fejl'>Filformatet er ikke gyldigt. Det skal v&aelig;re jpg, gif eller png</p>";
		echo "<p class='fejl'>Husk at tjekke om der er valgt et billede</p>";
	}
}
?>