<?php
include 'conn.php';

//POPULATE DROPDOWN LIST
$sql="SELECT ServiceType_Id,Name,Service_Information from servicetype";

$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}


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
 <div>
<label class="itemTittle">
  <center><b>Shipment Details</b></center></label>
<hr>
<p>
<font size=2 color=black><b>
<table>
<tr>
<td>Physical Weight : <?php echo $d_Weight;?></td>
</tr>
<tr><td>Length: <?php echo $d_Length;?></td></tr>
<tr><td>Width: <?php echo $d_Width;?></td></tr>
<tr><td>Height: <?php echo $d_Height;?></td></tr>
<tr>
<td>Mail Content : <?php echo $d_Content;?></td>
</tr>
</table>
</b></font>
</p>

</div>

<hr>
<div>
<label class="itemTittle">
  <center><b>Services</b></center></label>
<hr>
<p>
<b>Select your service type:</b><br/>
<table border="1">
<tr>
<th>Select</th>
<th>Service</th>
<th>Service Information</th>
</tr>

<form name="form1" method="post" action="register_info3.php">
<?php
foreach($result_array AS $row)
{
echo "<tr>";
echo "<td><input type=\"radio\" name=\"s_Service\" value=\"".$row[ServiceType_Id]."\"></td>";
echo "<td>".$row[Name]."</td>";
echo "<td>".$row[Service_Information]."</td>";
echo "</tr>";
}  
?>
</table>

</p></div>

<?php
//echo hidden field

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

//sender details
echo "<input type=\"hidden\" name=\"s_City\" value=\"".$s_City."\">\n";
echo "<input type=\"hidden\" name=\"s_Country\" value=\"".$s_Country."\">\n";
echo "<input type=\"hidden\" name=\"s_Postal\" value=\"".$s_Postal."\">\n";

//recipient details
echo "<input type=\"hidden\" name=\"r_City\" value=\"".$r_City."\">\n";
echo "<input type=\"hidden\" name=\"r_Country\" value=\"".$r_Country."\">\n";
echo "<input type=\"hidden\" name=\"r_Postal\" value=\"".$r_Postal."\">\n";

//shipment details
echo "<input type=\"hidden\" name=\"d_Weight\" value=\"".$d_Weight."\">\n";
echo "<input type=\"hidden\" name=\"d_Length\" value=\"".$d_Length."\">\n";
echo "<input type=\"hidden\" name=\"d_Width\" value=\"".$d_Width."\">\n";
echo "<input type=\"hidden\" name=\"d_Height\" value=\"".$d_Height."\">\n";
echo "<input type=\"hidden\" name=\"d_Content\" value=\"".$d_Content."\">\n";

?>


<br/>
  <p align=center><INPUT Type="button" VALUE="Back" onClick="history.go(-1);return true;">&nbsp;<input name=""  id="btnSubmit" type="submit" value="Next Step - Update Details" /></p>
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