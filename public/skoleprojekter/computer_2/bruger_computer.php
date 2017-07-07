<?php
if(isset($_GET["page"])) {
	$_GET["page"];
} else {
	$_GET["page"]=="forside";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="Denne side handler om navigationsdesign" />
		<meta name="keywords" content="navigation, design, navigationsdesign, s�geoptimering" />
		<meta name="robots" content="index" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Computerudl&aring;n - stort udvalg af computere til udl&aring;n</title>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
				<object width="800" height="150">
					<param name="movie" value="top.swf">
					<param name="autostart" value="true">
					<embed src="banner/top.swf" width="800" height="150">
					</embed>
				</object>
			</div>
			<div id="links">
				<a href="computer.php">Tilbage</a><a href="index.php">Tilbage til forsiden</a>
			</div>
			<div id="content">
				<?php
				if($_GET["page"]=="opret_computer") {
				?>
				<h1>Opret en computer</h1>
				<form action="" method="post">
					<p>Brugernavn</p><input type="text" name="" id="felt" />
					<p>Password</p><input type="password" name="" id="felt" />
					<input type="submit" value="Opret" id="knap" />
				</form>
				<?php
				}
				if($_GET["page"]=="slet_computer") {
				?>
				<h1>Slet en computer</h1>
				<form action="" method="post">
					<p>Brugernavn</p><input type="text" name="" id="felt" />
					<p>Password</p><input type="password" name="" id="felt" />
					<input type="submit" value="Opret" id="knap" />
				</form>
				<?php
				}
				if($_GET["page"]=="ret_computer") {
				?>
				<h1>Ret en computer</h1>
				<form action="" method="post">
					<p>Brugernavn</p><input type="text" name="" id="felt" />
					<p>Password</p><input type="password" name="" id="felt" />
					<input type="submit" value="Opret" id="knap" />
				</form>
				<?php
				}
				if($_GET["page"]=="opret_bruger") {
				?>
				<h1>Opret en bruger</h1>
				<form action="" method="post">
					<p>Brugernavn</p><input type="text" name="" id="felt" />
					<p>Password</p><input type="password" name="" id="felt" />
					<input type="submit" value="Opret" id="knap" />
				</form>
				<?php
				}
				if($_GET["page"]=="slet_bruger") {
				?>
				<h1>Slet en bruger</h1>
				<form action="" method="post">
					<p>Brugernavn</p><input type="text" name="" id="felt" />
					<p>Password</p><input type="password" name="" id="felt" />
					<input type="submit" value="Opret" id="knap" />
				</form>
				<?php
				}
				if($_GET["page"]=="ret_bruger") {
				?>
				<h1>Ret en bruger</h1>
				<form action="" method="post">
					<p>Brugernavn</p><input type="text" name="" id="felt" />
					<p>Password</p><input type="password" name="" id="felt" />
					<input type="submit" value="Opret" id="knap" />
				</form>
				<?php	
				}
				if($_GET["page"]=="lister") {
				?>	
				<h1>Lister</h1>
				<?php
				}
				?>
			</div>
		</div>
	</body>
</html>