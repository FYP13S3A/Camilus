<?php

include 'conn.php';

/*
2 types of report together
service & performance ( consider this as type )

each got 2 filter
-weekly and monthly ( consider this as a variable )
*/

//weekly or monthly
$time = $_GET['time'];
$timeSQL = "";

if($time=="week")
{
$timeSQL = "WEEKOFYEAR(Expected_End_DateTime)=WEEKOFYEAR(NOW())-1";
}
if($time=="month")
{
$timeSQL = "MONTH(Expected_End_DateTime)=MONTH(NOW())-1";
}

$building = $_GET['building'];
//get building name


$sql = "select Name from building WHERE Building_Code = $building;";

$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

//assign result to variable
$building_Name = $result_array[0][Name];

$writeLine = "";
$writeLine .= "<p><b>" . ucfirst($type) . " Report on Building $building_Name ($building)  for the previous $time.</b></p>";

unset($result_array);


//Number of delivery man
$sql = "SELECT COUNT(id) AS \"result\" FROM account a
INNER JOIN building b
ON b.Building_Code = a.Work_Location_Id
WHERE strcmp(substring(Userid,1,6),'Driver') = 0
AND b.Building_Code = $building;";

$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

//assign result to variable
$deliveryMan = $result_array[0][result];


$writeLine .= "<hr>";
$writeLine .= "<table width=\"80%\" border=\"1\">";
$writeLine .= "<tr><th><b>Vehicle Details</th><th>Mail Details</b></th></tr>";
$writeLine .= "<tr>";
$writeLine .= "<td>";
$writeLine .= "<p>Total number of Delivery man : " . $deliveryMan . "</p>";

unset($result_array);


//Get total number of Vehicles

$sql = 'SELECT COUNT(v.Vehicle_Id) AS "result" FROM vehicle v
INNER JOIN vehicletype vt 
ON v.Vehicle_Type_Id = vt.Vehicle_Type_Id
INNER JOIN building b
ON v.Building_Id = b.Building_Code
WHERE b.Building_Code = '.$building.';';

$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

//assign result to variable
$totalVehicle = $result_array[0][result];
$writeLine .= "<p>Total number of Vehicles : " . $totalVehicle . "</p>";

unset($result_array);


//get number of vehicle per building

$sql = 'SELECT vt.Name AS "Vehicle_Type", COUNT(v.Vehicle_Type_Id) AS "Amount" 
FROM vehicletype vt 
LEFT JOIN vehicle v
ON v.Vehicle_Type_Id = vt.Vehicle_Type_Id
AND v.Building_Id = '.$building.'
GROUP BY vt.Vehicle_Type_Id;';


$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

foreach($result_array as $row)
{
$count = $row[Amount];
$type = $row[Vehicle_Type];

$writeLine .= "<p>Total number of $type : " . $count. "</p>";
}



unset($result_array);




//get number of deliveries in the prev week

$sql = 'SELECT COUNT(d.Delivery_Id) AS "result"
FROM delivery d
INNER JOIN deliveryleg dl 
ON d.delivery_id = dl.delivery_id 
INNER JOIN account a
ON dl.Driver_Id = a.Id
INNER JOIN building b
ON a.Work_Location_Id = b.Building_Code
WHERE '.$timeSQL.'
AND b.Building_Code = '.$building.'
ORDER BY d.delivery_id;';


$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

//assign result to variable
$prev_Delivery = $result_array[0][result];

$writeLine .= "</td>";
//end table cell for vehicle information
//create table cell for mail information
$writeLine .= "<td>";
$timeSQL2 = "";

if($time=="week")
{
$timeSQL2 = "WEEKOFYEAR(m.Approved_Time)=WEEKOFYEAR(NOW())-1";
}
if($time=="month")
{
$timeSQL2 = "MONTH(m.Approved_Time)=MONTH(NOW())-1";
}


//-- List the Total number of mails (All types except unregistered mails) approved from each building for prev week

$sql = 'SELECT COUNT(m.Service_Type_Id) AS "result"
FROM mail m
INNER JOIN account a
ON a.Id = m.Approved_By_Id
INNER JOIN building b
ON a.Work_Location_Id = b.Building_Code
WHERE m.Approve_Status = 1
AND b.Building_Code = '.$building.'
AND '.$timeSQL2;


$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

$total_Mail_approve = $result_array[0][result];

$writeLine .= "<p>Total number of mails approved: " . $total_Mail_approve . "</p>";

unset($result_array);

//-- Check number of times a particular type of service occurs
//-- Registered approved

$sql = 'SELECT st.Name AS "Service_Type", COUNT(st.Name) AS "Occurance_Rate" 
FROM mail m
INNER JOIN servicetype st
ON m.Service_Type_Id = st.Service_Type_Id 
INNER JOIN account a
ON a.Id = m.Approved_By_Id
INNER JOIN building b
ON a.Work_Location_Id = b.Building_Code
WHERE b.Building_Code = '.$building.'
AND Approve_Status = 1 
AND '.$timeSQL2.'
GROUP BY m.Service_Type_Id;';


$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

foreach($result_array as $row)
{
$count = $row[Occurance_Rate];
$type = $row[Service_Type];

$writeLine .= "<p>Total number of $type : " . $count. "</p>";
}

unset($result_array);

$writeLine .= "<p><table border=\"1\" width=\"60%\">";
$writeLine .= "<tr><td colspan=\"3\"><b>&nbsp;Total Deliveries in Previous $time : " . $prev_Delivery . " </b></td></tr>";
$writeLine .= "<tr></tr>";
$writeLine .= "<tr><th>&nbsp;On Time &nbsp;</th><th>&nbsp;Late &nbsp;</th><th>&nbsp;Incomplete&nbsp;</th></tr>";
$writeLine .= "<tr>";

//get number of deliveries in the prev week on time

$sql = 'SELECT COUNT(d.Delivery_Id) AS "result"
FROM delivery d
INNER JOIN deliveryleg dl 
ON d.delivery_id = dl.delivery_id 
INNER JOIN account a
ON dl.Driver_Id = a.Id
INNER JOIN building b
ON a.Work_Location_Id = b.Building_Code
WHERE DATE(Expected_End_DateTime) >= DATE(Actual_End_DateTime)
AND '.$timeSQL.'
AND b.Building_Code = '.$building.'
ORDER BY d.delivery_id;';

$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

//assign result to variable
$prev_onTime = $result_array[0][result];
$writeLine .= "<td>&nbsp;" . $prev_onTime . "</td>";
unset($result_array);

//get number of deliveries late in the prev week

$sql = 'SELECT COUNT(d.Delivery_Id) AS "result"
FROM delivery d 
INNER JOIN deliveryleg dl 
ON d.delivery_id = dl.delivery_id 
INNER JOIN account a
ON dl.Driver_Id = a.Id
INNER JOIN building b
ON a.Work_Location_Id = b.Building_Code
WHERE DATE(Expected_End_DateTime) < DATE(Actual_End_DateTime)
AND '.$timeSQL.'
AND b.Building_Code = '.$building.'
ORDER BY d.delivery_id;';



$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

//assign result to variable
$prev_Late = $result_array[0][result];
$writeLine .= "<td>&nbsp;" . $prev_Late . "</td>";

unset($result_array);


//-- Number of deliveries in the prev week (Not Completed)


$sql = 'SELECT COUNT(d.Delivery_Id) AS "result"
FROM delivery d
INNER JOIN deliveryleg dl 
ON d.delivery_id = dl.delivery_id 
INNER JOIN account a
ON dl.Driver_Id = a.Id
INNER JOIN building b
ON a.Work_Location_Id = b.Building_Code
WHERE '.$timeSQL.'
AND b.Building_Code = '.$building.'
AND d.Actual_End_Datetime IS NULL
ORDER BY d.delivery_id;';

$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

//assign result to variable
$prev_Uncomplete = $result_array[0][result];
$writeLine .= "<td>&nbsp;" . $prev_Uncomplete . "</td>";

unset($result_array);



$writeLine .= "</tr>";
$writeLine .= "</table></p>";
$writeLine .= "</td>";
$writeLine .= "</tr>";
$writeLine .= "</table>";

unset($result_array);




//List all deliveries and the respective deliveryman name if any from specific building

$sql = 'SELECT d.Delivery_Id AS "Delivery_ID", d.Manifest_Id AS "Manifest_ID", d.Expected_End_DateTime AS "Expected_Delivery_Completion",
d.Actual_End_Datetime AS "Actual_Delivery_Completion",
a.FullName As "Delivery_Man", b.Name As "Building"
FROM delivery d 
INNER JOIN deliveryleg dl 
ON d.delivery_id = dl.delivery_id 
INNER JOIN account a
ON dl.Driver_Id = a.Id
INNER JOIN building b
ON a.Work_Location_Id = b.Building_Code
WHERE DATE(Expected_End_DateTime) < DATE(Actual_End_DateTime) 
AND '.$timeSQL.'
AND b.Building_Code = '.$building.'
ORDER BY d.delivery_id;';


$result = mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

$writeLine .="<hr><h3>Late Deliveries Details</h2></p>";
$writeLine .="<table border=\"1\">";
$writeLine .="<tr>";
$writeLine .="<th>Delivery Id&nbsp;</th>";
$writeLine .="<th>Manifest Id&nbsp;</th>";
$writeLine .="<th>Expected Delivery Complete&nbsp;</th>";
$writeLine .="<th>Actual Delivery Completion&nbsp;</th>";
$writeLine .="<th>Delivery Man Name&nbsp;</th>";
$writeLine .="<th>Building&nbsp;</th>";
$writeLine .="</tr>";

foreach($result_array as $row)
{
$writeLine .="<tr>";
$writeLine .="<td>$row[Delivery_ID]</td>";
$writeLine .="<td>$row[Manifest_ID]</td>";
$writeLine .="<td>$row[Expected_Delivery_Completion]</td>";
$writeLine .="<td>$row[Actual_Delivery_Completion]</td>";
$writeLine .="<td>$row[Delivery_Man]</td>";
$writeLine .="<td>$row[Building]</td>";
$writeLine .="</tr>";
}

$writeLine .="</table>";

unset($result_array);



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
<script src="_script/jquery.form-validator.js"></script>
</head>

<body>
<div class="gridContainer clearfix">
<div id="contentBox">
   <?php include("header.php"); ?>
   <div id="reportContent" style="width:100%; ">
   
   <!---Put the actual report here--->
   <?php echo $writeLine; ?>
   </div>
  <?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>
</body>
</html>