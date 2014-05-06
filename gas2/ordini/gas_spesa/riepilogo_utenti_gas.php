<?php

// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../ordini_renderer.php");
include_once ("../../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

//Se non � settato il gas lo imposto come quello dell'utente
if(!isset($id_gas)){$id_gas = _USER_ID_GAS;}

if(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini){
        $ok = "OK";
    }

    if(read_option_gas_text_new($id_gas,"_GAS_VISIONE_CONDIVISA")=="SI"){
        $ok = "OK";
    }
    if(id_referente_ordine_proprio_gas($id_ordine,$id_gas)==_USER_ID){
        $ok = "OK";
        $ref = "OK";
    }

    //SE E' UN AIUTO EXTRA
    if(check_option_referente_extra($id_ordine,_USER_ID)>0){
        $ok = "OK";
    }

    if($ok<>"OK"){
        go("ordini_form",_USER_ID,"Questa operazione ti � preclusa.","?id_ordine=$id_ordine");
        exit;
    }


$stato_ordine = stato_from_id_ord($id_ordine);


if($stato_ordine==2){
    $alert = "<div class=\"ui-state-error ui-corner-all padding_6px\">
                <h4>Finch� l'ordine non � CONVALIDATO, questi dati sono da considerarsi NON ATTENDIBILI<br>
                </h4>
              </div>  ";

}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men� verticale dovr� essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Spesa GAS - Riepilogo utenti";


//Messaggio popup;
//$r->messaggio = "Pagina di test";
//Dico quale men� orizzontale dovr� essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);

    //SE l'ordine � chiuso allora posso stamparlo
    if(is_printable_from_id_ord($id_ordine)){
    $r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["gas_ordine_riep_users"].'?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["gas_ordine_riep_users"].'?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                    </ul>
                                </li>';
    }
    $r->menu_orizzontale = ordini_menu_all($id_ordine);

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta




$query = "SELECT id_utenti FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' GROUP BY id_utenti;";
$res = $db->sql_query($query);




$h .= $alert;
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">Utente</th>";
    $h .="<th class=\"destra\">Trasporto</th>";
    $h .="<th class=\"destra\">Gestione</th>";
    $h .="<th class=\"destra\">Costo Gas</th>";
    $h .="<th class=\"destra\">Magg Gas</th>";
    $h .="<th class=\"destra\">Tot Costi</th>";
    $h .="<th class=\"destra\">Netto</th>";
    $h .="<th class=\"destra\">Totale</th>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){



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

        $riga++;
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

        if(check_option_prenotazione_ordine($id_ordine,$row["id_utenti"])){
            $prenotazione = "<span style=\"COLOR:red\"> (Prenotazione attiva)</span>";
        }else{
            $prenotazione="";
        }

        $h .="<tr $cl>";
        $h .="<td class=\"sinistra column_hide\">$opz</td>";
        $h .="<td class=\"sinistra\">&nbsp;</td>";

        if(_SITE_SHOW_USERID){
            $h .="<td class=\"sinistra\">ID: <strong>".$row["id_utenti"]."</strong> - ".fullname_from_id($row["id_utenti"])." $prenotazione</td>";
        } else {
            $h .="<td class=\"sinistra\">".fullname_from_id($row["id_utenti"])." $prenotazione</td>";
        }

        //$h .="<td class=\"sinistra\">".fullname_from_id($row["id_utenti"])." $prenotazione</td>";
        $h .="<td class=\"destra\">"._nf($trasporto)."</td>";
        $h .="<td class=\"destra\">"._nf($gestione)."</td>";
        $h .="<td class=\"destra\">"._nf($costo_gas)."</td>";
        $h .="<td class=\"destra\">"._nf($maggiorazione_gas)."</td>";
        $h .="<td class=\"destra\">"._nf($totale_costi)."</td>";
        $h .="<td class=\"destra\">"._nf($netto)."</td>";
        $h .="<td class=\"destra\">"._nf($totale_lordo)."</td>";
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
    $h .="<td class=\"destra\">"._nf($costo_trasporto)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\"></td>";
    $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

$costo_gestione = valore_costo_gestione_ordine_gas($id_ordine,$id_gas);
if($costo_gestione>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h .="<td class=\"sinistra\">&nbsp;</td>";
    $h .="<td class=\"sinistra\">Totale Gestione</td>";
    $h .="<td class=\"destra\"></td>";
    $h .="<td class=\"destra\">"._nf($costo_gestione)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\"></td>";
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
    $h2 .="<td class=\"destra\">"._nf($costo_gas)."</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td class=\"destra\"></td>";
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
    $h2 .="<td class=\"destra\">"._nf($magg_gas)."</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
    $h2 .="<td class=\"destra\">&nbsp;</td>";
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
    $h .="<th class=\"destra\">"._nf($costi_esterni)."</th>";
    $h .="<th class=\"destra\">"._nf($netto)."</th>";
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
    $h2 .="<th class=\"destra\">"._nf($costi)."</th>";
    $h2 .="<th class=\"destra\">"._nf($netto)."</th>";
    $h2 .="<th class=\"destra\">"._nf($netto+$costi)."</th>";
    $h2 .="</tr>";

    //SE � il MIO gas allora aggiungo i totali privati
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
    $o= "<h3>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") Riepilogo articoli ".gas_nome($id_gas)."</h3>";

}else{
    $i=load_pdf_header("../../images/rg.jpg");
    $o=render_scheda_pdf_ordine($id_ordine).
    "<h3>Riepilogo articoli ".gas_nome($id_gas)."</h3>";;
}

//Mando all'utente la sua pagina
if($output=="pdf"){
    require_once("../../lib/dompdf_3/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("riepilogo_utenti_gas_".$id_gas."_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();

}elseif($output=="html"){
    echo $s.$i.$o.$h;
}else{
    $r->contenuto =     schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>".gas_nome($id_gas)." (Riepilogo utenti)</h3>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r
unset($r)
?>