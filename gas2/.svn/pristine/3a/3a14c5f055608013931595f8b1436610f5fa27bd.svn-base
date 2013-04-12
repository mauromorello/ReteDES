<?php

session_start(); 

include_once ("../rend.php");

if (is_logged_in($user)){
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  = $cookie_read[0];
    $text = utf8_decode($text);
    
    
    log_me(0,$id_user,"CHT","MSG",$text,0,$text);

}



?>