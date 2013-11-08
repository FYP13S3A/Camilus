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

$checkMsg = $_GET['success'];
$systemMsg = "";

if($checkMsg=="1")
{
$systemMsg = "<font color=red size=2>System: <b>Successfully modify account.</b></font>";
}
if($checkMsg=="0")
{
$systemMsg = "<font color=red size=2>System: <b>Fail to modify account.</b></font>";
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
<script src="../zones/_script/respond.min.js"></script>
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
echo '<li class=\"has-sub\"><a href="../manageaccount/index.php">Manage Account</a>';
echo '<ul><li><a href="../manageaccount/a_createAccount.php">Create Account</a></li>';
echo '<li class=\"last\"><a href="../manageaccount/a_resetPassword.php">Reset Password</a></li></ul></li>';

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
    <td><label class="sectionTitle" id="frmItem">
Reset Password
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
          <td><label class="frmItemName">Username :</label></td>
          <td><input type="text" name="a_Username" value=""/></td>
        </tr>
        <input type="hidden" name="action" value="reset_password">
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" name="btnAdd" id="btnAdd" value="Submit to Server"></td>
        </tr>
      </table>
    </form></td>
  </tr>
  </table>

<?php echo $systemMsg;?>


</div>



</div>
<?php include("../../footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>