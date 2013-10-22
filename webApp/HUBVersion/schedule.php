<?php

include 'conn.php';

// this is for collection.
// for the counter staff - start location = post office , follow by nearest pdc to post office,  then recipient pdc, recipient house


//plan the route for shipment
$trackingID = $_GET['id'];

$sql = "select * from mail where Mail_Reference_No='" . $trackingID ."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_mail[] = $row;
}

//get the address ID of both sender and recipient
$s_AddressID = $result_mail[0][Sender_Address_Id];
$r_AddressID = $result_mail[0][Recipient_Address_Id];


$sql = "select * from address where Address_Id='" . $s_AddressID."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_sender[] = $row;
}

$s_Postal = $result_sender[0][Postal_Code];

//recipient address ID - need to draw from address table.

$sql = "select * from address where Address_Id='" . $r_AddressID."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_recipient[] = $row;
}

$r_Postal = $result_recipient[0][Postal_Code];

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


echo $s_zoneName . " -> " . $r_zoneName . " -> House of recipient "; 


?>
