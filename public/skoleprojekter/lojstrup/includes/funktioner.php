<?php
function vis_arrangement_venstre($row) {
	?>
	<table>
		<tr>
			<td class="tekst_celle_venstre">
				<h1><?php echo $row['titel']; ?></h1>
				<p><?php echo $row['kort_tekst']; ?></p>
				<a href="arrangement_omtale.php?arrangement_id=<?php echo $row[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
			</td>
			<td>
				<img src="admin/<?php echo $row['billede']; ?>" width="176px" height="176px" alt="<?php echo $row['titel']; ?>" title="<?php echo $row['titel']; ?>" />
			</td>
		</tr>
	</table>
<?php
}
function vis_arrangement_hojre($row) {
?>
	<table>
		<tr>
			<td>
				<img src="admin/<?php echo $row['billede']; ?>" width="124px" height="176px" alt="<?php echo $row['titel']; ?>" title="<?php echo $row['titel']; ?>" />
			</td>
			<td class="tekst_celle_hojre">
				<h1><?php echo $row['titel']; ?></h1>
				<p><?php echo $row['kort_tekst']; ?></p>
				<a href="arrangement_omtale.php?arrangement_id=<?php echo $row[arrangement_id]; ?>">:: l&aelig;s mere ::</a>
			</td>
		</tr>
	</table>
<?php
}
function vis_bog_venstre($row) {
?>
	<table>
		<tr>
			<td class="tekst_celle_venstre">
				<h1><?php echo $row['titel']; ?></h1>
				<p><?php echo $row['kort_omtale']; ?></p>
				<a href="bog_omtale.php?bog_id=<?php echo $row[bog_id]; ?>">:: l&aelig;s mere ::</a>
			</td>
			<td>
				<img src="admin/<?php echo $row['billede']; ?>" width="124px" height="176px" alt="<?php echo $row['titel']; ?>" title="<?php echo $row['titel']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="karakter_celle">
				<?php vis_stjerne($row[bog_id]); ?>
			</td>
			<td>
			</td>
		</tr>
	</table>
<?php
}
function vis_bog_hojre($row) {
?>
	<table>
		<tr>
			<td>
				<img src="admin/<?php echo $row['billede']; ?>" width="124px" height="176px" alt="<?php echo $row['titel']; ?>" title="<?php echo $row['titel']; ?>" />
			</td>
			<td class="tekst_celle_hojre">
				<h1><?php echo $row['titel']; ?></h1>
				<p><?php echo $row['kort_omtale']; ?></p>
				<a href="bog_omtale.php?bog_id=<?php echo $row[bog_id]; ?>">:: l&aelig;s mere ::</a>
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td class="karakter_celle_hojre">
				<?php vis_stjerne($row[bog_id]); ?>
			</td>
		</tr>
	</table>
<?php
}
function vis_stjerne($bog_id) {
	global $db;
	$sql_afstemning = "SELECT stjerne FROM skoleprojekter_lojstrup_afstemning_bog WHERE bog_id='$bog_id'";
	$result_afstemning = mysqli_query($db, $sql_afstemning);
	$antal_afstemning = mysqli_num_rows($result_afstemning);
	echo "<p>L&aelig;sernes bed&oslash;mmelse</p>";
	
	if($antal_afstemning != 0) {
		$sql_total = "SELECT sum(stjerne) FROM skoleprojekter_lojstrup_afstemning_bog WHERE bog_id='$bog_id'";
		$result_total = mysqli_query($db,$sql_total);
		$row_total = mysqli_fetch_assoc($result_total);
		$gennemsnit = round($row_total['sum(stjerne)'] / $antal_afstemning);
		
		for($i = 1; $i <= $gennemsnit; $i++) {
			echo "<img src='billeder/extra/star.jpg' class='afstemning' />";
		}
	}
}
?>