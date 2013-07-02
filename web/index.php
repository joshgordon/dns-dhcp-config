<?php
include 'loadDB.php'; 

$query = "select * from hosts"; 
$rs = mysql_query($query); 
?>

<table border=1 cellpadding=3>
<tr><td></td> <td>dhcp name</td> <td>dns name</td> <td>ip</td> <td>mac</td><td></td></tr>
<?php
while ($row = mysql_fetch_assoc($rs)) { ?>
    <tr> 
        <td><a href="change.php?dhcp=<?php echo $row['dhcpname']; ?>">Edit</a></td>
        <td><?php echo $row['dhcpname']; ?></td> 
        <td><?php echo $row['hostname'];?></td> 
        <td><?php echo $row['ipaddress'];?></td>  
        <td><?php echo $row['mac'];?></td>
        <td><a href="confirm.php?record=<?php echo $row['dhcpname'];?>">Delete</a></td>
    </tr>
<?php 
}
?>
</table>
<a href="change.php">Add new record</a>
<?php

mysql_close();

?>
