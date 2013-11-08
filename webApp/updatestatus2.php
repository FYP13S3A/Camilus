<?php

session_start();

$trackingID = $_GET['id'];

include 'conn.php';





echo $trackingID;


//get data from tracking id

$sql = "select * from mail where Mail_Reference_No='" . $trackingID ."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_mail[] = $row;
}

//sender & recipient info
$s_Name = $result_mail[0][Sender_FullName];
$s_Email = $result_mail[0][Sender_Email];
$s_Phone = $result_mail[0][Sender_Contact_No];

$r_Name = $result_mail[0][Recipient_FullName];
$r_Email = $result_mail[0][Recipient_Email];
$r_Phone = $result_mail[0][Recipient_Contact_No];

$s_Service = $result_mail[0][Service_Type_Id];

//shipment details
$d_Contents = $result_mail[0][Mail_Contents];
$d_Weight = $result_mail[0][Mail_Weight];
$d_Size = $result_mail[0][Mail_Size];

$dSizeArray = explode("-",$d_Size);
$d_Length = $dSizeArray[0];
$d_Width = $dSizeArray[1];
$d_Height = $dSizeArray[2];

//sender address ID - need to draw from address table.

$s_AddressID = $result_mail[0][Sender_Address_Id];
$r_AddressID = $result_mail[0][Recipient_Address_Id];


$sql = "select * from address where Address_Id='" . $s_AddressID."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_sender[] = $row;
}

$s_Address = $result_sender[0][Address_Line1];
$s_Country = $result_sender[0][Country_Code];
$s_Postal = $result_sender[0][Postal_Code];
$s_City = $result_sender[0][City_Name];

//recipient address ID - need to draw from address table.

$sql = "select * from address where Address_Id='" . $r_AddressID."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_recipient[] = $row;
}

$r_Address = $result_recipient[0][Address_Line1];
$r_Country = $result_recipient[0][Country_Code];
$r_Postal = $result_recipient[0][Postal_Code];
$r_City = $result_recipient[0][City_Name];

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
<form name="form1" method="post" action="confirm_mail3.php">

<table width="100%" border="0">
<tr>
    <td><label class="sectionTitle">
    <b>&nbsp;Sender Details</b></label></td>
    <td>&nbsp;</td>
    <td><label class="sectionTitle">
    <b>&nbsp;Recipient Details</b></label></td>
  </tr>
  <tr>
    <td><table class="registerMail_P1">
<tr>
<td>Name: </td><td><input type="text" name="s_Name" id="s_Name" size="40" value="<?php echo $s_Name;?>"></td>
</tr>

<tr>
<td><label class="frmItemName">Address :</label>
<br/><font color=red>Please ensure Your address <br/>Contains Unit No</font>
</td>
<td><textarea name="s_Address" cols="40" rows="3" id="s_Address"><?php echo $s_Address;?></textarea>
</td>
</tr>
<tr>
  <td>Email Address: </td>
  <td><input type="text" name="s_Email" id="s_Email" size="50" value="<?php echo $s_Email;?>"></td>
</tr>
<tr>
<td>Phone : </td><td><input type="text" name="s_Phone" id="s_Phone" size="20" value="<?php echo $s_Phone;?>"></td>
</tr>

</table></td>
    <td>&nbsp;</td>
    <td><table class="registerMail_P1">
<tr>
<td>Name: </td><td><input type="text" name="r_Name" id="r_Name" size="40" value="<?php echo $r_Name;?>"></td>
</tr>

<tr>
<td><label class="frmItemName">Address :</label>
<br/><font color=red>Please ensure Your address <br/>Contains Unit No</font>
</td>
<td><textarea id="r_Address" name="r_Address" rows="3" cols="40"><?php echo $r_Address;?></textarea>
</td>
</tr>
<tr>
  <td>Email Address: </td>
  <td><input type="text" name="r_Email" id="r_Email" size="50" value="<?php echo $r_Email;?>"></td>
</tr>
<tr>
<td>Phone : </td><td><input type="text" name="r_Phone" id="r_Phone" size="20" value="<?php echo $r_Phone;?>"></td>
</tr>

</table></td>
  </tr>

  <tr>
    <td></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>



  <table width="100%" border="0">
    <tr>
      <td><?php

echo "<input type=\"hidden\" name=\"s_TrackingID\" value=\"". $trackingID ."\">\n";

?>

</form>
</div>
<?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>
<script>
function start() {
var inputFields = form1.getElementsByTagName("input"),
    inputFieldLength = inputFields.length;

for(var i = 0; i < inputFieldLength; i++) {
           inputFields[i].readOnly = true;
}
var textArea = form1.getElementsByTagName("textarea"),
    textAreaLength = textArea.length;
	for(var j = 0; j < textAreaLength; j++) {
           textArea[j].readOnly = true;
}

}
onload = start;
function chgtx() {
	
var inputFields = form1.getElementsByTagName("input"),
    inputFieldLength = inputFields.length;

for(var i = 0; i < inputFieldLength; i++) {
           inputFields[i].readOnly = !form1.chkChange.checked;
}
var textArea = form1.getElementsByTagName("textarea"),
    textAreaLength = textArea.length;
	for(var j = 0; j < textAreaLength; j++) {
           textArea[j].readOnly = !form1.chkChange.checked;
}
    

}

</script>
</body>
</html>