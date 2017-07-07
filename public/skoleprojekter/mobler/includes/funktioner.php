<?php
$side = $_GET['page'];
function style($var) {
	//gør variablen $side global så man også kan bruge $side i funktionen 
    global $side;
	//hvis siden er det samme som argumentet returnere den en class på linket
    if($side == $var) {
        return "class='menu_link'";
    }
}
?>