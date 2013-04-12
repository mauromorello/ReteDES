<?php

// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if(!isset($oggetto)){
    pussa_via();
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::coseinutili;
//Assegno il titolo che compare nella barra delle info
$r->title = "Ricerca oggetti";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto

//echo $oggetto;

$oggetto = substr($oggetto,0,50);
$oggetto = urlencode($oggetto);
$lista_oggetti = file_get_contents('http://www.coseinutili.it/php/search.php?oggetto='.$oggetto);
$listona = json_decode($lista_oggetti);
$h.= "<div class=\"rg_widget rg_widget_helper\">";
$h.= "<h3>Oggetti corrispondenti a ".urldecode($oggetto)." trovati su Cose(in)utili.</h3>";
$h .= "<p>Max 50 oggetti ordinati per pertinenza con il termine cercato.</p>";
$h .= "<p>NB: occorre essere registrati su  www.coseinutili.it per poter concludere il baratto. Tutti i dettagli sono sul sito.</p>";
$h.= "<table id=\"output_1\">";
$h.= "<thead>";
    $h.= "<tr>";

        $h.= "<th>&nbsp;</th>";
        $h.= "<th>Titolo</th>";
        $h.= "<th>Crediti</th>";
        $h.= "<th>Descrizione</th>";
        $h.= "<th>Note</th>";
        $h.= "<th>Comune</th>";
        $h.= "<th>Distanza</th>";

        $h.= "</tr>";
$h.= "</thead>";
$h.= "<tbody>";


$log_lng = lon_lat_from_id(_USER_ID);
$log_lat = lat_lon_from_id(_USER_ID);

foreach ($listona as $i => $values) {
    $h.="<tr>";
    
    $titolo = $values->Annuncio;
    $foto = "http://www.coseinutili.it/public/annunci/".$values->id."_1_60.jpg";
    $descrizione = myTruncate($values->descrizione,100);
    $raccontaci = myTruncate($values->raccontaci,100);
    $crediti = $values->crediti;
    $comune = $values->Comune." (".$values->targa.")";
    $lat = $values->user_lat;
    $lng = $values->user_lng;
    $id_annuncio= $values->id;
    
    
    $distanza = round(getDistanceBetweenPointsNew($lat,$lng,$log_lat,$log_lng),1);
    
    
    
    $h.="<td><img SRC=\"$foto\" onerror='this.src = \"http://www.coseinutili.it/public/annunci/0_60.jpg\"'></td>";
    $h.="<td><a href=\"http://www.coseinutili.it/annuncio/?id=$id_annuncio\" target=\"_blank\">$titolo</a></td>";
    $h.="<td class=\"soldi centro\"><strong>$crediti</strong></td>";
    $h.="<td>$descrizione</td>";
    $h.="<td>$raccontaci</td>";
    $h.="<td>$comune</td>";
    $h.="<td class=\"destra\">$distanza Km.</td>";
    
    $h.="</tr>";
}
$h.= "</tbody>";
$h.= "</table>";
$h.= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);