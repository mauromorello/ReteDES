<?php

//L'update viene effettuato includendo "rend"
include_once("../rend.php");
   

//$do= $_GET["do"];	

switch ($do){
	
    
    case "db_update_32453rfwdf2343214erwfr2353":
	
    // UPDATE-----------------------------------------------------QUESTA VIENE ESEGUITA SEMPRE
   
    $log_ordini_chiusi = update_ordini_chiusi();
    $log_ordini_aperti = update_ordini_aperti();
    
    if($log_ordini_chiusi<>""){
		Echo  $log_ordini_chiusi;
	}else{
		Echo  "ORDINI CHIUSI NESSUNO\n";
	}
	if($log_ordini_aperti<>""){    
		Echo  $log_ordini_aperti;
	}else{
		Echo  "ORDINI APERTI NESSUNO\n";
	}
	
	break;
    
    
    case "check_users_outdated_dsfkiuhg43983hd":
        
        
        //ciclo tutti i gas
        $sql_gas = "SELECT * FROM retegas_gas;";
        $result_gas = $db->sql_query($sql_gas);
        while ($row_gas = $db->sql_fetchrow($result_gas)){
            
            $r .="------------------------------------------<br>";
            $r .="GAS: ".$row_gas["descrizione_gas"]."<br>";
            
            //per ogni gas sospendo gli utenti
            $days_for_suspend = CAST_TO_INT(read_option_gas_text($row_gas["id_gas"],"_GAS_SITE_INATTIVITA"));
            if($days_for_suspend>0){
                $sql_users= "SELECT *, DATEDIFF(NOW(),last_activity) as diff_date FROM `maaking_users` 
                WHERE DATEDIFF(NOW(),last_activity)>'$days_for_suspend' 
                AND id_gas='".$row_gas["id_gas"]."' AND isactive='1';";
                
                $result_users = $db->sql_query($sql_users);
                
                $frase = read_option_gas_text($row_gas["id_gas"],"_GAS_SITE_FRASE_INATTIVITA");
                if($frase==""){$frase="Account sospeso per prolungata inattività";}
                
                while ($row_users = $db->sql_fetchrow($result_users)){
                    $r .= "-----".$row_users["fullname"]." --> ".$days_for_suspend." -->".$row_users["diff_date"]."<br>";
                    
                    $sql_susp = "UPDATE maaking_users SET isactive=2 WHERE userid='".$row_users["userid"]."' LIMIT 1;";
                    
                    $res_susp = $db->sql_query($sql_susp);
                    write_option_text($row_users["userid"],"_NOTE_SUSPENDED",$frase);
                    
                    $suspended ++;
                }
                
                if($db->sql_numrows($result_users)>0){

                    //Dovrei mandare una mail ?  
                }
                                
                $r.= "totale utenti interessati: ".$db->sql_numrows($result_users)."<br>";
                
                $r.= "Frase agli utenti: ".$frase."<br>";
                $r.= "---------------------------------------------<br>";
            }else{
                $r.= "Nessun valore impostato<br>";
            }
            //Per ogni utente sospendo e setto la frase di sospensione
        
        }
        
        if($suspended>0){
            log_me(0,0,"CRO","SSP","Trovati $suspended utenti da sospendere",$suspended,$r);
        }
        
        
        Echo $r;
    
    break;

    
    
    case "alert_unconfirmed_orders_sdjfsgdf98dsfiuohsdfjhwef98fewhj";
        Echo  "ALERT SENT UNCONFIRMED ORDERS OK\n";
    break;
}