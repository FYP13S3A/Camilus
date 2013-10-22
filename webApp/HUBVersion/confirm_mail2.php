<?php

session_start();

$session_user = $_SESSION['username'];
$u_workLocation = $_SESSION['workLocation'];


$trackingID = $_POST['b_trackID'];

include 'conn.php';

//POPULATE DROPDOWN LIST
$sql="SELECT Service_Type_Id,Name,Service_Information from servicetype";

$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}


//POPULATE DROPDOWN LIST
$sql="SELECT NAME,CODE from country";
$result2 =mysql_query($sql);

while ($row = mysql_fetch_assoc($result2)) {
   $result_country[] = $row;
}


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
<li><a href="">Home</a></li>
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
<td><textarea id="s_Address" name="s_Address" rows="3" cols="40"><?php echo $s_Address;?></textarea>
</td>
</tr>

<tr>
<td><label class="frmItemName">Country:</label></td>
<td><select name="s_Country">
<?php
foreach($result_country AS $row)
{
if($row[CODE]==$s_Country)
{
   echo "<option selected=\"selected\" value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}//end if
else
{
   echo "<option value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}//end else
}//end foreach
?>
</select>
</tr>

<tr>
<td>Postal Code: </td><td><input type="text" name="s_Postal" id="s_Postal" size="40" value="<?php echo $s_Postal;?>"></td>
</tr>
<tr>
  <td>City: </td>
  <td><input type="text" name="s_City" id="s_City" size="40" value="<?php echo $s_City;?>"></td>
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
<td><label class="frmItemName">Country:</label></td>
<td><select name="r_Country">
<?php
foreach($result_country AS $row)
{
if($row[CODE]==$r_Country)
{
   echo "<option selected=\"selected\" value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}//end if
else
{
   echo "<option value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}//end else
}//end foreach
?>
</select>
</tr>

<tr>
<td>Postal Code: </td><td><input type="text" name="r_Postal" id="r_Postal" size="40" value="<?php echo $r_Postal;?>"></td>
</tr>
<tr>
  <td>City: </td>
  <td><input type="text" name="r_City" id="r_City" size="40" value="<?php echo $r_City;?>"></td>
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
  <tr>
    <td colspan="3" class="sectionTitle" id="sectionTitle2">&nbsp;Shipment Details</td>
  </tr>
  <tr>
    <td colspan="3" class="sectionTitle" id="sectionTitle"><table width="100%" class="registerMail_P1" >
      <tr>
        <th colspan="5" align="left"></th>
      </tr>
      <tr>
        <td align="left">&nbsp;Weight(kg)</td>
        <td align="left">&nbsp;Length(cm)</td>
        <td align="left">&nbsp;Width(cm)</td>
        <td align="left">&nbsp;Height(cm)</td>
        <td align="left">&nbsp;Mail Contents</td>
      </tr>
      <tr>
        <td>&nbsp;
          <input type="text" name="d_Weight" id="d_Weight" size="5" value="<?php echo $d_Weight;?>"></td>
        <td>&nbsp;
          <input type="text" name="d_Length" id="d_Length" size="5" value="<?php echo $d_Length;?>"></td>
        <td>&nbsp;
          <input type="text" name="d_Width" id="d_Width" size="5" value="<?php echo $d_Width;?>"></td>
        <td>&nbsp;
          <input type="text" name="d_Height" id="d_Height" size="5" value="<?php echo $d_Height;?>"></td>
        <td>&nbsp;
          <input type="text" name="d_Content" id="d_Content" size="20" value="<?php echo $d_Contents;?>"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><span class="sectionTitle">&nbsp;Services</span></td>
  </tr>
</table>



  <table width="100%" border="0">
    <tr>
      <td><table class="registerMail_P1">


    <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Selected Service type:</strong></td>
    </tr>
    <tr>
      <td><table width="100%">

<tr>
<th align="left">Select</th>
<th align="left">Service</th>
<th align="left">Service Information</th>
</tr>


<?php
foreach($result_array AS $row)
{
echo "<tr>";
if($s_Service==$row[Service_Type_Id])
{
echo "<td><input type=\"radio\" name=\"s_Service\" value=\"".$row[Service_Type_Id]."\" checked></td>";
}
else
{
echo "<td><input type=\"radio\" name=\"s_Service\" value=\"".$row[Service_Type_Id]."\"></td>";
}
echo "<td>".$row[Name]."</td>";
echo "<td>".$row[Service_Information]."</td>";
echo "</tr>";
}  
?>
</table>

 <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="right" class="registerMail_P1"><INPUT Type="button" VALUE="Back" onClick="history.go(-1);return true;">&nbsp;
<input  id="btnSubmit" type="submit" value="Approve Mail" />  </td>
  </tr>

  </table>
</form>
</div>
<?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>