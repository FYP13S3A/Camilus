<?php
 // receive handlerData from app's http request
 $handlerData=$_POST["handlerData"];
 //throw handlerData to array with ":" delimiter
 $dataArray = explode(":", $handlerData);
 
 //1:handle login request
 //2:handle delivery info retrieval request via delivery id
 //3:handle delivery status update requst and storage of signature(png format)
 //4:handle delivery status where recipient not at home
 
 //general return code
 //111: login success
 //999: login fail
 if($dataArray[0]=="1"){
	$user = $dataArray[1];
	$password = $dataArray[2];
	if(($user == "user1" && $password == "password1") or ($user == "abc" && $password == "def")){
		echo "101";
	}else{
		echo "109";
	}
 }else if($dataArray[0]=="2"){
	$parcelid = $dataArray[1];
	if($parcelid == "S8845582G"){
		//sender name:receipient name:receipient add:no. of items:item description:
		echo "201:David Tan:Sandra Chin:1 Mount Everest:01:Warmer";
	}else{
		echo "209";
	}
 }else if($dataArray[0]=="3"){
	$imageStr = $dataArray[1];
	$imageName = $dataArray[2] . ".jpg";
	$imageBinary=base64_decode($imageStr);
	header('Content-Type: bitmap; charset=utf-8');
	$file = fopen($imageName, 'wb');
	fwrite($file, $imageBinary);
	fclose($file);
	echo "301";
 }else if($dataArray[0]=="4"){
 	//handle not at home
 	echo "401";
 }else{
	echo "999";
 }
?>