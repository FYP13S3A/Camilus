<?php
if ($_GET['randomId'] != "zcFYbEj0El3zXnR34I4U9zOJbOQy0uquxyDlZBXVE7lzVw5HU60MJuf3PMMy4qia") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
