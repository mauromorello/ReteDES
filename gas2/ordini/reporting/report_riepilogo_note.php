<?php


// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}


//Se non è settato il gas lo imposto come quello dell'utente
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
        go("ordini_form",_USER_ID,"Questa operazione ti è preclusa.","?id_ordine=$id_ordine");
        exit;
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

$query = "SELECT id_utenti FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' GROUP BY id_utenti;";
$res = $db->sql_query($query);




$h .= $alert;
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">Utente</th>";
    $h .="<th class=\"sinistra\">Note</th>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){



    if(is_integer($riga / 2)){
        $cl  ="class=\"odd\"";
    }else{
        $cl = "";
    }



    if(id_gas_user($row["id_utenti"])==$id_gas){

        $riga++;

        $note_ordine = read_option_note($row["id_utenti"],"ORD_NOTE_".$id_ordine);

        $h .="<tr $cl>";
        $h .="<td class=\"sinistra column_hide\">$opz</td>";
        $h .="<td class=\"sinistra\">&nbsp;</td>";
        $h .="<td class=\"sinistra\">".fullname_from_id($row["id_utenti"])."</td>";
        $h .="<td class=\"sinistra\">".$note_ordine."</td>";
        $h .="</tr>";
    }

}
$h .="</tbody>";
$h .= "<tfoot>";


$h .= "</tfoot>";



$h .="</table>";








//Creo l'intestazione per il pdf e l'html
//devo assegnare l'url relativa dell'immagine del logo
//Formattazione PDF e HTML
//Uso lo stesso foglio di stile della pagina video
//a cui sovrappongo un po' di margine ai bordi
//I caratteri sono a punti
$gas_nome = gas_nome($id_gas);
$s=load_pdf_styles("../../css/");

if(_USER_OPT_NO_HEADER=="SI"){
    $i="";
    $o= "<h2>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") Riepilogo note utenti $gas_nome</h2>";

}else{
    $i=load_pdf_header("../../images/rg.jpg");
    $o=render_scheda_pdf_ordine($id_ordine).
    "<h2>Riepilogo Note utenti $gas_nome</h2>";;
}

//Mando all'utente la sua pagina
if($output=="pdf"){
    require_once("../../lib/dompdf_3/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("riepilogo_note_utenti_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();

}elseif($output=="html"){
    echo $s.$i.$o.$h;
}else{
    $r->contenuto = schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h2>Riepilogo Note utenti $gas_nome</h2>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r
unset($r)
?>