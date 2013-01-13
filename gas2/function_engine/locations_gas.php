<?php

if(isset($root) AND isset($iroot)){
$locations_gas=Array(//GAS
                          "gas_users"               =>$root."gas/gas_users.php",
                          "gas_users_ajax"          =>$root."gas/gas_users_ajax.php",
                          "gas_form"                =>$root."gas/gas_form.php",
                          "gas_modifica"            =>$root."gas/gas_form_edit.php",
                          "gas_perm_new_users"      =>$root."gas/gas_perm_new_users.php",
                          "gas_ids"                 =>$root."gas/gas_ids.php",
                          "gas_option_sito"         =>$root."gas/gas_option_sito.php",                         
                          "gas_user_activate"       =>$root."gas/gas_user_activate.php",
                          "gas_composizione"        =>$root."gas/gas_composizione.php",);
}

  
?>