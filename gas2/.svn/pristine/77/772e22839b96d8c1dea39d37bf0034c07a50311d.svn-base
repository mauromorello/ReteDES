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

if (ordine_io_cosa_sono($id_ordine,_USER_ID)<2){
    go("sommario",_USER_ID,"Questo ordine non mi compete");
}

$id_articolo_next = $id_articolo;

switch ($submit_form){
    case "Salva e torna all'ordine":
         $dove_vai = "ordine_partecipa";
    break;
    case "Salva e torna alla scheda dell'articolo univoco":
         
         $dove_vai = "ordini_mod_uni_new";
         
    break;
    case "Salva e torna alla home":
         $dove_vai = "sommario";
    break;     
}


if($do=="save_mods"){
    
    //Se l'ordine è aperto
    $stato = stato_from_id_ord($id_ordine);
    $log .="Trovato ordine $id_ordine in stato $stato<br>";
        
        
        
        //Passo la lista e faccio il totale
        $nuova_quantita_articolo = round(array_sum($box_quantita),4);
        $vecchia_quantita_articolo = round(n_articoli_per_riga($n_riga),4);

        $log .=  "NUOVA ".$nuova_quantita_articolo."; VECCHIA ".$vecchia_quantita_articolo."<br>";
        $msg .= "Articolo $id_articolo (".articolo_sua_descrizione($id_articolo).") :<br>";
        
        
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
                 $dove_vai = "ordini_mod_uni_new";
                 go($dove_vai,_USER_ID,$msg,"?id_ordine=$id_ordine&id_articolo=$id_articolo");
             }
             
         
         
         }
        
        
        
        
        
        
        
        
        
      
        $log .= "NUOVA Q > 0 <br>";
            //se il totale è multiplo corretto di articolo
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
                
                
                }else{
                   $msg .= "La nuova quantità non corrisponde a quella vecchia.<br>";
                   $log .= "Nuova Q <> Vecchia q<br>"; 
                }
                    
               
            }
            else{
                $msg .= "Il nuovo totale non è un multiplo di quantità minima<br>";
                $log .= "MULTIPLO : Not OK <br>";
            }
    
    
    
    //Se devo ritornare nella pagina dell'ordine
    log_me($id_ordine,_USER_ID,"ORD","ASS","Modifica assegnazione",0,$log);    
    
    if(_USER_USA_CASSA){           
           cassa_update_ordine_utente($id_ordine,_USER_ID); 
    }
    go($dove_vai,_USER_ID,$msg,"?id_ordine=$id_ordine&id_articolo=$id_articolo_next&n_riga=$n_riga");


    
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Modifica assegnazione";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);

$r->menu_orizzontale = ordini_menu_all($id_ordine);

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta


          
//TABELLA ATTUALI ASSEGNATARI

    $f = new rg_form();

    $f->form_name="assign_univoco";


    $sql = "SELECT * FROM retegas_amici WHERE is_visible=1 AND id_referente="._USER_ID.";";
    $res = $db->sql_query($sql);

    $a ++;
    $t = new rg_form_text();
    $t->number=$a;
    $t->name= "box_quantita[]";
    $t->label="Me stesso";
    $t->value= n_articoli_ord_dettaglio_distribuzione_n_riga(0,$n_riga);
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



    $t = new rg_form_submit();
    $t->number=$a+1;
    $t->name= "submit_form";
    $t->label="...e infine";
    $t->value= "Salva e torna all'ordine";
     $f->item[] = $t->create_form_submit_item();
    unset($t);
    
    $t = new rg_form_submit();
    $t->number=$a+2;
    $t->name= "submit_form";
    $t->label="...oppure";
    $t->value= "Salva e torna alla scheda dell'articolo univoco";
    $f->item[] = $t->create_form_submit_item();
    unset($t);
    
    $t = new rg_form_submit();
    $t->number=$a+3;
    $t->name= "submit_form";
    $t->label="...oppure ancora";
    $t->value= "Salva e torna alla home";
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




$r->contenuto =     schedina_ordine($id_ordine).
                    schedina_articolo($id_articolo)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>Assegnazione articolo univoco <strong>$n_riga</strong></h3>
                    <p>Assicurarsi che il totale assegnato agli amici corrisponda all'attuale ordinato.</p>"
                    .$h
                    ."</div>";
echo $r->create_retegas();

//Distruggo l'oggetto r    
unset($r)   
?>