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

//Payment Details
$s_Payment = $_POST['payment'];


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

//draw the price base on service type

$sql_getPrice = "SELECT Price_Less500g , Price_Less2000g , Price_5000g 
FROM servicetype
WHERE Service_Type_Id = $s_Service";

$result_Price =mysql_query($sql_getPrice);

while ($row = mysql_fetch_assoc($result_Price)) {
   $result_price[] = $row;
}

$price1 = $result_price[0][Price_Less500g];
$price2 = $result_price[0][Price_Less2000g];
$price3 = $result_price[0][Price_5000g];


/*CALCULATE MAIL PRICE */
if($d_Weight<500)
{
//lesser than 500g
$mail_price = $price1;
}
if($d_Weight<2000 && $d_Weight>500)
{
//more than 500g, less than 2kg
$mail_price = $price2;
}
if($d_Weight<5000 && $d_Weight>2000)
{
//more than 2kg, less than 5kg
$mail_price = $price3;
}

//check if its appointment
//additional $10 for appointment type

if($txtApptDate!="" && $timeSlots !="")
{
$mail_price = $mail_price + 10.00;
}


//Insert to Mail Table
$sql = "INSERT INTO mail";

$sql .= " (Mail_Reference_No,Sender_FullName,Sender_Address_Id,Sender_Contact_No,Sender_Email,Recipient_FullName,Recipient_Address_Id,Recipient_Contact_No,Recipient_Email,Mail_Size,Mail_Weight,Mail_Contents,Delivery_Status,Mail_Category_Id,Service_Type_Id,Approve_Status,Payment_Mode,Mail_Price)";

$sql .= " VALUES ('$mail_reference_no','$s_Name','$s_addrID','$s_Phone','$s_Email','$r_Name','$r_addrID','$r_Phone','$r_Email','$mailSize','$d_Weight','$d_Content','$d_Status',$m_catID,$s_Service,'$approveStatus','$s_Payment','$mail_price')" ;


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

$a_Name = $_POST['a_Name'];
$a_Address = $_POST['a_Address'];
$a_Email = $_POST['a_Email'];
$a_Phone = $_POST['a_Phone'];

$a_City = $_POST['a_City'];
$a_Country = $_POST['a_Country'];
$a_Postal = $_POST['a_Postal'];

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
       "(Name, Collection_DateTime, Collection_Address_Id, Mail_Reference_No, Remarks,Collection_Status) ".
       "VALUES ".
       "('$a_Name','$collection_DateTime', $c_AddressID,'$mail_reference_no','$s_Remarks','pending')";


$retval = mysql_query($sql);

if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}


//if user select payment mode as paypal

if($s_Payment=="paypal")
{

//send an email to the sender email
$to      = $s_Email;
$subject = 'Camilus - Invoice for Order # ' .$mail_reference_no;
$message = "Hi, you had choose online payment as your payment mode. \n Total Cost : S\$$mail_price \n To make payment for your order, please visit: \nhttps://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=support@camilus.org&amount=$mail_price&currency_code=SGD&item_name=Camilus%20Payment%20-%20$mail_reference_no \n Thanks for using Camilus.";

$headers = 'From: support@efxmarket.com' . "\r\n" .
    'Reply-To: support@efxmarket.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();



mail($to, $subject, $message, $headers);

}//end if payment by paypal



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
<li><a href="../../web/register_info.php">Register Info</a></li>
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