<?php

include 'conn.php';


//####################SQL########################################################

$sql = 'SELECT COUNT(m.Service_Type_Id) AS "result"
FROM mail m
INNER JOIN account a
ON a.Id = m.Approved_By_Id
INNER JOIN building b
ON a.Work_Location_Id = b.Building_Code
WHERE m.Approve_Status = 1
AND b.Building_Code = 234
AND WEEKOFYEAR(m.Approved_Time)=WEEKOFYEAR(NOW())-1;';

$result= mysql_query($sql);
while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

$result = $result_array[0][result];

echo "Total number of mails: " . $result;

///############### SQL is for total number of mails - END ############################

unset($result_array);

//################## SQL #############################################################

$sql = 'SELECT b.Name AS "Building_Name", st.Name AS "Service_Type", COUNT(st.Name) AS "Occurance_Rate" 
FROM mail m
INNER JOIN servicetype st
ON m.Service_Type_Id = st.Service_Type_Id 
INNER JOIN account a
ON a.Id = m.Approved_By_Id
INNER JOIN building b
ON a.Work_Location_Id = b.Building_Code
WHERE m.Service_Type_Id = 1 
AND b.Building_Code = 234
AND Approve_Status = 1 
AND WEEKOFYEAR(Approved_Time)=WEEKOFYEAR(NOW())-1;';

$result= mysql_query($sql);
while ($row = mysql_fetch_assoc($result)) {
   $result_array[] = $row;
}

$b_Name = $result_array[0][Building_Name];
$s_Type = $result_array[0][Service_Type];
$occurance_Rate = $result_array[0][Occurance_Rate];

echo "<hr>";
echo "<p>Building Name: <b>" . $b_Name . "</b></p>";



?>