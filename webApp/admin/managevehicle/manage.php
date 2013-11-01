<?php
include '../../conn.php';
session_start();

//dashboard codes
$session_user = $_SESSION['username'];
$role = $_SESSION['role'];

if(trim($session_user)=="" || $role!="region_manager")
{
header('Location: http://www.efxmarket.com/HUBVersion/index.php');
}

?>
 <?php
 
 	 if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
		 $id = $_GET['id'];
 		 $result = mysql_query("SELECT * FROM vehicle WHERE Vehicle_Id = $id")
 		 or die(mysql_error()); 
		 $rowcount = mysql_num_rows($result);
		$row = mysql_fetch_array($result);
		$mode ="update";
		$btnvalue="Update District";
	
		 if($rowcount>0){
			$regno = $row['Registration_Number'];
			$vehicletype = $row['Vehicle_Type_Id'];  
			$locationId = $row['Building_Id']; 
			$driver = $row['User_Id'];
			
		 }else{
			 echo "No results!"; 
		 }
	 }//GET CHECK END
	 else{
		 $mode = "new";
		 $btnvalue="Add District";
	 }
 
 ?>
 <?php
 //POPULATE VEHICLE DROPDOWN LIST
	$sql = "SELECT Vehicle_Type_Id, Name FROM vehicletype ORDER BY Vehicle_Type_Id ASC";
	$result=mysql_query($sql);

	while ($row = mysql_fetch_assoc($result)) {
   			$vehicleType[] = $row;
	}
	 //POPULATE BUILDING DROPDOWN LIST
	$sql = "SELECT Building_Code, Name FROM building ORDER BY Building_Code ASC";
	$result=mysql_query($sql);

	while ($row = mysql_fetch_assoc($result)) {
		 $building[] =  $row;

	}
	
		 //POPULATE DRIVERS DROPDOWN LIST
		 if($mode=="update"){
			 		$sql = "SELECT Id, FullName,Work_Location_Id as Building_Code FROM account WHERE Id=$driver AND Availability_Status=0";
		$result=mysql_query($sql);
		$drivers='';
		$row = mysql_fetch_assoc($result);
		$drivers[$row['Building_Code']][] = array("id" => $row['Id'], "val" => $row['FullName']);
		 }

		 
		 
	$sql = "SELECT Id, FullName, Work_Location_Id as Building_Code FROM account WHERE Availability_Status=1 ORDER BY Work_Location_Id ";
	$result=mysql_query($sql);
	
	while ($row = mysql_fetch_assoc($result)) {
		 $drivers[$row['Building_Code']][] = array("id" => $row['Id'], "val" => $row['FullName']);
	}
	
$jsondrivers = json_encode($drivers);

?>
 

<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Camilus -District Management</title>

<link href="../../_css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="../../_css/layout.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/css/bootstrap.min.css" />
<link rel="stylesheet" href="../../_css/typography.css" />


<!-- 
To learn more about the conditional comments around the html tags at the top of the file:
paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/

Do the following if you're using your customized build of modernizr (http://www.modernizr.com/):
* insert the link to your js here
* remove the link below to the html5shiv
* add the "no-js" class to the html tags at the top
* you can also remove the link to respond.min.js if you included the MQ Polyfill in your modernizr build 
-->
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="../zones/_script/respond.min.js"></script>
<script type='text/javascript'>

<?php
        
        echo "var drivers = $jsondrivers; \n";
      ?>
      function updateSubCats(){
        var catSelect = document.getElementById("buildingSelect");
        var catid = catSelect.value;
        var subcatSelect = document.getElementById("driverSelect");
        subcatSelect.options.length = 0; //delete all options if any present
        for(var i = 0; i < drivers[catid].length; i++){
          subcatSelect.options[i] = new Option(drivers[catid][i].val,drivers[catid][i].id);
        }
      }
    </script>

</head>

<body onLoad="updateSubCats()">
<div class="gridContainer clearfix">
<div id="contentBox">
   <?php include("../../header.php"); ?>
  <div class="leftSidebar">
  <div  id="navigationBox">
  <div id="ddmenu">
<ul>
<li class="active"><a href="">Home</a></li>

<?php 
//########### ROLE Links START #############
if($role=="counter_staff")
{
echo '<li><a href="confirm_mail.php">Confirm Mail</a></li>';
}

if($role=="region_manager")
{
echo '<li class=\"has-sub\"><a href="../managezone/index.php">Manage Zone</a>';
echo '<ul><li><a href="../managezone/index.php">View Zone</a></li>';
echo '<li class=\"last\"><a href="../managezone/manage.php">Add Zone</a></li></ul></li>';

echo '<li class=\"has-sub\"><a href="../managedistrict/index.php">Manage District</a>';
echo '<ul><li><a href="../managedistrict/index.php">View District</a></li>';
echo '<li class=\"last\"><a href="../managedistrict/manage.php">Add District</a></li></ul></li>';

echo '<li class=\"has-sub\"><a href="../managevehicle/index.php">Manage Vehicle</a>';
echo '<ul><li><a href="../managevehicle/index.php">View Vehicle</a></li>';
echo '<li><a href="../managevehicle/manage.php">Add Vehicle</a></li>';
echo '<li><a href="../managevehtype/index.php">View Vehicle Types</a></li>';
echo '<li class=\"last\"><a href="../managevehtype/manage.php">Add Vehicle Types</a></li></ul></li>';
}

//########### ROLE Links END #############
?>
<li class="last"><a href="../../logout.php">Logout</a></li>
</ul>
</div>
  </div>
  </div>
  <div id="mainContent">
<div>
<table width="100%" border="0">
  <tr>
    <td><label class="sectionTitle" id="frmItem">Add District</label></td>
    </tr>
  <tr>
    <td></td>
    </tr>
  <tr>
    <td><form action="handler.php" method="post" name="frmAddZone" id="frmAddZone">
    <input type="hidden" name="mode" value="<?php echo $mode; ?>"/>
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
     <input type="hidden" name="oldDriver" value="<?php echo $driver; ?>"/>
      <table width="57%" border="0">
        <tr>
          <td width="55%"><label class="frmItemName">Registration Number :</label></td>
          <td width="45%"><input type="text" name="regno" id="regno" size="40" value="<?php echo $regno;?>"></td>
        </tr>
        <tr>
          <td><label class="frmItemName">&nbsp;Vehicle Type :</label></td>
          <td colspan="2"><select name="vehicletype">
            <?php
				foreach($vehicleType AS $row){
					if($row['Vehicle_Type_Id']==$vehicletype){
   						echo "<option selected=\"selected\" value=\"" . $row['Vehicle_Type_Id'] . "\">" . $row['Name'] . "</option>";
					}//end if
				else{
   						echo "<option value=\"" . $row['Vehicle_Type_Id'] . "\">" . $row['Name'] . "</option>";
					}//end else
				}//end foreach
			?>
          </select>          </tr>
        <tr>
      <td><label class="frmItemName">&nbsp;Building :</label></td>
          <td colspan="2"><select name="buildingSelect" id="buildingSelect" onChange="updateSubCats()">
               <?php
				foreach($building AS $row){
					if($row['Building_Code']==$locationId){
   						echo "<option selected=\"selected\" value=\"" . $row['Building_Code'] . "\">" . $row['Name'] . "</option>";
					}//end if
				else{
   						echo "<option value=\"" . $row['Building_Code'] . "\">" . $row['Name'] . "</option>";
					}//end else
				}//end foreach
			?>
          
          </select>            </tr>
        <tr>
     <td><label class="frmItemName">&nbsp;Driver :</label></td>
          <td colspan="2">
         
          <select name="driverSelect" id="driverSelect" >
            
          
          </select> 
          </tr>
        
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" name="btnAdd" id="btnAdd" value="<?php echo $btnvalue;?>"></td>
        </tr>
      </table>
    </form></td>
  </tr>
  </table>



</div>



</div>
<?php include("../../footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>