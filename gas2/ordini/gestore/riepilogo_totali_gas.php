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
                <h4>Finchè l'ordine non è CONVALIDATO, questi dati sono da considerarsi NON ATTENDIBILI<br>
                </h4>
              </div>  ";

}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Totali tutti GAS";


//Messaggio popup;
//$r->messaggio = "Pagina di test";
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);

    //SE l'ordine è chiuso allora posso stamparlo
    if(is_printable_from_id_ord($id_ordine) or (_USER_ID==id_referente_ordine_globale($id_ordine))){
    $r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_gestione_riepgas"].'?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_gestione_riepgas"].'?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                    </ul>
                                </li>';
    }
    $r->menu_orizzontale = ordini_menu_all($id_ordine);

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta




$query = "SELECT
            Count(retegas_dettaglio_ordini.id_dettaglio_ordini),
            retegas_gas.descrizione_gas,
            retegas_gas.id_gas
            FROM
            retegas_dettaglio_ordini
            Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
            Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
            WHERE
            retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
            GROUP BY
            retegas_gas.descrizione_gas";
$res = $db->sql_query($query);

$avviso_costi = "<div class=\"ui-state-highlight ui-corner-all padding_6px\">
                <h4>NB : I costi Interni del proprio GAS non sono visibili in questo report.<br>
                </h4>
              </div>  ";


$h .= $alert;
$h .= $avviso_costi;
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">GAS</th>";
    $h .="<th class=\"sinistra\">Referente</th>";
    $h .="<th class=\"destra\">Trasporto</th>";
    $h .="<th class=\"destra\">Gestione</th>";
    $h .="<th class=\"destra\">Tot Costi</th>";
    $h .="<th class=\"destra\">Netto</th>";
    $h .="<th class=\"destra\">T Pubblico</th>";
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
    if($row["id_gas"]==_USER_ID_GAS){
        $opz_d='<a class="awesome small yellow" href="'.$RG_addr["gas_ordine_dett_users"].'?id_ordine='.$id_ordine.'&id_gas='.$row["id_gas"].'">Dett.</a>';
    }else{
        $opz_d="";
    }
    $opz_r='<a class="awesome small green" href="'.$RG_addr["gas_ordine_riepilogo"].'?id_ordine='.$id_ordine.'&id_gas='.$row["id_gas"].'">Riep.</a>';


        $trasporto = valore_costo_trasporto_ordine_gas($id_ordine,$row["id_gas"]);
        $gestione = valore_costo_gestione_ordine_gas($id_ordine,$row["id_gas"]);
        $costo_gas = valore_costo_mio_gas($id_ordine,$row["id_gas"]);
        $maggiorazione_gas = valore_reale_maggiorazione_percentuale_gas($id_ordine,$row["id_gas"]);
        $totale_costi = $trasporto +
                        $gestione;

        $netto =  valore_totale_mio_gas($id_ordine,$row["id_gas"]);
        $totale_lordo = $netto +
                        $totale_costi;


        $h .="<tr $cl>";
        $h .="<td class=\"sinistra column_hide\">$opz_r $opz_d</td>";
        $h .="<td class=\"sinistra\">".$row["descrizione_gas"]."</td>";
        $h .="<td class=\"sinistra\"><a href=\"".$RG_addr["pag_users_form"]."?id_utente=".mimmo_encode(id_referente_ordine_proprio_gas($id_ordine,$row["id_gas"]))."\">".fullname_from_id(id_referente_ordine_proprio_gas($id_ordine,$row["id_gas"]))."</a></td>";
        $h .="<td class=\"destra\">"._nf($trasporto)."</td>";
        $h .="<td class=\"destra\">"._nf($gestione)."</td>";

        $h .="<td class=\"destra\">"._nf($totale_costi)."</td>";
        $h .="<td class=\"destra\">"._nf($netto)."</td>";

        $h .="<td class=\"destra\">"._nf($totale_lordo)."</td>";
        $h .="</tr>";


}
$h .="</tbody>";
$h .= "<tfoot>";

$costo_trasporto = valore_trasporto($id_ordine,100);
if($costo_trasporto>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";

    $h .="<td class=\"sinistra\">Totale Trasporto</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_trasporto)."</td>";
    $h .="<td>&nbsp;</td>";
    //$h .="<td>&nbsp;</td>";
    //$h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\"></td>";
    $h .="<td>&nbsp;</td>";
    //$h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

$costo_gestione = valore_gestione($id_ordine,100);
if($costo_gestione>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";

    $h .="<td class=\"sinistra\">Totale Gestione</td>";
    $h .="<td class=\"sinistra\">&nbsp;</td>";
    $h .="<td class=\"destra\"></td>";
    $h .="<td class=\"destra\">"._nf($costo_gestione)."</td>";
    //$h .="<td>&nbsp;</td>";
    //$h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\"></td>";
    $h .="<td>&nbsp;</td>";
    //$h .="<td>&nbsp;</td>";
    $h .="</tr>";
}




//TOTALE VERSO GLI ESTERNI
$netto = valore_totale_ordine_qarr($id_ordine);
$costi_esterni = $costo_trasporto + $costo_gestione;
    $h .="<tr class=\"total\">";
    $h .="<th class=\"column_hide\">&nbsp;</th>";

    $h .="<th class=\"sinistra\">Totale pubblico:</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    //$h .="<th>&nbsp;</th>";
    //$h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"destra\">"._nf($costi_esterni)."</th>";
    $h .="<th class=\"destra\">"._nf($netto)."</th>";
    $h .="<th class=\"destra\">"._nf($netto+$costi_esterni)."</th>";
   // $h .="<th class=\"destra\">"._nf($netto+$costi_esterni)."</th>";
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
    $o= "<h3>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") Riepilogo gas ".gas_nome($id_gas)."</h3>";

}else{
    $i=load_pdf_header("../../images/rg.jpg");
    $o=render_scheda_pdf_ordine($id_ordine).
    "<h3>Riepilogo Gas ".gas_nome($id_gas)."</h3>";;
}

//Mando all'utente la sua pagina
if($output=="pdf"){
    require_once("../../lib/dompdf_3/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("riepilogo_gas_".$id_gas."_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();

}elseif($output=="html"){
    echo $s.$i.$o.$h;
}else{
    $r->contenuto =     schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>Riepilogo GAS</h3>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r
unset($r)
?>