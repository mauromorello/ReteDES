<?php
 $page_start = array_sum(explode(' ', microtime()));
 $_FUNCTION_LOADER=array("ordini",
                         "ordini_valori");
 
 include ("../rend.php");
 include ("ptp_functions.php");
 
 
 if(!_USER_LOGGED_IN){
    header("Location: index.php");     
 }
 
 $h = ptp_lista_ordini_aperti(_USER_ID_GAS);
 
 
 echo ptp_header().
      ptp_head().
      ptp_menu().
      $msg.
      $h.
      ptp_footer(); 
?>