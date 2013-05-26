<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../articoli/articoli_renderer.php");
include_once ("../ordini_renderer.php");
include_once ("../../retegas.class.php");

// http://retegas.altervista.org/gas2/ordini/modifica_qta/ordini_modifica_assegnazione.php?id_articolo=10745&id_ordine=450
// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     go("sommario",_USER_ID,"Non puoi partecipare agli ordini. Contatta il tuo referente GAS.");
}

if (ordine_io_cosa_sono($id_ordine,_USER_ID)<1){
    go("sommario",_USER_ID,"Questo ordine non mi compete");
}

$id_articolo_next = $id_articolo;

switch ($submit_form){
    case "Salva e torna all'ordine":
         $dove_vai = "ordine_partecipa";
    break;
    case "Salva e vai all'articolo successivo":
         $id_listino = id_listino_from_id_ordine($id_ordine);
         $sql = "SELECT * FROM retegas_articoli WHERE id_listini='$id_listino' ORDER BY descrizione_articoli ASC;";
         $res = $db->sql_query($sql);
         $i=0;
         $nuovo = false;
         while ($row = mysql_fetch_assoc($res)){
             //echo " i = $i ".$row["id_articoli"]."<br>";
             
             if($nuovo){
               $id_articolo_next = $row["id_articoli"];
               if(articolo_id_listino($id_articolo_next)<>$id_listino){
                   $id_articolo_next = $id_articolo;
               }
               break; 
             }
             
             if($id_articolo==$row["id_articoli"]){
               $nuovo=true;
            }
                 
         }
   $dove_vai = "ordini_mod_ass_new";

         
    break;
    case "Salva e torna alla home":
         $dove_vai = "sommario";
    break;
    case "Salva e vai al dettaglio articoli":
         $dove_vai = "ordini_mia_spesa_dettaglio";
    break;
    default:
         $dove_vai = "ordini_mod_ass_new";
    break;     
}


if($do=="save_mods"){
    
    $update_cassa = "NO";
    
    //CONTROLLO PER USCIRE SE NON POSSO
    //USER HA LA CASSA
    if(_USER_USA_CASSA){
        $log .="GAS USA CASSA, USER USA CASSA<br>"; 
    }
    //USER NON HA LA CASSA
    else{
        $log .="USER NON HA LA CASSA<br>";
        if(_GAS_USA_CASSA){
            $log .="GAS CON la cassa<br>";   
            if(_USER_ID_GAS ==id_gas_user(id_referente_ordine_globale($id_ordine))){
                //SE l'utente che partecipa è del gas dell'ordine
                if(ordini_field_value($id_ordine,"solo_cassati"=="SI")){
                     
                     //USER SENZA CASSA
                     //GAS CON CASSA
                     //STESSO GAS ORDINE
                     //ORDINE SOLO PER CASSATI
                    
                     $log .="Ordine $id_ordine SOLO PER CASSATI<br>";
                     $msg = "Ordine aperto solo a chi usa la cassa;";   
                     $dove_vai = "ordini_mod_ass_new";
                     go($dove_vai,_USER_ID,$msg,"?id_ordine=$id_ordine&id_articolo=$id_articolo");
     
                }else{
                     $log .="Ordine $id_ordine APERTO A TUTTI, OK<br>";
                }
            }else{
                $log .="User partecipante di GAS ESTERNO, OK<br>";
            }
        }else{
           $log .="GAS SENZA la cassa, OK<br>"; 
        }            
    }
    
    //Se l'ordine è aperto
    if(stato_from_id_ord($id_ordine)==2){
        $log .="Trovato ordine $id_ordine Aperto<br>";

        //Passo la lista e faccio il totale
        $nuova_quantita_articolo = round(array_sum($box_quantita),4);
        $vecchia_quantita_articolo = round(n_articoli_arrivati_da_user($id_ordine,$id_articolo,_USER_ID),4);

        $log .= "NUOVA ".$nuova_quantita_articolo."; VECCHIA ".$vecchia_quantita_articolo."<br>";
        $msg .= "Articolo $id_articolo (".articolo_sua_descrizione($id_articolo).") :<br>";
        
        //NUOVA QUANTITA' = 0
        if($nuova_quantita_articolo==0){
            $log .= "Imposta QTA = 0 <br>";
            
            //SE NON HO LA RIGA la trovo
            if(is_empty($n_riga)){
                $n_riga = n_riga_ordini_dettaglio_distribuzione($id_ordine,$id_articolo,_USER_ID);
                $log .= "Estratta riga= $n_riga <br>";
            }else{
                $log .= "Passata riga= $n_riga <br>";        
            }
            // CANCELLO DALLA TABELLA DETTAGLI ORDINE.
            $sqd = "delete from retegas_dettaglio_ordini 
                    WHERE retegas_dettaglio_ordini.id_dettaglio_ordini='$n_riga' LIMIT 1;";
            $db->sql_query($sqd);

            // CANCELLO DALLA DISTRIBUZIONE
            $sqd = "delete from retegas_distribuzione_spesa 
            WHERE retegas_distribuzione_spesa.id_riga_dettaglio_ordine='$n_riga';";
            $db->sql_query($sqd);
            $righe_cancellate = $db->sql_affectedrows();
            
            $msg .="Cancellato dall'ordine.<br>";
            $log .="Cancellato articolo $id_articolo dall'ordine <br>";
            
            if(n_articoli_ordini_user($id_ordine,_USER_ID)==0){
                //SE NON CI SONO PIU' ARTICOLI CANCELLO LA PRENOTAZIONE
                delete_option_prenotazione_ordine($id_ordine,_USER_ID);
                $log .="Ordine vuoto, cancellata eventuale prenotazione<br>";
            }
            
            
            $mail_necessaria ="SI";
            $update_cassa ="SI";
            
        }
        
        //NUOVA QUANTITA' MAGGIORE DI 0
        else{       
            $log .= "NUOVA Q > 0 <br>";
            //MULTIPLO CORRETTO
            if(is_multiplo(articolo_sua_qmin($id_articolo),$nuova_quantita_articolo)){
                $log .= "Multiplo: OK<br>";
            
                //SE LA NUOVA QUANTITA' E' UGUALE
                if($nuova_quantita_articolo==$vecchia_quantita_articolo){
                    
                    $log .= "QTA UGUALI<br>";
                    //HO SOLO CAMBIATO ASSEGNAZIONE
                    
                    //SE NON L'HO GIA' PASSATA COME PARAMETRO IN CASO DI UNIVOCO
                    //TROVO LA RIGA DEL DETTAGLIO
                    if(is_empty($n_riga)){
                        $n_riga = n_riga_ordini_dettaglio_distribuzione($id_ordine,$id_articolo,_USER_ID);
                        $log .= "Estratta riga= $n_riga <br>";
                    }else{
                        $log .= "Passata riga= $n_riga <br>";
                    }
                    //echo "$id_ordine $id_articolo N RIGA = $n_riga<br>";
                    //CANCELLO ASSEGNAZIONE
                    $sql = "DELETE FROM retegas_distribuzione_spesa 
                    WHERE id_articoli='$id_articolo'
                    AND id_user='"._USER_ID."'
                    AND id_riga_dettaglio_ordine='$n_riga';";
                    $res_1 = $db->sql_query($sql);
                    
                    //PASSO TUTTI AMICI E INSERISCO SE VALORE SOPRA ZERO
                    //SIA QORD CHE QARR - CAST TO FLOAT
                    foreach ($box_id_amico as $key => $id_amico) {
                        $log .= "Key: $key; id_amico: $id_amico, Qtà nuova: $box_quantita[$key]<br />\n";
                        
                        // id_amico = $id_amico
                        //qta = $box_quantita[$key]
                        $valore_da_inserire = CAST_TO_FLOAT($box_quantita[$key],0);
                        if($valore_da_inserire>0){
                                            
                            $log .= "INSERISCO : NRIGA =$n_riga AMICO=$id_amico,  $box_quantita[$key] $id_articolo<br />\n";
                            $msg .= "a ".amici_nome_di_amico($id_amico). ": n. $valore_da_inserire <br>";
                            //SCRIVO ASSEGNAZIONE
                            $query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
                                                         id_riga_dettaglio_ordine,
                                                         id_amico,             
                                                         qta_ord,
                                                         qta_arr,
                                                         data_ins,
                                                         id_articoli,
                                                         id_user,
                                                         id_ordine) 
                                                         VALUES (
                                                                    '$n_riga',
                                                                    '$id_amico',
                                                                    '$valore_da_inserire',
                                                                    '$valore_da_inserire',
                                                                     NOW(),
                                                                    '$id_articolo',
                                                                    '"._USER_ID."',
                                                                    '$id_ordine'
                                                                    );";
                            $res_2 = $db->sql_query($query_distribuzione_spesa);
                        }
                    }
                    unset($id_amico);
                    $update_cassa = "NO";
                
                }
                
                //SE E' INFERIORE
                if($nuova_quantita_articolo<$vecchia_quantita_articolo){
                    
                        $log .= "QTA VECCHIA < Q NUOVA><br>";
                        //TROVO IL NUMERO RIGA DI DETTAGLIO SE NON CE l'HO GIA'
                        if(is_empty($n_riga)){
                            $n_riga = n_riga_ordini_dettaglio_distribuzione($id_ordine,$id_articolo,_USER_ID);
                            $log .= "Estratta riga= $n_riga <br>";
                        }else{
                            $log .= "Passata riga= $n_riga <br>";
                        }
                        
                        //LASCIO INTATTA LA DATA DI ACQUISTO
                        //AGGIORNO DETTAGLIO
                        $log .= "Eseguo UPDATE<br>";
                        $squ = "UPDATE 
                                `retegas_dettaglio_ordini` 
                                SET 
                                `qta_ord` = '$nuova_quantita_articolo', 
                                `qta_arr` = '$nuova_quantita_articolo',
                                `timestamp_ord` = NOW() 
                                WHERE 
                                `retegas_dettaglio_ordini`.`id_dettaglio_ordini` = $n_riga LIMIT 1;";
                        $db->sql_query($squ);
                        
                        //CANCELLO ASSEGNAZIONE
                        $log .= "Eseguo DELETE ASSEGNAZIONE<br>";
                        $sqa = "DELETE FROM retegas_distribuzione_spesa 
                        WHERE id_articoli='$id_articolo'
                        AND id_user='"._USER_ID."'
                        AND id_riga_dettaglio_ordine='$n_riga';";
                        $res_1 = $db->sql_query($sqa);
                        
                        //RISCRIVO DISTRIBUZIONE
                        //PASSO TUTTI AMICI E INSERISCO SE VALORE SOPRA ZERO
                        //SIA QORD CHE QARR - CAST TO FLOAT
                        foreach ($box_id_amico as $key => $id_amico) {
                            $log .= "Controllo : NRIGA =$n_riga AMICO=$id_amico,  $box_quantita[$key] $id_articolo<br />";
                            // id_amico = $id_amico
                            //qta = $box_quantita[$key]
                            $valore_da_inserire = CAST_TO_FLOAT($box_quantita[$key],0);
                            if($valore_da_inserire>0){
                                                
                                $log .= "INSERISCO : NRIGA =$n_riga AMICO=$id_amico,  $box_quantita[$key] $id_articolo<br />";
                                $msg .= "a ".amici_nome_di_amico($id_amico). ": n. $box_quantita[$key] <br>";
                                //SCRIVO ASSEGNAZIONE
                                $query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
                                                             id_riga_dettaglio_ordine,
                                                             id_amico,             
                                                             qta_ord,
                                                             qta_arr,
                                                             data_ins,
                                                             id_articoli,
                                                             id_user,
                                                             id_ordine) 
                                                             VALUES (
                                                                        '$n_riga',
                                                                        '$id_amico',
                                                                        '$valore_da_inserire',
                                                                        '$valore_da_inserire',
                                                                         NOW(),
                                                                        '$id_articolo',
                                                                        '"._USER_ID."',
                                                                        '$id_ordine'
                                                                        );";
                                $res_2 = $db->sql_query($query_distribuzione_spesa);
                            }
                        }
                        unset($id_amico);
                        
                        $update_cassa ="SI";
                        //MANDO MAIL
                        $mail_necessaria="SI";
                    }
                    
                //CASSA    
                //SE ESISTEVA GIA' LA Q VECCHIA
                if($vecchia_quantita_articolo > 0){
                        $log .= "QTA VECCHIA > 0<br>";
                        //SE E' SUPERIORE
                        if($nuova_quantita_articolo>$vecchia_quantita_articolo){
                        
                            $log .= "QTA NUOVA > VECCHIA<br>";
                            
                            
                            //CONTROLLO LA CASSA
                            if(_USER_USA_CASSA){
                                 $log .= "Utente che usa la cassa<br>";
                                 $suo_prezzo = articolo_suo_prezzo($id_articolo);
                                 $valore_aggiunta = ($nuova_quantita_articolo * $suo_prezzo)- ($vecchia_quantita_articolo * $suo_prezzo);
                     
                                 $vo = CAST_TO_FLOAT($valore_aggiunta,0) -  valore_totale_mio_ordine($id_ordine,_USER_ID);
                                 
                                 // Aggiungo la percentuale prestabilita al valore del mio ordine
                                 $vo =  round((($vo/100)* _GAS_COPERTURA_CASSA ) + $vo);
                                 
                                 $vc = cassa_utente_tutti_movimenti(_USER_ID);
                                 
                                 //se il credito non basta
                                 if(($vc-$vo)< _GAS_CASSA_MIN_LEVEL){
                                     $log .= "Credito insuff.<br>";
                                     $msg = "Credito insufficiente per questo acquisto;<br>
                                                Ricorda che è contemplata una percentuale del "._GAS_COPERTURA_CASSA."% di spese accessorie che vanno a sommarsi all'importo dell'ordine.<br>
                                                Vi è inoltre una soglia minima di "._GAS_CASSA_MIN_LEVEL." Eu. (decisa dal tuo GAS) sotto la quale non si può ordinare.<br>
                                                I totali effettivi saranno modificati o confermati ad ordine chiuso dal gestore o dal cassiere.";   
                                     $dove_vai = "ordini_mod_ass_new";
                                     go($dove_vai,_USER_ID,$msg,"?id_ordine=$id_ordine&id_articolo=$id_articolo");
                                 }else{
                                     $update_cassa="SI";
                                 }
                                 
                             
                             
                             }
                           
                            //TROVO IL NUMERO RIGA DI DETTAGLIO SE NON CE l'HO GIA'
                            if(is_empty($n_riga)){
                                $n_riga = n_riga_ordini_dettaglio_distribuzione($id_ordine,$id_articolo,_USER_ID);
                                $log .= "Estratta riga= $n_riga <br>";
                            }else{
                                $log .= "Passata riga= $n_riga <br>";
                            }
                        
                            //NUOVA Q > VECCHIA Q e ARTICOLO CUMULABILE
                            if (!articolo_univoco($id_articolo)){
                            
                                //AGGIORNO DATA ACQUISTO
                                $squ = "UPDATE 
                                        `retegas_dettaglio_ordini` 
                                        SET 
                                        `qta_ord` = '$nuova_quantita_articolo', 
                                        `qta_arr` = '$nuova_quantita_articolo',
                                        `data_inserimento` = NOW(),
                                        `timestamp_ord` = NOW() 
                                        WHERE 
                                        `retegas_dettaglio_ordini`.`id_dettaglio_ordini` = $n_riga LIMIT 1;";
                                $db->sql_query($squ);
                            
                                //AGGIORNO DETTAGLIO
                                //CANCELLO DISTRIBUZIONE
                                //CANCELLO ASSEGNAZIONE
                                $sqa = "DELETE FROM retegas_distribuzione_spesa 
                                WHERE id_articoli='$id_articolo'
                                AND id_user='"._USER_ID."'
                                AND id_riga_dettaglio_ordine='$n_riga';";
                                $res_1 = $db->sql_query($sqa);
                                //RISCRIVO DISTRIBUZIONE
                                //RISCRIVO DISTRIBUZIONE
                                //PASSO TUTTI AMICI E INSERISCO SE VALORE SOPRA ZERO
                                //SIA QORD CHE QARR - CAST TO FLOAT
                                foreach ($box_id_amico as $key => $id_amico) {
                                    $log .= "Key: $key; id_amico: $id_amico, Qtà nuova: $box_quantita[$key]<br />\n";
                                    // id_amico = $id_amico
                                    //qta = $box_quantita[$key]
                                    $valore_da_inserire = CAST_TO_FLOAT($box_quantita[$key],0);
                                    if($valore_da_inserire>0){
                                                        
                                        $log .= "INSERISCO : NRIGA =$n_riga AMICO=$id_amico,  $box_quantita[$key] $id_articolo<br />\n";
                                        $msg .= "a ".amici_nome_di_amico($id_amico). ": n. $valore_da_inserire <br>";
                                        //SCRIVO ASSEGNAZIONE
                                        $query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
                                                                     id_riga_dettaglio_ordine,
                                                                     id_amico,             
                                                                     qta_ord,
                                                                     qta_arr,
                                                                     data_ins,
                                                                     id_articoli,
                                                                     id_user,
                                                                     id_ordine) 
                                                                     VALUES (
                                                                                '$n_riga',
                                                                                '$id_amico',
                                                                                '$valore_da_inserire',
                                                                                '$valore_da_inserire',
                                                                                 NOW(),
                                                                                '$id_articolo',
                                                                                '"._USER_ID."',
                                                                                '$id_ordine'
                                                                                );";
                                        $res_2 = $db->sql_query($query_distribuzione_spesa);
                                    }
                                }
                                unset($id_amico);
                        
                            //NUOVA Q > VECCHIA Q ma ARTICOLO UNICO
                            }else{
                                $log .= "USCITO : NRIGA =$n_riga AMICO=$id_amico,  $box_quantita[$key] $id_articolo QVecchia > QNuova ma già esistente<br />\n";
                                $msg .= "Operazione non consentita con articoli univoci.<br>";
                                $dove_vai = "ordini_mod_ass_new";
                            }    
                        //MANDO MAIL
                        }
                    }
                    
                //SE NON ESISTEVA LA Q VECCHIA
                else{
                        $log .= "QTA VECCHIA = 0 : HO UN INSERT<br>";
                        
                        
                        
                        //CONTROLLO LA CASSA
                        if(_USER_USA_CASSA){
                             $log .= "Utente che usa la cassa<br>";
                             $valore_aggiunta = $nuova_quantita_articolo * articolo_suo_prezzo($id_articolo);
                 
                             $vo = CAST_TO_FLOAT($valore_aggiunta,0) -  valore_totale_mio_ordine($id_ordine,_USER_ID);
                             
                             // Aggiungo la percentuale prestabilita al valore del mio ordine
                             $vo =  round((($vo/100)* _GAS_COPERTURA_CASSA ) + $vo);
                             
                             $vc = cassa_utente_tutti_movimenti(_USER_ID);
                             
                             //se il credito non basta
                             if(($vc-$vo)< _GAS_CASSA_MIN_LEVEL){
                                 $log .= "Credito insuff.<br>";
                                 $msg  = "Credito insufficiente per questo acquisto;<br>
                                            Ricorda che è contemplata una percentuale del "._GAS_COPERTURA_CASSA."% di spese accessorie che vanno a sommarsi all'importo dell'ordine.<br>
                                            Vi è inoltre una soglia minima di "._GAS_CASSA_MIN_LEVEL." Eu. (decisa dal tuo GAS) sotto la quale non si può ordinare.<br>
                                            I totali effettivi saranno modificati o confermati ad ordine chiuso dal gestore o dal cassiere.";   
                                 $dove_vai = "ordini_mod_ass_new";
                                 go($dove_vai,_USER_ID,$msg,"?id_ordine=$id_ordine&id_articolo=$id_articolo");
                             }else{
                                 $update_cassa="SI";
                             }
                             
                         
                         
                         }
                        
                        
                        //SE L'articolo NON ? univoco
                        if(!articolo_univoco($id_articolo)){
                        
                            $log .= "Articolo $id_articolo NON univoco<br>";
                            //LA QUANTITA' VECCHIA E' ZERO, DEVO INSERIRE L'ARTICOLO NUOVO
                            $query_inserimento_articolo = "INSERT INTO retegas_dettaglio_ordini ( 
                                                            id_utenti,
                                                            id_articoli,             
                                                            data_inserimento,
                                                            qta_ord,
                                                            id_amico,
                                                            id_ordine,
                                                            qta_arr) 
                                                            VALUES (
                                                                '"._USER_ID."',
                                                                '$id_articolo',
                                                                NOW(),
                                                                '$nuova_quantita_articolo',
                                                                '0',
                                                                '$id_ordine',
                                                                '$nuova_quantita_articolo'
                                                                );";
                            $result = $db->sql_query($query_inserimento_articolo);
                            $log .= "Inserito DETTAGLIO<br>";
                            //NON SI SA MAI
                            usleep(500);
                            $mail_necessaria = "SI";
                            // scopro qual'? l'ultimo ID inserito (RIGA Dettaglio_ordine)

                            $n_riga = n_riga_ordini_dettaglio_distribuzione($id_ordine,$id_articolo,_USER_ID);


                            // aggiungo un record in dettaglio_spesa con l'articolo caricato in utente id_user
                            foreach ($box_id_amico as $key => $id_amico) {
                                    $log .=  "Distribuzione nuova Key: $key; id_amico: $id_amico, Qtà nuova: $box_quantita[$key]<br/>";
                                    // id_amico = $id_amico
                                    //qta = $box_quantita[$key]
                                    $valore_da_inserire = CAST_TO_FLOAT($box_quantita[$key],0);
                                    if($valore_da_inserire>0){
                                                        
                                        $log .= "INSERISCO : NRIGA =$n_riga AMICO=$id_amico,  $box_quantita[$key] $id_articolo<br />";
                                        $msg .= "a ".amici_nome_di_amico($id_amico). ": n. $valore_da_inserire <br>";
                                        //SCRIVO ASSEGNAZIONE
                                        $query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
                                                                     id_riga_dettaglio_ordine,
                                                                     id_amico,             
                                                                     qta_ord,
                                                                     qta_arr,
                                                                     data_ins,
                                                                     id_articoli,
                                                                     id_user,
                                                                     id_ordine) 
                                                                     VALUES (
                                                                                '$n_riga',
                                                                                '$id_amico',
                                                                                '$valore_da_inserire',
                                                                                '$valore_da_inserire',
                                                                                 NOW(),
                                                                                '$id_articolo',
                                                                                '"._USER_ID."',
                                                                                '$id_ordine'
                                                                                );";
                                        $res_2 = $db->sql_query($query_distribuzione_spesa);
                                    }
                                }
                                unset($id_amico);
                            }else{
                                //MERDA ! E' un articolo UNICO
                                //NON GESTITO
                                //MA ADESSO LO GESTIAMO
                                //echo "Sono in nuovo articolo unico<br>";
                                //PASSO TUTTI GLI AMICI
                                foreach ($box_id_amico as $key => $id_amico) {
                                    //echo "Controllo amico $id_amico<br>";
                                    //SE L'importo di id_amico ? >0
                                    $valore_da_inserire = CAST_TO_FLOAT($box_quantita[$key],0);
                                    if($valore_da_inserire>0){
                                        //echo "$valore_da_inserire > 0 (Val da ins)<br>";
                                        //SE L'importo di amico ? un multiplo giusto
                                        $q_min = round(articolo_sua_qmin($id_articolo),4);
                                        if(is_multiplo($q_min,$valore_da_inserire)){
                                            //echo "E' multiplo corretto<br>";
                                            //Dall'importo - Qmin fino a 0
                                            for($i=$valore_da_inserire;$i>0;$i=$i-$q_min){
                                                //echo "Sono FOR i = $i : $q_min<br>";
                                                //echo "INSERISCO $q_min a $id_amico <br>";
                                                //INSERISCO DETTAGLIO con Qmin
                                                $code = CAST_TO_INT(random_string(6,"1234567890"));
                                                $log .= "Articolo $id_articolo Univoco<br>";
                                                //LA QUANTITA' VECCHIA E' ZERO, DEVO INSERIRE L'ARTICOLO NUOVO
                                                $query_inserimento_articolo = "INSERT INTO retegas_dettaglio_ordini ( 
                                                                                id_utenti,
                                                                                id_articoli,             
                                                                                data_inserimento,
                                                                                id_stati,
                                                                                qta_ord,
                                                                                id_amico,
                                                                                id_ordine,
                                                                                qta_arr) 
                                                                                VALUES (
                                                                                    '"._USER_ID."',
                                                                                    '$id_articolo',
                                                                                    NOW(),
                                                                                    '$code',
                                                                                    '$q_min',
                                                                                    '0',
                                                                                    '$id_ordine',
                                                                                    '$q_min'
                                                                                    );";
                                                $result = $db->sql_query($query_inserimento_articolo);
                                                $log .= "Inserito DETTAGLIO<br>";
                                                //NON SI SA MAI
                                                usleep(200);
                                                $mail_necessaria = "SI";
                                                // scopro qual'? l'ultimo ID inserito (RIGA Dettaglio_ordine)

                                                $n_riga = n_riga_ordini_from_code($id_ordine,$id_articolo,_USER_ID,$code);

                                                //INSERISCO DISTRIBUZIONE Qmin con id_amico
                                                $log .= "INSERISCO : NRIGA =$n_riga AMICO=$id_amico,  $q_min UNICA  $id_articolo<br />";
                                                $msg .= "a ".amici_nome_di_amico($id_amico). ": n. $q_min <br>";
                                                //SCRIVO ASSEGNAZIONE
                                                $query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
                                                                             id_riga_dettaglio_ordine,
                                                                             id_amico,             
                                                                             qta_ord,
                                                                             qta_arr,
                                                                             data_ins,
                                                                             id_articoli,
                                                                             id_user,
                                                                             id_ordine) 
                                                                             VALUES (
                                                                                        '$n_riga',
                                                                                        '$id_amico',
                                                                                        '$q_min',
                                                                                        '$q_min',
                                                                                         NOW(),
                                                                                        '$id_articolo',
                                                                                        '"._USER_ID."',
                                                                                        '$id_ordine'
                                                                                        );";
                                                $res_2 = $db->sql_query($query_distribuzione_spesa);
                                                usleep(200);
                                        
                                        
                                        
                                            //Fine ciclo su importo
                                            }
                                        //Fine se ? multiplo
                                        }else{
                                            $msg .= "E' stata trovata una quantità non corretta e non è stata inserita.<br>";
                                            $dove_vai = "ordini_mod_ass_new";
                                        }
                                    //Fine se ? maggiore di 0
                                        }
                                //Fine ciclo amico
                                }
                                                            
                                $log .= "ARTICOLO UNICO INSERITO<br>";
                               // $msg .= "Purtroppamente non ? possibile inserire in questo modo gli articoli univoci.<br>
                               //          Devi inserirli normalmente e poi dividerli tra gli amici";
                        }
                            
                            
                            
                    }
 
            }
            //MULTIPLO NON CORRETTO
            else{
                $msg .= "Il nuovo totale non è un multiplo di quantità minima<br>";
                $log .= "MULTIPLO : Not OK <br>";
                $update_cassa ="NO";
            }
        }
    
        //AGGIORNA CASSA SOLO SE FA BISOGNO
        //SIA CHE CI SIA STATA UNA CANCELLAZIONE CHE UN AGGIUNTA
        if($update_cassa=="SI"){
            $log .="Dovrei fare UPDATE CASSA<br>";
            if(read_option_prenotazione_ordine($id_ordine,_USER_ID)<>"SI"){
                $log .="PRENOTAZIONE ? NO, eseguo update cassa<br>";
                if(_USER_USA_CASSA){
                    $log .="Utente Con CASSA ATTIVA<br>";
                    cassa_update_ordine_utente($id_ordine,_USER_ID);
                }else{
                    $log .="Utente senza CASSA, nessun UPDATE<br>";
                }
            }else{
                $log .="PRENOTAZIONE ? SI, salto update cassa<br>";
            } 
        }
    }
    //ORDINE CHIUSO
    else{
    //Se l'ordine è chiuso
    $log .="Trovato ordine $id_ordine CHIUSO<br>";
    
    //Passo la lista e faccio il totale
    $nuova_quantita_articolo = round(array_sum($box_quantita),4);
    $vecchia_quantita_articolo = round(n_articoli_arrivati_da_user($id_ordine,$id_articolo,_USER_ID),4);

    $log .=  "NUOVA ".$nuova_quantita_articolo."; VECCHIA ".$vecchia_quantita_articolo."<br>";
    $msg .= "Articolo $id_articolo (".articolo_sua_descrizione($id_articolo).") :<br>";
    
    
    //Se il totale ? uguale a quello vecchio
    if($nuova_quantita_articolo==$vecchia_quantita_articolo){
                    
                    $log .= "QTA UGUALI<br>";
                    //HO SOLO CAMBIATO ASSEGNAZIONE
                    
                    //SE NON L'HO GIA' PASSATA COME PARAMETRO IN CASO DI UNIVOCO
                    //TROVO LA RIGA DEL DETTAGLIO
                    if(is_empty($n_riga)){
                        $n_riga = n_riga_ordini_dettaglio_distribuzione($id_ordine,$id_articolo,_USER_ID);
                        $log .= "Estratta riga= $n_riga <br>";
                    }else{
                        $log .= "Passata riga= $n_riga <br>";
                    }
                    //echo "$id_ordine $id_articolo N RIGA = $n_riga<br>";
                    //CANCELLO ASSEGNAZIONE
                    $sql = "DELETE FROM retegas_distribuzione_spesa 
                    WHERE id_articoli='$id_articolo'
                    AND id_user='"._USER_ID."'
                    AND id_riga_dettaglio_ordine='$n_riga';";
                    $res_1 = $db->sql_query($sql);
                    
                    //PASSO TUTTI AMICI E INSERISCO SE VALORE SOPRA ZERO
                    //SIA QORD CHE QARR - CAST TO FLOAT
                    foreach ($box_id_amico as $key => $id_amico) {
                        $log .= "Key: $key; id_amico: $id_amico, Qtà nuova: $box_quantita[$key]<br />\n";
                        
                        // id_amico = $id_amico
                        //qta = $box_quantita[$key]
                        $valore_da_inserire = CAST_TO_FLOAT($box_quantita[$key],0);
                        if($valore_da_inserire>0){
                                            
                            $log .= "INSERISCO : NRIGA =$n_riga AMICO=$id_amico,  $box_quantita[$key] $id_articolo<br />\n";
                            $msg .= "a ".amici_nome_di_amico($id_amico). ": n. $valore_da_inserire <br>";
                            //SCRIVO ASSEGNAZIONE
                            $query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
                                                         id_riga_dettaglio_ordine,
                                                         id_amico,             
                                                         qta_ord,
                                                         qta_arr,
                                                         data_ins,
                                                         id_articoli,
                                                         id_user,
                                                         id_ordine) 
                                                         VALUES (
                                                                    '$n_riga',
                                                                    '$id_amico',
                                                                    '$valore_da_inserire',
                                                                    '$valore_da_inserire',
                                                                     NOW(),
                                                                    '$id_articolo',
                                                                    '"._USER_ID."',
                                                                    '$id_ordine'
                                                                    );";
                            $res_2 = $db->sql_query($query_distribuzione_spesa);
                        }
                    }
                    unset($id_amico);
                
                    // NON AGGIORNO LA CASSA
                }
    //TOTALE NON UGUALE A QUELLO VECCHIO, ORDINE CHIUSO
    else{
        $log .= "USCITO : Q nuova <> da vecchia <br />\n";
        $msg .= "La quantità totale deve essere uguale a quella precedente.<br>";
        $dove_vai = "ordini_mod_ass_new";            
    }

    
    }
    
    //Se devo ritornare nella pagina dell'ordine
    log_me($id_ordine,_USER_ID,"ORD","ASS","Modifica assegnazione",0,$log);    
    go($dove_vai,_USER_ID,$msg,"?id_ordine=$id_ordine&id_articolo=$id_articolo_next");

}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Modifica assegnazione";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);

    $r->menu_orizzontale = ordini_menu_all($id_ordine);

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else{
    $r->messaggio=$msg;
}
//Creo la pagina dell'aggiunta


          
//TABELLA ATTUALI ASSEGNATARI

    $f = new rg_form();

    $f->form_name="assign_articolo";


    $sql = "SELECT * FROM retegas_amici WHERE is_visible=1 AND id_referente="._USER_ID.";";
    $res = $db->sql_query($sql);

    $a ++;
    $t = new rg_form_text();
    $t->number=$a;
    $t->name= "box_quantita[]";
    $t->label="Me stesso";
    
    $t->value= n_articoli_ordinati_da_amico($id_ordine,_USER_ID,0,$id_articolo);

    $t->autocomplete="off";
    $f->item[] = $t->create_form_text_item();
    unset($t);

    $t = new rg_form_hidden();
    $t->name= "box_id_amico[]";
    $t->value= "0";
    $f->item[] = $t->create_form_hidden_item();
    unset($t); 

    while ($row = $db->sql_fetchrow($res)){

        $quantita =   n_articoli_ordinati_da_amico($id_ordine,_USER_ID,$row["id_amici"],$id_articolo);
        
        if($quantita>0 OR $row["status"]==1){
        
            $a ++;
            $t = new rg_form_text();
            $t->number=$a;
            $t->name= "box_quantita[]";
            $t->label=$row["nome"];
            $t->autocomplete="off";
            if(is_empty($n_riga)){
                $t->value= $quantita;
            }else{
                $t->value= n_articoli_ord_dettaglio_distribuzione_n_riga($row["id_amici"],$n_riga);
                } 
            $f->item[] = $t->create_form_text_item();
            unset($t);
            
            $t = new rg_form_hidden();
            $t->name= "box_id_amico[]";
            $t->value= $row["id_amici"];
            $f->item[] = $t->create_form_hidden_item();
            unset($t);          
        
        }
              
    }

    if(stato_from_id_ord($id_ordine)==2){
        $t = new rg_form_submit();
        $t->number=$a+1;
        $t->name= "submit_form";
        $t->label="...e infine";
        $t->value= "Salva e torna all'ordine";
         $f->item[] = $t->create_form_submit_item();
        unset($t);
            if(!articolo_univoco($id_articolo)){
            $t = new rg_form_submit();
            $t->number=$a+2;
            $t->name= "submit_form";
            $t->label="...oppure";
            $t->value= "Salva e vai all'articolo successivo";
            $f->item[] = $t->create_form_submit_item();
            unset($t);
        }
    }
    
    $t = new rg_form_submit();
    $t->number=$a+3;
    $t->name= "submit_form";
    $t->label="...oppure";
    $t->value= "Salva e torna alla home";
    $f->item[] = $t->create_form_submit_item();
    unset($t);
    
    $t = new rg_form_submit();
    $t->number=$a+4;
    $t->name= "submit_form";
    $t->label="...oppure";
    $t->value= "Salva e vai al dettaglio articoli";
    $f->item[] = $t->create_form_submit_item();
    unset($t);

    $t = new rg_form_hidden();
    $t->name= "id_ordine";
    $t->value= $id_ordine;
    $f->item[] = $t->create_form_hidden_item();
    unset($t);

    $t = new rg_form_hidden();
    $t->name= "do";
    $t->value= "save_mods";
    $f->item[] = $t->create_form_hidden_item();
    unset($t);

    $t = new rg_form_hidden();
    $t->name= "id_articolo";
    $t->value= $id_articolo;
    $f->item[] = $t->create_form_hidden_item();
    unset($t);

    if(isset($n_riga)){ 
        $t = new rg_form_hidden();
        $t->name= "n_riga";
        $t->value= $n_riga;
        $f->item[] = $t->create_form_hidden_item();
        unset($t);
    }
    
    $h = $f->create_form();



$totale_articolo_attuale = round(n_articoli_arrivati_da_user($id_ordine,$id_articolo,_USER_ID),4);

$r->contenuto =     schedina_ordine($id_ordine).
                    schedina_articolo($id_articolo)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>Assegnazione attuale, per un totale di $totale_articolo_attuale articoli.</h3>"
                    .$h
                    ."</div>";
echo $r->create_retegas();

//Distruggo l'oggetto r    
unset($r)   
?>