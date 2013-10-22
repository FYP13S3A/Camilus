<?php
include 'conn.php';
session_start();

//dashboard codes
$session_user = $_SESSION['username'];

$sql = "select FullName,Work_Location_Id from account where UserId='" . $session_user."'";

$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_out[] = $row;
}

//counter staff info
$u_FullName = $result_out[0][FullName];
$u_WorkLocation = $result_out[0][Work_Location_Id];

//get work location postal code


$sql = "select Postal_Code from building where Building_Code='" . $u_WorkLocation."'";

$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_out2[] = $row;
}

$u_Postal = $result_out2[0][Postal_Code];

$_SESSION['username'] = $session_user;
$_SESSION['workLocation'] = $u_WorkLocation;
$_SESSION['startPostalCode'] = $u_Postal;
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
<title>Camilus - Confirm Mail</title>

<link href="_css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="_css/layout.css" rel="stylesheet" type="text/css">
<link href="_css/typography.css" rel="stylesheet" type="text/css" media="screen, projection">


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
   <?php include("header.php"); ?>
  <div class="leftSidebar">
  <div  id="navigationBox">
  <div class="wireframemenu">
<ul>
<li><a href="">Home</a></li>
<li><a href="confirm_mail.php">Confirm Mail</a></li>
<li><a href="logout.php">Logout</a></li>
</ul>
</div>
  </div>
  </div>
  <div id="mainContent">

<?php

echo "<font color=black size=2>Welcome <b>$u_FullName</b></font>";
?>

</div>
<?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>