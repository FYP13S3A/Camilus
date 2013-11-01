<?php


$s = '2013/10/23 14:00';
$date = strtotime($s);
$date = date('Y/m/d H:i', $date);

echo $date;

?>