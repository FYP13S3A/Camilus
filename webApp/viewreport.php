<?php
include 'conn.php';
//POPULATE THE TABLE


	$cirteria = $_POST['trackingno'];

$sql="SELECT Mail_Reference_No, Sender_FullName, Sender_Contact_No,Recipient_FullName,Recipient_Contact_No,Sender_Address_Id,Recipient_Address_Id  FROM mail WHERE Mail_Reference_No LIKE '%$cirteria%'";
$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

//sender & recipient info

$s_Name = $result_mail[0][Sender_FullName];
$s_Phone = $result_mail[0][Sender_Contact_No];
$s_AddressID = $result_mail[0][Sender_Address_Id];
$r_Name = $result_mail[0][Recipient_FullName];
$r_Phone = $result_mail[0][Recipient_Contact_No];
$r_AddressID = $result_mail[0][Recipient_Address_Id];

$sql = "select * from address where Address_Id='" . $s_AddressID."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_sender[] = $row;
}

$s_AddressLine1 = $result_sender[0][Address_Line1];
$s_AddressLine2 = $result_recipient[0][Address_Line2];
$s_Postal = $result_sender[0][Postal_Code];
$s_City = $result_sender[0][City_Name];

//recipient address ID - need to draw from address table.

$sql = "select * from address where Address_Id='" . $r_AddressID."'";
$result= mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   $result_recipient[] = $row;
}

$r_AddressLine1 = $result_recipient[0][Address_Line1];
$r_AddressLine2 = $result_recipient[0][Address_Line2];
$r_Postal = $result_recipient[0][Postal_Code];
$r_City = $result_recipient[0][City_Name];

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
   <div id="reportContent" style="width:100%; ">
   
   <!---Put the actual report here--->
   
   </div>
  <?php include("footer.php"); ?>
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