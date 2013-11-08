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
 		 $result = mysql_query("SELECT Name, Service_Information, Expected_Local_Duration, Expected_Overseas_Duration, Expected_Start_Time, Last_Collection_Time, Last_Delivery_Time, mailbag_usage FROM servicetype WHERE Service_Type_Id=$id")
 		 or die(mysql_error()); 
		 $rowcount = mysql_num_rows($result);
		$row = mysql_fetch_array($result);
		$mode ="update";
		$btnvalue="Update Service Type";
	
		 if($rowcount>0){
			$name = $row['Name'];
			$serviceInfo = $row['Service_Information'];  
			$localdura = $row['Expected_Local_Duration']; 
			$overseasdura = $row['Expected_Overseas_Duration']; 
			$starttime = $row['Expected_Start_Time']; 
			$coltime = $row['Last_Collection_Time']; 
			$deltime = $row['Last_Delivery_Time']; 
			$mailbag = $row['mailbag_usage'];
		 }else{
			 echo "No results!"; 
		 }
	 }//GET CHECK END
	 else{
		 $mode = "new";
		 $btnvalue="Add Service Type";
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
<title>Camilus - Service Type Management</title>

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
		echo "Add Service Type";
	}else if ($mode=="update"){
		echo "Update Service Type";
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
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
      <table width="56%" height="343" border="0">
        <tr>
          <td width="52%"><label class="frmItemName">Service Type Name :</label></td>
          <td width="48%"><input type="text" name="name"  size="40" value="<?php echo $name;?>"  data-validation="custom length" data-validation-length="max30" data-validation-regexp="^([a-zA-Z]+)$" data-validation-error-msg="Please enter service type name."/></td>
        </tr>
        <tr>
          <td><label class="frmItemName">Service Information :</label></td>
          <td>
            <textarea name="serviceInfo" cols="42" rows="5"  data-validation="custom length" data-validation-length="max100" data-validation-regexp="^([a-zA-Z]+)$" data-validation-error-msg="Please enter the service information."><?php echo $serviceInfo;?> </textarea>          
            </tr>
        <tr>
          <td><label class="frmItemName">Expected Local Duration :</label></td>
          <td >
            <input name="localdura" type="text"  value="<?php echo $localdura;?>" size="10" data-validation="number length" data-validation-length="1-2" data-validation-error-msg="Please enter the expected local duration."></tr>
        <tr>
          <td><label class="frmItemName">Expected Overseas Duration :</label></td>
          <td><input name="overseasdura" type="text"  value="<?php echo $overseasdura;?>" size="10" data-validation="number length" data-validation-length="1-2" data-validation-error-msg="Please enter the expected oversea duration." /></td>
        </tr>
        <tr>
          <td><label class="frmItemName">Expected Start Time : (HH:MM:SS)</label></td>
          <td><input name="starttime" type="text"  value="<?php echo $starttime;?>" size="10" data-validation="required" data-validation-error-msg="Please enter in the following format HH:MM:SS."></td>
        </tr>
        <tr>
          <td><label class="frmItemName">Last Collection Time : (HH:MM:SS)</label></td>
          <td><input name="coltime" type="text"  value="<?php echo $coltime;?>" size="10" data-validation="required" data-validation-error-msg="Please enter in the following format HH:MM:SS."></td>
        </tr>
             <tr>
          <td><label class="frmItemName">Last Delivery Time : (HH:MM:SS)</label></td>
          <td><input name="deltime" type="text"  value="<?php echo $deltime;?>" size="10" data-validation="required" data-validation-error-msg="Please enter in the following format HH:MM:SS."></td>
        </tr>
                <tr>
          <td><label class="frmItemName">Mail Bag Needed :</label></td>
          <td><select name="mailbag">
            <?php
				
					if($mailbag==1){
   						echo "<option selected=\"selected\" value=\"" . '1' . "\">" . 'Yes' . "</option>";
						echo "<option value=\"" . '0' . "\">" . 'No'. "</option>";
					}//end if
				else if ($mailbag==0){
					echo "<option selected=\"selected\" value=\"" . '0' . "\">" . 'No' . "</option>";
   						echo "<option value=\"" . '1' . "\">" . 'Yes'. "</option>";
					}//end else
				
			?>
          </select></td>
        </tr>
        <tr>
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
            requiredFields: 'All these fields are required.'
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