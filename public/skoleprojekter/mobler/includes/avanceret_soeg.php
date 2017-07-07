<?php
//tjekker om alle designere er valgt
if($_POST['designer'] == "alle") {
	$sqlSerie = mysqli_query($db, "SELECT serie_id FROM skoleprojekter_cmk_mobelserie WHERE navn='$_POST[mobelserie]'");
	$dataSerie = mysqli_fetch_assoc($sqlSerie);
	$avanceret_soeg = mysqli_query($db, "SELECT mobel_id, navn, beskrivelse FROM skoleprojekter_cmk_mobel WHERE serie_id LIKE '%".$dataSerie['serie_id']."%' AND 
	design_aar >= $_POST[min_ar] AND design_aar <= $_POST[max_ar] AND pris >= $_POST[min_pris] AND pris <= $_POST[max_pris] ORDER BY navn");
} else {
	$sqlSerie = mysqli_query($db, "SELECT navn FROM skoleprojekter_cmk_mobelserie WHERE navn='$_POST[mobelserie]'");
	$dataSerie = mysqli_fetch_assoc($sqlSerie);
	$sqlDesigner = mysqli_query($db, "SELECT designer_id, navn FROM skoleprojekter_cmk_designer WHERE designer_id='$_POST[designer]'");
	$dataDesigner = mysqli_fetch_assoc($sqlDesigner);
	$avanceret_soeg = mysqli_query($db, "SELECT mobel_id, navn, beskrivelse FROM skoleprojekter_cmk_mobel WHERE designer_id='$dataDesigner[designer_id]' AND serie_id LIKE '%".$dataSerie['serie_id']."%' AND 
	design_aar >= $_POST[min_ar] AND design_aar <= $_POST[max_ar] AND pris >= $_POST[min_pris] AND pris <= $_POST[max_pris] ORDER BY navn");
}
if(mysqli_num_rows($avanceret_soeg) != 0) {
	while($data_avanceret_soeg = mysqli_fetch_assoc($avanceret_soeg)) {
		$soeg_resultat = "ja";
		$billede = mysqli_query($db, "SELECT sti, titel FROM skoleprojekter_cmk_billeder WHERE mobel_id='$data_avanceret_soeg[mobel_id]'");
		$dataBillede = mysqli_fetch_assoc($billede);
	?>
	<div class="resultat_soeg">
		<a href="index.php?page=beskrivelse&amp;mobel_id=<?php echo $data_avanceret_soeg['mobel_id']; ?>">
		<table>
			<tr>
				<td class="resultat_celle">
					<img src="billeder/mobler/<?php echo $dataBillede['sti']; ?>" alt="<?php echo $dataBillede['titel']; ?>" />
				</td>
				<td>
					<h1 class="resultat_overskrift"><?php echo $data_avanceret_soeg['navn']; ?></h1>
					<?php
					if(strlen($data_avanceret_soeg['beskrivelse']) > 187) {
						echo "<p>".substr($data_avanceret_soeg['beskrivelse'], 0, 183)." ...</p>";
					} else {
						echo "<p>".$data_avanceret_soeg['beskrivelse']."</p>";
					}
					?>
				</td>
			</tr>
		</table>
		</a>
	</div>
	<?php 
	}
} else {
	echo "<p id='ingen_resultater'>Der er desv&aelig;rre ikke nogen emner der matcher dine kriterier.<br/>
		Vi anbefaler at du udvider din s&oslash;gning og pr&oslash;ver igen.</p>";
}
?>