<?php
#################################################
# Denne class opretter brugere, logger ind + ud,#
# og kan rette brugernavne og passwords osv..	#
# se eksempel i bunden..				        #
#################################################
class bruger {
	private $brugernavn, $kodeord;
	public $tabelNavn;//navenet p� DBtabellen

	/**
	 * Medsender DB tabelnavnet og logger brugeren ind hvis han eksisterer i DB
	 *
	 * @param string $tabelNavn
	 */
	public function __construct($forbindelse, $tabelNavn){
		$this->tabelNavn=$tabelNavn;
		if(isset($_SESSION["brugernavn"]) && isset($_SESSION["kodeord"])){
			$this->log_ind($forbindelse, $_SESSION["brugernavn"], $_SESSION["kodeord"]);
		}
	}
	
	/**
	 * metoden logger brugeren ind
	 *
	 * @param string $brugernavn
	 * @param string $kodeord
	 * @return bool
	 */
	public function log_ind($forbindelse, $brugernavn, $kodeord){
		$bruger_slash = addslashes($brugernavn);
		$kode_slash = addslashes($kodeord);
		$sql="SELECT * FROM ".$this->tabelNavn." WHERE email = '$bruger_slash' AND password = '$kode_slash'";
		$foresp = mysqli_query($forbindelse, $sql);
		if(mysqli_num_rows($foresp) == 1){
			$this->brugernavn = $brugernavn;
			$this->kodeord = $kodeord;
			$_SESSION["bruger"] = true;
			$_SESSION["brugernavn"] = $this->brugernavn;
			$_SESSION["kodeord"] = $this->kodeord;
			$login = mysqli_query($forbindelse, "UPDATE skoleprojekter_forum_bruger SET logget_ind='1', sidste_login='".time()."' WHERE email='$_SESSION[brugernavn]'");
			return true;
		} else {
			$this->log_ud($forbindelse);
			return false;
		}
	}

	/**
	 * metoden logger brugeren ud
	 *
	 * @return bool
	 */
	public function log_ud(){
		if($this->is_logged_in()){
			//mysqli_query($forbindelse, "UPDATE forum_bruger SET logget_ind='0' WHERE email='$_SESSION[brugernavn]'");
			unset($this->brugernavn);
			unset($_SESSION["brugernavn"]);
			unset($this->kodeord);
			unset($_SESSION["kodeord"]);
			unset($_SESSION["bruger"]);
			return true;
		} else{
			return false;
		}
	}

	/**
	 * Tester om brugeren er logged ind
	 *
	 * @return bool
	 */
	public function is_logged_in(){
		if(isset($this->brugernavn) && isset($this->kodeord)){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Metoden opretter en ny bruger hvis brugernavnet ikke er optaget
	 *
	 * @param string $brugernavn
	 * @param string $kodeord
	 * @return bool
	 */
	public function opret($forbindelse, $brugernavn, $kodeord, $navn){
		$bruger_slash = addslashes($brugernavn);
		$kode_slash = addslashes($kodeord);
		$navn_slash = addslashes($navn);
		$foresp = mysqli_query($forbindelse, "SELECT * FROM ". $this->tabelNavn ." WHERE email = '$bruger_slash'");
		if(mysqli_num_rows($foresp) != 0){
			return false;
		} else {
			mysqli_query($forbindelse, "INSERT INTO ". $this->tabelNavn ." (email, password, navn, logget_ind, sidste_login, tagline) ".
			"VALUES ('$bruger_slash', '$kode_slash', '$navn_slash', '1', '".time()."', '0')");
			$_SESSION["brugernavn"] = $bruger_slash;
			$_SESSION["kodeord"] = $kode_slash;
			$_SESSION["bruger"] = true;
			$besked = "Velkommen til ?-forum.\nDit brugernavn er din emailadresse og dit password er ".$kode_slash;
			$sendMail = mail($bruger_slash, "Loginoplysninger", $besked);
			return true;
		}
	}

	/**
	 * Metoden sletter brugeren i DB
	 *
	 * @param string $brugernavn
	 * @return bool
	 */
	public function fjern($forbindelse, $brugernavn){
		$bruger_slash = addslashes($brugernavn);
		$sql="SELECT * FROM  ". $this->tabelNavn ." WHERE email = '$bruger_slash'";
		$foresp = mysqli_query($forbindelse, $sql);
		if(mysqli_num_rows($foresp) != 1){
			return false;
		}else{
			mysqli_query($forbindelse, "DELETE FROM  ". $this->tabelNavn ." WHERE email = '$bruger_slash'") or die(mysqli_error($forbindelse));
			mysqli_query($forbindelse, "DELETE FROM skoleprojekter_forum_billeder WHERE bruger='$_SESSION[brugernavn]'");
			unset($_SESSION["brugernavn"]);
			unset($_SESSION["kodeord"]);
			unset($_SESSION["bruger"]);
			return true;
		}
	}

	/**
	 * Metoden viser brugerens brugernavn HVIS han er logged ind
	 *
	 * @return string
	 */
	public function hent_brugernavn(){
		if($this->is_logged_in()){
			return $this->brugernavn;
		}else{
			return "";
		}
	}

	/**
	 * metoden viser brugerens kodeord HVIS han er logged ind
	 *
	 * @return string
	 */
	public function hent_kodeord(){
		if($this->is_logged_in()){
			return $this->kodeord;
		}else{
			return "";
		}
	}

	/**
	 * Metoden bruges til at opdatere aka rette brugerens kodeord
	 *
	 * @param string $kodeord
	 * @return bool
	 */
	public function ret_kodeord($forbindelse, $kodeord){
		if($this->is_logged_in()){
			$bruger_slash = addslashes($this->brugernavn);
			$kode_slash = addslashes($kodeord);
			mysqli_query($forbindelse, "UPDATE  ". $this->tabelNavn ." SET kodeord = '$kode_slash' WHERE brugernavn = '" . $bruger_slash . "'");
			$this->kodeord = $kodeord;
			$_SESSION["kodeord"] = $kodeord;
			return true;
		} else {
			return false;
		}
	}
}
?> 

<?php

/**
 * session_start();
 * #test
 * //connect
 * mysql_connect('localhost','root','');
 * mysql_select_db('testBrugerClass')or die('forkert DBnavn: '.mysql_error());

 * //initialserer objekt
 * $user=new bruger('test');

 * //log ind
 * if($user->log_ind('palle','blabla')){
 * 	echo "Logget ind";
 * } else{
 * 	echo "Ikke Logget ind";
 * }

 * #�ndre kodeord
 * //$user->ret_kodeord('blabla');
 * /*
 * #opretbruger
 * if($user->opret('Thomas','1234')){
 * 	echo "bruger oprettet";
 * } else {
 * 	echo "bruger ikke oprettet";
 * }
 * */
 /* //fjerner bruger
 * if($user->fjern('Thomas')){
 * 	echo "bruger SLETTET";
 * 	
 * } else {
 * 	echo "bruger IKKE SLETTET";
 * }
 */
?>