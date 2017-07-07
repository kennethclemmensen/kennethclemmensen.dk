<?php
$db = mysqli_connect("mysql14.unoeuro.com", "kennethclem_dk", "webwi19");
mysqli_select_db($db, "kennethclemmensen_dk_db");
mysqli_query($db, "SET CHARACTER SET utf8");
?>