<?php
session_start();
if($_SESSION['elev'] != true) {
	header("location: ../index.php");
}
if(!isset($_GET['page'])) {
	$_GET['page'] = "forside";
}
include("../includes/db.php");
include("../includes/funktioner.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" href="../style/style.css" media="screen" />
		<title>Skoleportalen - elev</title>
	</head>
	
	<body>
		<div id="wrap">
			<div id="top">
				<h1>Skoleportalen</h1>
			</div><!-- slut top -->
			<div id="menu">
				<ul>
					<li><a href="elev.php?page=forside" <?php echo style("forside"); ?>>Forside</a></li>
					<li><a href="elev.php?page=opret_artikel" <?php echo style("opret_artikel"); ?>>Opret artikler</a></li>
					<li><a href="elev.php?page=ret_artikel" <?php echo style("ret_artikel"); echo style("rettet_artikel"); echo style("bekraeft_slet_artikel"); ?>>Rediger artikler</a></li>
					<li><a href="../includes/elev_logaf.php">Log af</a></li>
				</ul>
			</div><!-- slut menu -->
			<div id="samle">
				<div id="content">
					<?php
					if($_GET['page'] == "forside") {
					?>
					<h1>Hej <?php echo $_SESSION['navn']; ?></h1>
					<p>P&aring; disse sider kan du skrive, rette og slette artikler.</p>
					<?php
					}
					if($_GET['page'] == "opret_artikel") {
					?>
					<h1>Opret artikler</h1>
					<p>P&aring; denne side kan du oprette artikler.</p>
					<form action="" method="post" enctype="multipart/form-data">
						<label for="overskrift">Overskrift</label>
						<input type="text" name="overskrift" id="overskrift" class="textfield" value="<?php if(isset($_POST['overskrift']) && $_POST['overskrift'] != "") { echo $_POST['overskrift']; } ?>" />
						<label for="tekst">Tekst</label>
						<textarea name="tekst" id="tekst" class="textarea"><?php if(isset($_POST['tekst']) && $_POST['tekst'] != "") { echo $_POST['tekst']; } ?></textarea>
						<label for="billede">Billede</label>
						<input type="file" name="billede" id="billede" />
						<label for="titel">Titel</label>
						<input type="text" name="titel" id="titel" class="textfield" value="<?php if(isset($_POST['titel']) && $_POST['titel'] != "") { echo $_POST['titel']; } ?>" />
						<input type="submit" value="Opret" class="knapper" />
					</form>
					<?php
						if(isset($_POST['overskrift']) && $_POST['overskrift'] != "" && isset($_POST['tekst']) && $_POST['tekst'] != "" && isset($_POST['titel']) && $_POST['titel'] != "") {
							include("../includes/opret_artikel.php");
						}
					}
					if($_GET['page'] == "ret_artikel") {
					?>
					<h1>Ret eller slet en artikel</h1>
					<table>
						<tr>
							<td class="celle">Overskrift</td>
							<td class="celle">Dato</td>
							<td class="celle">Ret</td>
							<td class="celle">Slet</td>
						</tr>
						<?php
						$sql_artikler = mysqli_query($db, "SELECT artikel_id, overskrift, dato FROM skoleprojekter_skoleportalen_artikler WHERE elev_id='$_SESSION[elev_id]'");
						while($data_artikler = mysqli_fetch_assoc($sql_artikler)) {
							echo "<tr><td class='celle'><p>".$data_artikler['overskrift']."</p></td>";
							echo "<td class='celle'><p>".date("j-m-Y", $data_artikler['dato'])."</p></td>";
							echo "<td class='celle'><a href='elev.php?page=rettet_artikel&amp;artikel_id=$data_artikler[artikel_id]'>Ret</a></td>";
							echo "<td class='celle'><a href='elev.php?page=bekraeft_slet_artikel&amp;artikel_id=$data_artikler[artikel_id]'>Slet</a></td></tr>";
						}
						echo "</table>";
					}
					if($_GET['page'] == "rettet_artikel") {
					$sql_artikel = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_artikler WHERE artikel_id='$_GET[artikel_id]'");
						$data_artikel = mysqli_fetch_assoc($sql_artikel);
						?>
						<form action="" method="post" enctype="multipart/form-data">
							<label for="ret_overskrift">Overskrift</label>
							<input type="text" name="ret_overskrift" id="ret_overskrift" class="textfield" value="<?php if(isset($_POST['ret_overskrift']) && $_POST['ret_overskrift'] != "") { echo $_POST['ret_overskrift']; } else { echo $data_artikel['overskrift']; } ?>" />
							<label for="ret_tekst">Tekst</label>
							<textarea name="ret_tekst" id="ret_tekst" class="textarea"><?php if(isset($_POST['ret_tekst']) && $_POST['ret_tekst'] != "") { echo $_POST['ret_tekst']; } else { echo $data_artikel['tekst']; } ?></textarea>
							<label for="ret_billede">Billede</label>
							<input type="file" name="ret_billede" id="ret_billede" />
							<label for="ret_titel">Titel</label>
							<input type="text" name="ret_titel" id="ret_titel" class="textfield" value="<?php if(isset($_POST['ret_titel']) && $_POST['ret_titel'] != "") { echo $_POST['ret_titel']; } else { echo $data_artikel['billede_titel']; } ?>" />
							<input type="submit" name="ret_artikel" value="Ret" class="knapper" />
						</form>
					<?php
						if(isset($_POST['ret_artikel'])) {
							$_SESSION['artikel_id'] = $data_artikel['artikel_id'];
							include("../includes/ret_artikel.php");
						}
					}
					if($_GET['page'] == "bekraeft_slet_artikel") {
						$sql_bekraeft_slet = mysqli_query($db, "SELECT artikel_id, overskrift, dato FROM skoleprojekter_skoleportalen_artikler WHERE artikel_id='$_GET[artikel_id]'");
						$data_bekraeft_slet = mysqli_fetch_assoc($sql_bekraeft_slet);
						echo "<p>Er du sikker p&aring; at du vil slette artiklen ".$data_bekraeft_slet['overskrift']." fra d. ".date("j-m-Y", $data_bekraeft_slet['dato'])."?";
						echo "<a href='../includes/slet_artikel.php?artikel_id=$data_bekraeft_slet[artikel_id]' class='bekraeft_link'>Ja</a>";
						echo "<a href='elev.php?page=ret_artikel' class='bekraeft_link'>Nej</a>";
					}
					?>
				</div><!-- slut indhold -->
				<div id="hojre">
					
				</div><!-- slut hojre -->
			</div><!-- slut samle -->
			<div id="bund">
				<p>&copy; Skoleportalen</p>
			</div><!-- slut bund -->		
		</div><!-- slut wrap -->
	</body>
</html>