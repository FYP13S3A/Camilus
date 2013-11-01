<?php
include '../../conn.php';
if($_SERVER['REQUEST_METHOD'] == 'GET'){
if (isset($_GET['id']) && is_numeric($_GET['id'])){
 	 $id = $_GET['id'];
    $result=mysql_query("SELECT User_Id FROM vehicle WHERE Vehicle_Id=$id");
	$row = mysql_fetch_assoc($result);
	$deleteDriver = $row['User_Id'];
	$result=mysql_query("UPDATE `account` SET `Availability_Status` = 1 WHERE `Id` = $deleteDriver");
 	$result = mysql_query("DELETE FROM `vehicle` WHERE Vehicle_Id=$id")
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
	$regno = modify_input($_POST['regno']);
	$vehicletype = modify_input($_POST['vehicletype']);
	$building = modify_input($_POST['buildingSelect']);
	$driver = modify_input($_POST['driverSelect']);
	$oldDriver=modify_input($_POST['oldDriver']);

	if($_POST['mode'] =="update"){
		updateVehicle($id,$regno,$vehicletype,$building,$driver,$oldDriver);
		
	}else if($_POST['mode']=== "new"){
		addVehicle($id,$regno,$vehicletype,$building,$driver,$oldDriver);
		
		}
}

function modify_input($data){
	
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
function updateVehicle($id,$regno,$vehicletype,$building,$driver,$oldDriver){
	$result=mysql_query("SELECT * FROM vehicle WHERE `Vehicle_Id` = $id"); //Checks if the record exists
	$rowcount=mysql_num_rows($result);
	if($rowcount>0){
	$result=mysql_query("UPDATE `vehicle` SET `Registration_Number` = '$regno', `Building_Id` = $building, `User_Id` = $driver, `Vehicle_Type_Id` = $vehicletype WHERE `Vehicle_Id` = $id");//Update the vehicle table
	

			if ($result >0){
if($driver!=$oldDriver){
				$result=mysql_query("UPDATE `account` SET `Availability_Status` = 0 WHERE `Id` = $driver");	//Make the availability status of the new driver to be ocupied 1
				$result=mysql_query("UPDATE `account` SET `Availability_Status` = 1 WHERE `Id` = $oldDriver");	//Make the availability status of the old driver to be available 0
}else if($driver==$oldDriver){
	$result=mysql_query("UPDATE `account` SET `Availability_Status` = 0 WHERE `Id` = $driver");	//IF now driver change than make the current driver occupied 1
}
		echo "<script> window.alert(\"Record updated successfully!\");
		window.location.replace('index.php');
        </script>";
		}else{
		echo "<script> window.alert(\"Update unsuccessful!\");
		window.location.replace('manage.php?id=$id');
        </script>";
		}
	}else{
		echo "<script> window.alert(\"Update unsuccessful!\Record does not existis!\");
		window.location.replace('index.php');
        </script>";
	}
	



 }
function addVehicle($id,$regno,$vehicletype,$building,$driver,$oldDriver){

	$result=mysql_query("SELECT * FROM vehicle WHERE Registration_Number = '$regno'");
	$rowcount=mysql_num_rows($result);

	if ($rowcount >0){
		echo "<script> window.alert(\"Insert unsuccessful!\Record existis\");
		window.location.replace('manage.php');
        </script>";
		
	}else{
		$result=mysql_query("INSERT INTO `vehicle`(`Registration_Number`, `Building_Id`, `User_Id`, `Vehicle_Type_Id`,`Availability_Status`) VALUES (\"$regno\", $building, $driver, $vehicletype, 1)")
		or die (mysql_error());
		
		if($result>0){
			$result=mysql_query("UPDATE `account` SET `Availability_Status` = 0 WHERE `Id` = $driver");	//Make the availability status of the new driver to be ocupied 1
				echo "<script> window.alert(\"Record inserted successfully!\");
		window.location.replace('index.php');
        </script>";
		}else{
					echo "<script> window.alert(\"Insert unsuccessful!\");
		window.location.replace('manage.php');
        </script>";
		}
	
	}

}//End of Function

?>