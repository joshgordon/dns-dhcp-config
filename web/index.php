<?php

/**
 * A simple, clean and secure PHP Login Script
 * 
 * MINIMAL VERSION
 * (check the website / github / facebook for other versions)
 * 
 * A simple PHP Login Script without all the nerd bullshit.
 * Uses PHP SESSIONS, modern password-hashing and salting
 * and gives the basic functions a proper login system needs.
 * 
 * Please remember: this is just the minimal version of the login script, so if you need a more
 * advanced version, have a look on the github repo. there are / will be better versions, including
 * more functions and/or much more complex code / file structure. buzzwords: MVC, dependency injected,
 * one shared database connection, PDO, prepared statements, PSR-0/1/2 and documented in phpDocumentor style
 * 
 * @package php-login
 * @author Panique <panique@web.de>
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<') ) {    
  exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");  
}

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

// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {    
   include 'loadDB.php';
   
   $query = "select * from hosts";
   $rs = mysql_query($query);
   ?>
   
   <table border=1 cellpadding=3>
   <tr><td></td> <td>dhcp name</td> <td>dns name</td> <td>ip</td> <td>mac</td><td>Comment</td><td></td></tr>
   <?php
   while ($row = mysql_fetch_assoc($rs)) { ?>
       <tr>
           <td><a href="change.php?dhcp=<?php echo $row['dhcpname']; ?>">Edit</a></td>
           <td><?php echo $row['dhcpname']; ?></td>
           <td><?php echo $row['hostname'];?></td>
           <td><?php echo $row['ipaddress'];?></td>
           <td><?php echo $row['mac'];?></td>
           <td><?php echo $row['comment'];?></td>
           <td><a href="confirm.php?record=<?php echo $row['dhcpname'];?>">Delete</a></td>
       </tr>
    <?php
    }
    ?>
    </table>
    <a href="change.php">Add new record</a>
    <a href="cname.php">Edit cnames</a>
    <a href="index.php?logout">Logout</a> 
    <?php

    mysql_close(); 

    } else {
// the user is not logged in. you can do whatever you want here.
// for demonstration purposes, we simply show the "you are not logged in" view.
include("views/not_logged_in.php");
}
