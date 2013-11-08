<?php
include '../../conn.php';
if($_SERVER['REQUEST_METHOD'] == 'GET'){
if (isset($_GET['id']) && is_numeric($_GET['id'])){
 	 $id = $_GET['id'];
 	 $result = mysql_query("DELETE FROM `building` WHERE `Building_Code` = $id")
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
	$id = modify_input($_POST['code']);
	$name = modify_input($_POST['name']);
			$phone = modify_input($_POST['phone']); 
			$add1 = modify_input($_POST['add1']);
			$add2 = modify_input($_POST['add2']);
			$state = modify_input($_POST['state']);
			$postal = modify_input($_POST['postal']);
			$district = modify_input($_POST['districtid']); 
			$typeid = modify_input($_POST['typeid']);
	if($_POST['mode'] =="update"){
		updateBuilding($id,$name,$phone,$add1,$add2,$state,$postal,$district,$typeid);
	}else if($_POST['mode']=== "new"){
		addBuilding($id,$name,$phone,$add1,$add2,$state,$postal,$district,$typeid);
		}
}

function modify_input($data){
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
function updateBuilding($id,$name,$phone,$add1,$add2,$state,$postal,$district,$typeid){

		$result=mysql_query("SELECT * FROM building WHERE `Building_Code` = $id");
		$rowcount=mysql_num_rows($result);
	if ($rowcount ==1){
		echo "UPDATE `building` SET `Building_Code` = '$id', `Name` = '$name', `Phone` = '$phone', `Address_Line1` = '$add1', `Address_Line2` = '$add2', `State` = '$state', `Postal_Code` = '$postal', `District_Id` = '$district', `Building_Type_Id` = '$typeid' WHERE `building`.`Building_Code` = $id;";
		$result=mysql_query("UPDATE `building` SET `Building_Code` = '$id', `Name` = '$name', `Phone` = '$phone', `Address_Line1` = '$add1', `Address_Line2` = '$add2', `State` = '$state', `Postal_Code` = '$postal', `District_Id` = '$district', `Building_Type_Id` = '$typeid' WHERE `building`.`Building_Code` = $id;");
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
function addBuilding($id,$name,$phone,$add1,$add2,$state,$postal,$district,$typeid){
//echo $id;
		//$result=mysql_query("SELECT * FROM building WHERE Building_Code =$id");
	///echo $result;	
	$result=0;
if ($result>1){
		echo "<script> window.alert(\"Insert unsuccessful! Record exists!\");
		window.location.replace('manage.php');
        </script>";
}else{
	
		$result=mysql_query("INSERT INTO `building` (`Building_Code`, `Name`, `Phone`, `Address_Line1`, `Address_Line2`, `State`, `Postal_Code`, `District_Id`, `Building_Type_Id`) VALUES ('$id', '$name', '$phone', '$add1', '$add2', '$state', '$postal', '$district', '$typeid')");	
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