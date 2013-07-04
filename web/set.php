<?php
include 'loadDB.php'; 

$_POST = sanitize($_POST);


$record = $_POST['record']; 
$dhcpname = $_POST['dhcpname'];
$host = $_POST['host']; 
$ip = $_POST['ip']; 
$mac = $_POST['mac']; 
$comment = $_POST['comment']; 

if ($record == "") 
{ 
    $query = "insert into hosts (dhcpname, hostname, ipaddress, mac, comment) values('$dhcpname', '$host', '$ip', '$mac', '$comment')"; 
$rs = mysql_query($query); 
    echo "<h1>Record created successfully. </h1>"; 
} 
else
{ 
    $query = "update hosts set dhcpname='$dhcpname', hostname='$host', ipaddress='$ip', mac='$mac', comment='$comment' where dhcpname='$record';";
$rs = mysql_query($query);
    echo "<h1>Record updated successfully. </h1>";  
}

?> 
<a href="index.php">Return to main page.</a> 
