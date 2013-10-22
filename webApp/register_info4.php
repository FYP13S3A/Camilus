<?php

include 'conn.php';


$randomString = generateRandomString();

//Related Tables
//customerDetail
//sender_address
//recipient_address


//Service Detail

$s_Service = $_POST['s_Service'];

//Sender Details

$s_Name = $_POST['s_Name'];
$s_Address = $_POST['s_Address'];
$s_Contact =$_POST['s_Contact'];
$s_Email = $_POST['s_Email'];
$s_Phone = $_POST['s_Phone'];

$s_City = $_POST['s_City'];
$s_Country = $_POST['s_Country'];
$s_Postal = $_POST['s_Postal'];

//Recipient Details

$r_Name = $_POST['r_Name'];
$r_Address = $_POST['r_Address'];
$r_Contact =$_POST['r_Contact'];
$r_Email = $_POST['r_Email'];
$r_Phone = $_POST['r_Phone'];

$r_City = $_POST['r_City'];
$r_Country = $_POST['r_Country'];
$r_Postal = $_POST['r_Postal'];

$d_Weight = $_POST['d_Weight'];
$d_Length = $_POST['d_Length'];
$d_Width = $_POST['d_Width'];
$d_Height = $_POST['d_Height'];
$d_Content = $_POST['d_Content'];

/*

//SENDER SQL INPUT

$sql1 = 'INSERT INTO sender_address '.
        '(Address_Line1,Address_Line2,City_Id,Postal_Code,Phone_Number,last_update,Address_Type_Id)'
        'VALUES ("'.$s_Address.'","'.$s_Address2.'","'.$s_City.'","'.$s_Postal.'",,"'.$s_Phone.'",time(),"'.$addressTypeId.'");
//SENDER SQL INPUT END




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
<hr>
<p>
<font size=2 color=black>Hello <b><?php echo $s_Name;?></b>, below is your serial number. Please print and pass to the counter staff</font>
</p>
<hr>
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