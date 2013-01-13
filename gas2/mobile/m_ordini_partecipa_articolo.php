<?php   $_FUNCTION_LOADER=array("mobile",
                                "ordini",
                                "ordini_valori",
                                "gas",
                                "listini",
                                "ditte",
                                "tipologie");

include_once ("../rend.php");
include_once ("../jqm.class.php");


//Controllo su login
if(!_USER_LOGGED_IN){   
    go("sommario_mobile");       
}

if(!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
    go("sommario_mobile");              
}

if(!isset($id_ordine)){
    go("sommario_mobile"); 
}

if(!isset($id_articolo)){
    go("sommario_mobile"); 
}

(int)$id_ordine;
(int)$id_articolo;

if($do=="do_del_all_art"){
    if(stato_from_id_ord($id_ordine)==2){
        do_delete_all_articolo_specfico($id_articolo,$id_ordine,_USER_ID);
        
        //AGGIORNO LA CASSA 
        if(read_option_prenotazione_ordine($id_ordine,_USER_ID)<>"SI"){
                $log .="PRENOTAZIONE ? NO, eseguo update cassa<br>";
                cassa_update_ordine_utente($id_ordine,_USER_ID);
        }else{
                $log .="PRENOTAZIONE ? SI, salto update cassa<br>";
        }
        
        
        $msg = "Articolo tolto dall'ordine.";
        
    }else{
        $msg = "Questo ordine non è (più) aperto";
    }
}

if($do=="add_articolo"){
    
    
//INSERIRE IL CONTROLLO PER LA CASSA    
  
$qta_richiesta = CAST_TO_FLOAT($qta_richiesta,0,1000);
    if($qta_richiesta>0){
        if(is_multiplo(db_val_q("id_articoli",$id_articolo,"qta_minima","retegas_articoli"),$qta_richiesta)){
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
                                                        '$qta_richiesta',
                                                        '0',
                                                        '$id_ordine',
                                                        '$qta_richiesta'
                                                        );";

            $querona .= "INSERIMENTO ARTICOLO n. ".$id_articolo ."-->". $query_inserimento_articolo." <-- ";                                      
            $result = $db->sql_query($query_inserimento_articolo);
            $mail_necessaria = "SI";
        
            // scopro qual'è l'ultimo ID inserito (RIGA Dettaglio_ordine)

                    $res = mysql_query("SELECT LAST_INSERT_ID();");
                    $row = mysql_fetch_array($res);
                    $last_id=$row[0];

                    // aggiungo un record in dettaglio_spesa con l'articolo caricato in utente id_user

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
                                                        '$last_id',
                                                        0,
                                                        '$qta_richiesta',
                                                        '$qta_richiesta',
                                                         NOW(),
                                                        '$id_articolo',
                                                        '"._USER_ID."',
                                                        '$id_ordine'
                                                        );";

                    $querona .= "DISTRIBUZIONE ARTICOLO n. ".$id_articolo ."-->". $query_distribuzione_spesa." <-- ";                                     
                    $result_dettaglio_spesa = $db->sql_query($query_distribuzione_spesa);

                    //$output_html .= "INSERITO- - - - - - - - - - - - - -  - -- - <br>";

                    $msg ="Ordinati $qta_richiesta x ".db_val_q("id_articoli",$id_articolo,"descrizione_articoli","retegas_articoli").", <br>";
                    $vo = valore_totale_mio_ordine($id_ordine,_USER_ID);
                    log_me($id_ordine,_USER_ID,"ORD","ART","MOBILE Aggiunta di articoli all'ordine $id_ordine , adesso vale $vo",$vo,$querona);

                   if($mail_necessaria=="SI"){
                      //  rompi_le_balle($id_ordine,_USER_ID);
                   }
        
        
            
        }else{ 
            $msg = "La quantità richiesta non è un multiplo di quella minima."; 
        }
    }else{                                
      $msg = "Quantità non riconosciuta";  
    }
}






$h .= "<h3>$msg</h3>";
$h .= "<ul data-inset=\"true\" data-role=\"listview\">";
$h .= "<li data-role=\"list-divider\">Anagrafica</li>";
$h .= "<li>n. $id_ordine, ".descrizione_ordine_from_id_ordine($id_ordine)."</li>";
$cart = db_val_q("id_articoli",$id_articolo,"codice","retegas_articoli");
$descart = db_val_q("id_articoli",$id_articolo,"descrizione_articoli","retegas_articoli");
//$h .= "<li data-role=\"list-divider\">Articolo</li>";
$h .= "<li><h3>$cart, $descart</h3></li>";

//$h .= "<li data-role=\"list-divider\">Unità di vendita:</li>";
$prezzo = db_val_q("id_articoli",$id_articolo,"prezzo","retegas_articoli");
$um = db_val_q("id_articoli",$id_articolo,"u_misura","retegas_articoli");
$mis = db_val_q("id_articoli",$id_articolo,"misura","retegas_articoli");
$h .= "<li>"._nf($prezzo)." Euro per $um $mis </li>";

//$h .= "<li data-role=\"list-divider\">Confezione e minimo</li>";
$scat = _nf(db_val_q("id_articoli",$id_articolo,"qta_scatola","retegas_articoli"));
$mina = _nf(db_val_q("id_articoli",$id_articolo,"qta_minima","retegas_articoli"));
$h .= "<li>Scatola da $scat, minimo acquistabile : <strong>$mina</strong></li>";

//$h .= "<li data-role=\"list-divider\">Note</li>";
$note = db_val_q("id_articoli",$id_articolo,"articoli_note","retegas_articoli");
$h .= "<li><h4>Note:</h4><p>$note</p></li>";


$val_mio = valore_netto_arr_articolo_ordine_user($id_articolo,$id_ordine,_USER_ID);
    if($val_mio>0){
        
        $val_art = _nf(n_articoli_arrivati_da_user($id_ordine,$id_articolo,_USER_ID));
        
        $in_ordine = "<li data-theme=\"e\"><h5>In Ordine $val_art x $mis $um a $prezzo Cad.</h5>
                      <h3>Tot. $val_mio Eu.</h3></li>";
        $in_ordine .= "<li><p><a data-role=\"button\" data-theme=\"b\" data-inline=\"true\">Modifica</a>";
        $in_ordine .= "<a   href=\"".$RG_addr["m_ordini_partecipa_art"]."?id_articolo=$id_articolo&id_ordine=$id_ordine&do=do_del_all_art\"
                            data-role=\"button\" 
                            data-theme=\"a\" 
                            data-inline=\"true\"
                            data-ajax=\"false\">
                            Elimina</a></p></li>";
        $split="<a></a>";
    }else{
        $h .= "<li data-role=\"list-divider\">Acquista</li>";
        $h .= "<li>";
        $h .= "<form action=\"\" method=\"post\" data-ajax=\"false\">";
        $h .= "<div data-role=\"fieldcontain\">";
        $h .= "<label for=\"qta_richiesta\">Quantità richiesta</label>";
        $h .= "<input type=\"number\" id=\"qta_richiesta\" name=\"qta_richiesta\" min=\"0\" value=\"\" step=\"".db_val_q("id_articoli",$id_articolo,"qta_minima","retegas_articoli")."\">";
        $h .= "</div>";
        
        $h .= "<div data-role=\"fieldcontain\">";
        $h .= "<input type=\"hidden\" name=\"do\" value=\"add_articolo\">";
        $h .= "<input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">";
        $h .= "<input type=\"hidden\" name=\"id_articolo\" value=\"$id_articolo\">";
        $h .= "<label for=\"save\"></label>";        
        $h .= "<input data-theme=\"e\" type=\"submit\"  data-icon=\"check\"  name=\"salva_qta\" value=\"Salva l'acquisto\">";
        $h .= "</div>";
        
        $h .= "</form>";
        $h .= "</li>";
        
        $in_ordine = "";
        $split="";
    }

       
$h .=$in_ordine;
                
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());

//-------------------------------------------------------PAG 1                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param());
//Negli attributi assegno un ID
$p->jqm_page_attrib="id=\"scheda_articolo\"";
$p->jqm_footer_hide= true;
//Assegno la navbar

$n = new jqm_navbar(load_scheda_navbar(null,$id_ordine));
$n->jqm_navbar_set_item_attrib(1,"class=\"ui-btn-active ui-state-persist\"");
$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno i contenuti
$p->jqm_page_content =$h;
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 1



//La visualizzo
echo $j->jqm_render();
unset($j);