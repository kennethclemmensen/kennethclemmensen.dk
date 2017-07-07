<script type="text/javascript">
//opretter funktionen skift_element som har 2 parametre
function skift_element(id, state) {
	//opretter variablen e som indeholder funktionen getElementById
	var e = document.getElementById(id);
	//tjekker om state er 0. Hvis den er 0 bliver den ikke vist
	if(state == 0) {
		disp = "none";
	} else { //ellers bliver den vist
		disp = "block";
	}		
	e.style.display = disp;
}
//opretter funktionen soeg_valg_onchange
function soeg_valg_onchange() {
	//opretter variablen e som indeholder funktionen getElementById som har id'et fra <select>
	var e = document.getElementById("select_soeg");
	var chosen = e.options[e.selectedIndex];
	if(chosen.value=="boger") {
		skift_element("boger", 1);
		skift_element("arrangement", 0);
	} else if(chosen.value=="arrangementer") {
		skift_element("boger", 0);
		skift_element("arrangement", 1);
	}
}
</script>
<form action="index.php?page=avanceret_soeg" method="post" name="avanceret_soeg_formular" onsubmit="valider_avanceret_soeg_formular()">
	<p class="soeg_tekst"><label for="soeg">Avanceret s&oslash;g</label></p><input type="text" name="soeg" class="soeg_felt" value="<?php echo $_POST['soeg']; ?>" /><br/>
	<p class="soeg_tekst"><label for="soeg_valg">S&oslash;g i</label></p>
	<select id="select_soeg" name="soeg_valg" class="soeg_rullemenu" onchange="soeg_valg_onchange()">
		<option value="boger" <?php if($_POST['soeg_valg'] == "boger") echo "selected"; ?>>B&oslash;ger</option>
		<option value="arrangementer" <?php if($_POST['soeg_valg'] == "arrangementer") echo "selected"; ?>>Arrangementer</option>
	</select><br/>
	<p>S&oslash;g p&aring;</p>
	<table id="boger">
		<tr>
			<td class="soeg_celle"><p>Titel</p><input type="checkbox" name="titel" <?php if(isset($_POST['titel'])) echo "checked"; ?> /></td>
			<td class="soeg_celle"><p>Forfatter</p><input type="checkbox" name="forfatter" <?php if(isset($_POST['forfatter'])) echo "checked"; ?> /></td>
			<td class="soeg_celle"><p>ISBN nr.</p><input type="checkbox" name="isbn" <?php if(isset($_POST['isbn'])) echo "checked"; ?> /></td>
			<td class="soeg_celle"><p>Beskrivelse</p><input type="checkbox" name="beskrivelse" <?php if(isset($_POST['beskrivelse'])) echo "checked"; ?> /></td>
		</tr>
	</table>
	
	<table id="arrangement">
		<tr>
			<td class="soeg_celle"><p>Titel</p><input type="checkbox" name="arrangement_titel" <?php if(isset($_POST['arrangement_titel'])) echo "checked"; ?> /></td>
			<td class="soeg_celle"><p>Taler</p><input type="checkbox" name="taler" <?php if(isset($_POST['taler'])) echo "checked"; ?> /></td>
			<td class="soeg_celle"><p>Dato</p><input type="checkbox" name="dato" <?php if(isset($_POST['dato'])) echo "checked"; ?> /></td>
			<td class="soeg_celle"><p>Omtale</p><input type="checkbox" name="omtale" <?php if(isset($_POST['omtale'])) echo "checked"; ?> /></td>
		</tr>
	</table>
	<?php
	if($_POST['soeg_valg'] == "boger" || !isset($_POST['soeg_valg'])) {
	?>
	<script type="text/javascript">
		skift_element("boger", 1);
		skift_element("arrangement", 0);
	</script>
	<?php
	} else if($_POST['soeg_valg'] == "arrangementer") {
	?>
	<script type="text/javascript">
		skift_element("boger", 0);
		skift_element("arrangement", 1);
	</script>
	<?php
	}
	?>
	<input type="submit" value="S&oslash;g" class="soeg_knap" />
</form>
<br/><hr/>
<?php
if(isset($_POST['soeg']) && $_POST['soeg'] != "") {
	if($_POST['soeg_valg'] == "boger") {
		unset($felter);
		if(isset($_POST['titel'])) { $felter[] = " titel LIKE '%".$_POST['soeg']."%' "; }
		if(isset($_POST['forfatter'])) { $felter[] = " forfatter LIKE '%".$_POST['soeg']."%' "; }
		if(isset($_POST['isbn'])) { $felter[] = " isbn LIKE '%".$_POST['soeg']."%' "; }
		if(isset($_POST['beskrivelse'])) { $felter[] = " omtale LIKE '%".$_POST['soeg']."%' "; }
		//tjekker om nogle af felterne er hakket af
		if(!empty($felter)) {
			$sql = "SELECT * FROM lojstrup_bog WHERE ";
			$sql.= implode(" OR ", $felter);
			$result = mysqli_query($db, $sql) or die (mysqli_error($db));
			$result_antal = mysqli_num_rows($result);
			while($resultat = mysqli_fetch_assoc($result)) {
				if($result_antal == 1) {
				?>
				<table>
					<tr>
						<td>
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['omtale']); ?></p>
							<a href="bog_omtale.php?bog_id=<?php echo $resultat[bog_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				} else if($result_antal == 2 || $result_antal == 3) {
				?>
				<table>
					<tr>
						<td>
							<img src="admin/<?php echo $resultat['billede']; ?>" width="124px" height="176px" alt="<?php echo $resultat['titel']; ?>" title="<?php echo $resultat['titel']; ?>" />
						</td>
						<td class="tekst_celle_hojre">
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Forfatter <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['forfatter']); ?></p>
							<p>Forlag <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['forlag']); ?></p>
							<p>ISBN nr. <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['isbn']); ?></p>
							<p>Antal sider <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['sider']); ?></p>
							<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['kort_omtale']); ?></p>
							<a href="bog_omtale.php?bog_id=<?php echo $resultat[bog_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				} else if($result_antal > 3) {
				?>
				<table>
					<tr>
						<td>
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Forfatter <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['forfatter']); ?></p>
							<p>Forlag <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['forlag']); ?></p>
							<p>ISBN nr. <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['isbn']); ?></p>
							<p>Antal sider <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['sider']); ?></p>
							<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['kort_omtale']); ?></p>
							<a href="bog_omtale.php?bog_id=<?php echo $resultat[bog_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				}
			}
			if($result_antal == 0) {
				$intet_resultat = "INSERT INTO skoleprojekter_lojstrup_soeg (ord) VALUES ('$_POST[soeg]')";
				$intetResultat = mysqli_query($db, $intet_resultat) or die (mysqli_error($db));
				echo "<p>Ingen resultater fundet</p>";
			}
		} else {
			//hvis der ikke er hakket nogle af skal den s�ge i alle felter
			$sql = "SELECT * FROM skoleprojekter_lojstrup_bog WHERE titel LIKE '%".$_POST['soeg']."%' OR
			forfatter LIKE '%".$_POST['soeg']."%' OR 
			isbn LIKE '%".$_POST['soeg']."%' OR
			forlag LIKE '%".$_POST['soeg']."%' OR
			sider LIKE '%".$_POST['soeg']."%' OR
			kort_omtale LIKE '%".$_POST['soeg']."%' ORDER BY tilfoj_dato DESC";
			$result = mysqli_query($db, $sql) or die (mysqli_error($db));
			$result_antal = mysqli_num_rows($result);
			while($resultat = mysqli_fetch_assoc($result)) {
				if($result_antal == 1) {
				?>
				<table>
					<tr>
						<td>
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['omtale']); ?></p>
							<a href="bog_omtale.php?bog_id=<?php echo $resultat[bog_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				} else if($result_antal == 2 || $result_antal == 3) {
				?>
				<table>
					<tr>
						<td>
							<img src="admin/<?php echo $resultat['billede']; ?>" width="124px" height="176px" alt="<?php echo $resultat['titel']; ?>" title="<?php echo $resultat['titel']; ?>" />
						</td>
						<td class="tekst_celle_hojre">
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Forfatter <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['forfatter']); ?></p>
							<p>Forlag <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['forlag']); ?></p>
							<p>ISBN nr. <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['isbn']); ?></p>
							<p>Antal sider <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['sider']); ?></p>
							<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['kort_omtale']); ?></p>
							<a href="bog_omtale.php?bog_id=<?php echo $resultat[bog_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				} else if($result_antal > 3) {
				?>
				<table>
					<tr>
						<td>
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Forfatter <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['forfatter']); ?></p>
							<p>Forlag <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['forlag']); ?></p>
							<p>ISBN nr. <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['isbn']); ?></p>
							<p>Antal sider <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['sider']); ?></p>
							<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['kort_omtale']); ?></p>
							<a href="bog_omtale.php?bog_id=<?php echo $resultat[bog_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				}
			}
			if($result_antal == 0) {
				$intet_resultat = "INSERT INTO skoleprojekter_lojstrup_soeg (ord) VALUES ('$_POST[soeg]')";
				$intetResultat = mysqli_query($db, $intet_resultat) or die (mysqli_error($db));
				echo "<p>Ingen resultater fundet</p>";
			}
		}
	} else if($_POST['soeg_valg'] == "arrangementer") {
		// ARRANGEMENTER
		unset($felter);
		if(isset($_POST['arrangement_titel'])) { $felter[] = " titel LIKE '%".$_POST['soeg']."%' "; }
		if(isset($_POST['taler'])) { $felter[] = " taler LIKE '%".$_POST['soeg']."%' "; }
		if(isset($_POST['dato'])) { $felter[] = " dato LIKE '%".$_POST['soeg']."%' "; }
		if(isset($_POST['omtale'])) { $felter[] = " tekst LIKE '%".$_POST['soeg']."%' "; }
		//tjekker om nogle af felterne er hakket af
		if(!empty($felter)) {
			$sql = "SELECT * FROM skoleprojekter_lojstrup_arrangement WHERE ";
			$sql.= implode(" OR ", $felter);
			$result = mysqli_query($db, $sql) or die (mysqli_error($db));
			$result_antal = mysqli_num_rows($result);
			while($resultat = mysqli_fetch_assoc($result)) {
				if($result_antal == 1) {
				?>
				<table>
					<tr>
						<td>
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['tekst']); ?></p>
							<a href="arrangement_omtale.php?arrangement_id=<?php echo $resultat[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				} else if($result_antal == 2 || $result_antal == 3) {
				?>
				<table>
					<tr>
						<td>
							<img src="admin/<?php echo $resultat['billede']; ?>" width="124px" height="176px" alt="<?php echo $resultat['titel']; ?>" title="<?php echo $resultat['titel']; ?>" />
						</td>
						<td class="tekst_celle_hojre">
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Taler <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['taler']); ?></p>
							<p>Sted <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['sted']); ?></p>
							<p>Dato <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['dato']); ?></p>
							<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['kort_tekst']); ?></p>
							<a href="arrangement_omtale.php?arrangement_id=<?php echo $resultat[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				} else if($result_antal > 3) {
				?>
				<table>
					<tr>
						<td>
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Taler <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['taler']); ?></p>
							<p>Sted <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['sted']); ?></p>
							<p>Dato <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['dato']); ?></p>
							<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['kort_tekst']); ?></p>
							<a href="arrangement_omtale.php?arrangement_id=<?php echo $resultat[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				}
			}
			if($result_antal == 0) {
				$intet_resultat = "INSERT INTO skoleprojekter_lojstrup_soeg (ord) VALUES ('$_POST[soeg]')";
				$intetResultat = mysqli_query($db, $intet_resultat) or die (mysqli_error($db));
				echo "<p>Ingen resultater fundet</p>";
			}
		//hvis der ikke er hakket nogle af skal den s�ge i alle felter
		} else {
			$sql = "SELECT * FROM skoleprojekter_lojstrup_arrangement WHERE titel LIKE '%".$_POST['soeg']."%' OR
			taler LIKE '%".$_POST['soeg']."%' OR 
			sted LIKE '%".$_POST['soeg']."%' OR
			dato LIKE '%".$_POST['soeg']."%' OR
			entre LIKE '%".$_POST['soeg']."%' OR
			kort_tekst LIKE '%".$_POST['soeg']."%' ORDER BY dato DESC";
			$result = mysqli_query($db, $sql) or die (mysqli_error($db));
			$result_antal = mysqli_num_rows($result);
			while($resultat = mysqli_fetch_assoc($result)) {
				if($result_antal == 1) {
				?>
				<table>
					<tr>
						<td>
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['tekst']); ?></p>
							<a href="arrangement_omtale.php?arrangement_id=<?php echo $resultat[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				} else if($result_antal == 2 || $result_antal == 3) {
				?>
				<table>
					<tr>
						<td>
							<img src="admin/<?php echo $resultat['billede']; ?>" width="124px" height="176px" alt="<?php echo $resultat['titel']; ?>" title="<?php echo $resultat['titel']; ?>" />
						</td>
						<td class="tekst_celle_hojre">
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Taler <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['taler']); ?></p>
							<p>Sted <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['sted']); ?></p>
							<p>Dato <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['dato']); ?></p>
							<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['kort_tekst']); ?></p>
							<a href="arrangement_omtale.php?arrangement_id=<?php echo $resultat[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				} else if($result_antal > 3) {
				?>
				<table>
					<tr>
						<td>
							<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['titel']); ?></h1>
							<p>Taler <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['taler']); ?></p>
							<p>Sted <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['sted']); ?></p>
							<p>Dato <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['dato']); ?></p>
							<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat['kort_tekst']); ?></p>
							<a href="arrangement_omtale.php?arrangement_id=<?php echo $resultat[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
						</td>
					</tr>
				</table>
				<hr/>
				<?php
				}	
			}
			if($result_antal == 0) {
				$intet_resultat = "INSERT INTO lojstrup_soeg (ord) VALUES ('$_POST[soeg]')";
				$intetResultat = mysqli_query($db, $intet_resultat) or die (mysqli_error($db));
				echo "<p>Ingen resultater fundet</p>";
			}
		}
	}
}
?>