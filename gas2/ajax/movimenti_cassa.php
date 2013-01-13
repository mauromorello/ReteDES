<?php
include_once ("../rend.php");

$cookie_read     =explode("|", base64_decode($user));
$id_user  = $cookie_read[0];
//echo $key."  ".$key2; 
switch ($q){
     
       case "1":
       echo cassa_movimenti_utente_micro((int)$key,(int)$key2);           
       break;
       case "2":
       echo cassa_movimenti_ordine_micro((int)$key,(int)$key2);           
       break;
       case "3":
       echo cassa_movimenti_tipo_micro((int)$key,(int)$key2);           
       break;
       
}

?>