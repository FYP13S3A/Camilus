<?php

include 'conn.php';


/* 
Allocate Vehicle to proceed with delivery */
/*

System will do the following
- Look at mail table
- Look for mail that is approve, delivery status is still pending

*/


$sql="SELECT Mail_Reference_No,Service_Type_Id  from mail where Approve_Status='1' AND Delivery_Status='pending' ORDER by Service_Type_Id ASC";
$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}



foreach($result_array AS $row)
{

$durationTake = "";
$result_service_check = array();

$mail_ref = $row[Mail_Reference_No];
$mail_service_type = $row[Service_Type_Id];

//check if mail should start deliver
//get date of delivery to see if it should started

$sql_delivery = "SELECT * FROM delivery where Manifest_Id='$mail_ref'";
$result_delivery = mysql_query($sql_delivery);

while ($row_delivery = mysql_fetch_assoc($result_delivery)) {
   $result_delivery_array[] = $row_delivery;
}

$this_delivery_id = $result_delivery_array[0][Delivery_Id];
$expected_start_date_time = $result_delivery_array[0][Expected_Start_DateTime];

//set timezone to Singapore
date_default_timezone_set("Asia/Singapore");

//check if job are suppose to start.
if (time() >= strtotime($expected_start_date_time)) {
 //echo $mail_ref . " expected to start at " . $expected_start_date_time . "<hr>";

//find eligible driver to do the job
//if departure come from b_19299 , use motorbike - priority mail / priority express mail
//others use truck/van

//just get the first record 
$sql_delivery_leg = "SELECT * from deliveryleg where delivery_id='$this_delivery_id' AND Leg_Status='pending' LIMIT 0,1";
$result_delivery_leg = mysql_query($sql_delivery_leg);

while ($row_delivery_leg = mysql_fetch_assoc($result_delivery_leg)) {
   $result_delivery_leg_array[] = $row_delivery_leg;
}

//GET departure and destination information
$departure_loc = $result_delivery_leg_array[0][Departure];
$destination_loc = $result_delivery_leg_array[0][Destination];

//if departure location contain b_ , its need data from building

if($departure_loc=="b_19299")
{
//use motorbike driver of b_19299

}



//ASSIGN vehicle

}//end if job is suppose to start

unset($result_delivery_leg_array);
unset($result_delivery_array);
unset($result_service_check);
}//end foreach  



?>