<?php
include '../../conn.php';

//set timezone to Singapore
date_default_timezone_set("Asia/Singapore");

//Fields in appointment table
/*
*/

$s_Name = $_POST['s_Name'];
$s_Address = $_POST['s_Address'];
$s_Country = $_POST['s_Country'];
$s_Postal = $_POST['s_Postal'];
$s_City = $_POST['s_City'];
$s_Email = $_POST['s_Email'];
$s_Phone = $_POST['s_Phone'];
$txtApptDate = $_POST['txtApptDate'];
$timeSlots = $_POST['TimeSlots'];
$s_Remarks = $_POST['s_Remarks'];


//SENDER SQL INPUT

$sql = "INSERT INTO address ".
       "(Address_Line1, City_Name, Postal_Code, Phone_Number, last_update,Country_Code) ".
       "VALUES ".
       "('$s_Address','$s_City','$s_Postal','$s_Phone',".time().",'$s_Country')";



$retval = mysql_query($sql);

if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}

//get back the address id 
$s_addrID =  mysql_insert_id();

$collection_DateTime = $txtApptDate . " " . $timeSlots;
echo $collection_DateTime;

/*
$sql = "INSERT INTO appointment ".
       "(Collection_DateTime, Collection_Address_Id, Mail_Id, Remarks, Driver_Id,Collection_Status) ".
       "VALUES ".
       "()";
*/



?>


<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Camilus - Register a New Mail</title>

<link href="../../_css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="../../_css/layout.css" rel="stylesheet" type="text/css">
<link href="../../_css/typography.css" rel="stylesheet" type="text/css" media="screen, projection">


<!-- 
To learn more about the conditional comments around the html tags at the top of the file:
paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/

Do the following if you're using your customized build of modernizr (http://www.modernizr.com/):
* insert the link to your js here
* remove the link below to the html5shiv
* add the "no-js" class to the html tags at the top
* you can also remove the link to respond.min.js if you included the MQ Polyfill in your modernizr build 
-->
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="_script/respond.min.js"></script>

</head>

<body>
<div class="gridContainer clearfix">
<div id="contentBox">
   <?php include("../../header.php"); ?>
  <div class="leftSidebar">
  <div  id="navigationBox">
  <div class="wireframemenu">
<ul>
<li><a href="">Home</a></li>
<li><a href="register_info.php">Register Info</a></li>
<li><a href="#">Track Mail</a></li>
<li><a href="#">FAQ</a></li>
<li><a href="#">Contact Us</a></li>
</ul>
</div>
  </div>
  </div>
<div id="mainContent">

</div>
<?php include("../../footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>