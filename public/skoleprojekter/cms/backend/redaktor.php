<?php
session_start();
ob_start();
if($_SESSION['redaktor'] != true) {
	header("location: login.php");
}
include("../includes/db.php");
include("../includes/funktioner.php");
if(isset($_GET['page'])) {
	$_GET['page'];
} else {
	$_GET['page'] = "opret_journalist";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>		
		<meta http-equiv="Content-Language" content="en" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="style/backend_style.css" media="screen" />
		<title>NETavisen - redakt&oslash;r</title>
		<script type="text/javascript">
		function valider_journalist() {
			if(document.journalist_form.navn.value == "" ||
			document.journalist_form.password.value == "") {
				alert("Du skal udfylde begge felter!");
				return false;
			}
		}
		function valider_kategori() {
			if(document.kategori_form.kategori_navn.value == "") {
				alert("Du skal skrive et navn for at oprette kategorien!");
				return false;
			}
		}
		</script>
	</head>
	<body>
		<div id="wrap">
			<div id="top">
				<h1>Redakt&oslash;r <?php echo $_SESSION['redaktor_navn'] ?></h1>
			</div>
			<div id="menu">
				<a href="redaktor.php?page=opret_journalist">Opret en journalist</a>
				<a href="redaktor.php?page=slet_journalist">Slet en journalist</a>
				<a href="redaktor.php?page=opret_kategori">Opret en kategori</a>
				<a href="redaktor.php?page=ret_kategori">Ret en kategori</a>
				<a href="redaktor.php?page=slet_kategori">Slet en kategori</a>
				<a href="redaktor_logaf.php">Log af</a>
			</div>
			<div id="content">
			<?php
			if($_GET['page'] == "opret_journalist") {
			?>
			<h1>Opret en journalist</h1>
			<form action="" method="post" name="journalist_form" onsubmit="valider_journalist()">
				<p><label for="navn">Navn</label></p>	
				<input type="text" name="navn" id="navn" class="input" value="<?php echo $_POST['navn']; ?>" />
				<p><label for="password">Password</label></p>
				<input type="password" name="password" id="password" class="input" value="<?php echo $_POST['password']; ?>" />
				<input type="submit" value="Opret" class="redaktor_knap" />
			</form>
			<?php
				if(isset($_POST['navn']) && $_POST['navn'] != "" && isset($_POST['password']) && $_POST['password'] != "") {
					$navn = $_POST['navn'];
					$password = $_POST['password'];
					$sql = "INSERT INTO skoleprojekter_netavisen_journalist (navn, password) VALUES ('$navn', '$password')";
					$resultat = mysqli_query($db, $sql) or die (mysqli_error($db));
					if($resultat == true) {
						echo "<p>Journalisten ".$navn." er oprettet!";
					} else {
						echo "Der er sket en fejl!";
					}
				}
			}
			if($_GET['page'] == "slet_journalist") {
			?>
			<h1>Slet en journalist</h1>
			<table>
				<tr>
					<td class="slet_celle">
						<p>Navn</p>
					</td>
					<td class="slet_celle">
						<p>Password</p>
					</td>
					<td class="slet_celle">
						<p>Slet</p>
					</td>
				</tr>
				<?php
				$slet_journalist = mysqli_query($db, "DELETE FROM skoleprojekter_netavisen_journalist WHERE journalist_id='$_GET[journalist_id]'");
				$journalist_sql = "SELECT journalist_id, navn, password FROM skoleprojekter_netavisen_journalist ORDER BY navn";
				$journalist_resultat = mysqli_query($db, $journalist_sql);
				while($journalist_data = mysqli_fetch_assoc($journalist_resultat)) {
					echo "<tr><td class='slet_celle'><p>".$journalist_data['navn']."</p></td>";
					echo "<td class='slet_celle'><p>".$journalist_data['password']."</p></td>";
					echo "<td class='slet_celle'><a href='redaktor.php?page=slet_journalist&amp;journalist_id=$journalist_data[journalist_id]' class='slet_link'>Slet</a></td></tr>";
				}
				echo "</table>";
			}
			if($_GET['page'] == "opret_kategori") {
			?>
			<h1>Opret en kategori</h1>
			<form action="" method="post" name="kategori_form" onsubmit="valider_kategori()">
				<p><label for="kategori_navn">Navn</p>
				<input type="text" name="kategori_navn" id="kategori_navn" class="input" />
				<p><label for="kategori_valg">V&aelig;lg overordnet kategori</label></p>
				<select name="kategori_valg" id="kategori_valg" class="input">
				<?php
				$sql_valg = "SELECT * FROM skoleprojekter_netavisen_menu ORDER BY menu_id";
				$resultat_valg = mysqli_query($db, $sql_valg);
				while($data_valg = mysqli_fetch_assoc($resultat_valg)) {
					echo "<option value='".$data_valg['menu_id']."'>".ucfirst($data_valg['titel'])."</option>\n";
				}
				?>
				</select>
				<input type="submit" value="Opret" class="redaktor_knap" />
			</form>
			<?php
				if(isset($_POST['kategori_navn']) && $_POST['kategori_navn'] != "") {
					$sql_opret = "INSERT INTO skoleprojekter_netavisen_menu (titel, parent) VALUES ('$_POST[kategori_navn]', '$_POST[kategori_valg]')";
					$resultat_opret = mysqli_query($db, $sql_opret);
					if($resultat_opret == true) {
						echo "<p>Kategorien ".$_POST['kategori_navn']." er oprettet!</p>";
					} else {
						echo "<p>Der er sket en fejl!";
					}
				}
			}
			if($_GET['page'] == "ret_kategori") {
			?>
			<h1>Ret en kategori</h1>
				<?php
				$sql_underkategori = "SELECT menu_id, titel, parent FROM skoleprojekter_netavisen_menu WHERE parent = '0'";
				$resultat_underkategori = mysqli_query($db, $sql_underkategori);
				while($dataUnderkategori = mysqli_fetch_assoc($resultat_underkategori)) {
					echo "<a href='redaktor.php?page=ret_underkategori&amp;kategori_id=$dataUnderkategori[menu_id]'>".ucfirst($dataUnderkategori['titel'])."</a><br/>";
				}	
			}
			if($_GET['page'] == "ret_underkategori") {
				$sql_vis_kategori = "SELECT * FROM skoleprojekter_netavisen_menu WHERE menu_id='$_GET[kategori_id]'";
				$sql_resultat_kategori = mysqli_query($db, $sql_vis_kategori);
				while($data_kategori = mysqli_fetch_assoc($sql_resultat_kategori)) {
				?>
				<h1>Ret underkategorier til kategorien <?php echo ucfirst($data_kategori['titel']); ?></h1>
				<?php
					$sql_underkategori = "SELECT * FROM skoleprojekter_netavisen_menu WHERE parent='$data_kategori[menu_id]'";
					$resultat_underkategori = mysqli_query($db, $sql_underkategori);
					$data_underkategori = mysqli_fetch_assoc($resultat_underkategori);
					if(!is_null($data_underkategori['parent'])) {
						echo "<p>Navn</p>".getMenu($data_underkategori['parent']);
					} else {
						echo "<p>Der er endnu ingen underkategorier i denne kategori</p>";
					}
				}
				echo "<br/><a href='redaktor.php?page=ret_kategori'>Tilbage</a>";
			}
			if($_GET['page'] == "rettet_kategori") {
				$sql_ret = "SELECT menu_id, titel, parent FROM skoleprojekter_netavisen_menu WHERE menu_id='$_GET[kategori_id]'";
				$resultat_ret = mysqli_query($db, $sql_ret);
				while($data_ret = mysqli_fetch_assoc($resultat_ret)) {
				?>
					<h1>Ret kategorien <?php echo $data_ret['titel'] ?></h1>
					<form action="" method="post">
						<p><label for="ret_navn">Navn</label></p>
						<input type="text" name="ret_navn" id="ret_navn" class="input" value="<?php echo $data_ret['titel']; ?>" />
						<input type="submit" value="Ret" name="opdater_knap" class="redaktor_knap" />
					</form>
					<?php
				}
				if(isset($_POST['opdater_knap'])) {
					$sql_opdater = "UPDATE skoleprojekter_netavisen_menu SET titel='$_POST[ret_navn]' WHERE menu_id='$_GET[kategori_id]'";
					$resultat_opdater = mysqli_query($db, $sql_opdater);
					if($resultat_opdater == true) {
						header("location: redaktor.php?page=ret_kategori");
					} else {
						echo "<p>Der er sket en fejl!</p>";
					}
				}
				echo "<br/><a href='javascript: window.history.go(-1)'>Tilbage</a>";
			}
			if($_GET['page'] == "slet_kategori") {
			?>
			<h1>Slet en kategori</h1>
			<table>
				<tr>
					<td class="slet_celle">
						<p>Navn</p>
					</td>
					<td class="slet_celle">
						<p>Overordnet kategori</p>
					</td>
					<td class="slet_celle">
						<p>Slet</p>
					</td>
				</tr>
				<?php
				$sql_kategori = "SELECT menu_id, titel, parent FROM skoleprojekter_netavisen_menu WHERE parent != 0 ORDER BY parent";
				$resultat_kategori = mysqli_query($db, $sql_kategori);
				while($dataKategori = mysqli_fetch_assoc($resultat_kategori)) {
					echo "<tr><td class='slet_celle'><p>".$dataKategori['titel']."</p></td>";
					$sql_slet = "SELECT titel FROM netavisen_menu WHERE menu_id='$dataKategori[parent]'";
					$resultat_slet = mysqli_query($db, $sql_slet);
					$dataSlet = mysqli_fetch_assoc($resultat_slet);
					echo "<td class='slet_celle'><p>".$dataSlet['titel']."</p></td>";
					echo "<td class='slet_celle'><a href='slet_kategori.php?menu_id=$dataKategori[menu_id]' class='slet_link'>Slet</a></td></tr>";
				}
				?>
			</table>
			<?php
			}
			ob_flush();
			?>
			</div>
			<div id="clear"></div>
		</div>
	</body>
</html>