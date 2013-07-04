<?php
//This is where you set the database details. 
$host = "localhost"; 
$user = "networking"; 
$pass = "WOEC!D\$M"; 
$db   = "networking"; 

$r = mysql_connect($host, $user, $pass); 

$r2 = mysql_select_db($db);


//These two scripts were shamelessly copied and pasted from 
//http://css-tricks.com/snippets/php/sanitize-database-inputs/

function cleanInput($input) 
{
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );
 
    $output = preg_replace($search, '', $input);
    return $output;
}

function sanitize($input) 
{
    if (is_array($input)) 
    {
        foreach($input as $var=>$val) 
        {
            $output[$var] = sanitize($val); 
        }
    }
    else 
    {
        if (get_magic_quotes_gpc()) 
        {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}
?>
