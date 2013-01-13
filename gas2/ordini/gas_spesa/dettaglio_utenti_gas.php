<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../ordini_renderer.php");
include_once ("../../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//Se non è settato il gas lo imposto come quello dell'utente
if(!isset($id_gas)){$id_gas = _USER_ID_GAS;}

if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     go("sommario",_USER_ID,"Non puoi partecipare agli ordini. Contatta il tuo referente GAS.");
}

if (ordine_inesistente($id_ordine)){
     go("sommario",_USER_ID,"Ordine insesistente");
}

//Se non sono almeno referente GAS allora non posso vedere nulla.
$mio_Stato = ordine_io_cosa_sono($id_ordine,_USER_ID);

//Se posso vedere tutti gli ordini
if(!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){

    if ($mio_Stato<3){
        go("sommario",_USER_ID,"Questo ordine non mi compete");
    }

    //Se sono referente gas controllo di vedere il MIO gas
    if ($mio_Stato==3){
        if($id_gas<>_USER_ID_GAS){
            go("sommario",_USER_ID,"Solo il referente ordine può vedere tutti i gas.");
        }
    }
}

$stato_ordine = stato_from_id_ord($id_ordine);


if($stato_ordine==2){
    $alert = "<div class=\"ui-state-error ui-corner-all padding_6px\">
                <h4>Finchè l'ordine non è confermato, questi dati sono da considerarsi NON ATTENDIBILI<br>
                </h4>
              </div>  ";    
    
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Spesa GAS - Dettaglio utenti";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);
    
    //SE l'ordine è chiuso allora posso stamparlo
    //if(is_printable_from_id_ord($id_ordine)){
    $r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["gas_ordine_dett_users"].'?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["gas_ordine_dett_users"].'?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["gas_ordine_dett_users"].'?id_ordine='.$id_ordine.'&output=word&cod='.rand(0,999999999).'">Word</a></li>
                                    </ul>
                                </li>';
    //}
    $r->menu_orizzontale = array_merge(ordini_menu_all($id_ordine),$r->menu_orizzontale);


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta




$query = "SELECT id_utenti FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' GROUP BY id_utenti;";
$res = $db->sql_query($query);




$h .= $alert;
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">Utente</th>";
    $h .="<th class=\"sinistra\">Articolo</th>";
    $h .="<th class=\"sinistra\">Descrizione</th>";
    $h .="<th class=\"centro\">QO/QA</th>";
    $h .="<th class=\"centro\">SC/AV</th>";
    $h .="<th class=\"destra\">Prezzo</th>";
    $h .="<th class=\"destra\">Tot Riga</th>";
    $h .="<th class=\"destra\">Costi</th>";
    $h .="<th class=\"destra\">Totale</th>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){
    
    $riga++;
    
    if(is_integer($riga / 2)){
        $cl  ="class=\"odd\"";
    }else{
        $cl = "";
    }
    
    
    unset($opz);
    if($stato_ordine==2){
    //    $opz = "<a class=\"awesome option yellow\">M</a>
    //            <a class=\"awesome option red\">C</a>";
    }
    if($stato_ordine==3){
        //$opz = "<a class=\"option blue awesome\" title=\"Assegna\" href=\"../../ordini_chiusi/ordini_chiusi_ass_q.php?id=".$row["id_articoli"]."&id_ordine=$id_ordine&q_min=".db_val_q("id_articoli",$row["id_articoli"],"qta_minima","retegas_articoli")."&id_dett=".$row["id_dettaglio_ordini"]."\">A</a>";
    }
    

    if(id_gas_user($row["id_utenti"])==$id_gas){
    
        //Subtotale
        $h .="<tr class=\"total\">";
        $h .="<th class=\"sinistra column_hide\">$opz</th>";
        $h .="<th class=\"sinistra\">".fullname_from_id($row["id_utenti"])."</th>";
        $h .="<th class=\"centro\"><span class=\"small_link\">Articolo</span></th>";
        $h .="<th class=\"centro\"><span class=\"small_link\">Descrizione</span></th>";
        $h .="<th class=\"centro\"><span class=\"small_link\">QO / QA</span></th>";
        $h .="<th class=\"centro\"><span class=\"small_link\">SC / AV</span></th>";
        $h .="<th class=\"centro\"><span class=\"small_link\">Prezzo</span></th>";
        $h .="<th class=\"centro\"><span class=\"small_link\">Tot Riga</span></th>";
        $h .="<th class=\"centro\"><span class=\"small_link\">Costi</span></th>";
        $h .="<th class=\"centro\"><span class=\"small_link\">Totale</span></th>";
        $h .="</tr>";
        
        //--------------------------------------------LISTA UTENTE
                $query = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' AND id_utenti='".$row["id_utenti"]."';";
                $res_dett = $db->sql_query($query);
                while ($row_d = mysql_fetch_array($res_dett)){
    
                $riga++;
                
                if(is_integer($riga / 2)){
                    $cl  ="class=\"odd\"";
                }else{
                    $cl = "";
                }
                
                
                unset($opz);
                if($stato_ordine==2){
                //    $opz = "<a class=\"awesome option yellow\">M</a>
                //            <a class=\"awesome option red\">C</a>";
                }
                if($stato_ordine==3){
                 //   $opz = "<a class=\"option blue awesome\" title=\"Assegna\" href=\"../../ordini_chiusi/ordini_chiusi_ass_q.php?id=".$row["id_articoli"]."&id_ordine=$id_ordine&q_min=".db_val_q("id_articoli",$row["id_articoli"],"qta_minima","retegas_articoli")."&id_dett=".$row["id_dettaglio_ordini"]."\">A</a>";
                }
                
                $misura = " (".db_val_q("id_articoli",$row_d["id_articoli"],"u_misura","retegas_articoli")." ".db_val_q("id_articoli",$row_d["id_articoli"],"misura","retegas_articoli").")";
                
                unset($alert_qta);
                if($row_d["qta_arr"]==0){
                            $alert_qta = "<div class=\"campo_alert\">ANNULLATA</div>";
                        }else if($row_d["qta_arr"]<>$row_d["qta_ord"]){
                            $alert_qta = "<div class=\"campo_alert\">MODIFICATA</div>";
                        }
                $qta_scatola = db_val_q("id_articoli",$row_d["id_articoli"],"qta_scatola","retegas_articoli");
                
                //echo $qta_scatola."<br>";
                if (round($qta_scatola,2)>0){
                    $scatole = floor($row_d["qta_arr"] / $qta_scatola);
                    //$avanzo = (($row_d["qta_arr"]) % ($qta_scatola));
                }else{
                    $scatole = 0;
                    //$avanzo = ($row_d["qta_arr"]);
                }
                
                $avanzo = calcola_avanzo($row_d["qta_arr"],$qta_scatola);    
                
                
                //UNICO
                if(db_val_q("id_articoli",$row_d["id_articoli"],"articoli_unico","retegas_articoli")==1){
                    $unico = " (".$row_d["id_articoli"].")";
                }
                
                
                $h .="<tr $cl>";
                $h .="<td class=\"sinistra column_hide\">$opz</td>";
                $h .="<td class=\"sinistra\"><span class=\"small_link\">".fullname_from_id($row["id_utenti"])."</span></td>";
                $h .="<td class=\"sinistra\">".db_val_q("id_articoli",$row_d["id_articoli"],"codice","retegas_articoli").$unico."</td>";
                $h .="<td class=\"sinistra\">".db_val_q("id_articoli",$row_d["id_articoli"],"descrizione_articoli","retegas_articoli")."<span class=\"small_link\">".$misura."</span></td>";
                $h .="<td class=\"centro\">".round($row_d["qta_ord"],2)." / ".round($row_d["qta_arr"],2).$alert_qta."</td>";
                $h .="<td class=\"centro\">$scatole / $avanzo</td>";
                $h .="<td class=\"destra\">"._nf(db_val_q("id_articoli",$row_d["id_articoli"],"prezzo","retegas_articoli"))."</td>";
                $h .="<td class=\"destra\">"._nf($row_d["qta_arr"]*db_val_q("id_articoli",$row_d["id_articoli"],"prezzo","retegas_articoli"))."</td>";
                $h .="<td class=\"destra\">&nbsp;</td>";
                $h .="<td class=\"destra\">&nbsp;</td>";
                $h .="</tr>";
            }
        
        
        //--------------------------------------------LISTA UTENTE
        
        $trasporto = valore_costo_trasporto_ordine_user($id_ordine,$row["id_utenti"]);
        $gestione = valore_costo_gestione_ordine_user($id_ordine,$row["id_utenti"]); 
        $costo_gas = valore_costo_mio_gas($id_ordine,$row["id_utenti"]);
        $maggiorazione_gas = valore_costo_maggiorazione_mio_gas($id_ordine,$row["id_utenti"]);
        $totale_costi = $trasporto +
                        $gestione +
                        $costo_gas +
                        $maggiorazione_gas;
        $netto =  valore_arrivato_netto_ordine_user($id_ordine,$row["id_utenti"]);
        $totale_lordo = $netto +
                        $totale_costi;
        
        //Trasporto
        if($trasporto>0){
        $h .="<tr class=\"costo\">";
        $h .="<td class=\"sinistra column_hide\">$opz</td>";
        $h .="<td class=\"sinistra\"><span class=\"small_link\">".fullname_from_id($row["id_utenti"])."</span></td>";
        $h .="<td class=\"sinistra\">Costo Trasporto</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">"._nf($trasporto)."</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="</tr>";
        }
        //Gestione
        if($gestione>0){
        $h .="<tr class=\"costo\">";
        $h .="<td class=\"sinistra column_hide\">$opz</td>";
        $h .="<td class=\"sinistra\"><span class=\"small_link\">".fullname_from_id($row["id_utenti"])."</span></td>";
        $h .="<td class=\"sinistra\">Costo Gestione</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">"._nf($gestione)."</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="</tr>";
        }
        //Costo GAS
        if($costo_gas>0){
        $h .="<tr class=\"costo\">";
        $h .="<td class=\"sinistra column_hide\">$opz</td>";
        $h .="<td class=\"sinistra\"><span class=\"small_link\">".fullname_from_id($row["id_utenti"])."</span></td>";
        $h .="<td class=\"sinistra\">Costo proprio GAS</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">"._nf($costo_Gas)."</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="</tr>";
        }
        //MAgg
        if($maggiorazione_gas>0){
        $h .="<tr class=\"costo\">";
        $h .="<td class=\"sinistra column_hide\">$opz</td>";
        $h .="<td class=\"sinistra\"><span class=\"small_link\">".fullname_from_id($row["id_utenti"])."</span></td>";
        $h .="<td class=\"sinistra\">Maggiorazione GAS</td>";
        $h .="<td class=\"destra\">".testo_maggiorazione_mio_gas($id_ordine,$id_gas)."</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">"._nf($maggiorazione_gas)."</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="</tr>";
        }
        
        
        //NOte individuali
        
        $note_individuali = read_option_note($row["id_utenti"],"ORD_NOTE_".$id_ordine);
        if($note_individuali<>""){

            $h .="<tr class=\"costo\">";
                $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
                $h .="<th class=\"sinistra\"><span class=\"small_link\">Note individuali</span></th>";
                $h .="<th colspan=8 class=\"sinistra\">$note_individuali</th>";
            $h .="</tr>";
        }
        
        //Prenotazione
        
        if(check_option_prenotazione_ordine($id_ordine,$row["id_utenti"])){
            $prenotazione = "<span style=\"COLOR:red\" ".rg_tooltip("Significa che se l'utente non conferma la sua prenotazione
                                                                     alla chiusura dell'ordine questi articoli saranno cancellati automaticamente.")."> (Prenotazione attiva)</span>";
        }else{
            $prenotazione="";
        }
                
        //Subtotale
        $h .="<tr class=\"subtotal\">";
        $h .="<th class=\"sinistra column_hide\">$opz</th>";
        $h .="<th class=\"sinistra\">&nbsp;</th>";
        $h .="<th class=\"sinistra\">Subtotale utente</th>";
        $h .="<th class=\"destra\">".fullname_from_id($row["id_utenti"])." $prenotazione</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
        $h .="<th class=\"destra\">"._nf($netto)."</th>";
        $h .="<th class=\"destra\">"._nf($totale_costi)."</th>";
        $h .="<th class=\"destra\">"._nf($totale_lordo)."</th>";
        $h .="</tr>";
        
        //SPAZIO BIANCO
       $h .="<tr class=\"\">";
        $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
        $h .="<th class=\"sinistra\">&nbsp;</th>";
        $h .="<th class=\"sinistra\">&nbsp;</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
        $h .="<th class=\"destra\">&nbsp;</th>";
       $h .="</tr>";
    }
    
    
       
    
    
}
$h .="</tbody>";
$h .= "<tfoot>";

$costo_trasporto = valore_costo_trasporto_ordine_gas($id_ordine,$id_gas);
if($costo_trasporto>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">Totale Trasporto</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_trasporto)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

$costo_gestione = valore_costo_gestione_ordine_gas($id_ordine,$id_gas);
if($costo_gestione>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h .="<td class=\"sinistra\">&nbsp;</td>";
    $h .="<td class=\"sinistra\">Totale Gestione</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_gestione)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

$costo_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
if($costo_gas>0){
    $h2 .="<tr class=\"costo\">";
    $h2 .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h2 .="<td class=\"sinistra\">&nbsp;</td>";
    $h2 .="<td class=\"sinistra\">Costo mio GAS</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td class=\"destra\">"._nf($costo_gas)."</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="</tr>";
}

$magg_gas =valore_reale_maggiorazione_percentuale_gas($id_ordine,$id_gas);
if($magg_gas>0){
    $h2 .="<tr class=\"costo\">";
    $h2 .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td class=\"sinistra\">("._nf(valore_percentuale_maggiorazione_mio_gas($id_ordine,$id_gas))."%) ".testo_maggiorazione_mio_gas($id_ordine,$id_gas)."</td>";
    $h2 .="<td class=\"destra\"></td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td class=\"destra\">"._nf($magg_gas)."</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="</tr>";
}


//TOTALE VERSO GLI ESTERNI
$netto = valore_totale_mio_gas($id_ordine,$id_gas);
$costi_esterni = $costo_trasporto + $costo_gestione;
    $h .="<tr class=\"total\">";
    $h .="<th class=\"column_hide\">&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"sinistra\">Totale pubblico:</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"destra\">"._nf($netto)."</th>";
    $h .="<th class=\"destra\">"._nf($costi_esterni)."</th>";
    $h .="<th class=\"destra\">"._nf($netto+$costi_esterni)."</th>";
    $h .="</tr>";







//GRAN TOTALE INTERNO
   
    $costi = $costi_esterni+
             $costo_gas + 
             $magg_gas;
    
    $h2 .="<tr class=\"total\">";
    $h2 .="<th class=\"column_hide\">&nbsp;</th>";
    $h2 .="<th class=\"sinistra\">&nbsp;</th>";
    $h2 .="<th class=\"sinistra\">Totale privato ".gas_nome($id_gas)."</th>";
    $h2 .="<th class=\"destra\"></th>";
    $h2 .="<th class=\"destra\">&nbsp;</th>";
    $h2 .="<th class=\"destra\">&nbsp;</th>";
    $h2 .="<th class=\"destra\">&nbsp;</th>";
    $h2 .="<th class=\"destra\">"._nf($netto)."</th>";
    $h2 .="<th class=\"destra\">"._nf($costi)."</th>";
    $h2 .="<th class=\"destra\">"._nf($netto+$costi)."</th>";
    $h2 .="</tr>";

    //SE è il MIO gas allora aggiungo i totali privati
if($id_gas == _USER_ID_GAS ){
    $h.=$h2;
}
    
$h .= "</tfoot>";



$h .="</table>";





//Creo l'intestazione per il pdf e l'html
//devo assegnare l'url relativa dell'immagine del logo
//Formattazione PDF e HTML
//Uso lo stesso foglio di stile della pagina video
//a cui sovrappongo un po' di margine ai bordi
//I caratteri sono a punti
$s=load_pdf_styles("../../css/");

if(_USER_OPT_NO_HEADER=="SI"){
    $i="";
    $o= "<h3>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") Dettaglio Utenti ".gas_nome($id_gas)."</h3>";

}else{
    $i=load_pdf_header("../../images/rg.jpg");
    $o=render_scheda_pdf_ordine($id_ordine).
    "<h3>Dettaglio utenti ".gas_nome($id_gas)."</h3>";;    
}

//Mando all'utente la sua pagina
if($output=="pdf"){
    require_once("../../lib/dompdf_2/dompdf_config.inc.php");
    $o = sistema_accenti($o);
    
    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("dettaglio_utenti_gas_".$id_gas."_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();
    
}elseif($output=="html"){
    echo $s.$i.$o.$h;

}elseif($output=="word"){
    ob_start();
    include("../../css/my_v3.css");
    include("../../css/tables.css");
    
    $s="<style type=\"text/css\">
    ". ob_get_clean()."\n
    * {font-size:11px;}
    </style>";
    
    //echo ($s.$i.$o.$h);
    word_export($s.$i.$o.$h,"dettaglio_utenti_gas_".$id_gas."_$id_ordine-$cod.doc");
        
    
}else{
    $r->contenuto =     schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>".gas_nome($id_gas)." (Dettaglio utenti)</h3>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r    
unset($r)   
?>