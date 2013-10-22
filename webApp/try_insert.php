<?php
include 'conn.php';

//customerDetail
//sender_address
//recipient_address

$randomString = generateRandomString();

$sql = 'INSERT INTO customerDetail '.
       '(serialNo, senderID, receiverID) '.
       'VALUES ("'.$randomString.'", 1, 1 )';


$result=mysql_query($sql);

if(!$result)
{
  die('Could not enter data: ' . mysql_error());
}


echo "Serial number is \n" . $randomString;
mysql_close();


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


?>