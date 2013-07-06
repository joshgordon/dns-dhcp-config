<?php
// if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
// (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
require_once("libraries/password_compatibility_library.php");

// include the configs / constants for the database connection
require_once("config/db.php");

// load the login class
require_once("classes/Login.php");

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process. in consequence, you can simply ...
$login = new Login();
if ($login->isUserLoggedIn() == true) { 

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

<?php } 

else { 
    echo "<h1>You must be logged in to do that</h1>"; 
    include("views/not_logged_in.php");
} 
?> 
