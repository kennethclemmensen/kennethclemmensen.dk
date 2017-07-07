<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['laerer'] == true) {
	include("db.php");
	include("class.billede.php");
	$filen = $_FILES['ret_billede'];
	$billedet = $filen['name'];	
	//tjekker om der skal uploades et nyt billede
	if($billedet != "") {
		$billede = new billede("../billeder/klasser/");
		$sql_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_klasser WHERE klasse_id='$_SESSION[klasse_id]'");
		$data_billede = mysqli_fetch_assoc($sql_billede);
		$billede->delete($data_billede['billede']);
		$billedet = $billede->setBillede($filen);
		//retter klassen og opdaterer feltet med det nye billede
		$ret_klasse = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_klasser SET skole_id='$_POST[ret_skole]', navn='$_POST[ret_navn]', beskrivelse='$_POST[ret_beskrivelse]', billede='$billedet' WHERE klasse_id='$_SESSION[klasse_id]'") or die (mysqli_error($db));
		//hvis klassen bliver opdateret kan der godt uploades et billede
		if($ret_klasse == true) {
			$upload = $billede->upload();
		}
		//tjekker om klassen bliver rettet og at der uploades et nyt billede
		if($upload == true && $ret_klasse == true) {
			echo "<p>Klassen ".$_POST['ret_navn']." er rettet</p>";
		} else {
			echo "<p class='fejl'>Klassen blev ikke rettet</p>";
		}
	} else {
		$ret_klasse = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_klasser SET skole_id='$_POST[ret_skole]', navn='$_POST[ret_navn]', beskrivelse='$_POST[ret_beskrivelse]' WHERE klasse_id='$_SESSION[klasse_id]'");
		if($ret_klasse == true) {
			echo "<p>Klassen ".$_POST['ret_navn']." er rettet</p>";
		} else {
			echo "<p class='fejl'>Klassen blev ikke rettet</p>";
		}
	}
	unset($_SESSION['laerer_id']);
}
?>