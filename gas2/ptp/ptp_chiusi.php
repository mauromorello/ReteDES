<?php
  $page_start = array_sum(explode(' ', microtime()));
 // $_FUNCTION_LOADER=array("ordini",
  //                       "ordini_valori",
   //                      "gas");

 include ("../rend.php");
 include ("ptp_functions.php");
 
 
 if(!_USER_LOGGED_IN){
    header("Location: index.php");     
 }
 
 $h = ptp_ordini_io_coinvolto(_USER_ID,_USER_ID_GAS);
 
 
 echo ptp_header().
      ptp_head().
      ptp_menu().
      $msg.
      $h.
      ptp_footer(); 
?>