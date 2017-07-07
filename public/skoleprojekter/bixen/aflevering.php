<!DOCTYPE html PUBLIC 
"-//W3C//DTD XHTML 1.0 Transitional//DK" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Language" content="da" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<body>
<?
if ($_GET['mappe'] != '') {
	$mappe = $_GET['mappe'];
} else {
	$mappe = ".";
}
$handle = opendir($mappe);
while (false !== ($file = readdir($handle))) {
	if ($file != "." && $file != "..") {
		if(is_dir($mappe."/".$file) == true)
			$sub = 1;
 		$fileArray[] = array('navn' => $file,'subdir' => $sub); 
 		$sub = 0;
	} 
}
if(!empty($fileArray)){
	foreach ($fileArray as $key => $row) {
		$navn[$key]  = $row['navn'];
		$subdir[$key] = $row['subdir'];
	}
	array_multisort($subdir, SORT_DESC, $navn, SORT_ASC, $fileArray);
}
?>
<table border="1">
<tr><th>resultat</th><th>kode</th></tr>
<?
foreach ($fileArray as $k => $v){
	if($v[subdir] == 1){ 
			echo "<tr><td>";
			echo "<a class='folder_type' href='?mappe=".$mappe."/".$v['navn']."'>";
			echo $v['navn'] . "</a>";
			echo "</td><td></td></tr>";
	} else {
		echo "<tr><td><a href='" .$mappe ."/". $v['navn'] . "' target='_blank'>". $v['navn'] ."</a></td>";
		echo "<td><a href='highlight.php?fil=" . $mappe . "/" . $v['navn'] . "' target='_blank'>". $v['navn'] ."</a></td></tr>";
	}
}	
?>
</table>
</body>
</html>