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
 //IF THE FLAG HASN'T BEEN SET YET, SET THE DEFAULT
if(!isset($_GET['order'])) {
     $_GET['order'] = 'district';
	  $order='district';
}
 
//FIGURE OUT HOW TO SORT THE TABLE
switch($_GET['order']) {
     case 'zone_asc':
	 $orderBy = "Zone_Id ASC";
	 $order='zone_asc';
	 break;
	 case 'zone_desc':
	 $orderBy = "Zone_Id DESC";
	 $order='zone_desc';
	 break;
	 case 'code_asc':
     $orderBy = "Postal_Sector ASC";
	  $order='code_asc';
     break;
	 case 'code_desc':
     $orderBy = "Postal_Sector DESC";
	 $order='code_desc';
     break;
	 case 'district_desc':
     $orderBy = "District_Id DESC";
	 $order='district_desc';
     break;
     default:
     $_GET['order'] = 'district_asc';
     $orderBy = 'District_Id ASC';
	  $order='district_asc';
}

$sql = "SELECT * FROM  `district` ORDER BY  $orderBy";
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
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $add1 . '">' . $add1 . '</a> &nbsp;';
} else if ($pg == $lastPage) {
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <span class="pageActive">' . $pg . '</span> &nbsp;';
} else if ($pg > 2 && $pg < ($lastPage - 1)) {
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $sub2 . '">' . $sub2 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <span class="pageActive">' . $pg . '</span> &nbsp;';
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $add1 . '">' . $add1 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $add2 . '">' . $add2 . '</a> &nbsp;';
} else if ($pg > 1 && $pg < $lastPage) {
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerValues .= '&nbsp; <span class="pageActive">' . $pg . '</span> &nbsp;';
    $centerValues .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $add1 . '">' . $add1 . '</a> &nbsp;';
}

$limit = 'LIMIT ' .($pg - 1) * $recordsPerPage .',' .$recordsPerPage; //Setting the limit value in the SQL statement
$sql2 = "SELECT * FROM  `district` ORDER BY  $orderBy $limit";
$result2 = mysql_query($sql2); 

$paginationDisplay = ""; // Storing of the html and page number tags.
if ($lastPage != "1"){
    // Shows the current page number
    $paginationDisplay .= 'Page <strong>' . $pg . '</strong> of ' . $lastPage. '<br> ';
    // Putting the back link if we are not page 1
    if ($pg != 1) {
        $previous = $pg - 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $previous . '"> Back</a> ';
    } 
    // Putting the numbers inbetween back and next links
    $paginationDisplay .= '<span class="pageNumbers">' . $centerValues . '</span>';
    // Putting the next link if we are not on the last page.
    if ($pg != $lastPage) {
        $nextPage = $pg + 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?order='.$order.'&pg=' . $nextPage . '"> Next</a> ';
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
<title>Camilus - District Management</title>

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
    <td><label class="sectionTitle" id="frmItem">District Management</label></td>
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

if($_GET['order'] == 'district_asc')          { echo "<th scope=\"col\"><a href=\"index.php?order=district_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;District Id</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\"/></a>"; }

elseif($_GET['order'] == 'district_desc') { echo "<th scope=\"col\"><a href=\"index.php?order=district_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;District Id</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\" width=\"16\" height=\"19\"/></a>"; }

else                                          { echo "<th scope=\"col\"><a href=\"index.php?order=district_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;District Id</label></a>";  }
echo "</th>";


if($_GET['order'] == 'zone_asc')          { echo "<th scope=\"col\"><a href=\"index.php?order=zone_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Zone Id</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\" /></a>"; }

elseif($_GET['order'] == 'zone_desc') { echo "<th scope=\"col\"><a href=\"index.php?order=zone_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Zone Id</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\" width=\"16\" height=\"19\" /></a>"; }

else                                          { echo "<th scope=\"col\"><a href=\"index.php?order=zone_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Zone Id</label></a>";  }
echo "</th>";

if($_GET['order'] == 'code_asc')          { echo "<th scope=\"col\"><a href=\"index.php?order=code_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Postal Sector</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\"/></a>"; }

elseif($_GET['order'] == 'code_desc') { echo "<th scope=\"col\"><a href=\"index.php?order=code_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Postal Sector</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\" width=\"16\" height=\"19\" /></a>"; }

else                                          { echo "<th scope=\"col\"><a href=\"index.php?order=code_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Postal Sector</label></a>";  }
echo "</th>";
echo "<th scope=\"col\"><label class=\"frmItemName\">&nbsp;Update</label></th>";
echo "</tr>";
echo "<tr><td colspan=\"4\">&nbsp;</td></tr>";
$row_count=0;
while($res=mysql_fetch_array($result2)){
	$row_color = ($row_count % 2) ? $color1 : $color2;

	echo "<tr bgcolor=\"$row_color\">";
	echo "<td>".$res['District_Id']."</td>";
	echo "<td>".$res['Zone_Id']."</td>";
	echo "<td>".$res['Postal_Sector']."</td>";	
	echo "<td><a href=\"manage.php?id=$res[District_Id]\"><img src=\"../../_images/icon-edit.png \"></img>Edit</a> | <a href=\"handler.php?id=$res[District_Id]\"><img src=\"../../_images/icon-delete.png \"></img>Delete</a></td>";
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