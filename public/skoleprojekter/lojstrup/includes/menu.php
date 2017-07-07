<?php
$side = $_GET['page'];
function style($var) {
	global $side;
	if($side == $var) {
		echo "style='color: orange'";
	}
}
?>
<ul>
	<li <?php style("forside"); ?>><a href="index.php?page=forside" <?php style("forside"); ?>>FORSIDEN</a></li>
	<li <?php style("nye_boger"); ?>><a href="index.php?page=nye_boger" <?php style("nye_boger"); ?>>NYE B&Oslash;GER</a></li>
	<?php
	if($side == "nye_boger" || $side == "voksne" || $side == "unge" || $side == "born") {
	?>
		<li class="undermenu" <?php style("voksne"); ?>><a href="index.php?page=voksne" <?php style("voksne"); ?>>VOKSNE</a></li>
		<li class="undermenu" <?php style("unge"); ?>><a href="index.php?page=unge" <?php style("unge"); ?>>UNGE</a></li>
		<li class="undermenu" <?php style("born"); ?>><a href="index.php?page=born" <?php style("born"); ?>>B&Oslash;RN</a></li>
	<?php
	}
	?>
	<li <?php style("arrangementer"); ?>><a href="index.php?page=arrangementer" <?php style("arrangementer"); ?>>ARRANGEMENTER</a></li>
	<li <?php style("reglement"); ?>><a href="index.php?page=reglement" <?php style("reglement"); ?>>REGLEMENT</a></li>
	<li <?php style("kontakt"); ?>><a href="index.php?page=kontakt" <?php style("kontakt"); ?>>KONTAKT</a></li>
	<li <?php style("soeg"); style("avanceret_soeg"); ?>><a href="index.php?page=soeg" <?php style("soeg"); style("avanceret_soeg"); ?>>S&Oslash;G</a></li>
</ul>