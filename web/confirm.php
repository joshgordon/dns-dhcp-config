<?php
include 'loadDB.php'; 

$record = $_GET['record'];
?> 
<h1>ARE YOU SURE? </h1> 
<p>You are about to delete a record from the server. This operation can
not be undone. Are you sure? </p> 

<form action="delete.php" method="post"> 
    <input type="hidden" name="record" value="<?php echo $record; ?>"> 
    <input type="submit" value="yes">
</form> 
<?php 

mysql_close();
?>

