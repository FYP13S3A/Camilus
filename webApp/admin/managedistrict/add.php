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
<title>Camilus - Confirm Mail</title>

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
  <div class="wireframemenu">
<ul>
<li><a href="">Home</a></li>

<?php 
//########### ROLE Links START #############
if($role=="counter_staff")
{
echo '<li><a href="confirm_mail.php">Confirm Mail</a></li>';
}

if($role=="region_manager")
{
echo '<li><a href="managezone.php">Manage Region</a></li>';
echo '<li><a href="manage_district.php">Manage District</a></li>';
}

//########### ROLE Links END #############
?>

 <?php
$sql = "SELECT * FROM  `zone` ORDER BY  `Zone_Id` ASC ";
$result= mysql_query($sql);
//POPULATE DROPDOWN LIST
$sql="SELECT * FROM zone";
$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}


?>

<li><a href="../zones/logout.php">Logout</a></li>
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
    <td><form action="" method="post" name="frmAddZone" id="frmAddZone">
      <table width="57%" border="0">
        <tr>
          <td width="55%"><label class="frmItemName">&nbsp;Name :</label></td>
          <td width="45%"><input type="text" name="s_Name" id="z_Name" size="40" value=""></td>
        </tr>
        <tr>
          <td><label class="frmItemName">&nbsp;Zone</label></td>
          <td>
          <select name="s_Country" >

<?php
foreach($result_array AS $row)
{
echo "<option value=\"" . $row[Zone_Id] . "\">" . $row[Name] . "</option>";

}
?>

    </select>
          
          
          </td>
        </tr>
        <tr>
          <td><label class="frmItemName">&nbsp;Postal Sector : (First Two Digits)</label></td>
          <td><input type="text" name="s_Locations" id="z_Name2" size="40" value=""></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" name="btnAdd" id="btnAdd" value="Add Zone"></td>
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