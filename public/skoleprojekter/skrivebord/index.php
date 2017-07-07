<?php
include("includes/db.php");	
if(isset($_POST['note']) && $_POST['note'] != "") {
	$dato = date("j-m-Y")." ".date("H:i");
	$note = $_POST['note'];
	$sql_note = "INSERT INTO skoleprojekter_skrivebord_noter (dato, note) VALUES ('$dato', '$note')";
	$resultat_note = mysqli_query($db, $sql_note);
}
if(isset($_POST['ret_note']) && $_POST['ret_note'] != "") {
	$dato = date("j-m-Y")." ".date("H:i");
	$note = $_POST['ret_note'];
	$sql_note = "UPDATE skoleprojekter_skrivebord_noter SET dato='$dato', note='$note' WHERE note_id=''";
	$resultat_note = mysqli_query($db, $sql_note);
}
$slet_sql = "DELETE FROM skoleprojekter_skrivebord_noter WHERE note_id='$_GET[note_id]'";
$slet_resultat = mysqli_query($db, $slet_sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen" />
		<title>Windows Vista - Crap Edition</title>
		<script type="text/javascript" src="js/kalender.js"></script>
		<script type="text/javascript" src="js/byrei-dyndiv_1.0rc1-src.js"></script>
		<script type="text/javascript" src="js/byrei-dyndiv_1.0rc1.js"></script>
	 	<script type="text/javascript">
		function ur() {
			var dato = new Date();
			var timer = dato.getHours();
			var minutter = dato.getMinutes();
			if(timer < 10) {
				timer = "0" + timer;
			}
			if(minutter < 10) {
				minutter = "0" + minutter;
			}
			document.getElementById("ur_felt").innerHTML = "<a href=\"javascript: show_calendar('document.kalender.kalender_felt', document.kalender.kalender_felt.value);\"'>" + timer + ":" + minutter + "</a>";
			setTimeout("ur()", 1000);
		}
		function vis(id) {
			var e = document.getElementById(id);
			if(e.style.display == "block") {
		    		e.style.display = "none";
		  	} else {
				e.style.display = "block";
			}
		}
		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="skrivebord">
				<div id="ikon_div1" class="dynDiv_moveDiv">
					<a href="javascript: vis('denne_computer')" class="ikon_link"><img src="billeder/ikon_computer.gif" alt="Denne computer" title="Denne computer" />Denne computer</a>
				</div><!-- slut ikon_div1 -->
				
				<div id="ikon_div2"" class="dynDiv_moveDiv">
					<a href="javascript: vis('ny_note')" class="ikon_link"><img src="billeder/ikon_ny_note.gif" alt="Ny note" title="Ny note" />Ny note</a>
				</div><!-- slut ikon_div2 -->
				
				<div id="ikon_div3"" class="dynDiv_moveDiv">
					<a href="javascript: vis('ret_note')" class="ikon_link"><img src="billeder/ikon_ret_note.gif" alt="Ret note" title="Ret note" />Ret note</a>
				</div><!-- slut ikon_div3 -->
				
				<div id="ikon_div4" class="dynDiv_moveDiv">
					<a href="javascript: vis('slet_note')" class="ikon_link"><img src="billeder/ikon_slet_note.gif" alt="Slet note" title="Slet note" />Slet note</a>
				</div><!-- slut ikon_div4 -->
				
				<div id="note_boks">
				<?php
				$sql_vis_note = "SELECT dato, note FROM skoleprojekter_skrivebord_noter ORDER BY dato DESC LIMIT 10";
				$resultat_vis_note = mysqli_query($db, $sql_vis_note);
				$antal_noter = mysqli_num_rows($resultat_vis_note);
				if($antal_noter != 0) {
					while($data_note = mysqli_fetch_assoc($resultat_vis_note)) {
						echo "<p class='dato'>".$data_note['dato']."</p>";
						echo "<p>".$data_note['note']."</p><hr/>";
					}
				} else {
					echo "<p><b>Der er ingen noter at vise</b></p>";
				}
				?>
				</div><!-- slut note_boks -->
			</div><!-- slut skrivebord -->
			
			<div id="proceslinie">
				<a href="" id="start">Start</a>
				<form action="" method="post" name="kalender">
					<input type="hidden" name="kalender_felt" value="" />
					<div id="ur_felt">
						<script type="text/javascript">
						ur();
						//document.write("Hej");
						</script>
					</div><!-- slut ur_felt -->
				</form>
			</div><!-- slut proceslinie -->
			
			<div id="denne_computer">
				<div class="top_boks dynDiv_moveParentDiv dynDiv_saveSettings-position dynDiv_loadSettings">
					<a href="javascript: vis('denne_computer')"><img src="billeder/kryds.gif" alt="Kryds" title="Luk" /></a>
				</div>
				<p>Denne computer er tom!</p>
			</div><!-- slut denne_computer -->
			
			<div id="ny_note">
				<div class="top_boks dynDiv_moveParentDiv dynDiv_saveSettings-position dynDiv_loadSettings">
					<a href="javascript: vis('ny_note')"><img src="billeder/kryds.gif" alt="Kryds" title="Luk" /></a>
				</div>
				<p>Tilf&oslash;j en ny note</p>
				<form action="" method="post">
					<textarea name="note" class="note_felt"></textarea>
					<input type="submit" value="Tilf&oslash;j" class="note_knap" />
				</form>
			</div><!-- slut ny_note -->
			
			<div id="ret_note">
				<div class="top_boks top_boks dynDiv_moveParentDiv dynDiv_saveSettings-position dynDiv_loadSettings">
					<a href="javascript: vis('ret_note')"><img src="billeder/kryds.gif" alt="Kryds" title="Luk" /></a>
				</div>
				<p>V&aelig;lg den note du vil rette</p>
				<?php
				$sql_ret = "SELECT note FROM skoleprojekter_skrivebord_noter ORDER BY dato DESC";
				$resultat_ret = mysqli_query($db, $sql_ret);
				while($data_ret = mysqli_fetch_assoc($resultat_ret)) {
				?>
				<a href="javascript: vis('rettet_note')" class="note_link"><?php echo $data_ret['note']; ?></a>
				<?php
				}
				?>
			</div><!-- slut ret_note -->
			
			<div id="rettet_note">
				<div class="top_boks dynDiv_moveParentDiv dynDiv_saveSettings-position dynDiv_loadSettings">
					<a href="javascript: vis('rettet_note')"><img src="billeder/kryds.gif" alt="Kryds" title="Luk" /></a>
				</div>
				<p>Ret noten</p>
				<form action="" method="post">
					<textarea name="ret_note" class="note_felt"></textarea>
					<input type="submit" value="Ret note" class="note_knap" />
				</form>
			</div><!-- slut rettet_note -->
			
			<div id="slet_note">
				<div class="top_boks dynDiv_moveParentDiv dynDiv_saveSettings-position dynDiv_loadSettings">
					<a href="javascript: vis('slet_note')"><img src="billeder/kryds.gif" alt="Kryds" title="Luk" /></a>
				</div>
				<p>Klik p&aring; den note du vil slette</p>
				<?php
				$sql_slet = "SELECT note_id, note FROM skoleprojekter_skrivebord_noter ORDER BY dato DESC";
				$resultat_slet = mysqli_query($db, $sql_slet);
				while($data_slet = mysqli_fetch_assoc($resultat_slet)) {
					echo "<a href='index.php?note_id=$data_slet[note_id]' class='note_link'>".$data_slet['note']."</a>";
				}
				?>
			</div><!-- slut slet_note -->
		</div><!-- slut wrap -->
	</body>
</html>