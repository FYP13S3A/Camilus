<?php
session_start("info");

$s_FullName = $_POST['s_FullName'];
$s_Phone = $_POST['s_Phone'];
$s_Email = $_POST['s_Email'];
$s_Address = $_POST['s_Address'];
$s_Address2 = $_POST['s_Address2'];
$s_Postal = $_POST['s_Postal'];
$s_Country = $_POST['s_Country'];
$s_City = $_POST['s_City'];


$r_FullName = $_POST['r_FullName'];
$r_Phone = $_POST['r_Phone'];
$r_Email = $_POST['r_Email'];
$r_Address = $_POST['r_Address'];
$r_Address2 = $_POST['r_Address2'];
$r_Postal = $_POST['r_Postal'];
$r_Country = $_POST['r_Country'];
$r_City = $_POST['r_City'];


/*KEEP TO SESSION*/

$_SESSION['s_FullName'] = $_POST['s_FullName'];
$_SESSION['s_Phone'] = $_POST['s_Phone'];
$_SESSION['s_Email'] = $_POST['s_Email'];
$_SESSION['s_Address'] = $_POST['s_Address'];
$_SESSION['s_Address2'] = $_POST['s_Address2'];
$_SESSION['s_Postal'] = $_POST['s_Postal'];
$_SESSION['s_Country'] = $_POST['s_Country'];
$_SESSION['s_City'] = $_POST['s_City'];


$_SESSION['r_FullName'] = $_POST['r_FullName'];
$_SESSION['r_Phone'] = $_POST['r_Phone'];
$_SESSION['r_Email'] = $_POST['r_Email'];
$_SESSION['r_Address'] = $_POST['r_Address'];
$_SESSION['r_Address2'] = $_POST['r_Address2'];
$_SESSION['r_Postal'] = $_POST['r_Postal'];
$_SESSION['r_Country'] = $_POST['r_Country'];
$_SESSION['r_City'] = $_POST['r_City'];

/*DONE SESSION SENDING*/

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
<title>Camilus - Register a new mail</title>
<link href="_css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="_css/layout.css" rel="stylesheet" type="text/css">
<link href="_css/typography.css" rel="stylesheet" type="text/css" media="screen, projection">
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
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
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
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
<li><a href="register_info.php">Register Info</a></li>
<li><a href="register_mail.php">Register Mail</a></li>
<li><a href="#">Track Mail</a></li>
<li><a href="#">FAQ</a></li>
<li><a href="#">Contact Us</a></li>
</ul>
</div>
  </div>
  </div>
  <div id="mainContent">
  <div id="breadcumbsBox">
    <ul id="breadcrumbs-two">
      <b>&nbsp;Register Sender & Recipient Details</b>
</ul>
  </div>
<hr>
        <form name="form1" method="post" action="register_info3.php">

  <div>
<label class="itemTittle">
  <center><b>Sender Details</b></center></label>

<ul>
<li>Sender Name: <?php echo $s_FullName;?></li>
<li>Sender Phone: <?php echo $s_Phone;?></li>
<li>Sender Email: <?php echo $s_Email;?></li>
<li>Sender Address 1: <?php echo $s_Address;?></li>
<li>Sender Address 2: <?php echo $s_Address2;?></li>
<li>Sender Postal Code: <?php echo $s_Postal;?></li>
<li>Sender Country : <?php echo $s_Country;?></li>
<li>Sender City: <?php echo $s_City;?></li>
</ul>
</div>
<hr>

  <div>
<label class="itemTittle">
  <center><b>Recipient Details</b></center></label>

<ul>
<li>Recipient Name: <?php echo $r_FullName;?></li>
<li>Recipient Phone: <?php echo $r_Phone;?></li>
<li>Recipient Email: <?php echo $r_Email;?></li>
<li>Recipient Address 1: <?php echo $r_Address;?></li>
<li>Recipient Address 2: <?php echo $r_Address2;?></li>
<li>Recipient Postal Code: <?php echo $r_Postal;?></li>
<li>Recipient Country : <?php echo $r_Country;?></li>
<li>Recipient City: <?php echo $r_City;?></li>
</ul>
</div>
<hr>
<br/>
  <p align=center>&nbsp;<input name=""  id="btnSubmit" type="submit" value="Submit" /></p>
  </div>   
</form>
</div>



  <div class="rightSidebar">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
  <?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>