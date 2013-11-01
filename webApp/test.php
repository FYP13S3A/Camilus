<html>
<head>
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.15/jquery.form-validator.min.js"></script>
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0-wip/css/bootstrap.min.css" />
<script src="jquery.form-validator.js"></script>
<style>
input.valid {
    background: url(_images/icon-ok.png) no-repeat right center #e3ffe5;
    color: #002f00;
    border-color: #96b796 !important;
}

input.error {
    background: url(_images/icon-fail.png) no-repeat right center #ffebef;
    color: #480000;
}body {
	background-color: #CCC;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
  
  <!--  The form that will be parsed by jQuery before submit  -->
  <table width="500" border="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td> <form action="./" method="post" id="register-form" >
  
    <div class="label">Name</div><input type="text" id="name" name="name" value="" data-validation="number length"data-validation-length="min5"  data-validation-error-msg="The user name has to be an alphanumeric value between 3-12 characters"/><br />
    <div class="label">Gender</div><select id="gender" name="gender" />
                                      <option value="Female">Female</option>
                                      <option value="Male">Male</option>
                                      <option value="Other">Other</option>
                                   </select><br />
    <div class="label">Address</div><input type="text" id="address" name="address" value="" /><br />
    <div class="label">Email</div><input type="text" id="email" name="email" value="" /><br />
    <div class="label">Username</div><input type="text" id="username" name="username" value="" /><br />
    <div class="label">Password</div><input type="password" id="password" name="password" /><br />
    <div style="margin-left:140px;"><input type="submit" name="submit" value="Submit" /></div>
    
  </form></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
<script>
(function($) {

    var dev = window.location.hash.indexOf('dev') > -1 ? '.dev' : '';

    $.validate({
        language : {
            requiredFields: 'Du måste bocka för denna'
        },
        errorMessagePosition : 'top',
        scrollToTopOnError : true,
        modules : 'security'+dev,
        onModulesLoaded: function( $form ) {
            $('#password').displayPasswordStrength();
        },
        onValidate : function() {
            var $callbackInput = $('#callback');
            if( $callbackInput.val() == 1 ) {
                return {
                    element : $callbackInput,
                    message : 'This validation was made in a callback'
                };
            }
        },
        onError : function() {
            if( !$.formUtils.haltValidation ) {
                alert('Invalid');
            }
        },
        onSuccess : function() {
            alert('Valid');
            return false;
        }
    });

 

})(jQuery);
</script>
</body>
</html>