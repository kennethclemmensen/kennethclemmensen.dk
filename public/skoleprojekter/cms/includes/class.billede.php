<?php
class billede{

	private $mappe, $billede, $navn,$egenskaber;

	public function __construct($mappe){
		$this->mappe = $mappe;
	}

	/**
	 * Form-metode. 
	 *
	 * @return HMTL-string
	 */
	public function form(){
		if ($_FILES['billede']['tmp_name'] != "") {
			$this->billede = $_FILES['billede'];
			if ($_POST['navn'] != "") {
				$this->navn = $_POST['navn'];
			} else {
				$this->navn = $this->billede['name'];
			}
		} else {
			$tekst = '<form action="" method="post" enctype="multipart/form-data">
				Fil: <input type="file" name="billede"><br />
				Navn: <input type="text" name="navn"><br />
				<input type="submit" name="upload" value="Send">
				</form>';
			return $tekst;
		}
	}

	/**
	 * Sætter egenskaben billede. Modtager $_FILES['billede'] eller tilsvarende array.
	 *
	 * @param array $files
	 */
	public function setBillede($files){
		$this->billede = $files;
		$this->navn=$files['name'];
	}

	/**
	 * VIGTIGT: denne metode skal kaldes før funktionen upload
	 *
	 * @param  string $navn
	 */
	public function setNavn($navn){
		//finder filendelsen
		$array=explode('.', $this->billede['name']);
		//print_r($array);
		$antal=count($array)-1;
		$endelse=$array[$antal];

		$this->navn=$navn.'.'.$endelse;

	}
	/**
	 * Lægger billedet på serveren og returnerer navnet.
	 *
	 * @return string
	 */
	public function upload(){
		if ($this->billede != "") {
			//print_r($this->billede);
			$billednavn = time() . "_" . $this->navn;
			copy($this->billede['tmp_name'], $this->mappe.$billednavn);
			return $billednavn;
		} else {
			return false;
		}
	}

	/**
	 * Returnerer billedet pakket ind i html.
	 *
	 * @param string $navn
	 * @return HTML-string
	 */
	public function visBillede($navn){
		$res = "<img src='". $this->mappe . $navn . "'>";
		return $res;
	}

	/**
	 * Funktionen genererer en thumbnail af et billede i 100x100 px, hvis ikke andet medsendes
	 *
	 * @param string $navn
	 * @param int $bredde
	 * @param int $hoejde
	 */
	public function lavThumb($navn, $bredde='100', $hoejde='100'){
		$this->egenskaber=@getimagesize($this->mappe.$navn);
		//print_r($this->egenskaber);

		//finder typen
		$type=$this->egenskaber[2];


		//gif
		if($type==1){
			$nytNavn = $this->mappe . "thumb_" . $navn;
			copy($this->mappe.$navn, $nytNavn);
			$thumbnail = imagecreatetruecolor($bredde, $hoejde);
			$billede = imagecreatefromgif($nytNavn);
			$billedstr = getimagesize($nytNavn);
			imagecopyresampled($thumbnail, $billede, 0, 0, 0, 0, $newWidth, $newHeight, $billedstr[0], $billedstr[1]);
			imagegif($thumbnail, $nytNavn);
		}

		//jpeg
		if($type==2){
			$nytNavn = $this->mappe . "thumb_" . $navn;
			copy($this->mappe.$navn, $nytNavn);
			$thumbnail = imagecreatetruecolor($bredde, $hoejde);
			$billede = imagecreatefromjpeg($nytNavn);
			$billedstr = getimagesize($nytNavn);
			imagecopyresampled($thumbnail, $billede, 0, 0, 0, 0, $newWidth, $newHeight, $billedstr[0], $billedstr[1]);
			imagejpeg($thumbnail, $nytNavn);
		}

		//png
		if($type==3){
			$nytNavn = $this->mappe . "thumb_" . $navn;
			copy($this->mappe.$navn, $nytNavn);
			$thumbnail = imagecreatetruecolor($bredde, $hoejde);
			$billede = imagecreatefrompng($nytNavn);
			$billedstr = getimagesize($nytNavn);
			imagecopyresampled($thumbnail, $billede, 0, 0, 0, 0, $newWidth, $newHeight, $billedstr[0], $billedstr[1]);
			imagepng($thumbnail, $nytNavn);
		}


	}

	/**
	 * Genererer et skaleret thumbnail af et billede - bredden og hoejden styres af maxVaerdier
	 *
	 * @param string $navn
	 * @param int $maxBredde
	 * @param int $maxHoejde
	 */
	public function lavProportionalThumb($navn, $maxBredde='100', $maxHoejde='100'){
		$this->egenskaber=@getimagesize($this->mappe.$navn);
		//print_r($this->egenskaber);

		//udtrækker højde og bredde
		$width=$this->egenskaber[0];
		$height=$this->egenskaber[1];

		//finder typen jpg gif png
		$type=$this->egenskaber[2];


		if($width>=$height){
			$newWidth=floor($width*($maxBredde/$width));
			$newHeight=floor($height*($maxBredde/$width));
		}else{
			$newHeight=floor($height*($maxHoejde/$height));
			$newWidth=floor($width*($maxBredde/$width));
		}
		$nytNavn = $this->mappe . "thumb_" . $navn;
		copy($this->mappe.$navn, $nytNavn);
		$thumbnail = imagecreatetruecolor($newWidth, $newHeight);

		//gif
		if($type==1){
			$billede = imagecreatefromgif($nytNavn);
			$billedstr = getimagesize($nytNavn);
			imagecopyresampled($thumbnail, $billede, 0, 0, 0, 0, $newWidth, $newHeight, $billedstr[0], $billedstr[1]);
			imagegif($thumbnail, $nytNavn);
		}

		//jpegbillede
		if($type==2){
			$billede = imagecreatefromjpeg($nytNavn);
			$billedstr = getimagesize($nytNavn);
			imagecopyresampled($thumbnail, $billede, 0, 0, 0, 0, $newWidth, $newHeight, $billedstr[0], $billedstr[1]);
			imagejpeg($thumbnail, $nytNavn);
		}

		//png
		if($type==3){
			$billede = imagecreatefrompng($nytNavn);
			$billedstr = getimagesize($nytNavn);
			imagecopyresampled($thumbnail, $billede, 0, 0, 0, 0, $newWidth, $newHeight, $billedstr[0], $billedstr[1]);
			imagepng($thumbnail, $nytNavn);
		}
	}

	/**
	 * Udskriver direkte (ingen return) et galleri med alle filer i mappen. Thumbs vises som links til originalbilledet. Denne Funktion er kun til test IKKE PRODUKTION
	 *
	 * @param int $visNavn
	 * @param int $visSlet
	 */
	public function visGalleri($visNavn=0, $visSlet=0){
		$handle = opendir($this->mappe);
		while (false !== ($fil = readdir($handle))) {
			if ($fil != "." && $fil != ".." && stristr($fil, "thumb_")) {
				$vis = $this->mappe . ltrim($fil, "thumb_");
				$billedstr = getimagesize($vis);
				?>
				<a onclick="window.open ('<?php echo $vis;?>',null,'height=<?php echo $billedstr[1]?>,width=<?php echo $billedstr[0]?>')"><img src='<?php echo $this->mappe.$fil;?>'></a>
				<?php
				if ($visSlet != 0) {
					echo "<a href='?slet=$fil'>SLET</a>";
				}
				?>
				<br />
				<?php
				if($visNavn != 0){
					echo substr($fil, 17) . "<br />";
				}
			}
		}
	}

	/**
	 * Sletter et billede og den tilhørende thumbnail. Bør udvides med check for eksistens
	 *
	 * @param string $navn
	 */
	public function delete($navn){
		unlink($this->mappe . $navn);
		$original = ltrim($navn, "thumb_");
		unlink($this->mappe . $original);
	}

	/**
	 * Sender browseren til den side der medsendes i param $sti
	 *
	 * @param string $sti
	 */
	public function genlaes($sti){
		?>
		<script type="text/javascript">
		window.location="<?php echo $sti?>";
		</script>
		<?php	}
}
?>



<?php
/*
//EKSEMPEL PÅ BRUG AF KLASSEN:

//include("class.billede.php");

$mitBillede = new billede("billed/");

//echo $mitBillede->form();
$mitBillede->setBillede($_FILES['billede']);
$nytBillede = $mitBillede->upload();

if($nytBillede != ""){
//$mitBillede->lavProportionalThumb($nytBillede);
$mitBillede->lavThumb($nytBillede);
echo $mitBillede->visBillede("thumb_".$nytBillede);
} else {
$mitBillede->visGalleri(1,1);
}

if ($_GET['slet'] != "") {
$mitBillede->delete($_GET['slet']);
$mitBillede->genlaes("class.billede.php");
}

*/
?>

<!--
<form action="" method="post" enctype="multipart/form-data">
				Fil: <input type="file" name="billede"><br />
				<input type="submit" name="upload" value="Send">
				</form>
-->