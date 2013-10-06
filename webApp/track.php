<?php
include 'conn.php';

session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
{
// Get Details base on Track ID.
$trackID = $_POST['trackID'];
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
<title>Camilus - Tracking</title>
<link href="_css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="_css/layout.css" rel="stylesheet" type="text/css">
<link href="_css/typography.css" rel="stylesheet" type="text/css" media="screen, projection">
<!-- 

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
<li><a href="/home">Home</a></li>
<li><a href="#">Track Mail</a></li>
<li><a href="/faq">FAQ</a></li>
<li><a href="/contact">Contact Us</a></li>
</ul>
</div>
  </div>
  </div>
  <div id="mainContent">
<p>
&nbsp;&nbsp;Get the status of your shipment or a proof of delivery? <br/>&nbsp;&nbsp;Enter your <b>tracking number</b> below
</p>
  <div id="trackBox">
    <form name="form1" method="post" action="">
    <label id="lblTracking">Tracking ID:</label>
    <input name="trackID" id="trackID" type="text" size="25">
    &nbsp;<input name=""  id="btnSubmit" type="submit" value="Submit" /><br>
    </form>
    </div>
    
<?php 

if($trackID!="")
{
echo "<p><b>Shipment Details</b></p>";
echo "<p><b>Tracking ID: </b> : " . $trackID . "</p>";
}
?>
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