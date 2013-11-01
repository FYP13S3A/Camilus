<?php
include '../../conn.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$id = $_POST['id'];

	$name = modify_input($_POST['Name']);
	
	$locations = modify_input($_POST['Locations']);
	
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
	 $data = mysql_real_escape_string($data);
     return $data;
}
function updateZone($id,$name,$locations)
{
   
  mysql_query("UPDATE zone SET Name='$name', Locations='$locations' WHERE Zone_Id='$id'")
 or die(mysql_error());

 }
function addZone($id,$name,$locations)
{
$result=mysql_query("SELECT * FROM zone WHERE Zone_Id= $id");
$rowcount=mysql_num_rows($result);

if($rowcount <=0){
$resultmysql_query("INSERT INTO zone (`Zone_Id` ,`Name` ,`Locations`)VALUES ($id,'$name','$locations')")
or die(mysql_error());
if(mysqli_affected_rows($result)==1){
 header('Location:index.php?status=true');
}
}else{
header('Location:index.php?status=false');
}


 }

?>