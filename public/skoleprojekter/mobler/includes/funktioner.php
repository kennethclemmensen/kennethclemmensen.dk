<?php
$side = $_GET['page'];
function style($var) {
	//g�r variablen $side global s� man ogs� kan bruge $side i funktionen 
    global $side;
	//hvis siden er det samme som argumentet returnere den en class p� linket
    if($side == $var) {
        return "class='menu_link'";
    }
}
?>