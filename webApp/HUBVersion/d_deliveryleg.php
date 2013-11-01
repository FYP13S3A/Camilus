<?php
include 'conn.php';

//Plan Delivery Leg and record to database
//Delivery leg have the following

/* 
[ Database ]
Delivery_Leg_Id
Delivery_Id
Leg_No
Departure(Start)
Destination(End)
Leg_Status - pending/complete
*/

//optional
//$startzip = $_GET['start'];

// for the counter staff - start location = post office , follow by nearest pdc to post office,  then recipient pdc, recipient house

//plan the route for shipment

//only run from d_delivery
$trackingID = $argv[1];


$sql = "select * from mail where Mail_Reference_No='" . $trackingID ."'";
$result= mysql_query($sql);


while ($row = mysql_fetch_assoc($result)) {
   $result_mail[] = $row;
}

//get the address ID of both sender and recipient
$s_AddressID = $result_mail[0][Sender_Address_Id];
$r_AddressID = $result_mail[0][Recipient_Address_Id];
$staff_ApproveID= $result_mail[0][Approved_By_Id];
$s_Service_Type_Id = $result_mail[0][Service_Type_Id];


//GET FROM SERVICE TYPE

$sql = "select * from servicetype where Service_Type_Id='" . $s_Service_Type_Id ."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_service_check[] = $row;
}

$durationTake = $result_service_check[0][Expected_Local_Duration];

$priority_mail="false";

if($durationTake<=1)
{
$priority_mail="true";
}




/* GET START POSTAL CODE BASE ON STAFF WORKING LOCATION*/


$sql = "select Work_Location_Id from account where Id='" . $staff_ApproveID."'";

$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_out[] = $row;
}

$u_WorkLocation = $result_out[0][Work_Location_Id];


$sql = "select Building_Code from building where Building_Code='" . $u_WorkLocation."'";

$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_out2[] = $row;
}

$u_Postal = $result_out2[0][Building_Code];


//########### END GET START POSTAL CODE BASE ON STAFF WORKING LOCATION ###############/

$sql = "select * from address where Address_Id='" . $s_AddressID."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_sender[] = $row;
}

$s_Postal = $result_sender[0][Postal_Code];
$s_Postal_temp = $s_Postal;

//recipient address ID - need to draw from address table.

$sql = "select * from address where Address_Id='" . $r_AddressID."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_recipient[] = $row;
}

$r_Postal = $result_recipient[0][Postal_Code];
$r_Postal_Temp = $r_Postal;

//first 2 digit of sender and recipient


//get zone id from district

$s_Postal = substr($s_Postal,0,2);
$r_Postal = substr($r_Postal,0,2);

$sql = "SELECT * FROM district where Postal_Sector='" . $s_Postal . "'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_zone[] = $row;
}

$s_Zone = $result_zone[0][Zone_Id];

$sql = "SELECT * FROM district where Postal_Sector='" . $r_Postal . "'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_zone2[] = $row;
}

$r_Zone = $result_zone2[0][Zone_Id];

//now get zone name from zone id

$sql = "SELECT * FROM zone where Zone_Id='" . $s_Zone . "'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_zoneName[] = $row;
}

$s_zoneName = $result_zoneName[0][Name];

$sql = "SELECT * FROM zone where Zone_Id='" . $r_Zone . "'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_zoneName2[] = $row;
}

$r_zoneName = $result_zoneName2[0][Name];


//recipient Postal - $r_Postal

//echo $s_zoneName . $s_Zone . " -> " . $r_zoneName . $r_Zone . " -> House of recipient "; 


//assume registered mail from post office
// Leg 1 = Post Office, Leg 2 = PDC for Post Office,  Leg 3 = Foreign PDC, Leg 4 = Recipient Postal Code - Address

/* If International
Outgoing:
Leg 1  = Post office > Local PDC
Leg 2 = Local PDC >AIRPORT

Incoming
Leg 1  = Airport > Local PDC
Leg 2 = Local PDC > receipent house
*/


//Local Side
//as of now we use the postal code of staff working location

//$leg1 = $s_Postal_temp;
$leg1 = "b_" . $u_Postal;
$leg2 = "pdc_" . $s_Zone;
$leg3 = "pdc_" . $r_Zone;
$leg4 = $r_Postal_Temp;

$leg_status = "pending";
$manifest_id = $trackingID;
$Delivery_Id = "";
$leg_counter = "1";//start from 1


//GET delivery id from delivery table by manifest id
//#############################################################

$sql_deliver = "select Delivery_Id from delivery where Manifest_Id='" . $manifest_id."'";

$result_for_deliver = mysql_query($sql_deliver);

while ($row = mysql_fetch_assoc($result_for_deliver)) {
   $result_deliver[] = $row;
}

$Delivery_Id = $result_deliver[0][Delivery_Id];


//#############################################################

//3 sql for local , 2 for Oversea

if($priority_mail=="false")
{

// LEG 1
$sql1 = "INSERT INTO deliveryleg ".
       "(Delivery_Id, Leg_No, Departure, Destination, Leg_Status) ".
       "VALUES ".
       "('$Delivery_Id','$leg_counter','$leg1','$leg2','$leg_status')";

$leg_counter++;

$retval = mysql_query($sql1);

if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}



// LEG 2
$sql2 = "INSERT INTO deliveryleg ".
       "(Delivery_Id, Leg_No, Departure, Destination, Leg_Status) ".
       "VALUES ".
       "('$Delivery_Id','$leg_counter','$leg2','$leg3','$leg_status')";

$leg_counter++;

$retval = mysql_query($sql2);

if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}


// LEG 3
$sql3 = "INSERT INTO deliveryleg ".
       "(Delivery_Id, Leg_No, Departure, Destination, Leg_Status) ".
       "VALUES ".
       "('$Delivery_Id','$leg_counter','$leg3','$leg4','$leg_status')";


$retval = mysql_query($sql3);

if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}

}//end priority mail as false


if($priority_mail=="true")
{
//Priority Processing Distribuition Center
$building_for_ppdc = "19299";


$leg1 = "b_" . $u_Postal;
$leg2 = "b_" . $building_for_ppdc;
$leg3 = $r_Postal_Temp;

// LEG 1
$sql1 = "INSERT INTO deliveryleg ".
       "(Delivery_Id, Leg_No, Departure, Destination, Leg_Status) ".
       "VALUES ".
       "('$Delivery_Id','$leg_counter','$leg1','$leg2','$leg_status')";

$retval = mysql_query($sql1);

if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}


$leg_counter++;

// LEG 2
$sql2 = "INSERT INTO deliveryleg ".
       "(Delivery_Id, Leg_No, Departure, Destination, Leg_Status) ".
       "VALUES ".
       "('$Delivery_Id','$leg_counter','$leg2','$leg3','$leg_status')";

}

$retval = mysql_query($sql2);

if(! $retval )
{
  die('System Error, please contact our counter regarding this issue.');
}



?>