<?php include 'conn.php';


if (!empty($_POST["id"])) {
	$trackingno = $_POST['id'];
//get data from tracking id

$sql = "UPDATE `mail` SET `Delivery_Status` = 'onhold' WHERE `Mail_Reference_No` = '$trackingno'";
$result= mysql_query($sql);
 header("/index.php");
}
?>