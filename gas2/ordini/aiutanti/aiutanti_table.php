<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//Se non esiste l'ordine    
if(ordine_inesistente($id_ordine)){
        pussa_via();
        exit;
}


// Se in quell'ordine non posso fare nulla
if(!((_USER_PERMISSIONS & perm::puo_gestire_retegas) OR (_USER_PERMISSIONS & perm::puo_gestire_la_cassa))){    
    if(ordine_io_cosa_sono($id_ordine,_USER_ID)==0){
            pussa_via();
            exit;    
    }
}

if($do=="accetta"){
    
    activate_option_aiuto_ordine($id_ordine,mimmo_decode($id_utente));
    manda_mail(_USER_FULLNAME,
    email_from_id(_USER_ID),
    fullname_from_id(mimmo_decode($id_utente)),
    email_from_id(mimmo_decode($id_utente)),
    "["._SITE_NAME."] Aiuto accettato !!",
    null,
    "AIU",
    $id_ordine,
    _USER_ID,
    "<h3>Ho accettato la tua offerta di aiuto riguardo l'ordine $id_ordine</h3>
     <p>Ti contatterò al più presto per definire le modalità, i tempi e i luoghi. Ciau ! :)</p>
     <p>"._USER_FULLNAME."</p>");
    log_me($id_ordine,_USER_ID,"AIU","ACC","Accettato aiuto",0,"Da user ".fullname_from_id(mimmo_decode($id_utente)));
    $msg="Utente Avvisato";
}

if($do=="rifiuta"){
    
    refuse_option_aiuto_ordine($id_ordine,mimmo_decode($id_utente));
    manda_mail(_USER_FULLNAME,
    email_from_id(_USER_ID),
    fullname_from_id(mimmo_decode($id_utente)),
    email_from_id(mimmo_decode($id_utente)),
    "["._SITE_NAME."] Aiuto rifiutato :(",
    null,
    "AIU",
    $id_ordine,
    _USER_ID,
    "<h3>Grazie 1000</h3>
     <p>....ma per questa volta penso di non aver bisogno del tuo aiuto.</p>
     <p>Tieniti pronto per la prossima !!</p>
     <p>"._USER_FULLNAME."</p>");
    log_me($id_ordine,_USER_ID,"AIU","RIF","Rifiutato aiuto",0,"Da user ".fullname_from_id(mimmo_decode($id_utente)));
    $msg="Utente Avvisato";
}

if($do=="elimina"){
    
    delete_option_aiuto_ordine($id_ordine,mimmo_decode($id_utente));
    log_me($id_ordine,_USER_ID,"AIU","ELI","Eliminato aiuto",0,"Da user ".fullname_from_id(mimmo_decode($id_utente)));
    $msg="Richiesta eliminata : nessuno saprà nulla...";
    go("ordini_form_new",_USER_ID,$msg,null);
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Aiutanti ordine";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");

//MEssaggio eventuale
if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
if(!is_empty($msg)){
    $r->messaggio = $msg;
}

$query = "SELECT * FROM retegas_options WHERE
          chiave = 'AIUTO_ORDINI' AND
          id_ordine = '$id_ordine';";
$res = $db->sql_query($query);

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Aiutanti di questo ordine</h3>";
$h .= "<h4>Se clicchi su \"Accetta\" o \"Rifiuta\" verrà mandata una mail all'utente specificato che lo informerà riguardo al suo cambio di stato.</h4>";
$h .= "<table id=\"output_1\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>Id</th>";
    $h .="<th>Nome</th>";
    $h .="<th>Gas</th>";
    $h .="<th>Stato</th>";
    $h .="<th>Ruolo</th>";
    $h .="<th>Opzioni</th>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = $db->sql_fetchrow($res)){
    
    
    $ruolo = $row["valore_text"];
    $accetta = "<a class=\"awesome green small\" href=\"".$RG_addr["ordini_aiutanti_table"]."?id_ordine=$id_ordine&id_utente=".mimmo_encode($row["id_user"])."&do=accetta\">ACCETTA</a>";
    $rifiuta = "<a class=\"awesome red small\" href=\"".$RG_addr["ordini_aiutanti_table"]."?id_ordine=$id_ordine&id_utente=".mimmo_encode($row["id_user"])."&do=rifiuta\">RIFIUTA</a>";
    $elimina = "<a class=\"awesome black small\" href=\"".$RG_addr["ordini_aiutanti_table"]."?id_ordine=$id_ordine&id_utente=".mimmo_encode($row["id_user"])."&do=elimina\">ELIMINA</a>";
    
    $opz = $accetta." ".$rifiuta." ".$elimina;;
    
    switch ($row["valore_int"]){
       case 0:
        $stato =  "In attesa";
        $pal = pallino("grey",16);
       break;
       case 1:
        $stato =  "Accettato aiuto";
        $pal = pallino("green",16);
       break;
       case 2:
        $stato =  "Rifiutato aiuto";
        $pal = pallino("red",16);
       break;

    }
    
    $h .="<tr>";
    $h .="<td>".$row["id_user"]."</td>";
    $h .="<td><a href=\"".$RG_addr["user_form_public"]."?id_utente=".mimmo_encode($row["id_user"])."\">".fullname_from_id($row["id_user"])."</a></td>";
    $h .="<td>".gas_nome(id_gas_user($row["id_user"]))."</td>";
    $h .="<td>$pal $stato</td>";
    $h .="<td>$ruolo</td>";
    $h .="<td>$opz</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .="</table>";
$h .="</div>";



//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>