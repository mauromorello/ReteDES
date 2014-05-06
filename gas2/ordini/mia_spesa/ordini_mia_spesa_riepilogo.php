<?php

// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../ordini_renderer.php");
include_once ("../../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     go("sommario",_USER_ID,"Non puoi partecipare agli ordini. Contatta il tuo referente GAS.");
}

if (ordine_inesistente($id_ordine)){
     go("sommario",_USER_ID,"Ordine insesistente");
}

if (ordine_io_cosa_sono($id_ordine,_USER_ID)<2){
    go("sommario",_USER_ID,"Questo ordine non mi compete");
}

$stato_ordine = stato_from_id_ord($id_ordine);


if($stato_ordine==2){
    $alert = "<div class=\"ui-state-error ui-corner-all padding_6px\">
                <h4>Le operazioni di modifica quantitativo ed assegnazione prodotto si possono fare dalla scheda \"partecipa\"<br>
                    Finchè l'ordine non è CONVALIDATO, questi dati sono da considerarsi NON ATTENDIBILI<br>
                </h4>
              </div>  ";

}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Mia Spesa - Riepilogo";


//Messaggio popup;
//$r->messaggio = "Pagina di test";
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);
if(is_printable_from_id_ord($id_ordine)){
$r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                <ul>
                                    <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_mia_spesa_riepilogo"].'?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                    <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_mia_spesa_riepilogo"].'?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                </ul>
                            </li>';
}

$r->menu_orizzontale = array_merge(ordini_menu_all($id_ordine),$r->menu_orizzontale);

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta




$query = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' AND id_utenti='"._USER_ID."';";
$res = $db->sql_query($query);




$h .= $alert;
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">Distr.</th>";
    $h .="<th class=\"sinistra\">Articolo</th>";
    $h .="<th class=\"sinistra\">Descrizione</th>";
    $h .="<th class=\"centro\">Q Ord</th>";
    $h .="<th class=\"centro\">Q Arr</th>";
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
        $opz = "<a class=\"option blue awesome\" title=\"Assegna\" href=\"../../ordini_chiusi/ordini_chiusi_ass_q.php?id=".$row["id_articoli"]."&id_ordine=$id_ordine&q_min=".db_val_q("id_articoli",$row["id_articoli"],"qta_minima","retegas_articoli")."&id_dett=".$row["id_dettaglio_ordini"]."\">A</a>";
    }

    //$misura = " (".db_val_q("id_articoli",$row["id_articoli"],"u_misura","retegas_articoli")." ".db_val_q("id_articoli",$row["id_articoli"],"misura","retegas_articoli").")";

    unset($alert_qta);
    if($row["qta_arr"]==0){
                $alert_qta = "<div class=\"campo_alert\">ANNULLATA</div>";
            }else if($row["qta_arr"]<>$row["qta_ord"]){
                $alert_qta = "<div class=\"campo_alert\">MODIFICATA</div>";
            }
    if($row["prz_dett_arr"]<>$row["prz_dett"]){
        $alert_prz = "<div class=\"campo_alert\">MODIFICATO</div>";
    }


    $h .="<tr $cl>";
    $h .="<td class=\"sinistra column_hide\">$opz</td>";
    $h .="<td class=\"sinistra\">".db_nr_q("id_riga_dettaglio_ordine",$row["id_dettaglio_ordini"],"retegas_distribuzione_spesa")."</td>";
    $h .="<td class=\"sinistra\">".$row["art_codice"]."</td>";
    $h .="<td class=\"sinistra\">".$row["art_desc"]." ".$row["art_um"]."</td>";
    $h .="<td class=\"centro\">"._nf($row["qta_ord"])."</td>";
    $h .="<td class=\"centro\">"._nf($row["qta_arr"]).$alert_qta."</td>";
    $h .="<td class=\"destra\">"._nf($row["prz_dett_arr"]).$alert_prz."</td>";
    $h .="<td class=\"destra\">"._nf($row["qta_arr"]*$row["prz_dett_arr"])."</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .= "<tfoot>";

$costo_trasporto = valore_costo_trasporto_ordine_user($id_ordine,_USER_ID);
if($costo_trasporto>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">Costo Trasporto</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_trasporto)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

$costo_gestione = valore_costo_gestione_ordine_user($id_ordine,_USER_ID);
if($costo_gestione>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">Costo gestione</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_gestione)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

$costo_gas = valore_costo_mio_gas($id_ordine,_USER_ID);
if($costo_gas>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">Costo gas</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_gas)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

$magg_gas = valore_costo_maggiorazione_mio_gas($id_ordine,_USER_ID);
if($magg_gas>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">("._nf(valore_percentuale_maggiorazione_mio_gas($id_ordine,_USER_ID_GAS))."%) ".testo_maggiorazione_mio_gas($id_ordine,_USER_ID_GAS)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($magg_gas)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}
//GRAN TOTALE
    $netto = valore_arrivato_netto_ordine_user($id_ordine,_USER_ID);
    $costi = $costo_trasporto +
             $costo_gestione +
             $costo_gas +
             $magg_gas;

    $h .="<tr class=\"total\">";
    $h .="<th class=\"column_hide\">&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"sinistra\">Totale:</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"destra\">"._nf($netto)."</th>";
    $h .="<th class=\"destra\">"._nf($costi)."</th>";
    $h .="<th class=\"destra\">"._nf($netto+$costi)."</th>";
    $h .="</tr>";



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
    $o= "<h3>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).")</h3>";

}else{
    $i=load_pdf_header("../../images/rg.jpg");
    $o=render_scheda_pdf_ordine($id_ordine);
}

//Mando all'utente la sua pagina
if($output=="pdf"){
    require_once("../../lib/dompdf_3/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("riepilogo_ord_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();

}elseif($output=="html"){
    echo $s.$i.$o.$h;
}else{
    $r->contenuto =     schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>Riepilogo mia spesa</h3>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r
unset($r)
?>