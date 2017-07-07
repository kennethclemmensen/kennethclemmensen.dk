<?php
session_start();
if($_SESSION['admin'] == true) {
	include("db.php");
	include("class.billede.php");
	$billede = new billede("../billeder/laerere/");
	$billedet = $billede->setBillede($_FILES['billede']);
	if(substr($billedet, -3) == "jpg" || substr($billedet, -4) == "jpeg" || substr($billedet, -3) == "gif" || substr($billedet, -3) == "png") {
		$opret_laerer = mysqli_query($db, "INSERT INTO skoleprojekter_skoleportalen_laererer (skole_id, navn, billede, brugernavn, password) VALUES ('$_POST[skole]', '$_POST[navn]', '$billedet', '$_POST[brugernavn]', '$_POST[password]')") or die(mysqli_error($db));
		if($opret_laerer == true) {
			$upload = $billede->upload();			
		}
		if($upload == true && $opret_laerer == true) {
			echo "<p>L&aelig;reren ".$_POST['navn']." er oprettet</p>";
		} else {
			"<p class='fejl'>L&aelig;reren blev ikke oprettet</p>";
		}
	} else {
		echo "<p class='fejl'>Filformatet er ikke gyldigt. Det skal v&aelig;re jpg, gif eller png</p>";
		echo "<p class='fejl'>Husk at tjekke om der er valgt et billede</p>";
	}
}
?>