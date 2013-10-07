<!DOCTYPE html>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

<script>
    $(document).ready(function(){

    $("#input").click(function(){
    
$.ajax({
    url: 'getAddr.php?zip='+$("#zipcode").val(),
    type: 'GET',
    success: function (data) {$("#newhtml").html(data);}
});

    })


    });

    </script>

</head>
<body>
Zipcode: <input type="text" id="zipcode" name="zipcode"><br>
<input type="button" id="input" value="Search Address">
<div id="newhtml"></div>
</body>
</html>
