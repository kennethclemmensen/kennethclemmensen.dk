<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['laerer'] == true) {
	include("db.php");
	include("class.billede.php");
	$filen = $_FILES['ret_billede'];
	$billedet = $filen['name'];	
	//tjekker om der skal uploades et nyt billede
	if($billedet != "") {
		$billede = new billede("../billeder/elever/");
		$sql_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_elever WHERE elev_id='$_SESSION[elev_id]'");
		$data_billede = mysqli_fetch_assoc($sql_billede);
		$billede->delete($data_billede['billede']);
		$billedet = $billede->setBillede($filen);
		//retter eleven og opdaterer feltet med det nye billede
		$ret_elev = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_elever SET klasse_id='$_POST[ret_klasse]', navn='$_POST[ret_navn]', brugernavn='$_POST[ret_brugernavn]', password='$_POST[ret_password]', billede='$billedet' WHERE elev_id='$_SESSION[elev_id]'") or die (mysqli_error($db));
		//hvis eleven bliver opdateret kan der godt uploades et billede
		if($ret_elev == true) {
			$upload = $billede->upload();
		}
		//tjekker om eleven bliver rettet og at der uploades et nyt billede
		if($upload == true && $ret_elev == true) {
			echo "<p>Eleven ".$_POST['ret_navn']." er rettet</p>";
		} else {
			echo "<p class='fejl'>Eleven blev ikke rettet</p>";
		}
	} else {
		$ret_elev = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_elever SET klasse_id='$_POST[ret_klasse]', navn='$_POST[ret_navn]', brugernavn='$_POST[ret_brugernavn]', password='$_POST[ret_password]' WHERE elev_id='$_SESSION[elev_id]'") or die (mysqli_error($db));
		if($ret_elev == true) {
			echo "<p>Eleven ".$_POST['ret_navn']." er rettet</p>";
		} else {
			echo "<p class='fejl'>Eleven blev ikke rettet</p>";
		}
	}
	unset($_SESSION['elev_id']);
}
?>