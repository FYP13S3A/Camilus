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
echo '<li><a href="index.php">Manage Region</a></li>';
echo '<li><a href="/managedistrict/">Manage District</a></li>';
}

//########### ROLE Links END #############
?>

 <?php
$sql = "SELECT * FROM  `zone` ORDER BY  `Zone_Id` ASC ";
$result= mysql_query($sql);
$rowcount = mysql_num_rows($result);

//Check the pg in the url and only accept numerical values(security purpose). If it is missing force it to page 1
isset($_GET['pg']) ?   $pg = preg_replace('#[^0-9]#i', '', $_GET['pg']) : $pg = 1;

$recordsPerPage = 10; //Fixing the number of records per page.
$lastPage = ceil($rowcount / $recordsPerPage); //Getting the last page value

// Make sure page number is not lower than 1 and not more than the last page number
if ($pg < 1) { 
    $pg = 1; 
} elseif ($pg > $lastPage) { 
    $pg = $lastPage; 
}

//Creating page numbers in between the first and last page numbers
$centerValues = "";
$sub1 = $pg - 1;
$sub2 = $pg - 2;
$add1 = $pg + 1;
$add2 = $pg + 2;

if ($pg == 1) {
    $centerValues .= '&nbsp; <span class="pageActive">' . $pg . '</span> &nbsp;';
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $add1 . '">' . $add1 . '</a> &nbsp;';
} else if ($pg == $lastPage) {
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <span class="pageActive">' . $pg . '</span> &nbsp;';
} else if ($pg > 2 && $pg < ($lastPage - 1)) {
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $sub2 . '">' . $sub2 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <span class="pageActive">' . $pg . '</span> &nbsp;';
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $add1 . '">' . $add1 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $add2 . '">' . $add2 . '</a> &nbsp;';
} else if ($pg > 1 && $pg < $lastPage) {
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <span class="pageActive">' . $pg . '</span> &nbsp;';
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $add1 . '">' . $add1 . '</a> &nbsp;';
}


$limit = 'LIMIT ' .($pg - 1) * $recordsPerPage .',' .$recordsPerPage; //Setting the limit value in the SQL statement
$sql2 = "SELECT * FROM  `zone` ORDER BY  `Zone_Id` ASC $limit";
$result2 = mysql_query($sql2); 




$paginationDisplay = ""; // Storing of the html and page number tags.
if ($lastPage != "1"){
    // Shows the current page number
    $paginationDisplay .= 'Page <strong>' . $pg . '</strong> of ' . $lastPage. '<br> ';
    // Putting the back link if we are not page 1
    if ($pg != 1) {
        $previous = $pg - 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $previous . '"> Back</a> ';
    } 
    // Putting the numbers inbetween back and next links
    $paginationDisplay .= '<span class="pageNumbers">' . $centerValues . '</span>';
    // Putting the next link if we are not on the last page.
    if ($pg != $lastPage) {
        $nextPage = $pg + 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pg=' . $nextPage . '"> Next</a> ';
    } 
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
    <td><label class="sectionTitle" id="frmItem">Zone Management</label></td>
    </tr>
  <tr>
    <td></td>
    </tr>
  <tr>
    <td><?php
$color1 = "#FFFFFF";
$color2 = "#F4F9FF";

echo "<table width='100%' id='dbDataGrid'>";

echo "<tr bgcolor='#CCCCCC' border>";
echo "<td><label class=\"frmItemName\">&nbsp;Zone Id  </label></td>";
echo "<td><label class=\"frmItemName\">&nbsp;Name</label></td>";
echo "<td><label class=\"frmItemName\">&nbsp;Locations</label></td>";
echo "<td><label class=\"frmItemName\">&nbsp;Update </label></td>";
echo "</tr>";
echo "<tr><td colspan=\"4\">&nbsp;</td></tr>";
$row_count=0;
while($res=mysql_fetch_array($result2)){
	$row_color = ($row_count % 2) ? $color1 : $color2;

	echo "<tr bgcolor=\"$row_color\">";
	echo "<td>".$res['Zone_Id']."</td>";
	echo "<td>".$res['Name']."</td>";
	echo "<td>".$res['Locations']."</td>";	
	echo "<td><a href=\"edit.php?id=$res[Zone_Id]\"><img src=\"../../_images/icon-edit.png \"></img>Edit</a> | <a href=\"delete.php\"><img src=\"../../_images/icon-delete.png \"></img>Delete</a></td>";
	$row_count++;
	
}
echo "</table>";
?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div style="margin-left:58px; margin-right:58px; padding:6px;" align="center">
    <?php echo $paginationDisplay; ?>
    </div></td>
    </tr>
</table>



</div>



</div>
<?php include("../../footer.php"); ?>
</div><!---end#contentBox--->
</div>

</body>
</html>