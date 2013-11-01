<?php

include 'conn.php';

/* FOR DELIVERYMAN TO FETCH JOBS */

$driverID = $_GET['id'];

//Define Delimiter
$field_Delimiter = "|";
$line_Delimiter = "**";

// delivery/transfer/collection
$type_of_job = "";
$writeLine = "";

//Create Array to Store Data - Transfer
$transfer[] = "";



//delivery,jobId,parcelID,serviceType,sendername,recipientName,recipientAddress
//Create Array to Store Data - Delivery
$delivery[] = "";


//echo "<p>Job List for User <b>" . $driverID . "</b></p><hr>";

//get all jobs related to the driver
$sql1 = "SELECT * FROM `deliveryleg` WHERE Driver_Id='".$driverID."' AND Leg_Status='pending'";
$result1 = mysql_query($sql1);

while ($row1 = mysql_fetch_assoc($result1)) {
   $result_array1[] = $row1;
}//while fetch driver of location

foreach($result_array1 AS $row_list)
{
$itemOutput = "no";

$leg_ID = $row_list[Delivery_Leg_Id];
//echo "Job ID: <b>" . $leg_ID . "</b><br/>";
$delivery_ID = $row_list[Delivery_Id];

//echo $leg_ID . " " . $delivery_ID;

//null if service type is priority/priority express
$MailBag_Id = $row_list[MailBag_Id];

if($MailBag_Id!="")
{
echo "Item (MailBag) : " . $MailBag_Id;
$itemOutput = "yes";
}
else
{
$sql_manifest = "SELECT * FROM delivery WHERE Delivery_Id='$delivery_ID'";
$result_manifest = mysql_query($sql_manifest);
while ($row_manifest = mysql_fetch_assoc($result_manifest)) 
{
   $result_manifest_array[] = $row_manifest;
}//while fetch manifest id by delivery id

$manifest_id = $result_manifest_array[0][Manifest_Id];

//echo "Item : " . $manifest_id . "<br/>";
}

//Get Departure & Destination
$departure = $row_list[Departure];
$destination = $row_list[Destination];

if(strstr($departure,"b_"))
{
$departure_temp = str_replace("b_","",$departure);

//get building details
$sql2 = "SELECT * FROM building WHERE Building_Code='$departure_temp'";
$result2 = mysql_query($sql2);


while ($row2 = mysql_fetch_assoc($result2)) 
{
   $result_array2[] = $row2;
}//while fetch driver of location

$departure_building_name = $result_array2[0][Name];
$departure_building_code = $result_array2[0][Building_Code];

$departure_output = $departure_building_name . " ( " . $departure_building_code . ")";

}//end if departure is a building


if(strstr($destination,"b_"))
{
$type_of_job = "transfer";
$destination_temp = str_replace("b_","",$destination);

//get building details
$sql3 = "SELECT * FROM building WHERE Building_Code='".$destination_temp."'";
$result3 = mysql_query($sql3);

while ($row3 = mysql_fetch_assoc($result3)) 
{
   $result_array3[] = $row3;
}//while fetch driver of location

$destination_building_name = $result_array3[0][Name];
$destination_building_code = $result_array3[0][Building_Code];


$destination_output = $destination_building_name . " (" . $destination_building_code . ")";
}//end if departure is a building
else
{
$type_of_job = "delivery";
//destination is recipient house
//get recipient details
$sql3 = "SELECT * FROM delivery WHERE Delivery_Id='$delivery_ID'";
$result3 = mysql_query($sql3);

while ($row3 = mysql_fetch_assoc($result3)) 
{
   $result_array3[] = $row3;
}//while fetch manifest id by delivery id


$manifest_id = $result_array3[0][Manifest_Id];
$sql4 = "SELECT * FROM mail WHERE Mail_Reference_No='$manifest_id'";

$result4 = mysql_query($sql4);

while ($row4 = mysql_fetch_assoc($result4)) 
{
   $result_array4[] = $row4;
}//while fetch mail to get address id of recipient base on delivery id


$Recipient_Address_Id = $result_array4[0][Recipient_Address_Id];
$senderName = $result_array4[0][Sender_FullName];
$recipientName = $result_array4[0][Recipient_FullName];


$sql5 = "SELECT * FROM address WHERE Address_Id='$Recipient_Address_Id'";
$result5 = mysql_query($sql5);


while ($row5 = mysql_fetch_assoc($result5)) 
{
   $result_array5[] = $row5;
}//while fetch address line base on address id

$recipient_Address = $result_array5[0][Address_Line1];
$destination_output =  $recipient_Address . " (" . $Recipient_Address_Id . ")";

}//end if destination is recipient address.

//get service type name
$sql6 = "SELECT name FROM servicetype WHERE Service_Type_Id IN (Select Service_Type_Id from mail where Mail_Reference_No='$manifest_id')";
$result6 = mysql_query($sql6);

while ($row6 = mysql_fetch_assoc($result6)) 
{
   $result_array6[] = $row6;
}//while fetch address line base on address id

$serviceName = $result_array6[0][name];

if($type_of_job=="transfer")
{
//format: service |ManifestID  |  From        | To
$writeLine = "transfer". $field_Delimiter;
$writeLine .= $leg_ID . $field_Delimiter;
$writeLine .= $manifest_id . $field_Delimiter;
$writeLine .= $departure_output . $field_Delimiter;
$writeLine .= $destination_output;
}

if($type_of_job=="delivery")
{
//format: delivery,jobId,parcelID,serviceType,sendername,recipientName,recipientAddress
$writeLine = "delivery". $field_Delimiter;
$writeLine .= $leg_ID . $field_Delimiter;
$writeLine .= $manifest_id . $field_Delimiter;
$writeLine .= $serviceName . $field_Delimiter;
$writeLine .= $senderName . $field_Delimiter;
$writeLine .= $recipientName . $field_Delimiter;
$writeLine .= $recipient_Address;
}


echo $writeLine;

//echo "Departure > " . $departure_output . "<br>";
//echo "Destination > " . $destination_output . "<hr>";

unset($result_array1);
unset($result_array2);
unset($result_array3);
unset($result_array4);
unset($result_array5);
unset($result_array6);
unset($result_manifest_array);

}//end foreach job


?>