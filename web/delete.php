<?php 
include 'loadDB.php'; 

$record = $_POST['record']; 

$query = "delete from hosts where dhcpname='$record';";
mysql_query($query); 
mysql_close(); 
?> 
<h1>Record deleted successfully.</h1> 
<a href="index.php">Return to main list</a>
