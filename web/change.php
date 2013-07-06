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

    $_GET = sanitize($_GET); 

    $record = $_GET['dhcp']; 
    $query = "select * from hosts where dhcpname = '$record'"; 
    $rs = mysql_query($query); 


    $row = mysql_fetch_row($rs); 

    ?>
    <form action="set.php" method="post">
    <input type="hidden" name="record" value="<?php echo $record; ?>">
    <table border=1 cellpadding=4>
        <tr>
            <td>DHCP Name:</td>
            <td><input type="text" name="dhcpname" value="<?php echo $row[0]; ?>"></td>
        </tr>
        <tr>
            <td>Hostname</td> 
            <td><input type="text" name="host" value="<?php echo $row[1];?>"></td>
        </tr>
        <tr>
            <td>IP Address: </td>  
            <td><input type="text" name="ip" value="<?php echo $row[2];?>"></td>
        </tr>
        <tr> 
            <td>Mac Address: </td>
            <td><input type="text" name="mac" value="<?php echo $row[3];?>"></td>
        </tr>
        <tr> 
            <td>Comment: </td>
            <td><input type="text" name="comment" value="<?php echo $row[4];?>"></td>
        </tr>
    </table>
    <input type="submit" value="Update">
    </form>


    <?php 

    mysql_close();
    ?>
<?php
} else { 
    ?><h1>You must be logged in to do that.</h1> 
    <?php
    include include("views/not_logged_in.php");
}
?>
