<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    
if(!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){
    if(!posso_gestire_ordine_full($id_ordine,_USER_ID)){
        go("ordini_form",_USER_ID,"Questa operazione ti è preclusa.","?id_ordine=$id_ordine");
        exit;
    }
}




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Articoli per codice";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
 $r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                        <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                    </ul>
                                </li>';
    //}
$r->menu_orizzontale = array_merge(ordini_menu_all($id_ordine),$r->menu_orizzontale);


//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto

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
                    retegas_dettaglio_ordini.id_ordine =  '$id_ordine' 
                    GROUP BY
                    retegas_articoli.codice
                    ORDER BY
                    retegas_articoli.codice ASC
                    ";
$res = $db->sql_query($query);




$h .= $alert;
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
   // $h .="<th class=\"sinistra\">Utenti</th>";
    $h .="<th class=\"sinistra\">Codice</th>";
    $h .="<th class=\"sinistra\">Descrizione</th>";
    $h .="<th class=\"centro\">Richiesti</th>";
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
    //$h .="<td class=\"sinistra\">".$row["c_user"]."</td>";
    $h .="<td class=\"sinistra\">".$row["codice"]."</td>";
    $h .="<td class=\"sinistra\">".$row["descrizione_articoli"].$misura."</td>";
    $h .="<td class=\"centro\">".round($row["t_q_ord"],2)."</td>";
    $h .="<td class=\"destra\">"._nf($row["prezzo"])."</td>";
    $h .="<td class=\"destra\">"._nf($row["t_q_arr"]*$row["prezzo"])."</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .= "<tfoot>";

$costo_trasporto = valore_trasporto($id_ordine,100);
if($costo_trasporto>0){
    $h .="<tr class=\"costo\">";
        $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
        //$h .="<td>&nbsp;</td>";
        $h .="<td>&nbsp;</td>";
        $h .="<td class=\"sinistra\">Costo Trasporto</td>";
        //$h .="<td>&nbsp;</td>";
        $h .="<td>&nbsp;</td>";
        $h .="<td>&nbsp;</td>";
        $h .="<td>&nbsp;</td>";
        $h .="<td class=\"destra\">"._nf($costo_trasporto)."</td>";
        $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

$costo_gestione = valore_gestione($id_ordine,100);
if($costo_gestione>0){
    $h .="<tr class=\"costo\">";
        $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
        //$h .="<td>&nbsp;</td>";
        $h .="<td>&nbsp;</td>";
        $h .="<td class=\"sinistra\">Costo gestione</td>";
        //$h .="<td>&nbsp;</td>";
        $h .="<td>&nbsp;</td>";
        $h .="<td>&nbsp;</td>";
        $h .="<td>&nbsp;</td>";
        $h .="<td class=\"destra\">"._nf($costo_gestione)."</td>";
        $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

//TOTALE VERSO GLI ESTERNI
$netto = valore_totale_ordine_qarr($id_ordine);
$costi_esterni = $costo_trasporto + $costo_gestione;
    $h .="<tr class=\"total\">";
        $h .="<th class=\"column_hide\">&nbsp;</th>";
        //$h .="<th>&nbsp;</th>";
        $h .="<th>&nbsp;</th>";
        $h .="<th class=\"sinistra\">Totale pubblico:</th>";
        //$h .="<th>&nbsp;</th>";
        $h .="<th>&nbsp;</th>";
        $h .="<th>&nbsp;</th>";
        $h .="<th class=\"destra\">"._nf($netto)."</th>";
        $h .="<th class=\"destra\">"._nf($costi_esterni)."</th>";
        $h .="<th class=\"destra\">"._nf($netto+$costi_esterni)."</th>";
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
    $o= "<h2>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") Riepilogo articoli tutti i GAS</h2>";

}else{
    $i=load_pdf_header("../../images/rg.jpg");
    $o=render_scheda_pdf_ordine($id_ordine).
    "<h2>Riepilogo articoli raggruppati per CODICE</h2>";;    
}

//Mando all'utente la sua pagina
if($output=="pdf"){
    require_once("../../lib/dompdf_3/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("riepilogo_articoli_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();
    
}elseif($output=="html"){
    echo $s.$i.$o.$h;
}else{
    $r->contenuto = schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h2>Riepilogo articoli</h2>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r     
unset($r)   
?>