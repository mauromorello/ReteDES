<?php

// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");

$chiave = "SWITCH_".$codice;

if(!_USER_LOGGED_IN){
   go("sommario",0,null,"?q=121"); 
}

if(check_option_exist(0,$chiave)){
    //Echo "OPZIONE ESISTENTE <br>";
    $settings = unserialize(base64_decode(read_option_text(0,$chiave)));
    extract($settings);
    
    $id_utente_target = CAST_TO_INT($id_utente_target);
    $id_ordine = CAST_TO_INT($id_ordine);
    
    //echo "USER ID TARGET = ".$id_utente_target."<br>";
   // echo "ID ORDINE = ".$id_ordine."<br>";
    
    
    
     $sql = "UPDATE retegas_ordini 
              SET 
              retegas_ordini.id_utente = '$id_utente_target'
              WHERE 
              retegas_ordini.id_ordini = '$id_ordine' LIMIT 1;";
              
     $result = $db->sql_query($sql);
     
     $sql = "UPDATE retegas_referenze 
              SET 
              retegas_referenze.id_utente_referenze = '$id_utente_target'
              WHERE 
              retegas_referenze.id_ordine_referenze = '$id_ordine'
              AND
              retegas_referenze.id_gas_referenze = '"._USER_ID_GAS."' LIMIT 1;";
              
     $result = $db->sql_query($sql);
     
     //Cerco tra tutti gli switch
     $sql = "SELECT * FROM retegas_options WHERE chiave LIKE 'SWITCH_%';";
     $result = $db->sql_query($sql);
     //ASSSEGNO il vecchio id_ordine
     $check_ordine = $id_ordine;
    
     unset($sql_or);
     unset($sql_2);
     while ($row = mysql_fetch_array($result)){
         $settings = unserialize(base64_decode($row["valore_text"]));

         //Estraendo i settings ottengo la variabile "ID ordine" nuova
         extract($settings);

         if($id_ordine==$check_ordine){
            if (isset($row["id_option"])) {
                $sql_or .= " OR id_option ='".$row["id_option"]."'";

            } 
         }
            
     }
     $sql_or = substr($sql_or,3);
     $sql_2 = "DELETE FROM `retegas_options` WHERE ($sql_or);";   

     
     $result = $db->sql_query($sql_2);

     log_me($check_ordine,_USER_ID,"SWI","GES","Utente "._USER_ID." vuol sostituire il gestore ordine $check_ordine",0,$sql_2);
     go("sommario",_USER_ID,"Congratulazioni. L'ordine è tuo. Divertiti.");

     
}else{
    if(_USER_LOGGED_IN){
        go("sommario",_USER_ID,"Non esiste una richiesta di cambio gestore per questo ordine.<br>Potrebbe averla accettata qualcun altro nel frattempo.");
    }else{
        go("sommario");
    }
};