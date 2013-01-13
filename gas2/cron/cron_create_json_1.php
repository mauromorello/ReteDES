<?php
 $num=0; 
//any SQL select Query 
$sql="select * from students s"; 
$result=@mysql_query($sql) or die(mysql_error()); 
$num=mysql_affected_rows(); 
//calling class 
$objJSON=new mysql2json(); 
print(trim($objJSON->getJSON($result,$num)));  
?>