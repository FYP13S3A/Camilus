<?php
include '../../conn.php';

//POPULATE DROPDOWN LIST
$sql="SELECT Service_Type_Id,Name,Service_Information from servicetype";

$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}


$s_City = $_POST['s_City'];
$s_Country = $_POST['s_Country'];
$s_Postal = $_POST['s_Postal'];

$r_City = $_POST['r_City'];
$r_Country = $_POST['r_Country'];
$r_Postal = $_POST['r_Postal'];

$d_Weight = $_POST['d_Weight'];
$d_Length = $_POST['d_Length'];
$d_Width = $_POST['d_Width'];
$d_Height = $_POST['d_Height'];
$d_Content = $_POST['d_Content'];


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

<body>
<div class="gridContainer clearfix">
<div id="contentBox">
   <?php include("../../header.php"); ?>
  <div class="leftSidebar">
  <div  id="navigationBox">
  <div class="wireframemenu">
<ul>
<li><a href="">Home</a></li>
<li><a href="index.php">Register Info</a></li>
<li><a href="../../kiosk/tracking.php">Track Mail</a></li>
<li><a href="#">FAQ</a></li>
<li><a href="#">Contact Us</a></li>
</ul>
</div>
  </div>
  </div>
  <div id="mainContent">
<form name="form1" method="post" action="register_info3.php">
  <table width="100%" border="0">
    <tr>
      <td class="sectionTitle"><label class="itemTittle">
Shipment Details</label></td>
    </tr>
    <tr>
      <td><table class="registerMail_P1">
<tr>
  <td colspan="2"></td>
  </tr>
<tr>
<td width="14%">&nbsp;Physical Weight: </td>
<td width="86%"><?php echo $d_Weight;?></td>
</tr>
<tr>
  <td>&nbsp;Length: </td>
  <td><?php echo $d_Length;?></td>
</tr>
<tr><td>&nbsp;Width: </td>
  <td><?php echo $d_Width;?></td>
</tr>
<tr><td>&nbsp;Height: </td>
  <td><?php echo $d_Height;?></td>
</tr>
<tr>
<td>&nbsp;Mail Content: </td>
<td><?php echo $d_Content;?></td>
</tr>
</table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label class="sectionTitle">
        Services</label></td>
    </tr>
    <tr>
      <td><strong>Select your service type:</strong></td>
    </tr>
    <tr>
      <td><table  class="registerMail_P1">
<tr>
<th align="left">Select</th>
<th align="left">Service</th>
<th align="left">Service Information</th>
</tr>


<?php
foreach($result_array AS $row)
{
echo "<tr>";
echo "<td><input type=\"radio\" name=\"s_Service\" value=\"".$row[Service_Type_Id]."\"></td>";
echo "<td>".$row[Name]."</td>";
echo "<td>".$row[Service_Information]."</td>";
echo "</tr>";
}  
?>
</table>

</p>

<?php
//echo hidden field

$s_City = $_POST['s_City'];
$s_Country = $_POST['s_Country'];
$s_Postal = $_POST['s_Postal'];

$r_City = $_POST['r_City'];
$r_Country = $_POST['r_Country'];
$r_Postal = $_POST['r_Postal'];

$d_Weight = $_POST['d_Weight'];
$d_Length = $_POST['d_Length'];
$d_Width = $_POST['d_Width'];
$d_Height = $_POST['d_Height'];
$d_Content = $_POST['d_Content'];

//sender details
echo "<input type=\"hidden\" name=\"s_City\" value=\"".$s_City."\">\n";
echo "<input type=\"hidden\" name=\"s_Country\" value=\"".$s_Country."\">\n";
echo "<input type=\"hidden\" name=\"s_Postal\" value=\"".$s_Postal."\">\n";

//recipient details
echo "<input type=\"hidden\" name=\"r_City\" value=\"".$r_City."\">\n";
echo "<input type=\"hidden\" name=\"r_Country\" value=\"".$r_Country."\">\n";
echo "<input type=\"hidden\" name=\"r_Postal\" value=\"".$r_Postal."\">\n";

//shipment details
echo "<input type=\"hidden\" name=\"d_Weight\" value=\"".$d_Weight."\">\n";
echo "<input type=\"hidden\" name=\"d_Length\" value=\"".$d_Length."\">\n";
echo "<input type=\"hidden\" name=\"d_Width\" value=\"".$d_Width."\">\n";
echo "<input type=\"hidden\" name=\"d_Height\" value=\"".$d_Height."\">\n";
echo "<input type=\"hidden\" name=\"d_Content\" value=\"".$d_Content."\">\n";

?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" class="registerMail_P1"><INPUT Type="button" VALUE="&lt; Back" onClick="history.go(-1);return true;">
        &nbsp;
        <input  id="btnSubmit" type="submit" value="Next &gt;" /></td>
      </tr>
  </table>
</form>
</div>
<?php include("../../footer.php"); ?>
</div><!---end#contentBox--->
</div>
<script>
(function($) {

    var dev = window.location.hash.indexOf('dev') > -1 ? '.dev' : '';

    $.validate({
        language : {
            requiredFields: 'Please select the service you would like to use!'
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