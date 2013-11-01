<?php
include '../conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
// username and password sent from Form
$username=mysql_real_escape_string($_POST['mUser']); 
$password=mysql_real_escape_string($_POST['mPass']); 
$password=md5($password); // Encrypted Password

$sql="SELECT * FROM account WHERE Userid='$username' and H_value='$password'";
$result=mysql_query($sql);
$count=mysql_num_rows($result);
$status = "";

// If result matched $username and $password, table row must be 1 row
if($count==1)
{
echo "302";
}
else 
{
echo "404";
}
}

?>