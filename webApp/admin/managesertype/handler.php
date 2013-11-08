<?php
include '../../conn.php';
if($_SERVER['REQUEST_METHOD'] == 'GET'){
if (isset($_GET['id']) && is_numeric($_GET['id'])){
 	 $id = $_GET['id'];
 	 $result = mysql_query("DELETE FROM `servicetype` WHERE `Service_Type_Id` = $id")
 	 or die(mysql_error()); 
	 echo "<script> window.alert(\"Record deleted successfully!\");
	 window.location.replace('index.php');
      </script>";
 	
 }else{
	echo "<script> window.alert(\"Delete unsuccessful!\");
	window.location.replace('index.php');
     </script>";
 }
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$id = modify_input($_POST['id']);
	$name = modify_input($_POST['name']);
	$serviceInfo = modify_input($_POST['serviceInfo']);  
	$localdura = modify_input($_POST['localdura']); 
	$overseasdura = modify_input($_POST['overseasdura']); 
	$starttime = modify_input($_POST['starttime']); 
	$coltime = modify_input($_POST['coltime']); 
	$deltime = modify_input($_POST['deltime']); 
	$mailbag = modify_input($_POST['mailbag']);
	
		$L500g = modify_input($_POST['L500g']); 
	$L2000g = modify_input($_POST['L2000g']); 
	$L5000g = modify_input($_POST['L5000g']);
	if($_POST['mode'] =="update"){
		updateServiceType($id,$name,$serviceInfo,$localdura,$overseasdura,$starttime,$coltime,$deltime,$mailbag,$L500g,$L2000g,$L5000g);
	}else if($_POST['mode']=== "new"){
		addServiceType($id,$name,$serviceInfo,$localdura,$overseasdura,$starttime,$coltime,$deltime,$mailbag,$L500g,$L2000g,$L5000g);
		}
}

function modify_input($data){
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
function updateServiceType($id,$name,$serviceInfo,$localdura,$overseasdura,$starttime,$coltime,$deltime,$mailbag,$L500g,$L2000g,$L5000g){

		$result=mysql_query("SELECT * FROM servicetype WHERE Service_Type_Id=$id");
		$rowcount=mysql_num_rows($result);
	if ($rowcount ==1){
		$result=mysql_query("UPDATE `servicetype` SET `Name` = '$name', `Service_Information` = '$serviceInfo ', `Expected_Local_Duration` = '$localdura', `Expected_Overseas_Duration` = '$overseasdura', `Expected_Start_Time` = '$starttime', `Last_Collection_Time` = '$coltime', `Last_Delivery_Time` = '$deltime', `mailbag_usage` = '$mailbag', Price_Less500g = '$L500g',  Price_Less2000g = '$L2000g', Price_Less5000g = '$L5000g' WHERE `servicetype`.`Service_Type_Id` = $id");
		
	
		if ($result >0){
	echo "<script> window.alert(\"$result record updated successfully!\");
	window.location.replace('index.php');
        </script>";
		}else{
		echo "<script> window.alert(\"Update unsuccessful!\");
		window.location.replace('manage.php?id=$id');
        </script>";
		}
	}else {
		echo "<script> window.alert(\"Record not found!\");
		window.location.replace('index.php');
        </script>";
}

 }
function addServiceType($id,$name,$serviceInfo,$localdura,$overseasdura,$starttime,$coltime,$deltime,$mailbag,$L500g,$L2000g,$L5000g){

		$result=mysql_query("SELECT * FROM servicetype WHERE Service_Type_Id=$id");
		
if ($result>0){
		echo "<script> window.alert(\"Insert unsuccessful!\Record exists!\");
		window.location.replace('manage.php');
        </script>";
}else{
	
		$result=mysql_query("INSERT INTO `servicetype` (`Service_Type_Id`, `Name`, `last_update`, `Service_Information`, `Expected_Local_Duration`, `Expected_Overseas_Duration`, `Expected_Start_Time`, `Last_Collection_Time`, `Last_Delivery_Time`, `mailbag_usage`, Price_Less500g, Price_Less2000g, Price_Less5000g ) VALUES (NULL, '$name', CURRENT_TIMESTAMP, '$serviceInfo', '$localdura', '$overseasdura', '$starttime', '$coltime', '$deltime', '$mailbag', '$L500g', '$L2000g' ,'$L5000g')");	
		if($result >0){
		echo "<script> window.alert(\"$result Record inserted successfully!\");	window.location.replace('index.php');
        </script>";
		}else{
		echo "<script> window.alert(\"Insert unsuccessful!\");
		window.location.replace('manage.php');
        </script>";
		}
}
}//End of Function

?>