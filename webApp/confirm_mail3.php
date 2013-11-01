<?php

include 'conn.php';

session_start();

$session_user = $_SESSION['username'];
$u_workLocation = $_SESSION['workLocation'];
$u_PostalCode = $_SESSION['uPostalCode'];
$u_UserId = $_SESSION['uUserId'];

//Service Detail

$s_Service = $_POST['s_Service'];

//Sender Details

$s_Name = $_POST['s_Name'];
$s_Address = $_POST['s_Address'];
$s_Email = $_POST['s_Email'];
$s_Phone = $_POST['s_Phone'];

$s_City = $_POST['s_City'];
$s_Country = $_POST['s_Country'];
$s_Postal = $_POST['s_Postal'];

//Recipient Details

$r_Name = $_POST['r_Name'];
$r_Address = $_POST['r_Address'];
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

$trackingID = $_POST['s_TrackingID'];

//length - width - height
$mailSize = $d_Length . "-" . $d_Width . "-" . $d_Height;


//get primary key id of sender and recipient first.
//get data from tracking id

$sql = "select * from mail where Mail_Reference_No='" . $trackingID ."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_mail[] = $row;
}

$s_AddressID = $result_mail[0][Sender_Address_Id];
$r_AddressID = $result_mail[0][Recipient_Address_Id];

//we make the changes to the address table first

//############ UPDATE ADDRESS TABLE (SENDER)###############//
$sql_update1 = "UPDATE address SET Address_Line1 = '".$s_Address."',City_Name='".$s_City."',Postal_Code='".$s_Postal."',Phone_Number='".$s_Phone."',Country_Code='".$s_Country."' WHERE Address_Id='".$s_AddressID."'";
	
$result=mysql_query($sql_update1);

if($result == false)
{ 
    die("Didn't Update at sql1"); 
}

//############ UPDATE ADDRESS TABLE (RECIPIENT)###############//
$sql_update2 = "UPDATE address SET Address_Line1 = '".$r_Address."',City_Name='".$r_City."',Postal_Code='".$r_Postal."',Phone_Number='".$r_Phone."',Country_Code='".$r_Country."' WHERE Address_Id='".$r_AddressID."'";

$result=mysql_query($sql_update2);

if($result == false)
{ 
    die("Didn't Update at sql2"); 
}

//set time zone to singapore
date_default_timezone_set("Asia/Singapore");
$approve_time = date("Y-m-d H:i:s", time());

//############ UPDATE MAIl TABLE ###############//
$sql_update3 = "UPDATE mail SET Sender_FullName='".$s_Name."',Sender_Contact_No='".$s_Phone."',Sender_Email='".$s_Email."',Recipient_FullName='".$r_Name."',Recipient_Contact_No='".$r_Phone."',Recipient_Email='".$r_Email."',Mail_Size='".$mailSize."',Mail_Weight='".$d_Weight."',Mail_Contents='".$d_Content."',Service_Type_Id='".$s_Service."',Approved_By_Id='".$u_UserId."',Approve_Status='1',Approved_Time='".$approve_time."' where Mail_Reference_No='".$trackingID."'";

$result=mysql_query($sql_update3);
if($result == false)
{ 
    die("Didn't Update at sql3"); 
}



$shell_cmd = "php d_delivery.php ". $trackingID;
shell_exec($shell_cmd);


$status = "success";

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
<li><a href="dashboard.php">Home</a></li>
<li><a href="confirm_mail.php">Confirm Mail</a></li>
<li><a href="logout.php">Logout</a></li>
</ul>
</div>
  </div>
  </div>
  <div id="mainContent">

<?php
if($status=="success")
{
echo "<font size=5 color=black>Successfully confirm the following mail id : <b>$trackingID</b></font>";
}
?>

</div>
<?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>