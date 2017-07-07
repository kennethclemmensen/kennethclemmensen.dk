<?php
session_start();
if($_SESSION['admin'] != true) {
	header("location: index.php");
}
if(!isset($_GET['page'])) {
	$_GET['page'] = "opret_skole";
}
include("../includes/db.php");
include("../includes/funktioner.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>		
		<meta http-equiv="Content-Language" content="da" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../style/style.css" media="screen" />
		<title>Skoleportalen - administration</title>
	</head>
	
	<body>
		<div id="wrap">
			<div id="top">
				<h1>Skoleportalen</h1>
			</div><!-- slut top -->
			<div id="admin_menu">
				<ul>
					<li><a href="admin.php?page=opret_skole" <?php echo style("opret_skole");?>>Opret skoler</a></li>
					<li><a href="admin.php?page=rediger_skole" <?php echo style("rediger_skole"); echo style("ret_skole"); echo style("bekraeft_slet_skole"); ?>>Rediger skoler</a></li>
					<li><a href="admin.php?page=opret_laerer" <?php echo style("opret_laerer"); ?>>Opret l&aelig;rerer</a></li>
					<li><a href="admin.php?page=rediger_laerer" <?php echo style("rediger_laerer"); echo style("rettet_laerer"); echo style("bekraeft_slet_laerer"); ?>>Rediger l&aelig;rerer</a></li>
					<li><a href="admin.php?page=opret_klasse" <?php echo style("opret_klasse"); ?>>Opret klasser</a></li>
					<li><a href="admin.php?page=rediger_klasse" <?php echo style("rediger_klasse"); echo style("rettet_klasse"); echo style("bekraeft_slet_klasse"); ?>>Rediger klasser</a></li>
					<li><a href="admin.php?page=opret_elev" <?php echo style("opret_elev"); ?>>Opret elever</a></li>
					<li><a href="admin.php?page=rediger_elev" <?php echo style("rediger_elev"); echo style("rettet_elev"); echo style("bekraeft_slet_elev") ?>>Rediger elever</a></li>
					<li><a href="admin.php?page=opret_artikel" <?php echo style("opret_artikel"); ?>>Opret artikler</a></li>
					<li><a href="admin.php?page=rediger_artikel" <?php echo style("rediger_artikel"); echo style("rettet_artikel"); echo style("bekraeft_slet_artikel"); ?>>Rediger artikler</a></li>
					<li><a href="../includes/logaf.php">Log af</a></li>
				</ul>
			</div><!-- slut menu -->
			<div id="samle">
				<div id="content">
					<?php
					if($_GET['page'] == "opret_skole") {
					?>
					<h1>Opret en skole</h1>
					<form action="" method="post" enctype="multipart/form-data">
						<label for="navn">Navn</label>
						<input type="text" name="navn" id="navn" class="textfield" value="<?php if(isset($_POST['navn']) && $_POST['navn'] != "") { echo $_POST['navn']; } ?>" />
						
						<label for="style">Style</label>
						<input type="text" name="style" id="style" class="textfield" value="<?php if(isset($_POST['style']) && $_POST['style'] != "") { echo $_POST['style']; } ?>" />
						
						<label for="billede">Billede</label>
						<input type="file" name="billede" id="billede" />
						
						<label for="beskrivelse">Beskrivelse</label>
						<textarea name="beskrivelse" id="beskrivelse" class="textarea"><?php if(isset($_POST['beskrivelse']) && $_POST['beskrivelse'] != "") { echo $_POST['beskrivelse']; } ?></textarea>
						
						<label for="email">E-mail</label>
						<input type="text" name="email" id="email" class="textfield" value="<?php if(isset($_POST['email']) && $_POST['email'] != "") { echo $_POST['email']; } ?>" />
						
						<label for="adresse">Adresse</label>
						<input type="text" name="adresse" id="adresse" class="textfield" value="<?php if(isset($_POST['adresse']) && $_POST['adresse'] != "") { echo $_POST['adresse']; } ?>" />
						
						<label for="telefon">Telefon</label>
						<input type="text" name="telefon" id="telefon" class="textfield" value="<?php if(isset($_POST['telefon']) && $_POST['telefon'] != "") { echo $_POST['telefon']; } ?>" />
						<input type="submit" name="knap" value="Opret" class="knapper" />
					</form>
					<?php
						if(isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['style']) && $_POST['style'] != "" && isset($_POST['beskrivelse'])
						&& $_POST['beskrivelse'] != "" && isset($_POST['email']) && $_POST['email'] != "" && isset($_POST['adresse']) && $_POST['adresse'] != ""
						&& isset($_POST['telefon']) && $_POST['telefon'] != "") {
							include("../includes/opret_skole.php");
						}
					}
					if($_GET['page'] == "rediger_skole") {
					?>
					<h1>Ret eller slet en skole</h1>
					<table>
						<tr>
							<td class="celle"><h2>Skole</h2></td>
							<td class="celle"><h2>Ret</h2></td>
							<td class="celle"><h2>Slet</h2></td>
						</tr>
						<?php
						$sql_skole = mysqli_query($db, "SELECT skole_id, navn FROM skoleprojekter_skoleportalen_skoler ORDER BY navn");
						while($data_skole = mysqli_fetch_assoc($sql_skole)) {
							echo "<tr><td class='celle'><p>".$data_skole['navn']."</p></td>\n";
							echo "<td class='celle'><a href='admin.php?page=ret_skole&amp;skole_id=$data_skole[skole_id]'>Ret</a></td>\n";
							echo "<td class='celle'><a href='admin.php?page=bekraeft_slet_skole&amp;skole_id=$data_skole[skole_id]'>Slet</a></td></tr>\n";
						}
						?>
					</table>
					<?php
					}
					if($_GET['page'] == "ret_skole") {
						$sql_ret_skole = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$_GET[skole_id]'");
						$data_ret_skole = mysqli_fetch_assoc($sql_ret_skole);
						?>
						<form action="" method="post" enctype="multipart/form-data">
							<label for="ret_navn">Navn</label>
							<input type="text" name="ret_navn" id="ret_navn" class="textfield" value="<?php if(isset($_POST['ret_navn']) && $_POST['ret_navn'] != "") { echo $_POST['ret_navn']; } else { echo $data_ret_skole['navn']; } ?>" />
							
							<label for="ret_style">Style</label>
							<input type="text" name="ret_style" id="ret_style" class="textfield" value="<?php if(isset($_POST['ret_style']) && $_POST['ret_style'] != "") { echo $_POST['ret_style']; } else { echo $data_ret_skole['style']; } ?>" />
							
							<label for="ret_billede">Billede</label>
							<input type="file" name="ret_billede" id="ret_billede" />
							
							<label for="ret_beskrivelse">Beskrivelse</label>
							<textarea name="ret_beskrivelse" id="ret_beskrivelse" class="textarea"><?php if(isset($_POST['ret_beskrivelse']) && $_POST['ret_beskrivelse'] != "") { echo $_POST['ret_beskrivelse']; } else { echo $data_ret_skole['beskrivelse']; } ?></textarea>
							
							<label for="ret_email">E-mail</label>
							<input type="text" name="ret_email" id="ret_email" class="textfield" value="<?php if(isset($_POST['ret_email']) && $_POST['ret_email'] != "") { echo $_POST['ret_email']; } else { echo $data_ret_skole['email']; } ?>" />
							
							<label for="ret_adresse">Adresse</label>
							<input type="text" name="ret_adresse" id="ret_adresse" class="textfield" value="<?php if(isset($_POST['ret_adresse']) && $_POST['ret_adresse'] != "") { echo $_POST['ret_adresse']; } else { echo $data_ret_skole['adresse']; } ?>" />
							
							<label for="ret_telefon">Telefon</label>
							<input type="text" name="ret_telefon" id="ret_telefon" class="textfield" value="<?php if(isset($_POST['ret_telefon']) && $_POST['ret_telefon'] != "") { echo $_POST['ret_telefon']; } else { echo $data_ret_skole['telefon']; } ?>" />
							<input type="submit" name="ret_skole_knap" value="Ret" class="knapper" />
						</form>
					<?php
						if(isset($_POST['ret_skole_knap'])) {
							$_SESSION['skole_id'] = $data_ret_skole['skole_id'];
							include("../includes/ret_skole.php");
						}
					}
					if($_GET['page'] == "bekraeft_slet_skole") {
						$sql_skole = mysqli_query($db, "SELECT skole_id, navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$_GET[skole_id]'");
						$data_skole = mysqli_fetch_assoc($sql_skole);
						echo "<h1>Slet skole</h1>";
						echo "<p>Er du sikker p&aring; at du vil slette ".$data_skole['navn']."?</p>";
						echo "<a href='../includes/slet_skole.php?skole_id=$data_skole[skole_id]' class='bekraeft_link'>Ja</a>";
						echo "<a href='admin.php?page=rediger_skole' class='bekraeft_link'>Nej</a>";
					}
					if($_GET['page'] == "opret_laerer") {
					?>
					<h1>Opret en l&aelig;rer</h1>
					<form action="" method="post" enctype="multipart/form-data">
						<label for="skole">Skole</label>
						<select name="skole" id="skole" class="select">
							<?php
							$sql_skoler = mysqli_query($db, "SELECT skole_id, navn FROM skoleprojekter_skoleportalen_skoler ORDER BY navn");
							while($data_skoler = mysqli_fetch_assoc($sql_skoler)) {
								echo "<option value='$data_skoler[skole_id]'>".$data_skoler['navn']."</option>";
							}
							?>
						</select>
						<label for="navn">Navn</label>
						<input type="text" name="navn" id="navn" class="textfield" value="<?php if(isset($_POST['navn']) && $_POST['navn'] != "") { echo $_POST['navn']; } ?>" />
						<label for="billede">Billede</label>
						<input type="file" name="billede" id="billede" />
						<label for="brugernavn">Brugernavn</label>
						<input type="text" name="brugernavn" id="brugernavn" class="textfield" value="<?php if(isset($_POST['brugernavn']) && $_POST['brugernavn'] != "") { echo $_POST['brugernavn']; } ?>" />
						<label for="password">Password</label>
						<input type="password" name="password" id="password" class="textfield" />
						<input type="submit" value="Opret" class="knapper" />
					</form>
					<?php
						if(isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['brugernavn']) && $_POST['brugernavn'] != "" &&
						isset($_POST['password']) && $_POST['password'] != "") {
							include("../includes/opret_laerer.php");
						}
					}
					if($_GET['page'] == "rediger_laerer") {
					?>
					<h1>Ret eller slet en l&aelig;rer</h1>
					<table>
						<tr>
							<td class="celle"><h2>Navn</h2></td>
							<td class="celle"><h2>Skole</h2></td>
							<td class="celle"><h2>Ret</h2></td>
							<td class="celle"><h2>Slet</h2></td>
						</tr>
						<?php
						$sql = mysqli_query($db, "SELECT laerer_id, skole_id, navn FROM skoleprojekter_skoleportalen_laererer ORDER BY navn");
						while($data = mysqli_fetch_assoc($sql)) {
							echo "<tr><td class='celle'><p>".$data['navn']."</p></td>";
							$sqlSkole = mysqli_query($db, "SELECT navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$data[skole_id]'");
							$dataSkole = mysqli_fetch_assoc($sqlSkole);
							echo "<td class='celle'><p>".$dataSkole['navn']."</p></td>";
							echo "<td class='celle'><a href='admin.php?page=rettet_laerer&amp;laerer_id=$data[laerer_id]'>Ret</a></td>";
							echo "<td class='celle'><a href='admin.php?page=bekraeft_slet_laerer&amp;laerer_id=$data[laerer_id]'>Slet</a></td></tr>";
						}
						echo "</table>";
					}
					if($_GET['page'] == "rettet_laerer") {
						$sql_ret_laerer = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_laererer WHERE laerer_id='$_GET[laerer_id]'");
						$data_ret_laerer = mysqli_fetch_assoc($sql_ret_laerer);
						?>
						<form action="" method="post" enctype="multipart/form-data">
							<label for="ret_skole">Skole</label>
							<select name="ret_skole" id="ret_skole" class="select">
								<?php
								$sql_skoler = mysqli_query($db, "SELECT skole_id, navn FROM skoleprojekter_skoleportalen_skoler ORDER BY navn");
								while($data_skoler = mysqli_fetch_assoc($sql_skoler)) {
									echo "<option value='$data_skoler[skole_id]'";
									if($data_ret_laerer['skole_id'] == $data_skoler['skole_id']) {
										echo "selected='selected'";
									}
									echo ">".$data_skoler['navn']."</option>";
								}
								?>
							</select>
							<label for="ret_navn">Navn</label>
							<input type="text" name="ret_navn" id="ret_navn" class="textfield" value="<?php if(isset($_POST['ret_navn']) && $_POST['ret_navn'] != "") { echo $_POST['ret_navn']; } else { echo $data_ret_laerer['navn']; } ?>" />
							<label for="ret_billede">Billede</label>
							<input type="file" name="ret_billede" id="ret_billede" />
							<label for="ret_brugernavn">Brugernavn</label>
							<input type="text" name="ret_brugernavn" id="ret_brugernavn" class="textfield" value="<?php if(isset($_POST['ret_brugernavn']) && $_POST['ret_brugernavn'] != "") { echo $_POST['ret_brugernavn']; } else { echo $data_ret_laerer['brugernavn']; } ?>" />
							<label for="ret_password">Password</label>
							<input type="password" name="ret_password" id="ret_password" class="textfield" value="<?php if(isset($_POST['ret_password']) && $_POST['ret_password'] != "") { echo $_POST['ret_password']; } else { echo $data_ret_laerer['password']; } ?>" />
							<input type="submit" name="laerer_knap" value="Ret" class="knapper" />
						</form>
						<?php
						if(isset($_POST['laerer_knap'])) {
							$_SESSION['laerer_id'] = $data_ret_laerer['laerer_id'];
							include("../includes/ret_laerer.php");
						}
					}
					if($_GET['page'] == "bekraeft_slet_laerer") {
						$sql_slet_laerer = mysqli_query($db, "SELECT laerer_id, skole_id, navn FROM skoleprojekter_skoleportalen_laererer WHERE laerer_id='$_GET[laerer_id]'");
						$data_slet_laerer = mysqli_fetch_assoc($sql_slet_laerer);
						$skole = mysqli_query($db, "SELECT navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$data_slet_laerer[skole_id]'");
						$data_skolen = mysqli_fetch_assoc($skole);
						echo "<p>Er du sikker p&aring; at du vil slette ".$data_slet_laerer['navn']." fra ".$data_skolen['navn']."?</p>";
						echo "<a href='../includes/slet_laerer.php?laerer_id=$data_slet_laerer[laerer_id]' class='bekraeft_link'>Ja</a>";
						echo "<a href='admin.php?page=rediger_laerer' class='bekraeft_link'>Nej</a>";
					}
					if($_GET['page'] == "opret_klasse") {
					?>
					<h1>Opret en klasse</h1>
					<form action="" method="post" enctype="multipart/form-data">
						<label for="skole">Skole</label>
						<select name="skole" id="skole" class="select">
							<?php
							$sql_skole_klasse = mysqli_query($db, "SELECT skole_id, navn FROM skoleprojekter_skoleportalen_skoler ORDER BY navn");
							while($data_skole_klasse = mysqli_fetch_assoc($sql_skole_klasse)) {
								echo "<option value='$data_skole_klasse[skole_id]'>".$data_skole_klasse['navn']."</option>";
							}
							?>
						</select>
						<label for="billede">Billede</label>
						<input type="file" name="billede" id="billede" />
						<label for="navn">Navn</label>
						<input type="text" name="navn" class="textfield" value="<?php if(isset($_POST['navn']) && $_POST['navn'] != "") { echo $_POST['navn']; } ?>" />
						<label for="beskrivelse">Bekskrivelse</label>
						<textarea name="beskrivelse" id="beskrivelse" class="textarea"><?php if(isset($_POST['beskrivelse']) && $_POST['beskrivelse'] != "") { echo $_POST['beskrivelse']; } ?></textarea>
						<input type="submit" value="Opret" class="knapper" />
					</form>
					<?php
						if(isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['beskrivelse']) && $_POST['beskrivelse'] != "") {
							include("../includes/opret_klasse.php");
						}
					}
					if($_GET['page'] == "rediger_klasse") {
					?>
					<h1>Ret eller slet en klasse</h1>
					<table>
						<tr>
							<td class="celle"><h2>Navn</h2></td>
							<td class="celle"><h2>Skole</h2></td>
							<td class="celle"><h2>Antal elever</h2></td>
							<td class="celle"><h2>Ret</h2></td>
							<td class="celle"><h2>Slet</h2></td>
						</tr>
						<?php
						$sqlKlasse = mysqli_query($db, "SELECT klasse_id, skole_id, navn FROM skoleprojekter_skoleportalen_klasser ORDER BY navn");
						while($dataKlasse = mysqli_fetch_assoc($sqlKlasse)) {
							echo "<tr><td class='celle'><p>".$dataKlasse['navn']."</p></td>";
							$get_skole = mysqli_query($db, "SELECT navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$dataKlasse[skole_id]'");
							$data_get_skole = mysqli_fetch_assoc($get_skole);
							echo "<td class='celle'><p>".$data_get_skole['navn']."</p></td>";
							$antal_elever = mysqli_query($db, "SELECT * FROM skoleprojekter_skoleportalen_elever WHERE klasse_id='$dataKlasse[klasse_id]'") or die(mysqli_error($db));
							echo "<td class='celle'><p>".mysqli_num_rows($antal_elever)."</p></td>";
							echo "<td class='celle'><a href='admin.php?page=rettet_klasse&amp;klasse_id=$dataKlasse[klasse_id]'>Ret</a></td>";
							echo "<td class='celle'><a href='admin.php?page=bekraeft_slet_klasse&amp;klasse_id=$dataKlasse[klasse_id]'>Slet</a></td></tr>";
						}
						echo "</table>";
					}
					if($_GET['page'] == "rettet_klasse") {
						$sql_klasse = mysqli_query($db, "SELECT klasse_id, skole_id, navn, beskrivelse, billede FROM skoleprojekter_skoleportalen_klasser WHERE klasse_id='$_GET[klasse_id]'");
						$data_klasse = mysqli_fetch_assoc($sql_klasse);
						?>
						<form action="" method="post" enctype="multipart/form-data">
							<label for="ret_skole">Skole</label>
							<select name="ret_skole" id="ret_skole" class="select">
								<?php
								$sql_skole_klasse = mysqli_query($db, "SELECT skole_id, navn FROM skoleprojekter_skoleportalen_skoler ORDER BY navn");
								while($data_skole_klasse = mysqli_fetch_assoc($sql_skole_klasse)) {
									echo "<option value='$data_skole_klasse[skole_id]'";
									if($data_klasse['skole_id'] == $data_skole_klasse['skole_id']) {
										echo "selected='selected'";
									}
									echo ">".$data_skole_klasse['navn']."</option>";
								}
								?>
							</select>
							<label for="ret_billede">Billede</label>
							<input type="file" name="ret_billede" id="ret_billede" />
							<label for="navn">Navn</label>
							<input type="text" name="ret_navn" class="textfield" value="<?php if(isset($_POST['ret_navn']) && $_POST['ret_navn'] != "") { echo $_POST['ret_navn']; } else { echo $data_klasse['navn']; } ?>" />
							<label for="ret_beskrivelse">Beskrivelse</label>
							<textarea name="ret_beskrivelse" id="ret_beskrivelse" class="textarea"><?php if(isset($_POST['ret_beskrivelse']) && $_POST['ret_beskrivelse'] != "") { echo $_POST['ret_beskrivelse']; } else { echo $data_klasse['beskrivelse']; } ?></textarea>
							<input type="submit" name="ret_klasse" value="Ret" class="knapper" />
						</form>
					<?php
						if(isset($_POST['ret_klasse'])) {
							$_SESSION['klasse_id'] = $data_klasse['klasse_id'];
							include("../includes/ret_klasse.php");
						}
					}
					if($_GET['page'] == "bekraeft_slet_klasse") {
						$sql_slet_klasse = mysqli_query($db, "SELECT klasse_id, skole_id, navn FROM skoleprojekter_skoleportalen_klasser WHERE klasse_id='$_GET[klasse_id]'");
						$data_slet_klasse = mysqli_fetch_assoc($sql_slet_klasse);
						$skole_klasse = mysqli_query($db, "SELECT navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$data_slet_klasse[skole_id]'");
						$dataSkoleKlasse = mysqli_fetch_assoc($skole_klasse);
						echo "<p>Er du sikker p&aring; at du vil slette ".$data_slet_klasse['navn']." fra ".$dataSkoleKlasse['navn']."?";
						echo "<a href='../includes/slet_klasse.php?klasse_id=$data_slet_klasse[klasse_id]' class='bekraeft_link'>Ja</a>";
						echo "<a href='admin.php?page=rediger_klasse' class='bekraeft_link'>Nej</a>";
					}
					if($_GET['page'] == "opret_elev") {
					?>
					<h1>Opret en elev</h1>
					<form action="" method="post" enctype="multipart/form-data">
						<label for="klasse">Klasse</label>
						<select name="klasse" id="klasse" class="select">
							<?php
							$sql_klasser = mysqli_query($db, "SELECT klasse_id, navn FROM skoleprojekter_skoleportalen_klasser ORDER BY navn");
							while($data_klasser = mysqli_fetch_assoc($sql_klasser)) {
								echo "<option value='$data_klasser[klasse_id]'>".$data_klasser['navn']."</option>";
							}
							?>
						</select>
						<label for="navn">Navn</label>
						<input type="text" name="navn" id="navn" class="textfield" value="<?php if(isset($_POST['navn']) && $_POST['navn'] != "") { echo $_POST['navn']; } ?>" />
						<label for="brugernavn">Brugernavn</label>
						<input type="text" name="brugernavn" id="brugernavn" class="textfield" value="<?php if(isset($_POST['brugernavn']) && $_POST['brugernavn'] != "") { echo $_POST['brugernavn']; } ?>" />
						<label for="password">Password</label>
						<input type="password" name="password" id="password" class="textfield" />
						<label for="billede">Billede</label>
						<input type="file" name="billede" id="billede" />
						<input type="submit" value="Opret" class="knapper" />
					</form>
					<?php
						if($_POST['klasse'] != "" && isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['brugernavn']) && $_POST['brugernavn'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
							include("../includes/opret_elev.php");
						}
					}
					if($_GET['page'] == "rediger_elev") {
					?>
					<h1>Ret eller slet en elev</h1>
					<table>
						<tr>
							<td class="celle"><h2>Navn</h2></td>							
							<td class="celle"><h2>Klasse</h2></td>							
							<td class="celle"><h2>Skole</h2></td>							
							<td class="celle"><h2>Ret</h2></td>							
							<td class="celle"><h2>Slet</h2></td>							
						</tr>
						<?php
						$sql_elever = mysqli_query($db, "SELECT elev_id, klasse_id, navn FROM skoleprojekter_skoleportalen_elever ORDER BY navn");
						while($data_elever = mysqli_fetch_assoc($sql_elever)) {
							echo "<tr><td class='celle'>".$data_elever['navn']."</td>\n";
							$sql_klasse = mysqli_query($db, "SELECT skole_id, navn FROM skoleprojekter_skoleportalen_klasser WHERE klasse_id='$data_elever[klasse_id]'");
							while($data_klasse = mysqli_fetch_assoc($sql_klasse)) {
								echo "<td class='celle'>".$data_klasse['navn']."</td>\n";
								$sql_skole = mysqli_query($db, "SELECT navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$data_klasse[skole_id]'");
								$data_skole = mysqli_fetch_assoc($sql_skole);
								echo "<td class='celle'>".$data_skole['navn']."</td>\n";
							}
							echo "<td class='celle'><a href='admin.php?page=rettet_elev&amp;elev_id=$data_elever[elev_id]'>Ret</a></td>\n";
							echo "<td class='celle'><a href='admin.php?page=bekraeft_slet_elev&amp;elev_id=$data_elever[elev_id]'>Slet</a></td></tr>\n";
						}
					echo "</table>";
					}
					if($_GET['page'] == "rettet_elev") {
						$sql_eleven = mysqli_query($db, "SELECT elev_id, klasse_id, navn, brugernavn, password FROM skoleprojekter_skoleportalen_elever WHERE elev_id='$_GET[elev_id]'");
						$data_eleven = mysqli_fetch_assoc($sql_eleven);
						?>
						<form action="" method="post" enctype="multipart/form-data">
							<label for="ret_klasse">Klasse</label>
							<select name="ret_klasse" id="ret_klasse" class="select">
								<?php
								$sql_klasser = mysqli_query($db, "SELECT klasse_id, navn FROM skoleprojekter_skoleportalen_klasser ORDER BY navn");
								while($data_klasser = mysqli_fetch_assoc($sql_klasser)) {
									echo "<option value='$data_klasser[klasse_id]'";
									if($data_eleven['klasse_id'] == $data_klasser['klasse_id']) {
										echo "selected='selected'";
									}
									echo ">".$data_klasser['navn']."</option>";
								}
								?>
							</select>
							<label for="ret_navn">Navn</label>
							<input type="text" name="ret_navn" id="ret_navn" class="textfield" value="<?php if(isset($_POST['ret_navn']) && $_POST['ret_navn'] != "") { echo $_POST['ret_navn']; } else { echo $data_eleven['navn']; } ?>" />
							<label for="ret_brugernavn">Brugernavn</label>
							<input type="text" name="ret_brugernavn" id="ret_brugernavn" class="textfield" value="<?php if(isset($_POST['ret_brugernavn']) && $_POST['ret_brugernavn'] != "") { echo $_POST['ret_brugernavn']; } else { echo $data_eleven['brugernavn']; } ?>" />
							<label for="ret_password">Password</label>
							<input type="password" name="ret_password" id="ret_password" class="textfield" value="<?php echo $data_eleven['password']; ?>" />
							<label for="ret_billede">Billede</label>
							<input type="file" name="ret_billede" id="ret_billede" />
							<input type="submit" name="ret_eleven" value="Ret" class="knapper" />
						</form>
					<?php
						if(isset($_POST['ret_eleven'])) {
							$_SESSION['elev_id'] = $data_eleven['elev_id'];
							include("../includes/ret_elev.php");
						}
					}
					if($_GET['page'] == "bekraeft_slet_elev") {
						$sql_elev = mysqli_query($db, "SELECT elev_id, klasse_id, navn FROM skoleprojekter_skoleportalen_elever WHERE elev_id='$_GET[elev_id]'");
						$data_elev = mysqli_fetch_assoc($sql_elev);
						$sql_klasse = mysqli_query($db, "SELECT skole_id, navn FROM skoleprojekter_skoleportalen_klasser WHERE klasse_id='$data_elev[klasse_id]'");
						$data_klasse = mysqli_fetch_assoc($sql_klasse);
						$sql_skole = mysqli_query($db, "SELECT navn FROM skoleprojekter_skoleportalen_skoler WHERE skole_id='$data_klasse[skole_id]'");
						$data_skole = mysqli_fetch_assoc($sql_skole);
						echo "<p>Er du sikker p&aring; at du vil slette ".$data_elev['navn']." i ".$data_klasse['navn']." fra ".$data_skole['navn']."?</p>";
						echo "<a href='../includes/slet_elev.php?elev_id=$data_elev[elev_id]' class='bekraeft_link'>Ja</a>";
						echo "<a href='admin.php?page=rediger_elev' class='bekraeft_link'>Nej</a>";
					}
					if($_GET['page'] == "opret_artikel") {
					?>
					<h1>Opret en artikel</h1>
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
					if($_GET['page'] == "rediger_artikel") {
					?>
					<h1>Ret eller slet en artikel</h1>
					<table>
						<tr>
							<td class="celle"><h2>Overskrift</h2></td>
							<td class="celle"><h2>Dato</h2></td>
							<td class="celle"><h2>Ret</h2></td>
							<td class="celle"><h2>Slet</h2></td>
						</tr>
						<?php
						$sql_artikler = mysqli_query($db, "SELECT artikel_id, overskrift, dato FROM skoleprojekter_skoleportalen_artikler ORDER BY dato DESC");
						while($data_artikler = mysqli_fetch_assoc($sql_artikler)) {
							echo "<tr><td class='celle'><p>".$data_artikler['overskrift']."</p></td>\n";
							echo "<td class='celle'><p>".date("j-m-Y", $data_artikler['dato'])."</p></td>\n";
							echo "<td class='celle'><a href='admin.php?page=rettet_artikel&amp;artikel_id=$data_artikler[artikel_id]'>Ret</a></td>\n";
							echo "<td class='celle'><a href='admin.php?page=bekraeft_slet_artikel&amp;artikel_id=$data_artikler[artikel_id]'>Slet</a></td></tr>\n";
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
						echo "<a href='admin.php?page=rediger_artikel' class='bekraeft_link'>Nej</a>";
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