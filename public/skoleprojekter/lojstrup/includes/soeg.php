<table>
	<tr>
		<td>
			<form action="index.php?page=soeg" method="post" name="soeg_formular" onsubmit="valider_soeg_formular();">
				<p class="soeg_tekst"><label for="soeg">S&oslash;g</label></p><input type="text" name="soeg" class="soeg_felt" value="<?php echo $_POST['soeg']; ?>" />
				<input type="submit" value="S&oslash;g" class="soeg_knap" />
			</form>
		</td>
		<td>
			<a href="index.php?page=avanceret_soeg" class="soeg_link">Avanceret s&oslash;g</a>
		</td>
	</tr>
</table>
<hr/>
<?php
	if(isset($_POST['soeg']) && !empty($_POST['soeg'])) {
		$bog_sql = "SELECT * FROM skoleprojekter_lojstrup_bog WHERE titel LIKE '%".$_POST['soeg']."%' OR
		forfatter LIKE '%".$_POST['soeg']."%' OR 
		isbn LIKE '%".$_POST['soeg']."%' OR
		forlag LIKE '%".$_POST['soeg']."%' OR
		sider LIKE '%".$_POST['soeg']."%' OR
		kort_omtale LIKE '%".$_POST['soeg']."%' ORDER BY tilfoj_dato DESC";
		$bog_resultat = mysqli_query($db, $bog_sql) or die (mysqli_error($db));
		$bog_antal = mysqli_num_rows($bog_resultat);
		if($bog_antal == 1) {
			while($resultat_bog = mysqli_fetch_assoc($bog_resultat)) {
			?>
			<table>
				<tr>
					<td>
						<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['titel']); ?></h1>
						<p>Omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['omtale']); ?></p>
						<a href="bog_omtale.php?bog_id=<?php echo $resultat_bog[bog_id]; ?>">:: l&aelig;s mere ::</a>
					</td>
				</tr>
			</table>
			<hr/>
		<?php
			}
		} else if($bog_antal == 2 || $bog_antal == 3) {
			while($resultat_bog = mysqli_fetch_assoc($bog_resultat)) {
			?>
			<table>
				<tr>
					<td>
						<img src="admin/<?php echo $resultat_bog['billede']; ?>" width="124px" height="176px" alt="<?php echo $resultat_bog['titel']; ?>" title="<?php echo $resultat_bog['titel']; ?>" />
					</td>
					<td class="tekst_celle_hojre">
						<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['titel']); ?></h1>
						<p>Forfatter <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['forfatter']); ?></p>
						<p>Forlag <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['forlag']); ?></p>
						<p>ISBN nr. <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['isbn']); ?></p>
						<p>Antal sider <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['sider']); ?></p>
						<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['kort_omtale']); ?></p>
						<a href="bog_omtale.php?bog_id=<?php echo $resultat_bog[bog_id]; ?>">:: l&aelig;s mere ::</a>
					</td>
				</tr>
			</table>
			<hr/>
			<?php
			}
		} else if($bog_antal > 3) {
			while($resultat_bog = mysqli_fetch_assoc($bog_resultat)) {
			?>
			<table>
				<tr>
					<td>
						<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['titel']); ?></h1>
						<p>Forfatter <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['forfatter']); ?></p>
						<p>Forlag <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['forlag']); ?></p>
						<p>ISBN nr. <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['isbn']); ?></p>
						<p>Antal sider <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['sider']); ?></p>
						<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_bog['kort_omtale']); ?></p>
						<a href="bog_omtale.php?bog_id=<?php echo $resultat_bog[bog_id]; ?>">:: l&aelig;s mere ::</a>
					</td>
				</tr>
			</table>
			<hr/>
			<?php
			}
		} else {
			$intet_resultat = "INSERT INTO skoleprojekter_lojstrup_soeg (ord) VALUES ('$_POST[soeg]')";
			$intetResultat = mysqli_query($db, $intet_resultat) or die (mysqli_error($db));
			echo "<p>Ingen resultater fundet i b&oslash;ger</p>";
			echo "<hr/>";
		}
		
		$arrangement_sql = "SELECT * FROM skoleprojekter_lojstrup_arrangement WHERE titel LIKE '%".$_POST['soeg']."%' OR
		taler LIKE '%".$_POST['soeg']."%' OR 
		sted LIKE '%".$_POST['soeg']."%' OR
		dato LIKE '%".$_POST['soeg']."%' OR
		entre LIKE '%".$_POST['soeg']."%' OR
		kort_tekst LIKE '%".$_POST['soeg']."%' ORDER BY dato DESC";
		$arrangement_resultat = mysqli_query($db, $arrangement_sql) or die (mysqli_error($db));
		$arrangement_antal = mysqli_num_rows($arrangement_resultat);
		if($arrangement_antal == 1) {
			while($resultat_arrangement = mysqli_fetch_assoc($arrangement_resultat)) {
			?>
			<table>
				<tr>
					<td>
						<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['titel']); ?></h1>
						<p>Omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['tekst']); ?></p>
						<a href="arrangement_omtale.php?arrangement_id=<?php echo $resultat_arrangement[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
					</td>
				</tr>
			</table>
			<hr/>
			<?php
			}	
		} else if($arrangement_antal == 2 || $arrangement_antal == 3) {
			while($resultat_arrangement = mysqli_fetch_assoc($arrangement_resultat)) {
			?>
			<table>
				<tr>
					<td>
						<img src="admin/<?php echo $resultat_arrangement['billede']; ?>" width="124px" height="176px" alt="<?php echo $resultat_arrangement['titel']; ?>" title="<?php echo $resultat_arrangement['titel']; ?>" />
					</td>
					<td class="tekst_celle_hojre">
						<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['titel']); ?></h1>
						<p>Taler <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['taler']); ?></p>
						<p>Sted <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['sted']); ?></p>
						<p>Dato <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['dato']); ?></p>
						<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['kort_tekst']); ?></p>
						<a href="arrangement_omtale.php?arrangement_id=<?php echo $resultat_arrangement[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
					</td>
				</tr>
			</table>
			<hr/>
			<?php
			}
		} else if($arrangement_antal > 3) {
			while($resultat_arrangement = mysqli_fetch_assoc($arrangement_resultat)) {
			?>
			<table>
				<tr>
					<td>
						<img src="admin/<?php echo $resultat_arrangement['billede']; ?>" width="124px" height="176px" alt="<?php echo $resultat_arrangement['titel']; ?>" title="<?php echo $resultat_arrangement['titel']; ?>" />
					</td>
					<td class="tekst_celle_hojre">
						<h1><?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['titel']); ?></h1>
						<p>Taler <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['taler']); ?></p>
						<p>Sted <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['sted']); ?></p>
						<p>Dato <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['dato']); ?></p>
						<p>Kort omtale <?php echo str_ireplace($_POST['soeg'], "<b>".$_POST['soeg']."</b>", $resultat_arrangement['kort_tekst']); ?></p>
						<a href="arrangement_omtale.php?arrangement_id=<?php echo $resultat_arrangement[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
					</td>
				</tr>
			</table>
			<hr/>
			<?php
			}
			//titel, dato, tid, sted, navn og indledende omtale
		} else {
			$intet_resultat = "INSERT INTO skoleprojekter_lojstrup_soeg (ord) VALUES ('$_POST[soeg]')";
			$intetResultat = mysqli_query($db, $intet_resultat) or die (mysqli_error($db));
			echo "<p>Ingen resultater fundet i arrangementer</p>";
			echo "<hr/>";
		}
	}
?>