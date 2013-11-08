<?php
include '../../conn.php';

//POPULATE DROPDOWN LIST
$sql="SELECT NAME,CODE from country";
$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
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
 
<script src="file:///C|/Users/Sanaa Shamsul/Documents/_script/respond.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.15/jquery.form-validator.min.js"></script>
<script src="file:///C|/Users/Sanaa Shamsul/Documents/_script/jquery.form-validator.js"></script>
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
  <form id="form1" method="post" action="register_info2.php" novalidate>
<table width="100%" border="0">
  <tr>
    <td><label class="sectionTitle">
    <b>&nbsp;Sender Details</b></label></td>
    <td>&nbsp;</td>
    <td><label class="sectionTitle">
    <b>&nbsp;Recipient Details</b></label></td>
  </tr>
  <tr>
    <td><table class="registerMail_P1">

  <tr>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td width="48%"><label class="frmItemName">&nbsp;Country:</label></td>
    <td width="52%"><select name="s_Country" >

<?php
foreach($result_array AS $row)
{
if($row[NAME]=="Singapore")
{
   echo "<option selected=\"selected\" value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}//end if
else
{
   echo "<option value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}//end else
}//end foreach
?>

    </select></td>
  </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;City:</label></td>
    <td ><input type="text" name="s_City" id="s_City" size="30" value=""  data-validation="custom" data-validation-regexp="^([a-zA-Z]+)$" data-validation-error-msg="Please enter the sender's city."/></td>
  </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;Postal Code:</label></td>
    <td><input type="text" name="s_Postal" id="s_Postal" size="30" value=""  data-validation="number length"data-validation-length="min5"  data-validation-error-msg="Please enter a valid postal code."/></td>
  </tr>
  </table></td>
    <td>&nbsp;</td>
    <td><table class="registerMail_P1">

  <tr>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td width="49%"><label class="frmItemName">&nbsp;Country:</label></td>
    <td width="51%"><select name="r_Country">
<?php
foreach($result_array AS $row)
{
if($row[NAME]=="Singapore")
{
   echo "<option selected=\"selected\" value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}//end if
else
{
   echo "<option value=\"" . $row[CODE] . "\">" . $row[NAME] . "</option>";
}//end else
}//end foreach
?>
    </select></td>
  </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;City:</label></td>
    <td><input type="text" name="r_City" id="r_City" size="30" value="" data-validation="custom" data-validation-regexp="^([a-zA-Z]+)$" data-validation-error-msg="Please enter the recipient's city." /></td>
  </tr>
  <tr>
    <td><label class="frmItemName">&nbsp;Postal Code:</label></td>
    <td><input type="text" name="r_Postal" id="r_Postal" size="30" value="" data-validation="number length"data-validation-length="min5"  data-validation-error-msg="Please enter a valid postal code."></td>
  </tr>
  </table></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="sectionTitle" id="sectionTitle">&nbsp;Shipment Details</td>
  </tr>
  <tr>
    <td colspan="3"><table width="100%" class="registerMail_P1" >
<tr>
  <th colspan="5" align="left"></th>
  </tr>
<tr>
  <td align="left"><label class="frmItemName">&nbsp;Weight(grams)</label></td>
  <td align="left"><label class="frmItemName">&nbsp;Length(cm)</label></td>
  <td align="left"><label class="frmItemName">&nbsp;Width(cm)</label></td>
  <td align="left"><label class="frmItemName">&nbsp;Height(cm)</label></td>
  <td align="left"><label class="frmItemName">&nbsp;Mail Contents</label></td>
</tr>
<tr>
<td>&nbsp;<input type="text" name="d_Weight" id="d_Weight" size="10" value="" data-validation="number" data-validation-allowing="float" data-validation-error-msg="Please enter a numerical value."></td>
<td>&nbsp;<input type="text" name="d_Length" id="d_Length" size="10" value=""data-validation="number" data-validation-allowing="float"data-validation-error-msg="Please enter a numerical value."></td>
<td>&nbsp;<input type="text" name="d_Width" id="d_Width" size="10" value=""data-validation="number" data-validation-allowing="float" data-validation-error-msg="Please enter a numerical value."></td>
<td>&nbsp;<input type="text" name="d_Height" id="d_Height" size="10" value=""data-validation="number" data-validation-allowing="float" data-validation-error-msg="Please enter a numerical value."></td>
<td>&nbsp;<input type="text" name="d_Content" id="d_Content" size="40" value=""data-validation="length" data-validation-length="4-50" data-validation-error-msg="Please enter the contents of the mail."></td>
</tr>
</table></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="right" class="registerMail_P1"><input name="btnSubmit"  id="btnSubmit" type="submit" value="Next Step &gt;" /></td>
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