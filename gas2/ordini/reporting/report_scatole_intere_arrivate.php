<?php


// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");

$id_ordine = CAST_TO_INT($id_ordine);

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
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Solo scatole intere";



$r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                        <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                    </ul>
                                </li>';
$r->menu_orizzontale = array_merge(ordini_menu_all($id_ordine),$r->menu_orizzontale);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto --------------------------------------------------------------------
$my_query="SELECT
            retegas_articoli.codice,
            retegas_dettaglio_ordini.id_ordine,
            retegas_dettaglio_ordini.prz_dett_arr AS prezzo,
            retegas_articoli.descrizione_articoli,
            format(Sum(retegas_dettaglio_ordini.prz_dett_arr*retegas_dettaglio_ordini.qta_arr),2) AS tot,
            Count(retegas_dettaglio_ordini.id_ordine) AS qOrd,
            retegas_articoli.qta_scatola,
            format(Sum(retegas_dettaglio_ordini.qta_arr),2) AS Somma_qta,
            (sum(retegas_dettaglio_ordini.qta_arr) DIV retegas_articoli.qta_scatola) AS Scatole_intere,
            (sum(retegas_dettaglio_ordini.qta_arr) MOD retegas_articoli.qta_scatola) AS Avanzo,
            retegas_ordini.min_articoli,
            retegas_ordini.min_scatola,
            retegas_articoli.id_articoli,
            retegas_articoli.u_misura,
            retegas_articoli.misura
            FROM
            retegas_dettaglio_ordini
            Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
            Inner Join retegas_ordini ON retegas_dettaglio_ordini.id_ordine = retegas_ordini.id_ordini
            WHERE
            retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
            GROUP BY
            retegas_articoli.codice,
            retegas_articoli.id_articoli,
            retegas_articoli.descrizione_articoli
            ORDER BY
            retegas_articoli.codice;";

            $result = $db->sql_query($my_query);

            $h .= $alert;
            $h .= "<table id=\"output_1\">";
            $h .= "<thead>";
                $h .="<tr>";
                    $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
                    $h .="<th class=\"sinistra\">Codice</th>";
                    $h .="<th class=\"sinistra\">Descrizione</th>";
                    $h .="<th class=\"destra\">Prezzo</th>";
                    $h .="<th class=\"destra\">QTA per scatola</th>";
                    $h .="<th class=\"destra\">Scatole riempite</th>";
                    $h .="<th class=\"destra\">Tot Riga</th>";
                $h .="</tr>";
            $h .= "</thead>";
            $h .= "<tbody>";
            while ($row = mysql_fetch_array($result)){


              $c2 = $row["codice"];
              $c3 = $row["descrizione_articoli"];
              $c4 = (float)$row["prezzo"];
              $c5 = $row["qOrd"];
              $c6 = (int)$row["Scatole_intere"];

              $c8 = $row["qta_scatola"];
              $temp = ($c4 * ($c6 * $c8));
              $c7 = _nf($temp);

              $id_art = $row["id_articoli"];
              $misu = "(". $row["u_misura"] ." ". $row["misura"].")";
              $id_art= $row["id_articoli"];
              if($c6>0){//Salto righe senza scatole intere
                $riga++;
                $c4 = _nf($c4);

                if(is_integer($riga/2)){
                    $h.= "<tr class=\"odd $extra\">";    // Colore Riga
                }else{
                    $h.= "<tr class=\"$extra\">";
                }
                $h.= "          <td class=\"column_hide\">&nbsp;</td>
                                <td class=\"sinistra\">$c2</td>
                                <td class=\"sinistra\">$c3 $misu</td>
                                <td class=\"destra\">$c4 $euro</td>
                                <td class=\"destra\">$c8</td>
                                <td class=\"destra\"><b>$c6</b></td>
                                <td class=\"destra\">$c7 $euro</td>
                            </tr>";

                    $totale = $totale + $temp;
                    $n_scatole_intere = $n_scatole_intere + $c6;
                }
            }
            $totale = _nf($totale);
            $h .= "</tbody>";
            $h .= "<tfoot>";
            $h.= "<tr class=\"total\">
                                <td class=\"column_hide\">&nbsp;</td>
                                <td class=\"sinistra\"></td>
                                <td class=\"sinistra\">Totale:</td>
                                <td class=\"destra\"></td>
                                <td class=\"destra\"></td>
                                <td class=\"destra\"><b>$n_scatole_intere</b></td>
                                <td class=\"destra\">$totale $euro</td>
                            </tr>";
            $h .= "</tfoot>";
            $h .= "</table>";


//Creo l'intestazione per il pdf e l'html
//devo assegnare l'url relativa dell'immagine del logo
//Formattazione PDF e HTML
//Uso lo stesso foglio di stile della pagina video
//a cui sovrappongo un po' di margine ai bordi
//I caratteri sono a punti
$s=load_pdf_styles("../../css/");

if(_USER_OPT_NO_HEADER=="SI"){
    $i="";
    $o= "<h2>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") Solo scatole intere con qta ARRIVATE !!! :)</h2>";

}else{
    $i=load_pdf_header("../../images/rg.jpg");
    $o=render_scheda_pdf_ordine($id_ordine).
    "<h2>Solo scatole intere con qta ARRIVATE !!! :)</h2>";;
}

//Mando all'utente la sua pagina
if($output=="pdf"){


    require_once("../../lib/dompdf_3/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("scatole_intere_$id_ordine-$cod.pdf",array("Attachment" => 0));
    //echo "<html><head>".$s."</head><body>".$i.$o.$h."</body></html>";

    die();

}elseif($output=="html"){
    echo $s.$i.$o.$h;
}else{
    $r->contenuto = schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h2>Solo scatole intere con qta ARRIVATE !!! :) </h2>
                    <p>ATTENZIONE : Gli importi sono calcolati sulla quantità di articoli che compongono SOLO scatole intere.</p>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r
unset($r);