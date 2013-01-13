<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../ordini_renderer.php");
include_once ("../../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//Se non ? settato il gas lo imposto come quello dell'utente
if(!isset($id_gas)){$id_gas = _USER_ID_GAS;}

if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     go("sommario",_USER_ID,"Non puoi partecipare agli ordini. Contatta il tuo referente GAS.");
}

if (ordine_inesistente($id_ordine)){
     go("sommario",_USER_ID,"Ordine insesistente");
}


//Se posso vedere tutti gli ordini
if(!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){

        //Se non posso partecipare non vedo nemmeno gli articoli
        $mio_Stato = ordine_io_cosa_sono($id_ordine,_USER_ID);
        if ($mio_Stato<1){
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
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Spesa GAS - Riepilogo";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);
    
    //SE l'ordine ? chiuso allora posso stamparlo
    if(is_printable_from_id_ord($id_ordine)){
    $r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["gas_ordine_riepilogo"].'?id_ordine='.$id_ordine.'&id_gas='.$id_gas.'&output=html">Versione stampabile</a></li>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["gas_ordine_riepilogo"].'?id_ordine='.$id_ordine.'&id_gas='.$id_gas.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                    </ul>
                                </li>';
    }
    $r->menu_orizzontale = array_merge(ordini_menu_all($id_ordine),$r->menu_orizzontale);


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta




$query = "SELECT
                    count(retegas_dettaglio_ordini.id_utenti) AS c_user,
                    Sum(retegas_dettaglio_ordini.qta_ord) AS t_q_ord,
                    Sum(retegas_dettaglio_ordini.qta_arr) AS t_q_arr,
                    retegas_articoli.id_articoli,
                    retegas_articoli.id_listini,
                    retegas_articoli.codice,
                    retegas_articoli.u_misura,
                    retegas_articoli.misura,
                    retegas_articoli.descrizione_articoli,
                    retegas_articoli.qta_scatola,
                    retegas_articoli.prezzo,
                    retegas_articoli.ingombro,
                    retegas_articoli.qta_minima,
                    retegas_articoli.qta_multiplo,
                    retegas_articoli.articoli_note
                    FROM
                    retegas_dettaglio_ordini
                    Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                    Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
                    WHERE
                    retegas_dettaglio_ordini.id_ordine =  '$id_ordine' AND
                    maaking_users.id_gas =  '$id_gas'
                    GROUP BY
                    retegas_articoli.id_articoli
                    ORDER BY
                    retegas_articoli.codice ASC
                    ";
$res = $db->sql_query($query);




$h .= $alert;
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">Utenti</th>";
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
    
    $misura = " (". $row["u_misura"]." ".$row["misura"].")";
    
    unset($alert_qta);
    unset($qta);
    $qta = $row["t_q_arr"];
    if($qta==0){
                $alert_qta = "<div class=\"campo_alert\">ANNULLATA</div>";
                $qta="";
            }else if($qta<>$row["t_q_ord"]){
                $alert_qta = "<div class=\"campo_alert\">MODIFICATA</div>";
                
            }
    
    if($row["qta_scatola"]>0){
    $scatole = floor($row["t_q_arr"] / $row["qta_scatola"]);
    $avanzo = (($row["t_q_arr"]) % ($row["qta_scatola"]));
    }else{
    $scatole =0;
    $avanzo = $row["t_q_arr"];    
    }
    $avanzo = calcola_avanzo($row["t_q_arr"],$row["qta_scatola"]);
    
    $h .="<tr $cl>";
    $h .="<td class=\"sinistra column_hide\">$opz</td>";
    $h .="<td class=\"sinistra\">".$row["c_user"]."</td>";
    $h .="<td class=\"sinistra\">".$row["codice"]."</td>";
    $h .="<td class=\"sinistra\">".$row["descrizione_articoli"].$misura."</td>";
    $h .="<td class=\"centro\">".round($row["t_q_ord"],2)." / ".round($qta,2).$alert_qta."</td>";
    $h .="<td class=\"centro\">$scatole / $avanzo</td>";
    $h .="<td class=\"destra\">"._nf($row["prezzo"])."</td>";
    $h .="<td class=\"destra\">"._nf($row["t_q_arr"]*$row["prezzo"])."</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .= "<tfoot>";

$costo_trasporto = valore_costo_trasporto_ordine_gas($id_ordine,$id_gas);
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

$costo_gestione = valore_costo_gestione_ordine_gas($id_ordine,$id_gas);
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

//TOTALE VERSO GLI ESTERNI
$netto = valore_totale_mio_gas($id_ordine,$id_gas);
$costi_esterni = $costo_trasporto + $costo_gestione;
    $h .="<tr class=\"total\">";
    $h .="<th class=\"column_hide\">&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"sinistra\">Totale pubblico:</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"destra\">"._nf($netto)."</th>";
    $h .="<th class=\"destra\">"._nf($costi_esterni)."</th>";
    $h .="<th class=\"destra\">"._nf($netto+$costi_esterni)."</th>";
    $h .="</tr>";




$costo_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
if($costo_gas>0){
    $h2 .="<tr class=\"costo\">";
    $h2 .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td class=\"sinistra\">Costo gas</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
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
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td class=\"sinistra\">("._nf(valore_percentuale_maggiorazione_mio_gas($id_ordine,$id_gas))."%) ".testo_maggiorazione_mio_gas($id_ordine,$id_gas)."</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="<td class=\"destra\">"._nf($magg_gas)."</td>";
    $h2 .="<td>&nbsp;</td>";
    $h2 .="</tr>";
}
//GRAN TOTALE INTERNO
   
    $costi = $costi_esterni+
             $costo_gas + 
             $magg_gas;
    
    $h2 .="<tr class=\"total\">";
    $h2 .="<th class=\"column_hide\">&nbsp;</th>";
    $h2 .="<th>&nbsp;</th>";
    $h2 .="<th>&nbsp;</th>";
    $h2 .="<th class=\"sinistra\">Totale privato ".gas_nome($id_gas).":</th>";
    $h2 .="<th>&nbsp;</th>";
    $h2 .="<th>&nbsp;</th>";
    $h2 .="<th>&nbsp;</th>";
    $h2 .="<th class=\"destra\">"._nf($netto)."</th>";
    $h2 .="<th class=\"destra\">"._nf($costi)."</th>";
    $h2 .="<th class=\"destra\">"._nf($netto+$costi)."</th>";
    $h2 .="</tr>";

    //SE ? il MIO gas allora aggiungo i totali privati
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
    require_once("../../lib/dompdf_2/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("riepilogo_ord_gas_".$id_gas."_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();
    
}elseif($output=="html"){
    echo $s.$i.$o.$h;
}else{
    $r->contenuto =     schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>".gas_nome($id_gas)." (Riepilogo articoli)</h3>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r    
unset($r);