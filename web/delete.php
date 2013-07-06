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

$record = $_POST['record']; 

$query = "delete from hosts where dhcpname='$record';";
mysql_query($query); 
mysql_close(); 
?> 
<h1>Record deleted successfully.</h1> 
<a href="index.php">Return to main list</a>

<?php 
} else { 

?><h1>You must be logged in to do that </h1>
<?php include("views/not_logged_in.php");
 

} 
?>
