<?php
session_start();
if($_SESSION['admin'] == true) {
	include("db.php");
	include("class.billede.php");
	$filen = $_FILES['ret_billede'];
	$billedet = $filen['name'];	
	//tjekker om der skal uploades et nyt billede
	if($billedet != "") {
		$billede = new billede("../billeder/laerere/");
		//finder det gamle billede fra databasen og sletter det fra serveren
		$sql_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_laererer WHERE laerer_id='$_SESSION[laerer_id]'");
		$data_billede = mysqli_fetch_assoc($sql_billede);
		$billede->delete($data_billede['billede']);
		$billedet = $billede->setBillede($filen);
		//retter l�reren og opdaterer feltet med det nye billede
		$ret_laerer = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_laererer SET skole_id='$_POST[ret_skole]', navn='$_POST[ret_navn]', billede='$billedet', brugernavn='$_POST[ret_brugernavn]', password='$_POST[ret_password]' WHERE laerer_id='$_SESSION[laerer_id]'");
		//hvis l�reren bliver opdateret kan der godt uploades et billede
		if($ret_laerer == true) {
			$upload = $billede->upload();
		}
		//tjekker om l�reren bliver rettet og at der uploades et nyt billede
		if($upload == true && $ret_laerer == true) {
			echo "<p>L&aelig;reren ".$_POST['ret_navn']." er rettet</p>";
		} else {
			echo "<p class='fejl'>L&aelig;reren blev ikke rettet</p>";
		}
	//hvis der ikke skal uploades et nyt billede
	} else {
		$ret_laerer = mysqli_query($db, "UPDATE skoleprojekter_skoleportalen_laererer SET skole_id='$_POST[ret_skole]', navn='$_POST[ret_navn]', brugernavn='$_POST[ret_brugernavn]', password='$_POST[ret_password]' WHERE laerer_id='$_SESSION[laerer_id]'");
		if($ret_laerer == true) {
			echo "<p>L&aelig;reren ".$_POST['ret_navn']." er rettet</p>";
		} else {
			echo "<p class='fejl'>L&aelig;reren blev ikke rettet</p>";
		}
	}
	unset($_SESSION['laerer_id']);
}
?>