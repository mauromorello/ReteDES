<?php
function schedona_ordine_mobile($id_ordine,$id_user=null){
    global $db;
    global $RG_addr;
    
    $euro = "&#8364";  
    
    $id_gas= id_gas_user($id_user);
    $io_chi_sono = fullname_from_id($id_user);
    $gas_name = gas_nome($id_gas);
    $gas_ordine_id = id_gas_user(id_referente_ordine_globale($id_ordine));
    $gas_name_generale = gas_nome($gas_ordine_id);
    
    //ANAGRAFICHE
    $ordine_nome    =   descrizione_ordine_from_id_ordine($id_ordine);
    $id_listino     =   listino_ordine_from_id_ordine($id_ordine);
    $listino        =   listino_nome($id_listino);
    $id_ditta       =   ditta_id_from_listino($id_listino);
    $ditta          =   ditta_nome_from_listino($id_listino);
    $mail_ditta     =   ditta_mail_from_listino($id_listino);
    $tipologia      =   tipologia_nome_from_listino($id_listino);
    $data_apertura  =   conv_datetime_from_db(ordini_field_value($id_ordine,"data_apertura"));
    $data_chiusura  =   conv_datetime_from_db(ordini_field_value($id_ordine,"data_chiusura"));
    $note_ordine    =   ordini_field_value($id_ordine,"note_ordini");
    
    
    
    //ARTICOLI
    $articoli_ord           =   n_articoli_ordinati_da_id_ordine($id_ordine);
    $articoli_arr           =   n_articoli_arrivati_da_id_ordine($id_ordine);
    $scatole_intere_arr     =   q_scatole_intere_ordine_arr($id_ordine);
    $scatole_intere_ord     =   q_scatole_intere_ordine($id_ordine);
    $avanzo_articoli_ord    =   q_articoli_avanzo_ordine($id_ordine);    
    $avanzo_articoli_arr    =   q_articoli_avanzo_ordine_arr($id_ordine);
    
    // RUOLO     
    $user_level = "Utente Semplice;<br> "; 
        if (id_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user))==$id_user){
                $user_level .= "Referente Proprio GAS;<br> "; 
        }
        if (id_referente_ordine_globale($id_ordine)==$id_user){  
                $user_level .= "Referente ORDINE; "; 
        }
    $id_referente_ordine = id_referente_ordine_globale($id_ordine);
    $id_referente_proprio_gas = id_referente_ordine_proprio_gas($id_ordine,$id_gas); 
    $referente_generale = fullname_from_id($id_referente_ordine)." (".telefono_from_id($id_referente_ordine).")";
    $referente_gas = fullname_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user))." (".tel_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user)).")";
              
    //STATO
    $stato_attuale = stato_from_id_ord($id_ordine);
    if($stato_attuale==1){
        $stato = "Programmato";
    }
    if($stato_attuale==2){
        $stato = "Aperto";
    }
    if($stato_attuale==3){
        $stato = "Chiuso - ";  
        if(is_printable_from_id_ord($id_ordine)){
            $stato .="<b>STAMPABILE</b>";    
        }else{
            $stato .="<b>DA CONFERMARE</b>";
        }
    }
   
    //BACINO UTENZE 
    $bacino_tot = ordine_bacino_utenti($id_ordine);
    $bacino_part = ordine_bacino_utenti_part($id_ordine);
    $bacino_non_part = $bacino_tot-$bacino_part;
    $bacino_percentuale = number_format((($bacino_part/$bacino_tot)*100),1,",","")."%";
  
    $bacino_tot_mio_gas = gas_n_user($id_gas);
    $bacino_part_mio_gas = ordine_bacino_utenti_part_gas($id_ordine,$id_gas);
    $bacino_non_part_mio_gas = $bacino_tot_mio_gas-$bacino_part_mio_gas;
    $bacino_percentuale_mio_gas = number_format((($bacino_part_mio_gas/$bacino_tot_mio_gas)*100),1,",","")."%";
  
    $gas_coinvolti=ordine_gas_coinvolti($id_ordine);
    
    //SPESA ATTUALE
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine); 
    $costo_globale_trasporto = valore_trasporto($id_ordine,100);
    $costo_globale_gestione = valore_gestione($id_ordine,100);
    $maggiorazione_percentuale_mio_gas = valore_percentuale_maggiorazione_mio_gas($id_ordine,$id_gas);
    $costo_globale_mio_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
    if($maggiorazione_percentuale_mio_gas>0){
        $motivazione_maggiorazione = "(".testo_maggiorazione_mio_gas($id_ordine,$id_gas).")";
    }      
    
    if($valore_globale_attuale_netto_qarr>0){
        $valore_personale_attuale_netto_qarr = valore_totale_mio_ordine($id_ordine,$id_user);
        $valore_gas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas);
        if($valore_gas_attuale_netto_qarr>0){
            $percentuale_mio_ordine_gas = ($valore_personale_attuale_netto_qarr / $valore_gas_attuale_netto_qarr) *100;    
        }else{
            $percentuale_mio_ordine_gas = 0;
        }
        $percentuale_mio_ordine = ($valore_personale_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
        $costo_trasporto =  ($costo_globale_trasporto / 100) * $percentuale_mio_ordine;
        $costo_gestione =  ($costo_globale_gestione / 100) * $percentuale_mio_ordine;
        $costo_personale_mio_gas = ($costo_globale_mio_gas /100)*$percentuale_mio_ordine_gas;
        $valore_maggiorazione_mio_gas = ($valore_personale_attuale_netto_qarr / 100) * $maggiorazione_percentuale_mio_gas;
        

        
        
        $totale_ordine =  $valore_personale_attuale_netto_qarr +
                          $costo_trasporto +
                          $costo_gestione +
                          $costo_personale_mio_gas +
                          $valore_maggiorazione_mio_gas ;
                          
                           
        
        
    }else{
        $valore_personale_attuale_netto_qarr = 0;
        $costo_trasporto=   0;
        $costo_gestione=    0;
        $costo_personale_mio_gas =0;
        $valore_maggiorazione_mio_gas =0;
    }
        
        $totale_ordine =    $valore_personale_attuale_netto_qarr +
                            $costo_trasporto +
                            $costo_gestione +
                            $costo_personale_mio_gas +
                            $valore_maggiorazione_mio_gas ;
    
       //FORMATTAZIONE
       //$valore_personale_attuale_netto_qarr = number_format((float)round($valore_personale_attuale_netto_qarr,2),2,",","");
       //$costo_trasporto = number_format((float)round($costo_trasporto,2),2,",","");
       //$costo_gestione  = number_format((float)round($costo_gestione,2),2,",","");
       //$costo_personale_mio_gas = number_format((float)round($costo_personale_mio_gas,2),2,",","");
       //$valore_maggiorazione_mio_gas = (float)round($valore_maggiorazione_mio_gas,2);           
       //$totale_ordine = ((float)round($totale_ordine,2));
       ///$maggiorazione_percentuale_mio_gas = number_format((float)round($maggiorazione_percentuale_mio_gas,2),2,",","");
       //$costo_globale_mio_gas = number_format((float)round($costo_globale_mio_gas,2),2,",","");
       //$costo_globale_trasporto = number_format((float)round($costo_globale_trasporto,2),2,",","");
       //$costo_globale_gestione = number_format((float)round($costo_globale_gestione,2),2,",","");
       //$articoli_ord = (float)round($articoli_ord,2);
       //$articoli_arr = (float)round($articoli_arr,2);
      
       
       //GEOCODING
       
       //Ditta
       $ditta_gc_lat = db_val_q("id_ditte",$id_ditta,"ditte_gc_lat","retegas_ditte");
       $ditta_gc_lng = db_val_q("id_ditte",$id_ditta,"ditte_gc_lng","retegas_ditte");
       //echo "$ditta_gc_lat , $ditta_gc_lng<br>";
       if($ditta_gc_lat==0){
           $gc .= "Indirizzo ditta non valido<br>";
           $e_gc++;
       }
       
       //Mio Gas
       $gas_gc_lat = db_val_q("id_gas",$id_gas,"gas_gc_lat","retegas_gas");
       $gas_gc_lng = db_val_q("id_gas",$id_gas,"gas_gc_lng","retegas_gas");
       //echo "$gas_gc_lat , $gas_gc_lng<br>";
       if($gas_gc_lat==0){
           $gc .= "Indirizzo mio gas non valido<br>";
           $e_gc++;
       }
       
       //Gas ordinante
       if($gas_ordine_id<>$id_gas){
           $gas_ord_gc_lat = db_val_q("id_gas",$gas_ordine_id,"gas_gc_lat","retegas_gas");
           $gas_ord_gc_lng = db_val_q("id_gas",$gas_ordine_id,"gas_gc_lng","retegas_gas");
           //echo "$gas_gc_lat , $gas_gc_lng<br>";
           if($gas_ord_gc_lat==0){
               $gc .= "Indirizzo gas ordinante non valido<br>";
               $e_gc++;
           }
       }
       
       //user
       $user_gc_lat = db_val_q("userid",_USER_ID,"user_gc_lat","maaking_users");
       $user_gc_lng = db_val_q("userid",_USER_ID,"user_gc_lng","maaking_users");
       if($user_gc_lat==0){
           $gc .= "Indirizzo Utente non valido<br>";
           $e_gc++;
       }
       
       if($e_gc==0){
             //CHIAMA GOOGLE MAPS E SI FA PASSARE LA DISTANZA.
                $a = $ditta_gc_lat.",".$ditta_gc_lng;
                $b = $gas_gc_lat.",".$gas_gc_lng;
                $myurl = "http://".MAPS_HOST."/maps?q=from+{$a}+to+{$b}&output=kml";
                $f = fopen ($myurl, "r");
                $str = stream_get_contents($f);
                preg_match("/Distance: ([0-9,.-]+)/", $str, $distance);
            //Tolgo il separatore delle migliaia perchè rompe le palle
            $dist_ditta_gas =str_replace(",","",$distance[1]); 
            $dist_ditta_gas = round(floatval($dist_ditta_gas),2);
            
            $dist_gas_user = round(getDistanceBetweenPointsNew($user_gc_lat, $user_gc_lng, $gas_gc_lat, $gas_gc_lng),2);
            if($gas_ord_gc_lat>0){
                $dist_gas_ord =  round(getDistanceBetweenPointsNew($gas_gc_lat, $gas_gc_lng, $gas_ord_gc_lat, $gas_ord_gc_lng),2);
                $dist_gas_ord = $dist_gas_ord." Km + ";
            }
            $dist_tot = round($dist_ditta_gas + $dist_gas_user + $dist_gas_ord,2);
       
       
               
               
       
       
       
       
            $gc = $dist_ditta_gas." Km + ". $dist_gas_ord.$dist_gas_user." Km = <strong>".$dist_tot."</strong> Km Tot."; 
       
       
       
       
       
       
       }
       
       
       
       // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
                
     $h_table .=  "
     <h3>Ord. $id_ordine ($ordine_nome)</h3>
     <div data-role=\"collapsible-set\">
     <div data-role=\"collapsible\" data-collapsed=\"true\">
     <h3>Anagrafica</h3>
        <ul data-role=\"listview\" data-inset=\"true\">
             <li data-role=\"list-divider\" >Ordine $id_ordine ($ordine_nome)</li>
             <li><p>Fornitore: <a href=\"".$RG_addr["m_ditta_scheda"]."?id_ditta=$id_ditta\" data-role=\"button\">$ditta</a></p></li>
             <li><p>Listino: <a href=\"".$RG_addr["listini_scheda"]."?id_listino=$id_listino\" data-role=\"button\">$listino</a></p></li>
             <li>Merce trattata: $tipologia</li>
             <li>Stato: $stato</li>
             <li>Data apertura: $data_apertura</li>
             <li>Data Chiusura: $data_chiusura</li>
             <li>Km merce: $gc</li>
             <li>".strip_tags($note_ordine)."</li>
        </ul>
     </div>
     
     <div data-role=\"collapsible\" data-collapsed=\"true\">
        <h3>Situazione</h3>
        <ul data-role=\"listview\" data-inset=\"true\">
             <li data-role=\"list-divider\">Situazione</li>
             <li>Articoli ordinati: $articoli_ord</li>
             <li>Articoli Arrivati: $articoli_arr</li>
             <li><strong>Scatole ordinate: </strong>$scatole_intere_ord</li>
             <li><strong>Scatole arrivate: </strong>$scatole_intere_arr</li>
             <li><strong>Avanzo (ordinato): </strong>$avanzo_articoli_ord</li>
             <li><strong>Avanzo (arrivato): </strong>$avanzo_articoli_arr</li>
             <li><strong>Gas coinvolti: </strong>$gas_coinvolti ($bacino_tot Utenti)</li>                                    
             <li>Partecipanti mio Gas : $bacino_part_mio_gas</li>                                         
             <li>Partecipanti in tutti i GAS: $bacino_part</li>
             <li>Mio ruolo: $user_level</li>
             <li><p>Referente generale ($gas_name_generale): <a href=\"".$RG_addr["m_user_scheda"]."?id_utente=".mimmo_encode($id_referente_ordine)."\" data-role=\"button\">$referente_generale</a></p></li>
             <li><p>Referente del tuo gas ($gas_name): <a href=\"".$RG_addr["m_user_scheda"]."?id_utente=".mimmo_encode($id_referente_proprio_gas)."\" data-role=\"button\">$referente_gas</a></p></li>
         
        </ul>
     </div>
     <div data-role=\"collapsible\" data-collapsed=\"true\">
        <h3>Riepilogo mia spesa</h3>
         <ul data-role=\"listview\" data-inset=\"true\">
             <li data-role=\"list-divider\">Mia spesa</li>    
             <li>
                 <div class=\"ui-grid-a\">
                    <div class=\"ui-block-a\">Netto attuale:</div>
                    <div class=\"ui-block-b\">Eu. "._nf($valore_personale_attuale_netto_qarr)."</div>
                 </div>
             </li>
             <li>
                 <div class=\"ui-grid-a\">
                    <div class=\"ui-block-a\">Trasporto ($costo_globale_trasporto $euro):</div>
                    <div class=\"ui-block-b\">Eu. "._nf($costo_trasporto)."</div>
                 </div>
             </li>
             <li>
                 <div class=\"ui-grid-a\">
                    <div class=\"ui-block-a\">Gestione ($costo_globale_gestione $euro):</div>
                    <div class=\"ui-block-b\">Eu. "._nf($costo_gestione)."</div>
                 </div>
             </li>
             <li>
                 <div class=\"ui-grid-a\">
                    <div class=\"ui-block-a\">Costo GAS ($costo_globale_mio_gas $euro):</div>
                    <div class=\"ui-block-b\">Eu. "._nf($costo_personale_mio_gas)."</div>
                 </div>
             </li>
             <li>
                 <div class=\"ui-grid-a\">
                    <div class=\"ui-block-a\">Maggiorazione ".$maggiorazione_percentuale_mio_gas."% : <p>".$motivazione_maggiorazione."</p></div>
                    <div class=\"ui-block-b\">Eu. "._nf($valore_maggiorazione_mio_gas)."</div>
                 </div>
             </li>
             <li data-theme=\"e\">
                 <div class=\"ui-grid-a\">
                    <div class=\"ui-block-a\"><h3>TOTALE</h3></div>
                    <div class=\"ui-block-b\"><h3>Eu. "._nf($totale_ordine)."</h3></div>
                 </div>
             </li>
           
         </ul>
     </div>    
     </div>";
                    
            

      // END TABELLA ----------------------------------------------------------------------------
     
    
    
     return $h_table;
    
    
        
    } 
function schedona_ditta_mobile($id_ditta,$id_user=null){
    global $db;
    global $RG_addr;

    $nome_ditta = ditta_nome($id_ditta);
    $indirizzo_ditta = db_val_q("id_ditte",$id_ditta,"indirizzo","retegas_ditte");
    $telefono =    db_val_q("id_ditte",$id_ditta,"telefono","retegas_ditte");
    $mail_ditte =    db_val_q("id_ditte",$id_ditta,"mail_ditte","retegas_ditte");   
    
    $proponente =   fullname_from_id(db_val_q("id_ditte",$id_ditta,"id_proponente","retegas_ditte")); 
    $tag_associati =  db_val_q("id_ditte",$id_ditta,"tag_ditte","retegas_ditte"); 
    $listini_attivi = listini_ditte($id_ditta);
    
    
       // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
                
     $h_table .=  "
     <ul data-role=\"listview\" data-inset=\"true\">
         <li data-role=\"list-divider\">Anagrafica</li>
         <li>Nome $nome_ditta</li>
         <li>Indirizzo: $indirizzo_ditta</li>
         <li><a href=\"tel:$telefono\">$telefono</a></li>
         <li><a href=\"mailto:$mail_ditte\">$mail_ditte</a></li>
         <li>Sito:</li>
         <li data-role=\"list-divider\">Altro</li>
         
         <li>Proponente: $proponente</li>
         <li><h3>Tag associati:</h3> <p>$tag_associati</p></li>
         <li data-role=\"list-divider\">Attività ditta</li>
         <li>Listini attivi: $listini_attivi</li>
         

 </ul>";
                    
            

      // END TABELLA ----------------------------------------------------------------------------
     
    
    
     return $h_table;
    
    
        
    }    
    
//FUNCTION PER MOBILE
function load_jqm_param(){
    $p = array(
    "jqm_jqm_css"       =>  "http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.css",
    "jqm_jquery_url"    =>  "http://code.jquery.com/jquery-1.7.1.min.js",
    "jqm_jqm_url"       =>  "http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.js",
    "jqm_app_title"     =>  _SITE_NAME."(M)");
    return $p;
}
function load_page_param($title=_SITE_NAME,$id=null){
global $RG_addr;



$p =  array("jqm_page_attrib"=>"id=\"page_$id\"",
                "jqm_header_title"=>"$title",
                "jqm_header_attrib"=>"data-position=\"fixed\" id=\"head_$id\" data-position=\"inline\"",
                "jqm_header_icon_left"=>"<a href=\"#\" data-icon=\"back\" data-rel=\"back\" data-iconpos=\"notext\"></a>",
                
                "jqm_footer_title"=>"$title",
                "jqm_footer_attrib"=>"data-position=\"fixed\" id=\"foot_$id\"");    

if(_USER_LOGGED_IN){
    $p["jqm_header_icon_right"] = "<a href=\"".$RG_addr["sommario_mobile"]."\" data-icon=\"home\" data-iconpos=\"notext\" class=\"ui-btn-right\">Home</a>";
}else{
    $p["jqm_header_icon_right"] = "<a href=\"".$RG_addr["sommario_mobile"]."#page_login_form\" data-icon=\"star\" class=\"ui-btn-right\">Login</a>";
}

return $p;

}
function load_page_navbar($items_to_push=null){
global $RG_addr;



$n =  array("<li><a href=\"".$RG_addr["m_ordini"]."\" rel=\"external\" data-prefetch>Ordini</a></li>",
            "<li><a href=\"".$RG_addr["m_cassa_panel"]."\" data-icon=\"grid\" data-ajax=\"false\">Cassa</a></li>",
            "<li><a href=\"#\" data-icon=\"gear\">Altro</a></li>",
            
            "<li><a href=\"".$RG_addr["sommario_mobile"]."?do=logout\" data-icon=\"delete\" rel=\"external\" >Log Out</a></li>");    

if(is_array($items_to_push)){
    $n = array_push($n,$items_to_push);
}

return $n;

}
function load_ordini_navbar($items_to_push=null){
global $RG_addr;



$n =  array(
            "<li><a href=\"".$RG_addr["m_ordini"]."#aperti\">Aperti</a></li>",
            "<li><a href=\"".$RG_addr["m_ordini"]."#chiusi\">Chiusi</a></li>",
            "<li><a href=\"".$RG_addr["m_ordini"]."#futuri\">Futuri</a></li>",
            "<li><a href=\"".$RG_addr["m_ordini"]."#miei\"  >Miei</a></li>");    

if(is_array($items_to_push)){
    $n = array_push($n,$items_to_push);
}

return $n;

}
function load_scheda_navbar($items_to_push=null,$id_ordine=null){
global $RG_addr;


$n = array( 
            "<li><a href=\"".$RG_addr["m_ordini_scheda"]."?id_ordine=$id_ordine\" data-prefetch>Scheda</a></li>",
            //"<li><a href=\"".$RG_addr["m_ordini_partecipa"]."?id_ordine=$id_ordine\" data-prefetch>Partecipa</a></li>",
            "<li><a href=\"".$RG_addr["m_ordini_mia_spesa"]."?id_ordine=$id_ordine\" data-prefetch>Mia Spesa</a></li>");
            //"<li><a href=\"".$RG_addr["m_ordini"]."?id_ordine=$id_ordine\" data-icon=\"gear\">Gestisci</a></li>");    



if(is_array($items_to_push)){
    $n = array_push($n,$items_to_push);
}

return $n;

}
function load_scheda_ditta_navbar($items_to_push=null,$id_ditta=null){
global $RG_addr;


$n = array( 
            "<li><a href=\"".$RG_addr[""]."?id_ditta=$id_ditta\">Ditta</a></li>"//,
            //"<li><a href=\"".$RG_addr[""]."?id_ditta=$id_ditta\">Listini</a></li>",
            //"<li><a href=\"".$RG_addr[""]."?id_ditta=$id_ditta\">Ordini</a></li>",
            //"<li><a href=\"".$RG_addr[""]."?id_ditta=$id_ditta\" data-icon=\"gear\">Gestisci</a></li>"
            );    



if(is_array($items_to_push)){
    $n = array_push($n,$items_to_push);
}

return $n;

}
function load_scheda_utente_navbar($items_to_push=null,$id_utente=null){
global $RG_addr;


$n = array( 
            "<li><a href=\"".$RG_addr[""]."?id_utente=".mimmo_encode($id_utente)."\">Dati</a></li>"//,
            //"<li><a href=\"".$RG_addr[""]."?id_ditta=$id_ditta\">Listini</a></li>",
            //"<li><a href=\"".$RG_addr[""]."?id_ditta=$id_ditta\">Ordini</a></li>",
            //"<li><a href=\"".$RG_addr[""]."?id_ditta=$id_ditta\" data-icon=\"gear\">Gestisci</a></li>"
            );    



if(is_array($items_to_push)){
    $n = array_push($n,$items_to_push);
}

return $n;

}
function load_back($items_to_push=null){
global $RG_addr;


$n[]=("<li><a data-rel=\"back\" data-icon=\"back\">Indietro</a></li>");    



if(is_array($items_to_push)){
    $n = array_push($n,$items_to_push);
}

return $n;

}     
?>