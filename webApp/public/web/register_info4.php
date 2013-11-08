<?php

include '../../conn.php';


$randomString = generateRandomString();

//Related Tables
//customerDetail
//sender_address
//recipient_address

//Appointment Data(if its exist)

$txtApptDate = $_POST['txtApptDate'];
$timeSlots = $_POST['TimeSlots'];
$s_Remarks = $_POST['s_Remarks'];
$s_sameAddress = $_POST['sameAddress'];

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

$s_addrID =  mysql_insert_id();


//RECIPIENT SQL INPUT

$sql = "INSERT INTO address ".
       "(Address_Line1, City_Name, Postal_Code, Phone_Number, last_update, Country_Code) ".
       "VALUES ".
       "('$r_Address','$r_City','$r_Postal','$r_Phone',".time().",'$r_Country')";


$retval = mysql_query($sql);


if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}

$r_addrID =  mysql_insert_id();

//set mail reference no & mail size
$mail_reference_no = $s_Country . generateRandomString() . substr(time(),-4) . $r_Country;

//length - width - height
$mailSize = $d_Length . "-" . $d_Width . "-" . $d_Height;

$d_Status = "pending";
$m_catID = 1;
$approveStatus = "0";

//Insert to Mail Table
$sql = "INSERT INTO mail";

$sql .= " (Mail_Reference_No,Sender_FullName,Sender_Address_Id,Sender_Contact_No,Sender_Email,Recipient_FullName,Recipient_Address_Id,Recipient_Contact_No,Recipient_Email,Mail_Size,Mail_Weight,Mail_Contents,Delivery_Status,Mail_Category_Id,Service_Type_Id,Approve_Status)";

$sql .= " VALUES ('$mail_reference_no','$s_Name','$s_addrID','$s_Phone','$s_Email','$r_Name','$r_addrID','$r_Phone','$r_Email','$mailSize','$d_Weight','$d_Content','$d_Status',$m_catID,$s_Service,'$approveStatus')" ;


$retval = mysql_query($sql);

if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}



if($txtApptDate!="" && $timeSlots!="")
{
//its an appointment
$collection_DateTime = $txtApptDate . " " . $timeSlots;



if($s_sameAddress=="yes")
{
$c_AddressID = $s_addrID;
}//if collection address same as sender address.

else
{
//get appointment key-ed in data

$a_Name = $_POST['r_Name'];
$a_Address = $_POST['r_Address'];
$a_Email = $_POST['r_Email'];
$a_Phone = $_POST['r_Phone'];

$a_City = $_POST['r_City'];
$a_Country = $_POST['r_Country'];
$a_Postal = $_POST['r_Postal'];

//Appointment Address SQL INPUT

$sql = "INSERT INTO address ".
       "(Address_Line1, City_Name, Postal_Code, Phone_Number, last_update, Country_Code) ".
       "VALUES ".
       "('$a_Address','$a_City','$a_Postal','$a_Phone',".time().",'$a_Country')";

$retval = mysql_query($sql);


if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}

$c_AddressID  =  mysql_insert_id();

}//end else if collection address different from sender address


$sql = "INSERT INTO appointment ".
       "(Collection_DateTime, Collection_Address_Id, Mail_Reference_No, Remarks,Collection_Status) ".
       "VALUES ".
       "('$collection_DateTime', $c_AddressID,'$mail_reference_no','$s_Remarks','pending')";


$retval = mysql_query($sql);

if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}


}





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
<script src="../../_script/respond.min.js"></script>

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
<li><a href="index.php">Register Info</a></li>
<li><a href="../../kiosk/tracking.php">Track Mail</a></li>
<li><a href="#">FAQ</a></li>
<li><a href="#">Contact Us</a></li>
</ul>
</div>
  </div>
  </div>
  <div id="mainContent">

       
  
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
<img src="../../barcode.php?text=<?php echo $mail_reference_no;?>">
</center>
<hr>
</div>
<hr>

 

</div>
  <?php include("../../footer.php"); ?>
</div><!---end#contentBox--->
</div>

<?php


function generateRandomString($length = 3) {
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


?>

</body>
</html>