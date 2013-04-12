<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../ordini_renderer.php");
include_once ("../../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    


if(isset($id_ordine)){
    (int)$id_listini = listino_ordine_from_id_ordine($id_ordine);
}

if (ordine_inesistente($id_ordine)){
     go("sommario",_USER_ID,"Ordine insesistente");
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
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_listino_compilabile"].'?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                        <li><a class="awesome medium silver"  href="'.$RG_addr["ordini_listino_compilabile"].'?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                    </ul>
                                </li>';
    //}
    $r->menu_orizzontale[] = ordini_menu_visualizza($user,$id_ordine);
    $r->menu_orizzontale[] = ordine_menu_operazioni_base(_USER_ID,$id_ordine);
    $r->menu_orizzontale[] = ordine_menu_mia_spesa(_USER_ID,$id_ordine);
    $r->menu_orizzontale[] = ordine_menu_gas(_USER_ID,$id_ordine,_USER_ID_GAS);
    $r->menu_orizzontale[] = ordine_menu_gestisci_new(_USER_ID,$id_ordine,_USER_ID_GAS);
    $r->menu_orizzontale[] = ordine_menu_comunica(_USER_ID,$id_ordine,_USER_ID_GAS);
    $r->menu_orizzontale[] = ordine_menu_extra(_USER_ID,$id_ordine,_USER_ID_GAS);

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta




$query = "SELECT * FROM retegas_articoli WHERE id_listini='$id_listini';";
$res = $db->sql_query($query);




$h .= $alert;
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra\">Codice</th>";
    $h .="<th class=\"sinistra\">Descrizione</th>";
    $h .="<th class=\"destra\">UdV</th>";
    $h .="<th class=\"sinistra\">Prezzo</th>";
    $h .="<th class=\"centro\">&nbsp;</th>";
    $h .="<th class=\"centro\">&nbsp;</th>";
    $h .="<th class=\"centro\">&nbsp;</th>";
    
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
    
        $qxp =  $row["u_misura"]." "._nf($row["misura"]);
    
        //Subtotale
        $h .="<tr $cl>";
        $h .="<td class=\"sinistra\" style=\"width:6em;overflow:hidden;\">".$row["codice"]."</td>";
        $h .="<td class=\"sinistra\" style=\"width:6em;overflow:hidden;\">".$row["descrizione_articoli"]."</td>";
        $h .="<td class=\"destra\" style=\"width:6em;overflow:hidden;\"><span class=\"\">$qxp</span></td>";
        $h .="<td class=\"sinistra\" style=\"width:6em;overflow:hidden;\"><span class=\"\">"._nf($row["prezzo"])." Eu.</span></td>";
        $h .="<td class=\"sinistra\" style=\"width:10em;overflow:hidden;\"><span class=\"small_link\">".strip_tags($row["ingombro"])."</span></td>";
        $h .="<td class=\"sinistra\" style=\"width:10em;overflow:hidden;\"><span class=\"small_link\">".strip_tags($row["note_articoli"])."</span></td>";
        $h .="<td class=\"destra\" style=\"border-bottom:solid 1px black;width:10em;overflow:hidden;\"><span class=\"\">&nbsp;</span></td>";
        

        $h .="</tr>";

    
    
       
    
    
}
$h .="</tbody>";
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
    $o= "<h3>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") Listino compilabile di: _____________________ </h3>";

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
    $dompdf->stream("listino_compilabile_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();
    
}elseif($output=="html"){
    echo $s.$i.$o.$h;
}else{
    $r->contenuto =     schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>Ordine ".$id_ordine." - Listino compilabile</h3>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r    
unset($r)   
?>