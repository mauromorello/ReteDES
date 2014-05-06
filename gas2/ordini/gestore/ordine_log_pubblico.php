<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     go("sommario",_USER_ID,"Non puoi partecipare agli ordini. Contatta il tuo referente GAS.");
}




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Cronologia ordine";
 $r->javascripts[]='<script type="text/javascript">                
                        $(document).ready(function() 
                            {
                                $("#table").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\'],
                                                        cancelSelection : true,
                                                        dateFormat : \'ddmmyyyy\',                                                               
                                                        }); 
                                } 
                            );
</script>';

//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);;


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}

$id_ordine = CAST_TO_INT($id_ordine);
if(ordine_io_cosa_sono($id_ordine,_USER_ID)<1){
    pussa_via();
}


$trad = array ( "MAN"=>"Invio mail manuale",
                "ART"=>"Aggiunta articoli",
                "AIU"=>"Accettato aiuto",
                "ASS"=>"Modifica Assegnazione",
                "MOD"=>"Modifica parametri ordine",
                "PRE"=>"Prenotazione confermata",
                "ACC"=>"Aiuto accettato",
                "APE"=>"Apertura ordine",
                "AUT"=>"Mail automatica",
                "REF"=>"Mail da Referente",
                "NOT"=>"Inserita nota personale",
                "ORD"=>"Partecipazione rifiutata",
                "CRE"=>"Creazione rapida ordine",
                "OUT"=>"Operazione rifiutata",
                "REG"=>"Cassiere in azione",
                "DEL"=>"Cancellazione movimenti cassa");


//Contenuto
$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Sequenza degli accadimenti.</h3>";
$h .= "<table id=\"table\">";
$h .= "<thead>";
$h .= "<tr>";
$h .= "<th>Data - Ora</th>";
$h .= "<th>Ruolo</th>";
$h .= "<th>Autore</th>";
$h .= "<th>Operazione</th>";
$h .= "<th>Note</th>";
$h .= "</tr>";
$h .= "</thead>";
$h .= "<tbody>";

$sql ="SELECT * from retegas_messaggi WHERE id_ordine='$id_ordine' ORDER BY id_messaggio DESC";
$res =$db->sql_query($sql);

$referente = id_referente_ordine_globale($id_ordine);
$ref_gas = id_referente_ordine_proprio_gas($id_ordine,_USER_ID_GAS);

while ($row = $db->sql_fetchrow($res)){

$datetime = conv_datetime_from_db($row["timbro"]);
$cosa = $row["tipo2"]." - ".$row["messaggio"];
$note ="";

$ruolo = "Utente semplice";

if($ref_gas==$row["id_user"]){    
    $ruolo = "REF.GAS : ".fullname_from_id($row["id_user"]);
}
                                  
if($referente==$row["id_user"]){    
    $ruolo = "REF.ORD : ".fullname_from_id($row["id_user"]);
}

if($row["id_user"]==0){
    $ruolo = "RETEDES.IT";
}

if(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini){
    $verbose = rg_toggable($cosa,"id_".$row["id_messaggio"],$row["query"]);
}

if(id_gas_user($row["id_user"])==_USER_ID_GAS){
    
    if(leggi_permessi_utente($row["id_user"]) & perm::puo_vedere_tutti_ordini){
        $ruolo .= " SUPERVISORE ORDINI";
        $chi = fullname_from_id($row["id_user"]);
        
    }
    
    
    $autore =" ".gas_nome(_USER_ID_GAS);
}else{
    $autore =" altro gas";
}

    
$h .= "<tr>";
$h .= "<td>$datetime</td>";
$h .= "<td>$ruolo</td>";
$h .= "<td>$autore $chi</td>";
$h .= "<td>".$trad[$row["tipo2"]]."</td>";
$h .= "<td>$verbose</td>";
$h .= "</tr>";    

}




$h .= "</tbody>";
$h .= "</table>";
$h .= "<div>";


//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);