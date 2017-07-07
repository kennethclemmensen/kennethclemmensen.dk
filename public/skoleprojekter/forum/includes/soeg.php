<?php
include("db.php");
if($_POST['soeg_kategori'] == "bruger") {
	$sql = mysqli_query($db, "SELECT email, navn FROM skoleprojekter_forum_bruger WHERE email LIKE '%".$_POST['soeg']."%' OR navn LIKE '%".$_POST['soeg']."%'");
	if(mysqli_num_rows($sql) != 0) {
		while($data = mysqli_fetch_assoc($sql)) {
			echo "<p>".$data['email']." | ".$data['navn']."</p>";
		}
	} else {
		echo "<p>Ingen resultater fundet</p>";
	}
}
if($_POST['soeg_kategori'] == "indlaeg") {
	$sql = mysqli_query($db, "SELECT indlaeg_id, overskrift, tekst FROM skoleprojekter_forum_indlaeg WHERE overskrift LIKE '%".$_POST['soeg']."%' OR tekst LIKE '%".$_POST['soeg']."%'");
	if(mysqli_num_rows($sql) != 0) {
		while($data = mysqli_fetch_assoc($sql)) {
			echo "<h2>".$data['overskrift']."</h2>";
			echo "<p>".substr($data['tekst'], 0, 50)."... <a href='index.php?page=indlaegget&amp;indlaeg_id=$data[indlaeg_id]'>L&aelig;s mere</a></p>";
		}
	} else {
		echo "<p>Ingen resultater fundet</p>";
	}
}
?>