<?php

include 'conn.php';

//POPULATE DROPDOWN LIST
$sql="SELECT NAME,CODE from country";
$result=mysql_query($sql);

while ($row = mysql_fetch_assoc($result)) {
   echo $row[NAME] . " " . $row[CODE] . "<hr>";
}

?>