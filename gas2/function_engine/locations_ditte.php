<?php

if(isset($root) AND isset($iroot)){
$locations_ditte=Array(//DITTE e listini e tipologie
                          "form_ditta"              =>$root."ditte/ditte_form_new.php",
                          "ditte_form_new"          =>$root."ditte/ditte_form_new.php",
                          "add_ditta"               =>$root."ditte/ditte_form_add.php",
                          "mod_ditta"               =>$root."ditte/ditte_form_edit.php",
                          "opinione_certificante"   =>$root."ditte/opinione_certificante.php",
                          "opinione_relazionante"   =>$root."ditte/opinione_relazionante.php",
                          "add_listino"             =>$root."listini/listini_form_add.php",
                          "tutte_le_ditte"          =>$root."ditte/ditte_table_3.php",
                          "ditte_table_3"           =>$root."ditte/ditte_table_3.php", 
                          "mie_ditte"               =>$root."ditte/ditte_table_mie.php",
                          "ditte_geo"               =>$root."ditte/ditte_geo.php",
                          "ditte_geo_cluster"       =>$root."ditte/ditte_geo3.php",
                          "miei_listini"            =>$root."listini/listini_table_miei.php",
                          "delete_articoli"         =>$root."listini/listini_table_delete_articoli.php", 
                          "tipologie"               =>$root."tipologie/tipologie_table.php",
                          "ditte_form_stat_listini" =>$root."ditte/ditte_form_stat_listini.php",
                          "ditte_form_stat_ord_s"   =>$root."ditte/ditte_form_stat_ordini_soldi.php"
                          );
}

  
?>