<?php
include '../../conn.php';
session_start();

//set time zone to singapore
date_default_timezone_set("Asia/Singapore");
$action = $_POST['action'];

if($action=="reset_password")
{
$username = $_POST['a_Username'];

//check if username exist;

$sql= "SELECT * FROM account WHERE Userid='$username'";
$result=mysql_query($sql);
$count=mysql_num_rows($result);

while ($row = mysql_fetch_assoc($result)) {
   $result_account[] = $row;
}

//obtain service type id

$s_Email = $result_account[0][Email_Address];


$status = "";

//If UserId exist
if($count==0)
{
//error account exist
header('Location: a_resetPassword.php?success=0');
}//if account exist
else
{
//if exist
$password = generateRandomString();
$salt = '$2a$07$'.mt_rand().'$';

//combine username + password to give algorithm more chars to work with
$password_hash = crypt($username. $password, $salt);


$sql = "UPDATE account SET H_Value='$password_hash',S_Value='$salt' where Userid='".$username."'";
$result=mysql_query($sql);

if($result == false)
{ 
header('Location: a_resetPassword.php?success=0');
}
else
{
//successfully update

$to      = 'baokydev@gmail.com';
$subject = 'Camilus - Password Reset';
$message = 'Hi, your temporary password is ' . $password . " , please login out platform and change a new password soon.";

$headers = 'From: support@camilus.com' . "\r\n" .
    'Reply-To: support@camilus.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();


mail($to, $subject, $message, $headers);

header('Location: a_resetPassword.php?success=1');
}


}//end else account exist

}//end if action is reset password
if($action=="register")
{
    
$username = $_POST['a_Username'];
$name = $_POST['a_Fullname'];
$password = $_POST['a_Password'];
$workLocation = $_POST['building_id'];
$email = $_POST['a_Email'];

//check if username exist;

$sql= "SELECT * FROM account WHERE Userid='$username'";
$result=mysql_query($sql);
$count=mysql_num_rows($result);
$status = "";

//If UserId exist
if($count==1)
{
//error account exist
header('Location: a_createAccount.php?success=0');
}//if account exist
else
{

//get the date
$today = date("Y-m-d", time());
$salt = '$2a$07$'.mt_rand().'$';

//combine username + password to give algorithm more chars to work with
$password_hash = crypt($username. $password, $salt);

// Create Account
$sql = "INSERT INTO account".
       "(Userid, FullName, Email_Address, S_Value,H_Value,Created_Date,Work_Location_Id) ".
       "VALUES ".
       "('$username','$name','$email','$salt','$password_hash',$today,$workLocation)";



$retval = mysql_query($sql);

if(! $retval )
{
header('Location: a_createAccount.php?success=0');
}//end if fail craete account
else
{
header('Location: a_createAccount.php?success=1');
}//end else if successful create account

}//end if account does not exist
}//end if action == register




function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

?>