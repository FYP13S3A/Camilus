<?php
include 'conn.php';

//POPULATE DROPDOWN LIST
$sql="SELECT NAME,CODE from country";
$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

$s_Service = $_POST['s_Service'];

$s_City = $_POST['s_City'];
$s_Country = $_POST['s_Country'];
$s_Postal = $_POST['s_Postal'];

$r_City = $_POST['r_City'];
$r_Country = $_POST['r_Country'];
$r_Postal = $_POST['r_Postal'];

$d_Weight = $_POST['d_Weight'];
$d_Length = $_POST['d_Length'];
$d_Width = $_POST['d_Width'];
$d_Height = $_POST['d_Height'];
$d_Content = $_POST['d_Content'];



//get sender address by its input postal code
$s_Address = file_get_contents("http://www.efxmarket.com/getAddr.php?zip=" . $s_Postal);
$r_Address = file_get_contents("http://www.efxmarket.com/getAddr.php?zip=" . $r_Postal);

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
<title>Camilus - Register a New Mail</title>

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
  <form name="form1" method="post" action="register_info4.php">
  <table width="100%" border="0">
  <tr>
    <td colspan="2"><label class="sectionTitle">
      <b>&nbsp;Make Collection Appointment</b></label></td>
    </tr>
  <tr>
    <td><table class="registerMail_P1">
<tr>
  <td colspan="2"><label for="sameAddress"></label>
     &nbsp;<input type="checkbox" name="sameAddress" id="sameAddress">
    Collection address same as sender's address</td>
  </tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
<td>Name: </td><td><input type="text" name="s_Name" id="s_Name" size="40" value=""></td>
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
foreach($result_array AS $row)
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
  <td><input type="text" name="s_Email" id="s_Email" size="50" value=""></td>
</tr>
<tr>
  <td>Phone : </td>
  <td><input type="text" name="s_Phone" id="s_Phone" size="20" value=""></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>Collection Date : DD/MM/YYY</td>
  <td><label for="txtApptDate"></label>
    <input type="text" name="txtApptDate" id="txtApptDate"></td>
</tr>
<tr>
  <td>Collection Timing : HH:MM</td>
  <td><select name="TimeSlots" id="TimeSlots">
    <option value="0800">08:00 -09:00</option>
    <option value="0900">09:00 - 10:00</option>
    <option value="1000">10:00 - 11:00</option>
  </select></td>
</tr>
<tr>
<td>Remarks :</td><td><label for="TimeSlots">
  <textarea id="s_Remarks" name="s_Remarks" rows="3" cols="40"><?php echo $s_Address;?></textarea>
</label></td>
</tr>

</table></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2"><?php

//service detail

echo "<input type=\"hidden\" name=\"s_Service\" value=\"".$s_Service."\">\n";

//shipment details
echo "<input type=\"hidden\" name=\"d_Weight\" value=\"".$d_Weight."\">\n";
echo "<input type=\"hidden\" name=\"d_Length\" value=\"".$d_Length."\">\n";
echo "<input type=\"hidden\" name=\"d_Width\" value=\"".$d_Width."\">\n";
echo "<input type=\"hidden\" name=\"d_Height\" value=\"".$d_Height."\">\n";
echo "<input type=\"hidden\" name=\"d_Content\" value=\"".$d_Content."\">\n";
?>
   
</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="right" class="registerMail_P1"><INPUT Type="button" VALUE="Back" onClick="history.go(-1);return true;">&nbsp;
<input  id="btnSubmit" type="submit" value="Next&gt;" />  </td>
  </tr>
</table>
  
  
  </form>
        
</div>
<?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>