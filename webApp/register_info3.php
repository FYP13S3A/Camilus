<?php
include 'conn.php';

//customerDetail
//sender_address
//recipient_address

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


$randomString = generateRandomString();



/*
$sql = 'INSERT INTO customerDetail '.
       '(serialNo, senderID, receiverID) '.
       'VALUES ("'.$randomString.'", 1, 1 )';


$result=mysql_query($sql);


if(!$result)
{
  die('Could not enter data: ' . mysql_error());
}
*/

mysql_close();

?>

<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<script>
function printpage()
  {
  window.print()
  }
</script>
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
      <b>Success Register</b>
</ul>
  </div>
<hr>
       
  <div>
<label class="itemTittle">
<p align=right>
<input type="button" value="Print this page" onclick="printpage()">
</p>
<center>
<font size=4><b>Your Serial Number is : </b></font>
<hr>
<img src="./barcode.php?text=<?php echo $randomString;?>">

</center>
<hr>
</div>
<hr>

 

</div>



  <div class="rightSidebar">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
  <?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>

<?php


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


?>

</body>
</html>