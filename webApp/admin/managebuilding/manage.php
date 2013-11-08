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
 		 $result = mysql_query("SELECT * FROM building WHERE Building_Code=$id")
 		 or die(mysql_error()); 
		 $rowcount = mysql_num_rows($result);
		$row = mysql_fetch_array($result);
		$mode ="update";
		$btnvalue="Update Building";
	
		 if($rowcount>0){
			$name = $row['Name'];
			$phone = $row['Phone'];  
			$add1 = $row['Address_Line1']; 
			$add2 = $row['Address_Line2']; 
			$state = $row['State']; 
			$postal = $row['Postal_Code']; 
			$districtid = $row['District_Id']; 
			$typeid = $row['Building_Type_Id'];
		 }else{
			 echo "No results!"; 
		 }
	 }//GET CHECK END
	 else{
		 $mode = "new";
		 $btnvalue="Add Building";
	 }
 
 ?>
  <?php
 //POPULATE DISTRICT DROPDOWN LIST
	$sql = "SELECT District_Id,Postal_Sector FROM district ORDER BY District_Id ASC";
	$result=mysql_query($sql);

	while ($row = mysql_fetch_assoc($result)) {
   			$district[] = $row;
	}
	 //POPULATE BUILDING TYPE DROPDOWN LIST
	$sql = "SELECT Building_Type_Id, Name FROM buildingtype ORDER BY Building_Type_Id ASC";
	$result=mysql_query($sql);

	while ($row = mysql_fetch_assoc($result)) {
		 $buildingtype[] =  $row;

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
<title>Camilus - Building Management</title>

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
<script src="../../_script/respond.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.15/jquery.form-validator.min.js"></script>
<script src="../../_script/jquery.form-validator.js"></script>
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
    <td><label class="sectionTitle" id="frmItem"><?php 
	if($mode=="new"){
		echo $btnvalue;
	}else if ($mode=="update"){
		echo $btnvalue;
	}
	?>
	</label></td>
    </tr>
  <tr>
    <td></td>
    </tr>
  <tr>
    <td><form action="handler.php" method="post" name="frmAddZone" id="frmAddZone">
    <input type="hidden" name="mode" value="<?php echo $mode; ?>"/>
    
      <table width="56%" height="343" border="0">
        <tr>
          <td><label class="frmItemName">Building Code :</label></td>
          <td><input type="text" name="code" value="<?php echo $id; ?>" data-validation="number length" data-validation-length="max11" data-validation-error-msg="Please enter building code!"/></td>
        </tr>
        <tr>
          <td width="52%"><label class="frmItemName">Name :</label></td>
          <td width="48%"><input type="text" name="name"  size="40" value="<?php echo $name;?>" data-validation="custom length" data-validation-length="max50" data-validation-regexp="^([a-zA-Z]+)$" data-validation-error-msg="Please enter building name!"></td>
        </tr>
        <tr>
          <td><label class="frmItemName">Phone Number :</label></td>
          <td><input name="phone" type="text" value="<?php echo $phone;?>" size="40" data-validation="number length" data-validation-length="max50" data-validation-error-msg="Please enter phone number!">          
            </tr>
        <tr>
          <td><label class="frmItemName">Address Line 1:</label></td>
          <td ><textarea name="add1" cols="42" rows="5" data-validation="custom length" data-validation-length="max50" data-validation-regexp="^([a-zA-Z]+)$" data-validation-error-msg="Please enter address line 1!" ><?php echo $add1;?></textarea>          
            </tr>
        <tr>
          <td><label class="frmItemName">Address Line 2 :</label></td>
          <td><textarea name="add2" cols="42" rows="5" data-validation="custom length" data-validation-length="max50" data-validation-regexp="^([a-zA-Z]+)$" data-validation-error-msg="Please enter address line 2!" ><?php echo $add2;?></textarea></td>
        </tr>
        <tr>
          <td><label class="frmItemName">State : </label></td>
          <td><input name="state" type="text" value="<?php echo $state;?>" size="40" data-validation="custom length" data-validation-length="max50" data-validation-regexp="^([a-zA-Z]+)$" data-validation-error-msg="Please enter state!" ></td>
        </tr>
        <tr>
          <td><label class="frmItemName">Postal Code :</label></td>
          <td><input name="postal" type="text"  value="<?php echo $postal;?>" size="40"  data-validation="number length"data-validation-length="max11" data-validation-error-msg="Please enter postal code!" ></td>
        </tr>
                <tr>
                  <td><label class="frmItemName">Postal Sector :</label></td>
                  <td><select name="districtid">
                    <?php
				foreach($district AS $row){
					if($row['District_Id']==$districtid){
   						echo "<option selected=\"selected\" value=\"" . $row['District_Id'] . "\">" . $row['Postal_Sector'] . "</option>";
					}//end if
				else{
   						echo "<option value=\"" . $row['District_Id'] . "\">" . $row['Postal_Sector'] . "</option>";
					}//end else
				}
			?>
                    </select></td>
                </tr>
                <tr>
          <td><span class="frmItemName">
            <label class="frmItemName">Building Type :</label></span></td>
          <td><select name="typeid">
           <?php
				foreach($buildingtype AS $row){
					if($row['Building_Type_Id']==$typeid){
   						echo "<option selected=\"selected\" value=\"" . $row['Building_Type_Id'] . "\">" . $row['Name'] . "</option>";
					}//end if
				else{
   						echo "<option value=\"" . $row['Building_Type_Id'] . "\">" . $row['Name'] . "</option>";
					}//end else
				}
			?>
				
          </select></td>
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
<script>
(function($) {

    var dev = window.location.hash.indexOf('dev') > -1 ? '.dev' : '';
	


    $.validate({
        language : {
            requiredFields: 'All these fields are required!!'
        },
        errorMessagePosition : 'top',
        scrollToTopOnError : true,
          decimalSeparator : '.',
        onValidate : function() {
            var $callbackInput = $('#callback');
            if( $callbackInput.val() == 1 ) {
                return {
                    element : $callbackInput,
                    message : 'This validation was made in a callback'
                };
            }
        }
      
    });

 

})(jQuery);
</script>
</body>
</html>