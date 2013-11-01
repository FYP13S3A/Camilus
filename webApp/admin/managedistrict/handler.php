<?php
include '../../conn.php';
if($_SERVER['REQUEST_METHOD'] == 'GET'){
if (isset($_GET['id']) && is_numeric($_GET['id'])){
 	 $id = $_GET['id'];
 	 $result = mysql_query("DELETE FROM district WHERE District_Id=$id")
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
	$Zone_Id = modify_input($_POST['Zone_Id']);
	$Postal_Sector = modify_input($_POST['Postal_Sector']);
	
	if($_POST['mode'] =="update"){
		updateDistrict($id,$Zone_Id,$Postal_Sector);
		
	}else if($_POST['mode']=== "new"){
		addDistrict($Zone_Id,$Postal_Sector);
		
		}
}

function modify_input($data){
	
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
function updateDistrict($id,$Zone_Id,$Postal_Sector){
	
	$result=mysql_query("SELECT * FROM district WHERE Postal_Sector= $Postal_Sector AND Zone_Id= $Zone_Id");
	$rowcount=mysql_num_rows($result);
	
	if ($rowcount >0){
		echo "<script> window.alert(\"Update unsuccessful!\");
		window.location.replace('manage.php?id=$id');
        </script>";
	}else{
		mysql_query("UPDATE district SET Zone_Id='$Zone_Id', Postal_Sector='$Postal_Sector' WHERE District_Id='$id'")
 		or die(mysql_error());
		echo "<script> window.alert(\"Record updated successfully!\");
		window.location.replace('index.php');
        </script>";
	}
 }
function addDistrict($Zone_Id,$Postal_Sector){

	$result=mysql_query("SELECT * FROM district WHERE Postal_Sector= $Postal_Sector");
	$rowcount=mysql_num_rows($result);

	if ($rowcount >0){
		echo "<script> window.alert(\"Insert unsuccessful!\");
		window.location.replace('manage.php');
        </script>";
		
	}else{
		$result=mysql_query("INSERT INTO `district` (Zone_id, Postal_Sector) VALUES (\"$Zone_Id\", \"$Postal_Sector\")")
		or die (mysql_error());
		echo "<script> window.alert(\"Record inserted successfully!\");
		window.location.replace('index.php');
        </script>";
	}

}//End of Function

?>