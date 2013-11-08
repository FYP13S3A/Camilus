<?php

session_start();

$trackingID = $_GET['id'];

include 'conn.php';

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
$status= $result_mail[0][Delivery_Status];
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
<form name="form1" method="post" action="updatestatus3.php">

<table width="22%" border="0">
<tr>
    <td width="42%"><label class="sectionTitle">
    <b>&nbsp;Sender Details</b></label></td>
    <td width="8%">&nbsp;</td>
    <td width="50%"><label class="sectionTitle">
    <b>&nbsp;Recipient Details</b></label></td>
  </tr>
  <tr>
    <td>
    <table class="registerMail_P1">
<tr>
<td>Name: </td><td><input name="s_Name" type="text" id="s_Name" value="<?php echo $s_Name;?>" size="40" readonly></td>
</tr>

<tr>
<td><label class="frmItemName">Address :</label>
<br/></td>
<td><textarea name="s_Address" cols="40" rows="3" readonly id="s_Address"><?php echo $s_Address;?></textarea>
</td>
</tr>
<tr>
  <td>Email Address: </td>
  <td><input name="s_Email" type="text" id="s_Email" value="<?php echo $s_Email;?>" size="50" readonly></td>
</tr>
<tr>
<td>Phone : </td><td><input name="s_Phone" type="text" id="s_Phone" value="<?php echo $s_Phone;?>" size="20" readonly></td>
</tr>

</table></td>
    <td>&nbsp;</td>
    <td><table class="registerMail_P1">
      <tr>
        <td>Name: </td>
        <td><input name="r_Name" type="text" id="r_Name" value="<?php echo $r_Name;?>" size="40" readonly></td>
      </tr>
      <tr>
        <td><label class="frmItemName">Address :</label></td>
        <td><textarea name="r_Address" cols="40" rows="3" readonly id="r_Address"><?php echo $r_Address;?></textarea></td>
      </tr>
      <tr>
        <td>Email Address: </td>
        <td><input name="r_Email" type="text" id="r_Email" value="<?php echo $r_Email;?>" size="50" readonly></td>
      </tr>
      <tr>
        <td>Phone : </td>
        <td><input name="r_Phone" type="text" id="r_Phone" value="<?php echo $r_Phone;?>" size="20" readonly></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
   
  </tr>
  <tr>
    <td><label class="frmItem">Update Status :</label></td>
    <td colspan="2"><select name="status">
    <?php 
    if($status=="onhold"){
    echo  "<option value=\"onhold\" selected='true'>On Hold</option>";
	echo " <option value=\"pending\" >Pending</option><option value=\"completed\">Completed</option>";
    }else{
		 echo  "<option value=\"onhold\">On Hold</option>";
	echo " <option value=\"pending\" >Pending</option><option value=\"completed\">Completed</option>";
	}
	?>
     
    
    </select></td>
  </tr>
  <tr>
<td colspan="3" align="right"><input type="submit" name="btnSubmit" id="btnSubmit" value="Submit"></td>
</tr>
</table>
      </form>
      <input type="hidden" name"id" value"<?php echo $$trackingID; ?>" />
</div>
<?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>