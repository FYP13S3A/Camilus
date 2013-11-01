<?php
include '../../conn.php';

if($_SERVER['REQUEST_METHOD'] == 'GET'){
 if (isset($_GET['id']) && is_numeric($_GET['id'])) {
 	$id = $_GET['id'];
 	$result = mysql_query("DELETE FROM zone WHERE Zone_Id=$id")
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
	
	$id = $_POST['id'];
	$name = modify_input($_POST['Name']);
	$name = mysql_real_escape_string($name);
	$locations = modify_input($_POST['Locations']);
	$locations = mysql_real_escape_string($locations);
	
	if($_POST['mode'] =="update"){
		updateZone($id,$name,$locations);
	}else if($_POST['mode']=== "new"){
		addZone($id,$name,$locations);
		
	}
}

function modify_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
	 return $data;
}
function updateZone($id,$name,$locations){
  	$result=mysql_query("SELECT * FROM zone WHERE Zone_Id= $id");
	$rowcount=mysql_num_rows($result);

	if ($rowcount >0){
		mysql_query("UPDATE zone SET Name='$name', Locations='$locations' WHERE Zone_Id='$id'")
 		or die(mysql_error());
 		echo "<script> window.alert(\"Record updated successfully!\");
		window.location.replace('index.php');
        </script>";
	}else{
				echo "<script> window.alert(\"Update unsuccessful!\");
		window.location.replace('manage.php?id=$id');
        </script>";	
		}
 }
function addZone($id,$name,$locations){

	$result=mysql_query("INSERT INTO zone (`Name` ,`Locations`)VALUES ('$name','$locations')")
	or die (mysql_error());
	echo "<script> window.alert(\"Record inserted successfully!\");
	window.location.replace('index.php');
    </script>";
	

}//End of Function

?>