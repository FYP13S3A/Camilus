<?php
include 'conn.php';

$tracking_ID = $_POST['tracking_ID'];

$sql0 = "SELECT * FROM servicetype where Service_Type_Id IN (select Service_Type_Id from mail where Mail_Reference_No='$tracking_ID')";
$result0= mysql_query($sql0);
while ($row = mysql_fetch_assoc($result0)) {
   $result_array0[] = $row;
}

$service_Name = $result_array0[0][Name];


$sql1 = "SELECT * FROM delivery WHERE Manifest_Id =  '$tracking_ID'";
$result1= mysql_query($sql1);
while ($row = mysql_fetch_assoc($result1)) {
   $result_array1[] = $row;
}

$delivery_ID = $result_array1[0][Delivery_Id];
$start_Time = $result_array1[0][Actual_Start_DateTime];
$end_Time = $result_array1[0][Actual_End_Datetime];

$sql2= "SELECT * FROM deliveryleg WHERE delivery_Id ='$delivery_ID'";

$result2= mysql_query($sql2);
while ($row = mysql_fetch_assoc($result2)) {
   $result_array2[] = $row;
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
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/css/bootstrap.min.css" />
<link rel="stylesheet" href="_css/typography.css" />

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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.15/jquery.form-validator.min.js"></script>
<script src="jquery.form-validator.js"></script>
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
<li><a href="tracking.php">Track Mail</a></li>
<li><a href="#">FAQ</a></li>
<li><a href="#">Contact Us</a></li>
</ul>
</div>
  </div>
  </div>
  <div id="mainContent">

<p>
<b>Tracking Id : <font color=blue><?php echo $tracking_ID;?></font></b>
</p>
<p>
Mail Service Type: <b><?php echo $service_Name; ?></b>
</p>
<ul>
<li><b>Start Time : <?php echo $start_Time;?></b></li>
<li><b>End Time : <?php echo $end_Time;?></b></li>
</ul>
<p><b>Trip Details</b></p>

<table width="100%" border="0">
<tr>
<th>Departure</th>
<th>Destination</th>
<th>Status</th>
</tr>
<?php
//echo legs detail
foreach($result_array2 as $row)
{
$leg_Status = $row[Leg_Status];
$departure = $row[Departure];
$destination = $row[Destination];

if(strstr($departure,"b_"))
{
//replace away building prefix
$departure = str_replace("b_","",$departure);

//get building details
$sql3 = "SELECT * FROM building WHERE Building_Code='$departure'";

$result3 = mysql_query($sql3);

while ($row3 = mysql_fetch_assoc($result3)) 
{
   $result_array3[] = $row3;
}//while fetch driver of location

$departure_building_name = $result_array3[0][Name];
$departure_building_code = $result_array3[0][Postal_Code];

//get building name
$departure = $departure_building_name . " (" . $departure_building_code . ")";
}//end if departure is building


if(strstr($destination,"b_"))
{
//replace away building prefix
$destination= str_replace("b_","",$destination);

//get building details
$sql4 = "SELECT * FROM building WHERE Building_Code='$destination'";

$result4 = mysql_query($sql4);

while ($row4 = mysql_fetch_assoc($result4)) 
{
   $result_array4[] = $row4;
}//while fetch driver of location

$destination_building_name = $result_array4[0][Name];
$destination_building_code = $result_array4[0][Postal_Code];

//get building name
$destination = $destination_building_name . " (" . $destination_building_code . ")";
}
else
{
$destination = "Recipient Postal : " . $destination;
}



echo "<tr>";
echo "<td>$departure</td>";
echo "<td>$destination</td>";
echo "<td>$leg_Status</td>";
echo "</tr>";


unset($result_array3);
unset($result_array4);
}

?>
</table>
</p>




  </div>

  
  </div>
  <?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>