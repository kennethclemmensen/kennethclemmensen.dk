<?php
session_start();
if($_SESSION['admin'] == true || $_SESSION['elev'] == true) {
	include("db.php");
	include("class.billede.php");
	$filen = $_FILES['ret_billede'];
	$billedet = $filen['name'];	
	//tjekker om der skal uploades et nyt billede
	if($billedet != "") {
		$billede = new billede("../billeder/artikler/");
		$sql_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_artikler WHERE artikel_id='$_SESSION[artikel_id]'");
		$data_billede = mysqli_fetch_assoc($sql_billede);
		$billede->delete($data_billede['billede']);
		$billedet = $billede->setBillede($filen);
		//tjekker om eleven er logget ind
		if($_SESSION['elev'] == true) {
			//retter artikel og opdaterer feltet med det nye billede
			$ret_artikel = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_artikler SET elev_id='$_SESSION[elev_id]', overskrift='$_POST[ret_overskrift]', tekst='$_POST[ret_tekst]', billede='$billedet', billede_titel='$_POST[ret_titel]' WHERE artikel_id='$_SESSION[artikel_id]'") or die (mysqli_error($db));
		//hvis admin er logget ind
		} else {
			//retter artikel og opdaterer feltet med det nye billede
			$ret_artikel = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_artikler SET elev_id='0', overskrift='$_POST[ret_overskrift]', tekst='$_POST[ret_tekst]', billede='$billedet', billede_titel='$_POST[ret_titel]' WHERE artikel_id='$_SESSION[artikel_id]'") or die (mysqli_error($db));
		}
		//hvis artiklen bliver opdateret kan der godt uploades et billede
		if($ret_artikel == true) {
			$upload = $billede->upload();
		}
		if($upload == true && $ret_artikel == true) {
			echo "<p>Artiklen ".$_POST['ret_overskrift']." er rettet</p>";
		} else {
			echo "<p class='fejl'>Artiklen blev ikke rettet</p>";
		}
	} else {
		//hvis eleven er logget ind
		if($_SESSION['elev'] == true) {
			$ret_artikel = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_artikler SET elev_id='$_SESSION[elev_id]', overskrift='$_POST[ret_overskrift]', tekst='$_POST[ret_tekst]', billede_titel='$_POST[ret_titel]' WHERE artikel_id='$_SESSION[artikel_id]'") or die (mysqli_error($db));
		//hvis admin er logget ind
		} else {
			$ret_artikel = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_artikler SET elev_id='0', overskrift='$_POST[ret_overskrift]', tekst='$_POST[ret_tekst]', billede_titel='$_POST[ret_titel]' WHERE artikel_id='$_SESSION[artikel_id]'") or die (mysqli_error($db));
		}
		if($ret_artikel == true) {
			echo "<p>Artiklen ".$_POST['ret_overskrift']." er rettet</p>";
		} else {
			echo "<p class='fejl'>Artiklen blev ikke rettet</p>";
		}
	}
	unset($_SESSION['artikel_id']);
}
?>