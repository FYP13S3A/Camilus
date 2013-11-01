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
     $_GET['order'] = 'servicetype_asc';
	  $order='servicetype_asc';
	  $orderBy = 'Service_Type_Id ASC';
}
 
//FIGURE OUT HOW TO SORT THE TABLE
switch($_GET['order']) {
		 
	 case 'name_asc':
	 $orderBy = "Name ASC";
	 $order='name_asc';
	 break;
	 
	 case 'name_desc':
	 $orderBy = "Name DESC";
	 $order='name_desc';
	 break;
	 

	 case 'deltime_asc':
	 $orderBy = " Last_Delivery_Time ASC";
	 $order='deltime_asc';
	 break;
	 
	 case 'deltime_desc':
	 $orderBy = " Last_Delivery_Time DESC";
	 $order='deltime_desc';
	 break;
	 
	 case 'coltime_asc':
	 $orderBy = "Last_Collection_Time ASC";
	 $order='coltime_asc';
	 break;
	 
	 case 'coltime_desc':
	 $orderBy = "Last_Collection_Time DESC";
	 $order='coltime_desc';
	 break;
	 
	 case 'starttime_asc':
	 $orderBy = "Expected_Start_Time ASC";
	 $order='starttime_asc';
	 break;
	 
	 case 'starttime_desc':
	 $orderBy = "Expected_Start_Time DESC";
	 $order='starttime_desc';
	 break;
	 
	 case 'overseasdura_asc':
	 $orderBy = "Expected_Overseas_Duration ASC";
	 $order='overseasdura_asc';
	 break;
	 
	 case 'overseasdura_desc':
	 $orderBy = "Expected_Overseas_Duration DESC";
	 $order='overseasdura_desc';
	 break;
	 
	 case 'localdura_asc':
	 $orderBy = "Expected_Local_Duration ASC";
	 $order='localdura_asc';
	 break;
	 
	 case 'localdura_desc':
	 $orderBy = "Expected_Local_Duration DESC";
	 $order='localdura_desc';
	 break;
	 

	 
	 case 'servicetype_desc':
     $orderBy = "Service_Type_Id DESC";
	 $order='servicetype_desc';
     break;
	 
     default:
     $_GET['order'] = 'servicetype_asc';
     $orderBy = 'Service_Type_Id ASC';
	  $order='servicetype_asc';
}

$sql = 'SELECT * FROM servicetype ORDER BY '.$orderBy;

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
$sql2 = 'SELECT * FROM servicetype ORDER BY '.$orderBy.' '.$limit;
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

if($_GET['order'] == 'servicetype_asc')
{ echo "<th scope=\"col\" width=\"10%\"><a href=\"index.php?order=servicetype_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Type Id&nbsp;</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\" /></a>"; }
elseif($_GET['order'] == 'servicetype_desc')
{ echo "<th scope=\"col\" width=\"10%\"><a href=\"index.php?order=servicetype_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Type Id&nbsp;</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\" width=\"16\" height=\"19\"/></a>"; }
else
{ echo "<th scope=\"col\" width=\"10%\"><a href=\"index.php?order=servicetype_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Type Id</label></a>";  }
echo "</th>";


if($_GET['order'] == 'name_asc')
{ echo "<th scope=\"col\" width=\"16%\"><a href=\"index.php?order=name_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Name&nbsp;</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\" /></a>"; }
elseif($_GET['order'] == 'name_desc')
{ echo "<th scope=\"col\" width=\"16%\"><a href=\"index.php?order=name_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Name&nbsp;</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\"  width=\"16\" height=\"19\"/></a>"; }
else
{ echo "<th scope=\"col\" width=\"16%\"><a href=\"index.php?order=name_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Name</label></a>";  }
echo "</th>";

echo "<th scope=\"col\" width=\"16%\"><label class=\"frmItemName\">&nbsp;Service Information</label>"; 
echo "</th>";

if($_GET['order'] == 'localdura_asc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=localdura_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Local Duration&nbsp;</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\" /></a>"; }
elseif($_GET['order'] == 'localdura_desc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=localdura_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Local Duration&nbsp;</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\"  width=\"16\" height=\"19\"/></a>"; }
else
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=localdura_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Local Duration</label></a>";  }
echo "</th>";


if($_GET['order'] == 'overseasdura_asc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=overseasdura_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Overseas Duration&nbsp;</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\" /></a>"; }
elseif($_GET['order'] == 'overseasdura_desc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=overseasdura_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Overseas Duration&nbsp;</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\"  width=\"16\" height=\"19\"/></a>"; }
else
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=overseasdura_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Overseas Duration</label></a>";  }
echo "</th>";

if($_GET['order'] == 'starttime_asc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=starttime_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Expected Start Time&nbsp;</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\" /></a>"; }
elseif($_GET['order'] == 'starttime_desc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=starttime_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Expected Start Time&nbsp;</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\"  width=\"16\" height=\"19\"/></a>"; }
else
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=starttime_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Expected Start Time</label></a>";  }
echo "</th>";

if($_GET['order'] == 'coltime_asc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=coltime_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Last Collection Time&nbsp;</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\" /></a>"; }
elseif($_GET['order'] == 'coltime_desc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=coltime_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Last Collection Time&nbsp;</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\"  width=\"16\" height=\"19\"/></a>"; }
else
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=coltime_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Last Collection Time</label></a>";  }
echo "</th>";

if($_GET['order'] == 'deltime_asc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=deltime_desc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Last Delivery Time&nbsp;</label><img src=\"../../_images/icon-arrowUp.png\" alt=\"Ascending\" width=\"16\" height=\"19\" /></a>"; }
elseif($_GET['order'] == 'deltime_desc')
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=deltime_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Last Delivery Time&nbsp;</label><img src=\"../../_images/icon-arrowDown.png\" alt=\"Descending Order\"  width=\"16\" height=\"19\"/></a>"; }
else
{ echo "<th scope=\"col\" width=\"11%\"><a href=\"index.php?order=deltime_asc&pg=$pg\"><label class=\"frmItemName\">&nbsp;Last Delivery Time</label></a>";  }
echo "</th>";

echo "<th scope=\"col\"><label class=\"frmItemName\">&nbsp;Update</label></th>";
echo "</tr>";
echo "<tr><td colspan=\"4\">&nbsp;</td></tr>";
$row_count=0;
while($res=mysql_fetch_array($result2)){
	$row_color = ($row_count % 2) ? $color1 : $color2;

	echo "<tr bgcolor=\"$row_color\">";
	echo "<td>".$res['Service_Type_Id']."</td>";
	echo "<td>".$res['Name']."</td>";
	echo "<td>".$res['Service_Information']."</td>";	
	echo "<td>".$res['Expected_Local_Duration']."</td>";	
	echo "<td>".$res['Expected_Overseas_Duration']."</td>";	
	echo "<td>".$res['Expected_Start_Time']."</td>";	
	echo "<td>".$res['Last_Collection_Time']."</td>";	
	echo "<td>".$res['Last_Delivery_Time']."</td>";
	
	echo "<td><a href=\"manage.php?id=".$res['Service_Type_Id']."\"><img src=\"../../_images/icon-edit.png\"></img>Edit</a> | <a href=\"handler.php?id=".$res['Service_Type_Id']."\"><img src=\"../../_images/icon-delete.png\"></img>Delete</a></td>";
	
	$row_count++;
	
}
echo "</table>";
?></td>
  </tr>
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="9%">Type Id <img src="../../_images/icon-arrowDown.png" width="16" height="18"></td>
        <td width="20%">Name <img src="../../_images/icon-arrowDown.png" alt="" width="16" height="18"></td>
        <td width="18%">Service Information</td>
        <td width="10%">Local Duration <img src="../../_images/icon-arrowDown.png" alt="" width="16" height="18"></td>
        <td width="11%">Overseas Duration <img src="../../_images/icon-arrowDown.png" alt="" width="16" height="18"></td>
        <td width="11%">Expected Start Time <img src="../../_images/icon-arrowDown.png" alt="" width="16" height="18"></td>
        <td width="21%">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
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