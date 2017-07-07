<?php
session_start();
if($_SESSION["bruger"]!==true) {
	header("location: index.php");
}
if(isset($_GET["page"])) {
	$_GET["page"];
} else {
	$_GET["page"] = "forside";
}
include("db.php");
include("dktime.php");
include("mytime.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Rejsesiden</title>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
			</div>
			<div id="samle">
				<div id="menu">
					<ul>
						<li><a href="bruger.php?page=forside">Forside</a></li>
						<li><a href="bruger.php?page=webblog">Webblog</a></li>
						<li><a href="logaf.php">Log af</a></li>
					</ul>
				</div>
				<div id="content">
					<?php
					if($_GET["page"]=="forside") {
					?>
					<h1>Velkommen <?php echo $_SESSION["brugernavn"]; ?></h1>
					<p>Hvis du klikker p� linket Webblog kan du skrive i webbloggen</p>
					<?php
					}
					if($_GET["page"]=="webblog") {
					?>
					<h1>Webblog</h1>
					<?php
						$visWebblog = mysqli_query($db, "SELECT dato, skrevet_af, indlaeg FROM skoleprojekter_rejsesiden_webblog ORDER BY dato DESC");
						while($webblog = mysqli_fetch_array($visWebblog)) {
							echo "<p class='skrevet_af'>".$webblog["skrevet_af"]."</p>";
							echo "<p class='dato'>".$webblog["dato"]."</p>";
							echo "<p class='indlaeg'>".$webblog["indlaeg"]."</p>";
						}
					?>
					<form action="" method="post">
						<input type="text" name="webblog" id="webblog" />
						<input type="submit" value="Send" id="webblog_knap" />
					</form>
					<?php
						if(isset($_POST["webblog"])) {
							$dato         = date("j-m-y")." ".date("H:i");
							$webblogTekst = $_POST["webblog"];
							$gemTekst     = mysqli_query($db, "INSERT INTO skoleprojekter_rejsesiden_webblog (dato, skrevet_af, indlaeg) VALUES ('$dato', '$_SESSION[brugernavn]', '$webblogTekst')");
							if($gemTekst == true) {
								include("webblog_header.php");
							} else {
								echo "<p class='opret'>Dit indl&aelig;g blev ikke gemt</p>";
							}
						}
					}
					?>
				</div>
			</div>
			<div id="samle_bund">
				<div id="bund1">
					<p>
					<script language="javascript">
					dkTime();
					</script>
					</p>
				</div>
				<div id="bund2">
					<p>
					<script type="text/javascript">	
					myTime();
					</script>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>