<?php
/**

 * // EKSEMPEL PÅ BRUG

 * $validering = new validering();
 * $validering->mail('annaoglotte@');
 * $validering->postnr('90');
 * $validering->url('http://ninjasødoku.com');
 * $validering->heltal(14.5);
 * $validering->maxTegn('hej',2);
 * $validering->minTegn('hej',6);
 * $validering->tal('Nå');
 * $validering->tom($test);
 * $validering->strongPassword('AASDF123');

 * 
 * //print_r kun til test
 * echo "<pre>";
 * print_r($validering->msg());
 * echo "</pre>";
 * 

 * //returnerer false hvis der ingen fejl er
 * if ($validering->msg()) {
 * 	$array=$validering->msg();
 * 	foreach($array as $value){
 * 		echo $value."<br />";
 * 	}
 * } else {
 * 	//Ingen fejl, indsæt i DB eller...
 * 	echo "ingen fejl";
 * }
 */


class validering{
	private $error=false;

	public function __construct(){}
/**
 * Denne funktion tjekker om en email adresse er gyldig
 *
 * @param string $mail
 * @param string $fejl
 * @return bool
 */
	public function  mail($mail, $fejl='Den anførte email adresse er ugyldig!'){
		if (preg_match("/[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$/", $mail)) {
			return true;
		} else {
			$this->error[]=$fejl;
			return false;
		}
	}

    /**
     * Denne funktion tjekker om input er et heltal
     *
     * @param string $var
     * @param string $fejl
     * @return bool
     */
	public function heltal($var, $fejl='Det anførte tal er ikke et heltal.'){
		if (is_int($var)) {
			return true;
		} else {
			$this->error[]=$fejl;
			return false;
		}
	}

    /**
     * Denne funktion tjekker om værdien er tom
     *
     * @param string $var
     * @param string $fejl
     * @return bool
     */
    public function tom($var,$fejl='værdien er tom.'){
		if (!empty($var)) {
			return true;
		} else {
			$this->error[]=$fejl;
			return false;
		}
	}

    /**
     * Denne funktion tjekker om et postnr er gyldig til enten danmark eller færørerne
     *
     * @param string $var
     * @param string $fejl
     * @return bool
     */
	public function postnr($var, $fejl='Det anførte postnr er ugyldigt!'){
		if (preg_match("/^[0-9]{3,4}$/",$var)) {
			return true;
		} else {
			$this->error[]=$fejl;
			return false;
		}
	}
    
     /**
     * Denne funktion tjekker om input er et tal
     *
     * @param string $var
     * @param string $fejl
     * @return bool
     */
        public function tal($var, $fejl='Værdien er ikke et tal.'){
		if (is_numeric($var)) {
			return true;
		} else {
			$this->error[]=$fejl;
			return false;
		}
	}
    
 
     /**
     * Denne funktion tjekker om input er en gyldig URL
     *
     * @param string (URL) $var
     * @param string $fejl
     * @return bool
     */
     public function url($var, $fejl='Den anførte Url er ugyldig'){
		if (preg_match("/^[a-zA-Z]+[:\/\/]+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+$/i", $var)){
			return true;
		} else {
			$this->error[]=$fejl;
			return false;
		}
	}

    /**
     * Denne funktion tjekker om input er større end max antal tegn
     *
     * @param string $var
     * @param string $fejl
     * @return bool
     */
     public function maxTegn($var, $max, $fejl='Det er for mange tegn i det angivne'){
		if (strlen($var) <= $max) {
			return true;
		} else {
			$this->error[]=$fejl;
			return false;
		}
	}

    /**
     * Denne funktion tjekker om input er mindre end min antal tegn
     *
     * @param string $var
     * @param string $fejl
     * @return bool
     */
	public function minTegn($var, $min, $fejl='Der er for få tegn i det angivene'){
		if (strlen($var) >= $min) {
			return true;
		} else {
			$this->error[]=$fejl;
			return false;
		}
	}

    /**
     * Denne funktion tjekker om input er et stærkt password dvs at det indeholder storre og små bogstaver samt heltal
     *
     * @param string $password
     * @param string $fejl
     * @return bool
     */
	public function strongPassword($password, $fejl='Password skal indeholde tal samt både små og store bogstaver, og være mindst 8 tegn langt'){
		if (preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,1000}$/", $password)) {
			return true;
		} else {
			$this->error[]=$fejl;
			return false;
		}

	}
    /**
     * Denne funktion returnerer evt fejl som et array
     *
     * @return array
     */
    public function msg(){

		return $this->error;
	}


}
?>