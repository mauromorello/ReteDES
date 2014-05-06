<?php


   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Altri gas nell'universo";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;
$r->javascripts[]=java_tablesorter("output_1");
$r->menu_orizzontale = gas_menu_completo($user);
if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto


$h.= "<div class=\"rg_widget rg_widget_helper\">";
$h.= "<h4>I GAS in ITALIA</h4>
      <p>La realtà dei GAS in italia sta crescendo ogni giorno che passa. Ad oggi esistono circa (stime ufficiali) 2500 GAS operanti, più o meno; Una parte di questi gas
      l'ho trovata registrata su www.retegas.org (è probabile che ci sia anche già il vostro gas), il portale nazionale della rete gas.<br>
      <strong>I dati dei vari gas sono disponibili a tutti, </strong> e io dopo averli raccolti li ho geolocalizzati, in modo da avere
      un colpo d'occhio geografico sulla situazione.</p>
      <p><a href=\"#output_1\">In basso</a> c'è una tabella, con i gas selezionati nel raggio di 50Km da dove ti trovi tu. Il link ti porta alla pagina di <a href=\"http://www.retegas.org\">www.retegas.org</a> dalla quale sono stati presi i dati.</p>
      <p>Perchè non provare a contattare anche loro e proporgli di entrare in retedes.it ? Più gas siamo e più sarà facile (e proficuo) gestire ordini condivisi !<br>
      <small>ATTENZIONE : i contatti non sono verificati, pertanto alcuni potrebbero essere obsoleti o comunque inesatti.</small></p>";

$h.= "<iframe frameborder=0 src=\"http://www.coseinutili.it/php/test_7.php\" style=\"width:800px;height:680px;\"></iframe>";

//Questo ?? il contenuto della pagina

$lista_oggetti = file_get_contents('http://www.coseinutili.it/php/search_gas.php');
$listona = json_decode($lista_oggetti);

//print_r($listona);die();


$h .= "<h3>Lista dei gas vicini a te.</h3>";
$h.= "<table id=\"output_1\">";
$h.= "<thead>";
    $h.= "<tr>";

        $h.= "<th>&nbsp;</th>";
        $h.= "<th>Nome</th>";
        $h.= "<th>Descrizione</th>";
        $h.= "<th>Mail</th>";
        $h.= "<th>Web, Tel</th>";
        $h.= "<th>Indirizzo</th>";
        $h.= "<th>Distanza</th>";

        $h.= "</tr>";
$h.= "</thead>";
$h.= "<tbody>";


$log_lng = lon_lat_from_id(_USER_ID);
$log_lat = lat_lon_from_id(_USER_ID);

foreach ($listona as $i => $values) {
    
    
    $titolo = $values->nome;
    //$foto = "http://www.coseinutili.it/images/tempoUtile/".$values->categoria_id.".jpg";
    $note = myTruncate($values->note,200);
    
    $mail = $values->mail;
    $indirizzo = $values->indirizzo;
    $lat = $values->gas_lat;
    $lng = $values->gas_lng;
    $id_annuncio= $values->id;
    $web = $values->web;
    if($web<>" - "){
        $web = "<a href=\"$web\" target=\"_blank\">Sito</a>";
    }else{
        $web="";
    }
    
    $tel = $values->tel;
    
    $distanza = round(getDistanceBetweenPointsNew($lat,$lng,$log_lat,$log_lng),1);
    
    if($distanza<50){
    $h.="<tr>";
    $h.="<td>&nbsp;</td>";
    $h.="<td><a href=\"http://www.retegas.org/index.php?module=pagesetter&func=viewpub&tid=3&pid=$id_annuncio\" target=\"_blank\">$titolo</a></td>";
    $h.="<td><span class=\"small_link\">$note</span></td>";
    $h.="<td>$indirizzo</td>";
    $h.="<td>$web</td>";
    $h.="<td>$mail</td>";
    $h.="<td class=\"destra\">$distanza Km.</td>";
    
    $h.="</tr>";
    }
}
$h.= "</tbody>";
$h.= "</table>";
$h.= "</div>";









$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);