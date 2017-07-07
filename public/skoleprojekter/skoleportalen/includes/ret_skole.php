<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['laerer'] == true) {
	include("db.php");
	include("class.billede.php");
	$filen = $_FILES['ret_billede'];
	$billedet = $filen['name'];	
	//tjekker om der skal uploades et nyt billede
	if($billedet != "") {
		$billede = new billede("../billeder/skoler/");
		//finder det gamle billede fra databasen og sletter det fra serveren
		$sql_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$_SESSION[skole_id]'");
		$data_billede = mysqli_fetch_assoc($sql_billede);
		$billede->delete($data_billede['billede']);
		$billedet = $billede->setBillede($filen);
		//retter skolen og opdaterer feltet med det nye billede
		$ret_skole = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_skoler SET navn='$_POST[ret_navn]', style='$_POST[ret_style]', billede='$billedet', beskrivelse='$_POST[ret_beskrivelse]', email='$_POST[ret_email]', adresse='$_POST[ret_adresse]', telefon='$_POST[ret_telefon]' WHERE skole_id='$_SESSION[skole_id]'");
		//hvis skolen bliver opdateret kan der godt uploades et billede
		if($ret_skole == true) {
			$upload = $billede->upload();
		}
		//tjekker om skolen bliver rettet og at der uploades et nyt billede
		if($upload == true && $ret_skole == true) {
			echo "<p>Skolen ".$_POST['ret_navn']." er rettet</p>";
		} else {
			echo "<p>Skolen blev ikke rettet</p>";
		}
	//hvis der ikke skal uploades et nyt billede	
	} else {
		//retter skolen uden et nyt billede
		$ret_skole = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_skoler SET navn='$_POST[ret_navn]', style='$_POST[ret_style]', beskrivelse='$_POST[ret_beskrivelse]', email='$_POST[ret_email]', adresse='$_POST[ret_adresse]', telefon='$_POST[ret_telefon]' WHERE skole_id='$_SESSION[skole_id]'");
		//tjekker om skolen bliver rettet
		if($ret_skole == true) {
			echo "<p>Skolen ".$_POST['ret_navn']." er rettet</p>";
		} else {
			echo "<p>Skolen blev ikke rettet</p>";
		}
	}
	unset($_SESSION['skole_id']);
}
?>