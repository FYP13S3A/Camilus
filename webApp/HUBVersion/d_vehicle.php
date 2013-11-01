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
}//end while loop


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
}//end while delivery contain manifest

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
}//end while loop fetch delivery leg

//GET departure and destination information
$delivery_leg_id = $result_delivery_leg_array[0][Delivery_Leg_Id];
$departure_loc = $result_delivery_leg_array[0][Departure];
$destination_loc = $result_delivery_leg_array[0][Destination];
$leg_driver_id = $result_delivery_leg_array[0][Driver_Id];
//echo $departure_loc . " " . $destination_loc  . "<hr>";

//if departure location contain b_ , its need data from building

if(strstr($departure_loc,"b_"))
{
$work_location = str_replace("b_","",$departure_loc);
}//end if location contain building tag b_

if($departure_loc!="b_19299")
{
//get all drivers not riding motorbike
$sql4 = "SELECT Id from account where Work_Location_Id='$work_location' AND UserId LIKE 'Driver%' AND Id IN
(SELECT User_Id
FROM `vehicle`
WHERE Vehicle_Type_Id != '2')";
}
else
{
//get all drivers riding motorbike
$sql4 = "SELECT Id from account where Work_Location_Id='$work_location' AND UserId LIKE 'Driver%' AND Id IN
(SELECT User_Id
FROM `vehicle`
WHERE Vehicle_Type_Id = '2')";
}

//echo $sql4;

$result4 = mysql_query($sql4);

while ($row4 = mysql_fetch_assoc($result4)) {
   $result_array4[] = $row4;
}//while fetch driver of location

//do a compare to get the lowest job on driver
$driver_counter = "999";
$driver_selection = "0";

foreach($result_array4 AS $row_da)
{
$driverId = $row_da[Id];

$sql_count_leg = "SELECT * from deliveryleg where Driver_Id='$driverId'";
$result_count_leg = mysql_query($sql_count_leg);
$totalCount = mysql_num_rows($result_count_leg);

if($driver_counter>$totalCount)
{
$driver_selection = $driverId;
$driver_counter = $totalCount;
}//end if driver counter

}//end foreach

//update database after select driver

$sql_update_driver = "UPDATE deliveryleg SET Driver_Id='$driver_selection' where Delivery_Leg_Id='".$delivery_leg_id."'";



if( (trim($leg_driver_id)) == "" || (trim($leg_driver_id)) == "0")
{

/* Debugging
echo "Work Location: " . $work_location . "<hr>";
echo $mail_ref . " expected to start at " . $expected_start_date_time . "<hr>";
echo $sql_update_driver;
*/

$result=mysql_query($sql_update_driver);
if($result == false)
{ 
    die("Didn't Update"); 
}
}//end if vehicle is not yet assign


}//end if job is suppose to start


unset($result_array4);
unset($result_delivery_leg_array);
unset($result_delivery_array);
unset($result_service_check);

}//end foreach pending delivery job



?>