<?php

// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../ordini_renderer.php");
include_once ("../../retegas.class.php");

function disegna_amico($id_amico,$id_ordine,&$riga){
global $db, $RG_addr,$output;
$qu = "SELECT
        retegas_distribuzione_spesa.id_articoli,
        retegas_distribuzione_spesa.qta_ord,
        retegas_distribuzione_spesa.qta_arr,
        retegas_dettaglio_ordini.art_desc,
        retegas_dettaglio_ordini.prz_dett_arr,
        retegas_dettaglio_ordini.art_codice,
        retegas_dettaglio_ordini.art_um,
        retegas_amici.nome,
        retegas_distribuzione_spesa.id_riga_dettaglio_ordine
        FROM
        retegas_distribuzione_spesa
        Left Join retegas_dettaglio_ordini ON retegas_distribuzione_spesa.id_riga_dettaglio_ordine = retegas_dettaglio_ordini.id_dettaglio_ordini
        Left Join retegas_amici ON retegas_distribuzione_spesa.id_amico = retegas_amici.id_amici
        WHERE
        retegas_distribuzione_spesa.id_user =  '"._USER_ID."' AND
        retegas_distribuzione_spesa.id_ordine =  '$id_ordine' AND
        retegas_distribuzione_spesa.id_amico =  '$id_amico' ;";

$res = $db->sql_query($qu);
        while ($row = mysql_fetch_array($res)){

            if($id_amico==0){
                $chi = "Me stesso";
            }else{
                $chi = $row["nome"];
            }

            $opz = "&nbsp;";
            if(!isset($output)){

                if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
                    if(!articolo_univoco($row["id_articoli"])){
                        $opz = "<a class=\"awesome option blue\" href =\"".$RG_addr["ordini_mod_ass_new"]."?id_ordine=$id_ordine&id_articolo=".$row["id_articoli"]."&n_riga=".$row["id_riga_dettaglio_ordine"]."\">A</a>";
                    }else{
                        $opz = "<a class=\"awesome option blue\" href =\"".$RG_addr["ordini_mod_uni_new_form"]."?id_ordine=$id_ordine&id_articolo=".$row["id_articoli"]."&n_riga=".$row["id_riga_dettaglio_ordine"]."\">A</a>";
                    }
                }
            }

            $riga++;

            if(is_integer($riga / 2)){
                $cl  ="class=\"odd\"";
            }else{
                $cl = "";
            }

            unset($alert_qta);
            if($row["qta_arr"]==0){
                $alert_qta = "<div class=\"campo_alert\">ANNULLATA</div>";
            }else if($row["qta_arr"]<>$row["qta_ord"]){
                $alert_qta = "<div class=\"campo_alert\">MODIFICATA</div>";
            }

            $misura = " <span class=\"small_link\">(".$row["art_um"].")</span>";
            //$prezzo = db_val_q("id_dettaglio_ordini",$row["id_riga_dettaglio_ordine"],"prz_dett_arr","retegas_dettaglio_ordini");
            $prezzo = $row["prz_dett_arr"];


            $tot_riga = _nf($row["qta_arr"]*$prezzo);

            if(articolo_univoco($row["id_articoli"])){
                $extra_code = "<span class=\"small_link\"> - ".$row["id_riga_dettaglio_ordine"]."</span>";
            }

            $h .="<tr $cl>";
            $h .="<td class=\"sinistra\">$opz</td>";
            $h .="<td class=\"sinistra\">$chi</td>";
            $h .="<td class=\"sinistra\">".$row["art_codice"].$extra_code."</td>";
            $h .="<td class=\"sinistra\">".$row["art_desc"].$misura."</td>";
            $h .="<td class=\"centro\">"._nf($row["qta_ord"])."</td>";
            $h .="<td class=\"centro\">"._nf($row["qta_arr"]).$alert_qta."</td>";
            $h .="<td class=\"destra\">$prezzo</td>";
            $h .="<td class=\"destra\">$tot_riga</td>";
            $h .="<td class=\"destra\">&nbsp;</td>";
            $h .="<td class=\"destra\">&nbsp;</td>";
            $h .="</tr>";
        }
$costo_trasporto = valore_costo_trasporto_ordine_amico($id_ordine,_USER_ID, $id_amico);
if($costo_trasporto>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra\">&nbsp</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">Costo Trasporto</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_trasporto)."</td>";
    $h .="<td class=\"destra\"></td>";
    $h .="</tr>";
}

$costo_gestione = valore_costo_gestione_ordine_amico($id_ordine,_USER_ID, $id_amico);
if($costo_gestione>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">Costo gestione</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_gestione)."</td>";
    $h .="<td class=\"destra\">&nbsp</td>";
    $h .="</tr>";
}

$costo_gas = valore_costo_mio_gas_amico($id_ordine,_USER_ID,$id_amico);
if($costo_gas>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra\">&nbsp;</td>";
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

$magg_gas = valore_costo_maggiorazione_mio_gas_amico($id_ordine,_USER_ID,$id_amico);
if($magg_gas>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">("._nf(valore_percentuale_maggiorazione_mio_gas($id_ordine,_USER_ID_GAS))."%) ".testo_maggiorazione_mio_gas($id_ordine,_USER_ID_GAS)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($magg_gas)."</td>";
    $h .="<td>&nbsp</td>";
    $h .="</tr>";
}
//SUB TOTALE
    $netto = valore_netto_singolo_amico($id_ordine,_USER_ID, $id_amico);
    $costi = $costo_trasporto +
             $costo_gestione +
             $costo_gas +
             $magg_gas;

    $logo_forbici="<img src=\"../../images/forbici.png\" style=\"width:16px;height:16px;\">";

    $h .="<tr class=\"subtotal\">";
    $h .="<th class=\"sinistra\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">$chi</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"sinistra\">Subtotale $chi:</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"destra\">"._nf($netto)."</th>";
    $h .="<th class=\"destra\">"._nf($costi)."</th>";
    $h .="<th class=\"destra\">"._nf($netto+$costi)."</th>";
    $h .="</tr>";

    unset($qu);
   return $h;
}

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
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Mia Spesa - Dettaglio Amici";


//Messaggio popup;
//$r->messaggio = "Pagina di test";
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);
    if(is_printable_from_id_ord($id_ordine)){
    $r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_mia_spesa_dettaglio"].'?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_mia_spesa_dettaglio"].'?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                    </ul>
                                </li>';
    }

    $r->menu_orizzontale = array_merge(ordini_menu_all($id_ordine),$r->menu_orizzontale);

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Creo la pagina dell'aggiunta




$my_query="SELECT
retegas_distribuzione_spesa.id_amico
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_ordine =  '$id_ordine' AND
retegas_distribuzione_spesa.id_user =  '"._USER_ID."'
GROUP BY retegas_distribuzione_spesa.id_amico;";


$res = $db->sql_query($my_query);




$h .= $alert;
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra\">&nbsp;</th>";
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

//CICLO CHE PASSA TUTTI GLI AMICI
while ($row = mysql_fetch_array($res)){

    $h .=disegna_amico($row["id_amico"],$id_ordine,$riga);


}
$h .="</tbody>";
$h .= "<tfoot>";
// TOTALE
    $netto = valore_arrivato_netto_ordine_user($id_ordine,_USER_ID);
    $costi = valore_costi_totali($id_ordine,_USER_ID);


    $h .="<tr class=\"total\">";
    $h .="<th class=\"sinistra\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"sinistra\">Totale :</th>";
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
                    <h3>Dettaglio mia spesa</h3>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r
unset($r)
?>