<?php

include 'conn.php';

/*
post office - after 5pm create mail bag
local pdc - reach same day. Create mailbag next day 4pm. to deliver at 5pm to foreign pdc
foreign pdc - reach same day. Deliver next day anytime
*/

//set time zone to singapore
date_default_timezone_set("Asia/Singapore");

//Define Weight Limit
$weightLimit = "10.0";

//bag max pieces
$maxPieces = 3;


/*
Look FOR ALL MAIL that got no mail bag id yet and if the delivery suppose to start.

MAX: 50 Pieces
Weight: 10 kg

How mailbag arrangement:

Mailbag are for non-priority mail like registered mail.
Firstly, mailbag are created when the following is fulfill

> Check if service type require mailbag > mailbag_usage
> Registered Mail of Identical "Destination" & "Departure" are grouped together.
> Running a while loop to keep add item to "mailbag" until it max and reach its limit (50 pieces or 10kg in weight)
> Mailbag can be an array, that push manifest id into its collection.
> Once all registered mail had a mailbag assign, they will be put into the mailbag, and prepare for deliver.
*/

$sql="SELECT Mail_Reference_No,Mail_Weight from mail where Approve_Status='1' AND Delivery_Status='pending' AND Service_Type_Id IN  (select Service_Type_Id from servicetype where mailbag_usage='1')";

$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}//end while loop

//Result are Mails that are APPROVED, delivery status still pending & they required MailBag as indicated in their service type.
// Mail bag are created assuming that all mail for the day is done and going to pack into mailbag & prepare for transfer.



//todayDate in format YYYYMMDD

$todayDate= date("Ymd", time());

foreach($result_array AS $row)
{
$mail_ref = $row[Mail_Reference_No];
$mail_weight = $row[Mail_Weight];

$sql_delivery = "SELECT * FROM delivery where Manifest_Id='$mail_ref'";
$result_delivery = mysql_query($sql_delivery);

while ($row_delivery = mysql_fetch_assoc($result_delivery)) {
   $result_delivery_array[] = $row_delivery;
}//end while delivery contain manifest

$expected_start_date_time = $result_delivery_array[0][Expected_Start_DateTime];

unset($result_delivery_array);

//check if job are suppose to start.
if (time() >= strtotime($expected_start_date_time)) 
{

$sql1= "SELECT * FROM deliveryleg WHERE delivery_Id IN (SELECT delivery_Id FROM delivery WHERE Manifest_Id =  '$mail_ref' AND Leg_Status='pending') LIMIT 0,1";


$result1= mysql_query($sql1);


while ($row = mysql_fetch_assoc($result1)) {
   $result_array1[] = $row;
}

//get the address ID of both sender and recipient

$s_legID = $result_array1[0][Delivery_Leg_Id];
$s_Departure = $result_array1[0][Departure];
$s_Destination = $result_array1[0][Destination];
$s_Mailbag = $result_array1[0][Mailbag_Id];

unset($result_array1);

$s_Departure = str_replace("b_","$",$s_Departure);
$s_Destination = str_replace("b_","$",$s_Destination);

echo $mail_ref . $s_Mailbag . "<hr>";

if( (strstr($s_Destination,"$")) && ($s_Mailbag<1))
{

//mailbag format keep as YYYYMMDD-$SOURCE-$DESTINATION-BAGNUM
//source = substr away b_ departure and destination = substr away d_ destination 
//bag number is the bag count for the departure & destination.

$mailbag_manifest = $todayDate . "-" . $s_Departure. "-" . $s_Destination;


//check database if such mailbag is created.
//if its found. check if the current weight & no of pieces will exceed if we add the current item.
//if it will exceed, we need create mailbag number 2, if it will not exceed, we can add it into this current mailbag.
//need to check if the leg destination contain b_ because b_ refer to transfer , pdc to pdc, if its to recipient, it not suppose in mailbag.
//search for bag of the manifest , no matter what bag number it is we use a wildcard %


$sql_check_bag = "select * from mailbag where Manifest_Id LIKE '$mailbag_manifest%' Order By Mailbag_Id DESC LIMIT 1";


$result=mysql_query($sql_check_bag);
$count=mysql_num_rows($result);

// If bag Exist
if($count==1)
{
//if bag exist, we check its weight and its number of pieces

while ($row = mysql_fetch_assoc($result)) {
   $result_array2[] = $row;
}

$s_Bag = $result_array2[0][Manifest_Id];
$s_Weight = $result_array2[0][Total_Weight];
$s_Pieces = $result_array2[0][No_Pieces];

//calculate final weight

$final_Weight = $s_Weight + $mail_weight;

//if Final weight after adding the new mail is less than our limit, we allow it . 

if( ($final_Weight < $weightLimit) && ($s_Pieces < $maxPieces) )
{
//add to mailbag
//we alter the delivery leg and set the mailbag id to the assigned mailbag.
$sql2 = "UPDATE deliveryleg SET Mailbag_Id='$s_Bag' WHERE Delivery_Leg_Id='$s_legID'";


$result=mysql_query($sql2);
if($result == false)
{ 
die("Didn't Update at sql2"); 
}//if no update
}//if within limits of weight & pieces
else
{
//over weight mailbag or pieces is more than our max piece
//we need create 2nd mailbag

//we need know which bag number we are handling
//we can simply break the bag manifest into pieces

$break_Bag = explode("-",$s_Bag);
$bagNumber = $break_Bag[3];
$nextBag = $bagNumber + 1;

$bag_manifest = $break_Bag[0] . "-" .  $break_Bag[1] .  "-" . $break_Bag[2] .  "-" . $nextBag;

//create the new bag
$insertSql = "INSERT INTO mailbag ".
       "(Manifest_Id,No_Pieces,Total_Weight) ".
       "VALUES ".
       "('$bag_manifest',0,0)";

$retval = mysql_query($insertSql);

//assign to the new bag

$sql2b = "UPDATE deliveryleg SET Mailbag_Id='$bag_manifest' WHERE Delivery_Leg_Id='$s_legID'";

$result=mysql_query($sql2b);
if($result == false)
{ 
die("Didn't Update at sql2b"); 
}//if no update

//for update of weight and number of pieces - mailbag
$s_Bag = $bag_manifest;

}//end if need to create a new bag
}//end if bag exist
else
{

//if no bag found. we will create bag 1
$bag_manifest = $mailbag_manifest . "-1";

$insertSql = "INSERT INTO mailbag ".
       "(Manifest_Id,No_Pieces,Total_Weight) ".
       "VALUES ".
       "('$bag_manifest',0,0)";

$retval = mysql_query($insertSql);

if(! $retval )
{  
die('System Error, please contact our counter regarding this issue.');
}

//we alter the delivery leg and set the mailbag id to the assigned mailbag.
$sql2 = "UPDATE deliveryleg SET Mailbag_Id='$bag_manifest' WHERE Delivery_Leg_Id='$s_legID'";	


$result=mysql_query($sql2);
if($result == false)
{ 
    die("Didn't Update at sql2"); 
}

//set selected Bag as bag manifest
$s_Bag = $bag_manifest;

}//end else bag not found


//update the mailbag weight & number of pieces

$sql0 = "select m.Mail_Weight,d.Manifest_Id,dl.Delivery_Id from mail m,delivery d,deliveryleg dl where dl.Mailbag_Id LIKE '$s_Bag%' AND m.Mail_Reference_No=d.Manifest_Id AND d.Delivery_Id=dl.Delivery_Id";

$result0=mysql_query($sql0);
while ($row = mysql_fetch_assoc($result0)) {
   $result_array0[] = $row;
}

$calc_Weight = "0";
$calc_Pieces = mysql_num_rows($result0);

foreach($result_array0 AS $row)
{
$calc_Weight = $calc_Weight + $row[Mail_Weight];
}

//now update mailbag on its current weight & pieces

$sql4 = "UPDATE mailbag SET No_Pieces='$calc_Pieces',Total_Weight='$calc_Weight' WHERE Manifest_Id='$s_Bag'";	


$result=mysql_query($sql4);
if($result == false)
{ 
    die("Didn't Update at sql4"); 
}


//we need re-calculate the bag weight and number of pieces .

unset($result_array2);
unset($result_array1);
unset($result_array0);

}//end if its is for transfer between pdc

}//end if its suppose to start
}//end foreach result array of mailbag that are approve and delivery status as pending.

?>