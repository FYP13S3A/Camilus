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
<title>Untitled Document</title>
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
      <li><a href="">&nbsp;Step One: Sender & Recipient Details</a></li>
</ul>
  </div>
  <div>
<label class="itemTittle"><br>
  Sender Details:</label>
<br>
<table width="100%" >
  <tr>
    <th colspan="2" scope="col">&nbsp;</th>
    </tr>
  <tr>
    <td width="24%"><label class="frmItemName">&nbsp;Full Name: </label></td>
    <td width="76%"><label for="textfield"></label>
      <input type="text" name="textfield" id="textfield"></td>
    </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;Contact No: </label></td>
    <td><input type="text" name="textfield2" id="textfield2"></td>
    </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;Email Address:</label></td>
    <td><input type="text" name="textfield3" id="textfield3"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    
 <tr>
    <td><label class="frmItemName">&nbsp;Address Line 1:</label></td>
    <td><input type="text" name="textfield3" id="textfield3"></td>
    </tr>
  <tr>
   <tr>
    <td><label class="frmItemName">&nbsp;Address Line 2:</label></td>
    <td><input type="text" name="textfield3" id="textfield3"></td>
    </tr>
  <tr>
   <tr>
    <td><label class="frmItemName">&nbsp;Country:</label></td>
    <td><select name="City">
      <option value="SGP">SINGAPORE</option>
    </select>

    </tr>
   <tr>
    <td><label class="frmItemName">&nbsp;City:</label></td>
    <td><select name="City">
      <option value="SGP">SINGAPORE</option>
    </select></td>
    </tr>
 
   <tr>
    <td><label class="frmItemName">&nbsp;Postal Code:</label></td>
    <td><p>
      <input type="text" name="textfield3" id="textfield3">
    </p>
      <p>&nbsp; </p></td>
    </tr>
  
</table>
  </div>
    <div>
   <label class="itemTittle">Recipient Details:</label>
<br>
<table width="100%" >
  <tr>
    <th colspan="2" scope="col">&nbsp;</th>
    </tr>
  <tr>
    <td width="24%"><label class="frmItemName">&nbsp;Full Name: </label></td>
    <td width="76%"><label for="textfield"></label>
      <input type="text" name="textfield" id="textfield"></td>
    </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;Contact No: </label></td>
    <td><input type="text" name="textfield2" id="textfield2"></td>
    </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;Email Address:</label></td>
    <td><input type="text" name="textfield3" id="textfield3"></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    
 <tr>
   <td><label class="frmItemName">&nbsp;Address Line 1:</label></td>
   <td><input type="text" name="textfield3" id="textfield3"></td>
 </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;Address Line 2:</label></td>
    <td><input type="text" name="textfield3" id="textfield3"></td>
  </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;Country:</label></td>
    <td><select name="City">
      <option value="SGP">SINGAPORE</option>
    </select>

    </tr>
   <tr>
    <td><label class="frmItemName">&nbsp;City:</label></td>
    <td><select name="City">
      <option value="SGP">SINGAPORE</option>
    </select></td>
    </tr>
 
   <tr>
    <td><label class="frmItemName">&nbsp;Postal Code:</label></td>
    <td><input type="text" name="textfield3" id="textfield3"></td>
    </tr>
   <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
</table>

    </div>
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
