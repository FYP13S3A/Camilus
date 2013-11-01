<?php
include 'conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
// username and password sent from Form
$username=mysql_real_escape_string($_POST['txtUsername']); 
$password=mysql_real_escape_string($_POST['txtPassword']); 
$password=md5($password); // Encrypted Password
$sql="SELECT * FROM account WHERE Userid='$username' and H_value='$password'";
$result=mysql_query($sql);
$count=mysql_num_rows($result);
$status = "";

// If result matched $username and $password, table row must be 1 row
if($count==1)
{
session_start();
$_SESSION['username'] = $username;
header("location: dashboard.php");
}
else 
{
$status="System: Incorrect username/password.";
}
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
<title>Camilus - Homepage</title>
<link href="_css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="_css/layout.css" rel="stylesheet" type="text/css">
<link href="_css/typography.css" rel="stylesheet" type="text/css" media="screen, projection">
<!-- 

-->
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="_script/respond.min.js"></script>
</head>
<body>


<div class="gridContainer clearfix">
  <div class="mainContainer" id="mainContainer">
    <?php include("header.php"); ?>
    <div id="LoginBox">
      <div id="boxTitle">
        <h1>Camilus System Login</h1>
      </div>
      <div id="sysMsgContainer"></div>
      <div id="loginDesc">
        &nbsp;Use a valid username and password to gain access
        <table width="100%" border="0">
          <tr>
            <td align="center" valign="top" ><p><img src="_assests/padLock.jpg" alt="Lock" width="98" height="138"></td>
          </tr>
        </table>
      </div>
      <div id="sectionBox">
        <form name="form1" method="post" action="">
        <br><br>
        &nbsp;<label id="lblUsername">User Name:</label>
        <input name="txtUsername" id="txtUsername" type="text" size="15"></input><br><br>
        &nbsp;<label id="lblPassword">Password:&nbsp;&nbsp;</label>
      <input name="txtPassword" id="txtPassword" type="password" size="15" /><br><br><br>
      &nbsp;<input name=""  id="btnSubmit" type="submit" value="Submit" /><br><br>
        </form>
<?php
if($status!="")
{
echo '<p><font color=red>'.$status.'</p>';
}
?>
      </div>
    </div>
     <?php include("footer.php"); ?>
     <!--done-->
  </div>
</div>
</body>
</html>