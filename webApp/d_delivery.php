<?php
// ### php file to plan delivery scheduling ###

include 'conn.php';

$trackingID = $argv[1];

//Check if delivery is already assigned to the Manifest id

$sql= "SELECT * FROM delivery WHERE Manifest_Id='$trackingID '";
$result=mysql_query($sql);
$count=mysql_num_rows($result);
$status = "";

//Kill activity if delivery manifest id already exist in table.
if($count==1)
{
echo "Delivery is already setup on this manifest id " . $trackingID;
die();
}


//select from mail table to get service type id of the manifest

$sql = "SELECT Service_Type_Id from mail where Mail_Reference_No='" . $trackingID ."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_mail[] = $row;
}

//obtain service type id

$s_Service = $result_mail[0][Service_Type_Id];

//Obtain Service Information on the 5 things Expected_Local_Duration, Expected_Overseas_Duration , Last_Collection_Time , Last_Delivery_Time , Expected_Start_Time

$sql = "SELECT Expected_Local_Duration, Expected_Overseas_Duration, Expected_Start_Time, Last_Collection_Time, Last_Delivery_Time from servicetype where Service_Type_Id='" . $s_Service."'";

$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_service[] = $row;
}

$s_Expected_Local_Duration = $result_service[0][Expected_Local_Duration];
$s_Expected_Overseas_Duration = $result_service[0][Expected_Overseas_Duration];
$s_Last_Collection_Time = $result_service[0][Last_Collection_Time];

$s_start_Delivery_Time = $result_service[0][Expected_Start_Time];
$s_Last_Delivery_Time = $result_service[0][Last_Delivery_Time];

$db_expect_start = "";
$db_expect_end = "";
$vehicle_id = "";

//set time zone to singapore
date_default_timezone_set("Asia/Singapore");

//get the date
$today = date("Y-m-d", time());
$tommorow = date("Y-m-d", strtotime("tomorrow"));

//if its local
$totalDuration = $s_Expected_Local_Duration;


if($totalDuration==0)
{
// For Priority Express
//if its today and start date time is N + 1 hr.

$db_expect_start = $today . " " . $s_start_Delivery_Time;
$db_expect_end = $today . " " . $s_Last_Delivery_Time;
}

if($totalDuration==1)
{
$db_expect_start = $tommorow . " " . $s_start_Delivery_Time;

//we need add N days

//if local +1 cause it start tommorow
$addDays = $s_Expected_Local_Duration;
$db_expect_end = Date('Y-m-d', strtotime("+$addDays days")) . " " . $s_Last_Delivery_Time;
}

if($totalDuration>1)
{
// For Non Priority Express
//if its today and start date time is N + 1 day + start time.

$db_expect_start = $tommorow . " " . $s_start_Delivery_Time;

//we need add N days

$addDays = $s_Expected_Local_Duration;

$setDate = $tommorow;
$setDate = strtotime($setDate);
$setDate = strtotime("+$addDays days", $setDate);
$setDate = date('Y-m-d', $setDate);

$db_expect_end = $setDate . " " . $s_Last_Delivery_Time;

}

if($s_Expected_Overseas_Duration>1)
{
//means its a oversea package

$db_expect_start = $tommorow . " " . $s_start_Delivery_Time;

$addDays = $s_Expected_Overseas_Duration;

$setDate = $tommorow;
$setDate = strtotime($setDate);
$setDate = strtotime("+$addDays days", $setDate);
$setDate = date('Y-m-d', $setDate);

$db_expect_end = $setDate . " " . $s_Last_Delivery_Time;

}

//echo "Expect Start Date > " . $db_expect_start;
//echo "<br/>";
//echo "Expect End Date > " . $db_expect_end;

$db_expect_start_date = strtotime($db_expect_start);
$db_expect_start_date = date('Y-m-d H:i:s', $db_expect_start_date);

$db_expect_end_date = strtotime($db_expect_end);
$db_expect_end_date = date('Y-m-d H:i:s', $db_expect_end_date);

// Insert into Database
$sql = "INSERT INTO delivery ".
       "(Manifest_Id , Expected_Start_DateTime, Expected_End_DateTime) ".
       "VALUES ".
       "('$trackingID','$db_expect_start_date','$db_expect_end_date')";


$retval = mysql_query($sql);

if(! $retval )
{
echo $sql;
  die('System Error, please contact our counter regarding this issue.');
}

$shell_cmd = "php d_deliveryleg.php ". $trackingID;

shell_exec($shell_cmd);

echo "Deliver Setup Done.";

/* Delivery Table
##############################################################################################

the system will check the mail status at the mail table if it is completed or not
if not it will check its leg status and the leg count
if there is legs that is not completed
than it will schedule for a driver or mailbag
if it is in the last leg and the status is still not completed
than it will assign a bike driver

9am - 12 noon for collection/delivery of mail items
12 noon - 2pm for sorting of mail items in PDCs; this is also the rest time for delivery man.
2pm - 6pm for the delivery of mail items; at 5pm the delivery man will collect mails from the postbox/post office.

Delivery_Id - Auto Generate 
Manifest_Id - Described Below
Expected_Start_DateTime  - Calculation Algorithm Needed
Expected_End_DateTime - Calculation Algorithm Needed
Actual_Start_DateTime - Accept Null
Actual_End_Datetime - Accept Null
Vehicle_Id - Need to do a script to allocate vehicle & check for available vehicle
International_Traking_Id - Not applicable as of now
*/

//draw the service type first


$expect_start_dt = "";

/* PE & P Condition
Manifest ID equals to Tracking ID

Rest - Follow on Mail Bag ID

- Coding Part 1 will be on P.E and P -
- Expected Start Date Time  (If same day + 1 hr )
- Expected End Date Time 

Value take from Service Type ID 

Actual Start & End - Android Update

Vehicle ID - Building_Id , Available_Status & Threshold , Vehicle Type Id

Van for < 10 mail bag
Truck for > 10 mail bag

Motorbike for Priority Express & Priority

###############################################################################################
*/

// 4 SQL to complete task

// First SQL draw Service Type ID from Manifest ID - Assume P.E / P
// If service type is non p.e / p , generate mail bag to obtain mainfest id
// Generation of mail bag sequence as followed
// Manifest ID in format ddmmyyy_o1 (o refer to oversea, r refer to registered, u refer to unregistered ) ( 1 refer to mail bag number for the day )
// no pieces is referring to amount of item in the mail bag
// total weight is the sum of weight of all items in mail bag 


// 2nd SQL Draw - Vehicle Available on building id

// 3rd SQL - Update Delivery Table

// 4th SQL - Plan Delivery Leg - Now with schedule.php on trip leg



?>