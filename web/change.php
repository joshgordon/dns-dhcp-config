<?php
include 'loadDB.php'; 

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
</table>
<input type="submit" value="Update">
</form>


<?php 

mysql_close();
?>
