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
 		 $result = mysql_query("SELECT * FROM district WHERE District_Id = $id")
 		 or die(mysql_error()); 
		 $rowcount = mysql_num_rows($result);
 		 $row = mysql_fetch_array($result);
		$mode ="update";
		$btnvalue="Update District";
		 if($rowcount>0){
			$Zone_Id = $row['Zone_Id'];
			$Postal_Sector = $row['Postal_Sector']; 
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
 //POPULATE DROPDOWN LIST
	$sql = "SELECT * FROM  `zone` ORDER BY  `Zone_Id` ASC ";
	$result=mysql_query($sql);

	while ($row = mysql_fetch_assoc($result)) {
   			$result_array[] = $row;
	}
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

</head>

<body>
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
      <table width="57%" border="0">
        <tr>
          <td width="55%"><label class="frmItemName">&nbsp;Zone :</label></td>
          <td width="45%"><select name="Zone_Id">
            <?php
				foreach($result_array AS $row){
					if($row['Zone_Id']==$Zone_Id){
   						echo "<option selected=\"selected\" value=\"" . $row['Zone_Id'] . "\">" . $row['Name'] . "</option>";
					}//end if
				else{
   						echo "<option value=\"" . $row['Zone_Id'] . "\">" . $row['Name'] . "</option>";
					}//end else
				}//end foreach
			?>
          </select></td>
        </tr>
        <tr>
          <td><label class="frmItemName">&nbsp;Postal Sector : (First Two Digits)</label></td>
          <td colspan="2"><input type="text" name="Postal_Sector" id="Postal_Sector" size="40" value="<?php echo $Postal_Sector; ?>">          
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