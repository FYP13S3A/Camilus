<?php

error_reporting(0);

session_start();
session_destroy();

if(count($_SESSION) == 0)
{
$_SESSION=array();
session_destroy();
}


?>

<meta http-equiv="refresh" content="3; url=http://www.efxmarket.com/HUBVersion/index.php">
<font size=4 color=black>Logging out in progress... </font>