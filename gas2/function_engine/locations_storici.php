<?php

if(isset($root) AND isset($iroot)){
$locations_storici=Array(//STORICI
                          "storici_miei_ordini"     =>$root."storici/storici_ordini_miei.php",
                          "storici_ditte_gas"       =>$root."storici/storici_ditte_gas.php",
                          "storici_ditte_gas_graf"  =>$root."storici/grafici/grafico_storici_ditte_gas.php",
                          "storici_ditte_mie"       =>$root."storici/storici_ditte_mie.php",
                          "storici_ordini_gas"      =>$root."storici/storici_ordini_gas.php",
                          "storici_fam_gas"         =>$root."storici/storici_famiglie_gas.php",
                          "storici_fam_gas_graf"    =>$root."storici/grafici/grafico_storici_famiglie_gas.php");
}

  
?>