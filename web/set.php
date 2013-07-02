<?php
include 'loadDB.php'; 

$record = $_POST['record']; 
$dhcpname = $_POST['dhcpname'];
$host = $_POST['host']; 
$ip = $_POST['ip']; 
$mac = $_POST['mac']; 

if ($record == "") 
{ 
    $query = "insert into hosts (dhcpname, hostname, ipaddress, mac) values('$dhcpname', '$host', '$ip', '$mac')"; 
$rs = mysql_query($query); 
    echo "<h1>Record created successfully. </h1>"; 
} 
else
{ 
    $query = "update hosts set dhcpname='$dhcpname', hostname='$host', ipaddress='$ip', mac='$mac' where dhcpname='$record';";
$rs = mysql_query($query);
    echo "<h1>Record updated successfully. </h1>";  
}

?> 
<a href="index.php">Return to main page.</a> 
