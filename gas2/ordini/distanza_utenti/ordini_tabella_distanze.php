<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if(ordine_io_cosa_sono($id_ordine,_USER_ID)==0){
         go("sommario",_USER_ID,"Questa operazione non è possibile.");    
}

$my_lat = lat_lon_from_id(_USER_ID);
$my_lon = lon_lat_from_id(_USER_ID);

if((int)$my_lat==0){
    go("ordini_form",_USER_ID,"Per poter utilizzare questa funzione devi avere un indirizzo postale correttamente riconosciuto","?id_ordine=$id_ordine");
    
}

//MANDA MAIL AI VICINI
if($do=="send"){           // REFERENTE -----> TUTTI UTENTI ORDINE
       
       // CONTROLLARE SE USER E' REFERENTE ORDINE   
        $da_chi = fullname_from_id(_USER_ID);
        $mail_da_chi = id_user_mail(_USER_ID);
        
          
        $descrizione_ordine = descrizione_ordine_from_id_ordine($id_ordine);
        
        if(!is_array($box_utenti))
        { go("ordini_form",_USER_ID,"Non hai selezionato nessun vicino.","?id_ordine=$id_ordine");       }
        
        
        foreach ($box_utenti as $chiave => $utente) {
        
        //echo $row[0] ." - ". $row[1]."<br />";
        $User_lista =  fullname_from_id($utente);
        $verso_chi[] = $User_lista;
        $mail_verso_chi[] = email_from_id($utente);
        $lista_destinatari .= $User_lista."<br>"; 
        
        }

        $message = sanitize($message);
        $soggetto = "[RETEGAS AP] - [VICINO DI CASA] $da_chi per ordine $id ($descrizione_ordine)";
        
          manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($message),"MAN",$id_ordine,_USER_ID,$message);
            
          $msg="Mail correttamente inviata a : <br>$lista_destinatari";
          unset($do);
          go("ordini_form",_USER_ID,$msg,"?id_ordine=$id_ordine");
          
      }





//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Distanza da utenti ordine";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
//$r->menu_orizzontale = amministra_menu_completo();
$r->menu_orizzontale[] = ordini_menu_visualizza($user,$id_ordine);
$r->menu_orizzontale[] = ordine_menu_operazioni_base(_USER_ID,$id_ordine);
$r->menu_orizzontale[] = ordine_menu_mia_spesa(_USER_ID,$id_ordine);
$r->menu_orizzontale[] = ordine_menu_gas(_USER_ID,$id_ordine,_USER_ID_GAS);
$r->menu_orizzontale[] = ordine_menu_gestisci_new(_USER_ID,$id_ordine,_USER_ID_GAS);
$r->menu_orizzontale[] = ordine_menu_cassa(_USER_ID,$id_ordine,_USER_ID_GAS);
$r->menu_orizzontale[] = ordine_menu_comunica(_USER_ID,$id_ordine,_USER_ID_GAS);
$r->menu_orizzontale[] = ordine_menu_extra($id_ordine);



//Assegno le due tabelle a tablesorter
$r->javascripts_header[] = java_head_jquery_metadata();
$r->javascripts_header[] = java_head_ckeditor();
$r->javascripts[]=java_tablesorter("output_1");


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

//http://retegas.altervista.org/gas2/ordini/distanza_utenti/ordini_tabella_distanze.php?id_ordine=435
//Contenuto
$query_lon_lat ="SELECT *,(((acos(sin((".$latitude."*pi()/180)) * sin((`Latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`Latitude`*pi()/180)) * cos(((".$longitude."- `Longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance FROM `MyTable` WHERE distance >= ".$distance."";



$sql = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' GROUP BY id_utenti";
$res = $db->sql_query($sql);




$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>I miei vicini di casa</h3>";
$h .= "<div class=\"ui-state-highlight ui-corner-all padding_6px\">
       <p>In questa pagina vengono mostrati gli utenti che :
       <ul>
            <li>Hanno il loro indirizzo correttamente interpretato</li>
            <li>Hanno comprato merce in questo stesso ordine</li>
            <li>Abitano a meno di 5 Km in linea d'aria da casa tua.</li>     
       </ul>
       NB: Il gestore dell'ordine non compare in questa tabella.
       </p>
       <p>Potresti mettervi d'accordo per il ritiro e distribuzione della vostra merce tra di voi, no?</p> 
       </div>";
$h .= "<form action=\"\" method=\"POST\" class=\"retegas_form\">";
$h .= "<table id=\"output_1\" class=\"{sortlist: [[2,0]]}\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra\">Utente</td>";
    $h .="<th class=\"sinistra\">Indirizzo</td>";
    $h .="<th class=\"sinistra\">GAS</td>";
    $h .="<th class=\"sinistra  {sorter: 'floating'}\">Distanza</td>";
    $h .="<th class=\"destra\">Seleziona</td>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";




$nr=0;
while ($row = mysql_fetch_array($res)){

if(id_gas_user($row["id_utenti"])==_USER_ID_GAS){
    $lon  =  lon_lat_from_id($row["id_utenti"]);
        if(($lon>0) AND (_USER_ID<>$row["id_utenti"])){
            if(id_referente_ordine_proprio_gas($id_ordine,_USER_ID_GAS)<>$row["id_utenti"]){
            $lat = lat_lon_from_id($row["id_utenti"]);
            $distanza = getDistanceBetweenPointsNew($my_lat,$my_lon,$lat,$lon);
                if($distanza<5){
                    $nr++;    
                    $h .= "<tr>";
                    $h .= "<td>".fullname_from_id($row["id_utenti"])."</td>";
                    $h .= "<td>".indirizzo_user_from_id($row["id_utenti"])."</td>";
                    $h .= "<td>".gas_nome(id_gas_user(($row["id_utenti"])))."</td>";
                    $h .= "<td>".round($distanza,1)." Km circa.</td>";
                    $h .= "<td><input type=\"checkbox\" name=\"box_utenti[]\" value=\"".$row["id_utenti"]."\"></td>";
                    $h .= "</tr>";
                }
            }
        }       
    }
}    
$h .="</tbody>";
$h .="</table>";
$h .="<textarea class=\"ckeditor\" name=\"message\"></textarea>";
$h .="<input type=\"submit\" name=\"submit\" value=\"Manda una mail ai tuoi vicini che hai selezionato\">";
$h .="<input type=\"hidden\" name=\"do\" value=\"send\">";
$h .="<input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">";
$h .="</form>";
$h .="</div><br>";


if($nr==0){ //Se non ci sono vicini
$h = "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>I miei vicini di casa</h3>";
$h .= "<div class=\"ui-state-highlight ui-corner-all padding_6px\">
       <p>In questa pagina vengono mostrati gli utenti che :
       <ul>
            <li>Hanno il loro indirizzo correttamente interpretato</li>
            <li>Hanno comprato merce in questo stesso ordine</li>
            <li>Abitano a meno di 5 Km in linea d'aria da casa tua.</li>     
       </ul>
       NB: Il gestore dell'ordine non compare in questa tabella.
       </p>
       <p><strong>In questo caso nessuno vicino a te ha comprato merce in questo ordine.</strong></p> 
       </div>";   
$h .="</div>";    
}
//Questo è¨ il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>