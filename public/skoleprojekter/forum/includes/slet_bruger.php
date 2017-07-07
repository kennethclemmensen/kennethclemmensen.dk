<?php
session_start();
include("db.php");
//tjekker om det er admin der sletter
if($_SESSION['admin'] == true) {
	//finder brugeren
	$sqlBruger = mysqli_query($db, "SELECT * FROM skoleprojekter_forum_bruger WHERE bruger_id='$_GET[bruger_id]'");
	while($dataBruger = mysqli_fetch_assoc($sqlBruger)) {
		$sqlProfilbillede = mysqli_query($db, "SELECT sti FROM skoleprojekter_forum_billeder WHERE bruger='$dataBruger[email]'");
		while($dataProfilbillede = mysqli_fetch_assoc($sqlProfilbillede)) {
			//sletter profilbilledet fra serveren og databasen
			unlink("../billeder/profilbilleder/".$dataProfilbillede['sti']);
			mysqli_query($db, "DELETE FROM skoleprojekter_forum_billeder WHERE bruger='$dataBruger[email]'");
		}
		//sletter fra nyhedsbrev-tabellen
		$sletNyhedsbrev = mysqli_query($db, "DELETE FROM skoleprojekter_forum_nyhedsbrev WHERE modtager='$dataBruger[email]'");
		//sletter brugeren
		$sletBruger = mysqli_query($db, "DELETE FROM skoleprojekter_forum_bruger WHERE bruger_id='$dataBruger[bruger_id]'");
	}
	header("location: ../admin/admin.php?page=slet_bruger");
//hvis det er brugeren der sletter sig selv
} else {
	//finder brugeren
	$sqlBruger = mysqli_query($db, "SELECT * FROM skoleprojekter_forum_bruger WHERE bruger_id='$_GET[bruger_id]'");
	while($dataBruger = mysqli_fetch_assoc($sqlBruger)) {
		$sqlProfilbillede = mysqli_query($db, "SELECT sti FROM skoleprojekter_forum_billeder WHERE bruger='$dataBruger[email]'");
		while($dataProfilbillede = mysqli_fetch_assoc($sqlProfilbillede)) {
			//sletter profilbilledet fra serveren og databasen
			unlink("../billeder/profilbilleder/".$dataProfilbillede['sti']);
			mysqli_query($db, "DELETE FROM skoleprojekter_forum_billeder WHERE bruger='$dataBruger[email]'");
		}
		//sletter fra nyhedsbrev-tabellen
		$sletNyhedsbrev = mysqli_query($db, "DELETE FROM skoleprojekter_forum_nyhedsbrev WHERE modtager='$dataBruger[email]'");
		//sletter brugeren
		$sletBruger = mysqli_query($db, "DELETE FROM skoleprojekter_forum_bruger WHERE bruger_id='$dataBruger[bruger_id]'");
	}
	//�del�gger sessions
	unset($_SESSION['brugernavn']);
	unset($_SESSION['bruger']);
	unset($_SESSION['kodeord']);
	header("location: ../index.php");
}
?>