<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['laerer'] == true) {
	include("db.php");
	include("class.billede.php");
	$billede = new billede("../billeder/elever/");
	$billedet = $billede->setBillede($_FILES['billede']);
	if(substr($billedet, -3) == "jpg" || substr($billedet, -4) == "jpeg" || substr($billedet, -3) == "gif" || substr($billedet, -3) == "png") {
		$opret_elev = mysqli_query($db, "INSERT INTO skoleprojekter_skoleportalen_elever (klasse_id, navn, brugernavn, password, billede) VALUES ('$_POST[klasse]', '$_POST[navn]', '$_POST[brugernavn]', '$_POST[password]', '$billedet')");
		if($opret_elev == true) {
			$upload = $billede->upload();
		}
		if($opret_elev == true && $upload == true) {
			echo "<p>Eleven ".$_POST['navn']." er oprettet</p>";
		} else {
			echo "<p class='fejl'>Eleven blev ikke oprettet</p>";
		}
	} else {
		echo "<p class='fejl'>Filformatet er ikke gyldigt. Det skal v&aelig;re jpg, gif eller png</p>";
		echo "<p class='fejl'>Husk at tjekke om der er valgt et billede</p>";
	}
}
?>