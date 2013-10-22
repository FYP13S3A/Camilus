<?php
$mysql_hostname = "localhost";
$mysql_user = "camilus_dbadmin";
$mysql_password = "login.123";
$mysql_database = "camilus_db1";
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) 
or die("Opps some thing went wrong");
mysql_select_db($mysql_database, $bd) or die("Opps some thing went wrong");
?>