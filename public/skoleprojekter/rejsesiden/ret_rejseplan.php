<?php
session_start();
if($_SESSION["admin"]!==true) {
	header("location: admin_login.php");
}
include("db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Rejsesiden</title>
		<script language="javascript">
		function valider_rejseplan() {
			if(document.rejseplan.dato.value=="" ||
			document.rejseplan.tekst.value=="") {
				alert("Du skal udfylde begge felter!");
				return false;
			}
		}
		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
			</div>
			<div id="samle">
				<div id="menu">
					<ul>
						<li><a href="admin.php?page=forside">Forside</a></li>
						<li><a href="admin.php?page=lokaltid">Indstil tid</a></li>
						<li><a href="admin.php?page=rejseplan">Rejseplan</a></li>
						<li><a href="admin.php?page=ret_rejseplan">Ret din rejseplan</a></li>
						<li><a href="admin.php?page=slet_rejseplan">Slet din rejseplan</a></li>
						<li><a href="admin.php?page=opretbruger">Opret bruger</a></li>
						<li><a href="admin.php?page=sletbruger">Slet bruger</a></li>
						<li><a href="admin.php?page=sletindlaeg">Slet indl&aelig;g</a></li>
						<li><a href="logaf_admin.php">Log af</a></li>
					</ul>
				</div>
				<div id="content">
					<h1>Ret din rejseplan</h1>
					<?php
					$vis = mysqli_query($db, "SELECT id, dato, tekst FROM skoleprojekter_rejsesiden_rejseplan WHERE id='$_GET[id]'");
					while($ret = mysqli_fetch_array($vis)) {
					?>
					<form action="" method="post" name="rejseplan" onsubmit="return valider_rejseplan();">
						<label for="dato"><p class="opret">Dato<p></label><input type="text" name="dato" id="rejseplan_dato" value="<?php echo $ret["dato"]; ?>" />
						<label for="tekst"><p class="opret">Tekst</p></label><input type="text" name="tekst" id="rejseplan_tekst" value="<?php echo $ret["tekst"]; ?>" />
						<input type="submit" value="Ret" id="opretbruger_knap" />
					</form>
					<?php
					}
					if(isset($_POST["dato"]) && isset($_POST["tekst"])) {
						$dato    = $_POST["dato"];
						$tekst   = $_POST["tekst"];
						$opdater = mysqli_query($db, "UPDATE skoleprojekter_rejsesiden_rejseplan SET dato='$dato', tekst='$tekst' WHERE id='$_GET[id]'");
						if($opdater == true) {
							include("header.php");
						} else {
							echo "<p class='opret'>Der er sket en fejl</p>";
						}
					}
					?>
				</div>
			</div>
			<div id="bund">
			</div>
		</div>
	</body>
</html>