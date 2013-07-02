<?php
//This is where you set the database details. 
$host = "localhost"; 
$user = "dummy-user"; 
$pass = "password"; 
$db   = "networking"; 

$r = mysql_connect($host, $user, $pass); 

$r2 = mysql_select_db($db);

?>
