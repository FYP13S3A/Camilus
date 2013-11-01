<?php

session_start();

$session_user = $_SESSION['username'];

if(trim($session_user)=="")
{
header('Location: http://www.efxmarket.com/HUBVersion/index.php');
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
<form name="form1" method="post" action="confirm_mail2.php">
<table width="100%" border="0">
  <tr>
    <td><label class="sectionTitle">
    <b>&nbsp;Reterive Information</b></label></td></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" class="registerMail_P1">
      <tr>
        <td width="10%" >Tracking ID:</td>
        <td width="90%"><input style="font-size:20px" type="text" name="b_trackID" id="b_trackID" size="30" value="" placeholder="Enter Tracking ID..."></td>
      </tr>
      <tr>
        <td colspan="2" ><input  id="btnSubmit" type="submit" value="Reterive Information &gt;" /></td>
        </tr>

    </table></td>
    </tr>
</table>

      </form>
</div>
<?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>