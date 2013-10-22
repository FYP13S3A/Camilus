<?php
include 'conn.php';

//POPULATE DROPDOWN LIST
$sql="SELECT NAME,CODE from country";
$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
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
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Camilus - Register a New Mail</title>

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
        <form name="form1" method="post" action="register_step2.php">

  <div>
<label class="itemTittle">
  <center><b>Sender Details</b></center></label>

<table width="100%" >
  <tr>
    <th colspan="2" scope="col">&nbsp;</th>
    </tr>
 
    <tr>
    <td align="right"><label class="frmItemName">&nbsp;Country:</label></td>
    <td><select name="s_Country">
<?php
foreach($result_array AS $row)
{
   echo "<option value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}  
?>
 </select>
    </tr>
    <td align="right"><label class="frmItemName">&nbsp;City:</label></td>
    <td><input type="text" name="s_City" id="s_City" size="30" value="">
    </tr>   

    <td align="right"><label class="frmItemName">&nbsp;Postal Code:</label></td>
    <td><input type="text" name="s_Postal" id="s_Postal" size="30" value="">
    </tr>   

</table>
<hr>
</div>
  <div>
<label class="itemTittle">
  <center><b>Recipient Details</b></center></label>

<table width="100%" >
  <tr>
    <th colspan="2" scope="col">&nbsp;</th>
    </tr>
 
    <tr>
    <td align="right"><label class="frmItemName">&nbsp;Country:</label></td>
    <td><select name="r_Country">
<?php
foreach($result_array AS $row)
{
   echo "<option value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}  
?>
    </select>
    </tr>
    <td align="right"><label class="frmItemName">&nbsp;City:</label></td>
    <td><input type="text" name="r_City" id="r_City" size="30" value="">
    </tr>   

    <td align="right"><label class="frmItemName">&nbsp;Postal Code:</label></td>
    <td><input type="text" name="r_Postal" id="r_Postal" size="30" value="">
    </tr>   

</table>
<hr>

<br/>
  <p align=center>&nbsp;<input name=""  id="btnSubmit" type="submit" value="Next Step - Select Service" /></p>
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