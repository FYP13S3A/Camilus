<?php
//include mysql connection
include 'conn.php'; 

//validate login 
$userCheck = $_POST['txtUsername'];
$passCheck = $_POST['txtPassword'];

$sql = "select * from account where userCheck='" . mysql_real_escape_string($userCheck) . "' AND H_Value='" .  mysql_real_escape_string($passCheck) . "'";

$boolCheck = "false";

$result = mysql_query($sql);
 
while($info = mysql_fetch_array( $result )) 
 { 
if($info['Userid']==$userCheck && $info['H_Value']=="passCheck")
{
$boolCheck = "true";
}
 } 

if($boolCheck == "true")
{
header('Location: /dashboard.php');
}
if($boolCheck == "false")
{
header('Location: /?status=fail');
}
?>