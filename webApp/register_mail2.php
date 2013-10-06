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
<li><a href="#">Home</a></li>
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
        <form name="form1" method="post" action="register_confirm.php">

  <div>
<label class="itemTittle">
  <center><b>Sender Details</b></center></label>

<table width="100%" >
  <tr>
    <th colspan="2" scope="col">&nbsp;</th>
    </tr>
  <tr>
    <td align="right" width="24%"><label class="frmItemName">&nbsp;Full Name: </label></td>
    <td width="76%"><label for="textfield"></label>
      <input type="text" name="s_FullName" id="s_FullName" size="40"></td>
    </tr>
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;Phone Number:</label></td>
    <td><input type="text" name="s_Phone" id="s_Phone" size="40"></td>
    </tr>
  <tr>
    <td align="right"><label class="frmItemName">&nbsp;Email Address:</label></td>
    <td><input type="text" name="s_Email" id="s_Email" size="40"></td>
    </tr>
    
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;Address :</label></td>
    <td><textarea id="s_Address" name="s_Address" rows="3" cols="40"></textarea></td>
    </tr>
     <tr>
    <td align="right"><label class="frmItemName">&nbsp;Address Line 2:</label></td>
    <td><textarea id="s_Address" name="s_Address2" rows="3" cols="40"></textarea></td>
    </tr>
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;Postal Code:</label></td>
    <td><input type="text" name="s_Postal" id="s_Postal" size="40"></td>
    </tr>   ​
  <tr>
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;Country:</label></td>
    <td><select name="s_Country">
      <option value="SGP">SINGAPORE</option>
    </select>

    </tr>
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;City:</label></td>
    <td><select name="s_City">
      <option value="SGP">SINGAPORE</option>
    </select></td>
    </tr>
</table>
  </div>
<hr>

  <div>
<label class="itemTittle">
  <center><b>Recipient Details</b></center></label>

<table width="100%" >
  <tr>
    <th colspan="2" scope="col">&nbsp;</th>
    </tr>
  <tr>
    <td align="right" width="24%"><label class="frmItemName">&nbsp;Full Name: </label></td>
    <td width="76%"><label for="textfield"></label>
      <input type="text" name="r_FullName" id="r_FullName" size="40"></td>
    </tr>
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;Phone Number:</label></td>
    <td><input type="text" name="r_Phone" id="r_Phone" size="40"></td>
    </tr>
  <tr>
    <td align="right"><label class="frmItemName">&nbsp;Email Address:</label></td>
    <td><input type="text" name="r_Email" id="r_Email" size="40"></td>
    </tr>
    
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;Address :</label></td>
    <td><textarea id="r_Address" name="r_Address" rows="3" cols="40"></textarea></td>
    </tr>
     <tr>
    <td align="right"><label class="frmItemName">&nbsp;Address Line 2 :</label></td>
    <td><textarea id="r_Address" name="r_Address2" rows="3" cols="40"></textarea></td>
    </tr>
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;Postal Code:</label></td>
    <td><input type="text" name="r_Postal" id="r_Postal" size="40"></td>
    </tr>   ​
  <tr>
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;Country:</label></td>
    <td><select name="r_Country">
      <option value="SGP">SINGAPORE</option>
    </select>

    </tr>
   <tr>
    <td align="right"><label class="frmItemName">&nbsp;City:</label></td>
    <td><select name="r_City">
      <option value="SGP">SINGAPORE</option>
    </select></td>
    </tr>
</table>
<hr>
<br/>
  <p align=center>&nbsp;<input name=""  id="btnSubmit" type="submit" value="Submit Details" /></p>
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