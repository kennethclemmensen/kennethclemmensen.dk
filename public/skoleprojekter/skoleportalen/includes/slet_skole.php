<?php
session_start();
if($_SESSION['admin'] == true) {
	include("db.php");
	//sletter billedet af skolen
	$sql_slet_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$_GET[skole_id]'");
	$data_slet_billede = mysqli_fetch_assoc($sql_slet_billede);
	unlink("../billeder/skoler/".$data_slet_billede['billede']);
	//sletter skolen
	$slet_skole = mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$_GET[skole_id]'");
	//sletter l�rerene og billederne
	$laerer = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_laererer WHERE skole_id='$_GET[skole_id]'");
	while($data_laerer = mysqli_fetch_assoc($laerer)) {
		unlink("../billeder/laerere/".$data_laerer['billede']);
	}
	$slet_laerer = mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_laererer WHERE skole_id='$_GET[skole_id]'");
	//sletter klasserne og billederne
	$sql_klasse = mysqli_query($db, "SELECT klasse_id, billede FROM skoleprojekter_skoleportalen_klasser WHERE skole_id='$_GET[skole_id]'");
	while($data_klasse = mysqli_fetch_assoc($sql_klasse)) {
		//sletter billedet til klassen
		unlink("../billeder/klasser/".$data_klasse['billede']);
		//sletter klassen
		$slet_klasse = mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_klasser WHERE skole_id='$_GET[skole_id]'");
		//sletter eleverne og billederne fra klassen
		$sql_elev_billede = mysqli_query($db, "SELECT elev_id, billede FROM skoleprojekter_skoleportalen_elever WHERE klasse_id='$data_klasse[klasse_id]'");
		while($data_elev_billede = mysqli_fetch_assoc($sql_elev_billede)) {
			unlink("../billeder/elever/".$data_elev_billede['billede']);
			//sletter artiklerne og billederne som h�rer til
			$sql_artikel_billede = mysqli_query($db, "SELECT billede FROM skoleprojekter_skoleportalen_artikler WHERE elev_id='$data_elev_billede[elev_id]'");
			$data_artikel_billede = mysqli_fetch_assoc($sql_artikel_billede);
			unlink("../billeder/artikler/".$data_artikel_billede['billede']);
			mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_artikler WHERE elev_id='$data_elev_billede[elev_id]'");
		}
		mysqli_query($db, "DELETE FROM skoleprojekter_skoleportalen_elever WHERE klasse_id='$data_klasse[klasse_id]'");
	}
	
	header("location: ../admin/admin.php?page=rediger_skole");
}
?>