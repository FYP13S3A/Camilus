<?php
include 'conn.php';
//POPULATE THE TABLE

if (!empty($_POST["b_trackID"])) {
	$trackingno = $_POST['b_trackID'];


//Search using tracing id

$sql="SELECT Mail_Reference_No, Sender_FullName, Sender_Contact_No,Recipient_FullName,Recipient_Contact_No FROM mail WHERE Mail_Reference_No LIKE '%$trackingno%'";
$result=mysql_query($sql);
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
<title>Camilus - Register a New Mail</title>

<link href="_css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="_css/layout.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/css/bootstrap.min.css" />
<link rel="stylesheet" href="_css/typography.css" />

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
 
<script src="_script/respond.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.15/jquery.form-validator.min.js"></script>
<script src="_script/jquery.form-validator.js"></script>
</head>

<body>
<div class="gridContainer clearfix">
<div id="contentBox">
   <?php include("header.php"); ?>
  <div class="leftSidebar">
  <div  id="navigationBox">
  <div class="wireframemenu">
<ul>
<li><a href="">Home</a></li>
<li><a href="public/web/register_info.php">Register Info</a></li>
<li><a href="public/web/tracking.php">Track Mail</a></li>
<li><a href="#">FAQ</a></li>
<li><a href="#">Contact Us</a></li>
</ul>
</div>
  </div>
  </div>
  <div id="mainContent">
  <table width="100%" border="0">
  <tr>
    <td width="100%"><label class="sectionTitle">
      <b>Update Status</b></label><label class="sectionTitle"><b></b></label></td>
    </tr>
  <tr>
    <td>
    <form name="frm1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"   >
    <table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" class="registerMail_P1">
      <tr>
        <td width="10%" ><label class="frmItem">Tracking ID:</label></td>
        <td width="90%"><input style="font-size:20px" type="text" name="b_trackID" id="b_trackID" size="30" value="" placeholder="Enter Tracking ID..."></td>
      </tr>
      <tr>
        <td colspan="2" ><input  id="btnSubmit" type="submit" value="Serach" /></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
<?php
echo "<table width=\"100%\">";
echo "<tr bgcolor='#CCCCCC' >";
echo "<th scope=\"col\"><label class=\"frmItemName\">Tracking Id</label>";  
echo "</th>";

echo "<th scope=\"col\"><label class=\"frmItemName\">Sender Fullname</label>"; 
echo "</th>";

echo "<th scope=\"col\" ><label class=\"frmItemName\">Sender Contact No</label>";  
echo "</th>";

echo "<th scope=\"col\"><label class=\"frmItemName\">Recipent Fullname</label>"; 
echo "</th>";

echo "<th scope=\"col\" ><label class=\"frmItemName\">Recipent Contact No</label>";  
echo "</th>";

echo "<th scope=\"col\" ><label class=\"frmItemName\">Update</label></th>";
echo "</tr>";
echo "<tr><td colspan=\"4\">&nbsp;</td></tr>";
$row_count=0;
if (!empty($_POST["b_trackID"])){
while($res=mysql_fetch_array($result)){
	$row_color = ($row_count % 2) ? $color1 : $color2;

	echo "<tr bgcolor=\"$row_color\">";
	echo "<td>".$res['Mail_Reference_No']."</td>";
	echo "<td>".$res['Sender_FullName']."</td>";
	echo "<td>".$res['Sender_Contact_No']."</td>";	
	echo "<td>".$res['Recipient_FullName']."</td>";	
	echo "<td>".$res['Recipient_Contact_No']."</td>";	
	
	echo "<td><a href=\"updatestatus2.php?id=".$res['Mail_Reference_No']."\">Update Status </a> </td>";
	
	$row_count++;
	
}
}

echo "</table>";
?>
 </td>
    </tr>
</table>

    
    
    </form>
    </td>
  </tr>

    
    </table>

 
  

  
  </div>
  <?php include("footer.php"); ?>
</div><!---end#contentBox--->
</div>
</body>
</html>