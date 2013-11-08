<?php
include '../../conn.php';
if($_SERVER['REQUEST_METHOD'] == 'GET'){
if (isset($_GET['id']) && is_numeric($_GET['id'])){
 	 $id = $_GET['id'];
 	 $result = mysql_query("DELETE FROM `buildingtype` WHERE `Building_Type_Id` = $id")
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
	if($_POST['mode'] =="update"){
		updateBuildingType($id,$name);
	}else if($_POST['mode']=== "new"){
		addBuildingType($name);
		}
}

function modify_input($data){
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
function updateBuildingType($id,$name){

		$result=mysql_query("SELECT * FROM buildingtype WHERE `Building_Type_Id` = $id");
		$rowcount=mysql_num_rows($result);
	if ($rowcount ==1){
		$result=mysql_query("UPDATE `buildingtype` SET `Name` = '$name' WHERE `Building_Type_Id` = $id");
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
function addBuildingType($name){

		$result=mysql_query("SELECT * FROM buildingtype WHERE Name =$name");
		
if ($result>0){
		echo "<script> window.alert(\"Insert unsuccessful!\Record exists!\");
		window.location.replace('manage.php');
        </script>";
}else{
	
		$result=mysql_query("INSERT INTO `buildingtype` (`Building_Type_Id`, `Name`) VALUES (NULL, '$name')");	
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