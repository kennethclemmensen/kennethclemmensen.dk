<?php
$side = $_GET['page'];
$galleri_id = $_GET['galleri_id'];
include("db.php");
function style($var) {
    global $side;
    if($side == $var) {
        return "class='menu_link'";
    }
}
function style_galleri($var) {
    global $galleri_id;
    if($galleri_id == $var) {
        return "class='link'";
    }
}
function getMenu($parent = 0) {
	global $db;
	$sql = "SELECT * FROM skoleprojekter_netavisen_menu WHERE parent='$parent'";
	$result = mysqli_query($db, $sql);
	if(mysqli_num_rows($result) == 0) {
		return null;
	}
	static $output;
	
	$output.= "<ul>\n";
	while($row = mysqli_fetch_assoc($result)) {
		$output.= "<li><a href='redaktor.php?page=rettet_kategori&amp;kategori_id=".$row['menu_id']."'>".$row['titel']."</a></li>\n";
		$parent = $row['menu_id'];
		getMenu($parent);
	}
	$output.= "</ul>\n";
	return $output;
}
function getKategori($parent = 0) {
	global $db;
	$sql = "SELECT * FROM skoleprojekter_netavisen_menu WHERE parent='$parent'";
	$result = mysqli_query($db, $sql);
	if(mysqli_num_rows($result) == 0) {
		return null;
	}
	
	$output = "<ul>\n";
	while($row = mysqli_fetch_assoc($result)) {
		$output.= "<li><a href='index.php?page=".$row[titel]."'>".$row['titel']."</a></li>\n";
		$parent = $row['menu_id'];
	}
	$output.= "</ul>\n";
	return $output;
}
?>