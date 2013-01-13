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
                    Finchè l'ordine non è confermato, questi dati sono da considerarsi NON ATTENDIBILI<br>
                </h4>
              </div>  ";    
    
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Mia Spesa - Riepilogo Amici";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);
if(is_printable_from_id_ord($id_ordine)){
$r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                <ul>
                                    <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_mia_spesa_riepami"].'?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                    <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_mia_spesa_riepami"].'?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                </ul>
                            </li>';
}
$r->menu_orizzontale = array_merge(ordini_menu_all($id_ordine),$r->menu_orizzontale);

$r->messaggio = $msg;
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
    $h .="<th class=\"sinistra column_hide\">Opzione</th>";
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
        
    
        if($row["id_amico"]==0){
            $chi = "Me stesso";
        }else{
            $chi = db_val_q("id_amici",$row["id_amico"],"nome","retegas_amici");    
        }
        
        
        
        
        $riga++;
        
        if(is_integer($riga / 2)){
            $cl  ="class=\"odd\"";
        }else{
            $cl = "";
        }
        
        $tot_riga = valore_netto_singolo_amico($id_ordine,_USER_ID, $row["id_amico"]);
        $tot_costi = valore_costi_totali_amico($id_ordine,_USER_ID, $row["id_amico"]);
        $tot_amico = _nf($tot_riga + $tot_costi);
        $tot_riga = _nf($tot_riga);
        $tot_costi = _nf($tot_costi);
        
         
        $h .="<tr $cl>";
        $h .="<td class=\"sinistra column_hide\">$opz</td>";
        $h .="<td class=\"sinistra\">$chi</td>";
        $h .="<td class=\"sinistra\">&nbsp;</td>";
        $h .="<td class=\"sinistra\">&nbsp;</td>";
        $h .="<td class=\"centro\">&nbsp;</td>";
        $h .="<td class=\"centro\">&nbsp;</td>";
        $h .="<td class=\"destra\">&nbsp;</td>";
        $h .="<td class=\"destra\">$tot_riga</td>";
        $h .="<td class=\"destra\">$tot_costi</td>";
        $h .="<td class=\"destra\">$tot_amico</td>";
        $h .="</tr>";  
  

}
$h .="</tbody>";
$h .= "<tfoot>";
//SUB TOTALE
    $netto = valore_arrivato_netto_ordine_user($id_ordine,_USER_ID);
    $costi = valore_costi_totali($id_ordine,_USER_ID);
    
    
    $h .="<tr class=\"total\">";
    $h .="<th class=\"column_hide\">&nbsp;</th>";
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
    require_once("../../lib/dompdf_2/dompdf_config.inc.php");

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
                    <h3>Riepilogo amici</h3>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r    
unset($r)   
?>