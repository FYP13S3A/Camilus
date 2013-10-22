<?php
$zipCode = $_GET['zip'];
$cityId = "SGP";

$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$zipCode."&sensor=false";

//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);

$decodeData = json_decode($result, true);

$getLat = $decodeData['results'][0]['geometry']['location']['lat'];
$getLng = $decodeData['results'][0]['geometry']['location']['lng'];

$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$getLat.",".$getLng."&sensor=false";

//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);

$decodeData = json_decode($result, true);

$blockNumber = $decodeData['results'][0]['address_components']['0']['long_name'];
$streetName = $decodeData['results'][0]['address_components']['1']['long_name'];
$countryName = $decodeData['results'][0]['address_components']['2']['long_name'];
$zipCode = $decodeData['results'][0]['address_components']['4']['long_name'];

echo "Block " . $blockNumber . " " . $streetName . " " . $countryName . " " . $zipCode;


?>