<?php
 
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI

//SE ORDINE E' PARTECIPABILE DA USER

//SE ARTICOLI = 0 

if($do=="attiva_prenotazione"){
    write_option_prenotazione_ordine($id_ordine,_USER_ID,"SI");
    log_me($id_ordine,_USER_ID,"ORD","PRE","Prenotazione attivata",0,"");
    $msg = "Prenotazione attivata";
    go("ordini_form_new",_USER_ID,$msg,"?id_ordine=$id_ordine");
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Prenotazione ordini";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);  



if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = schedina_ordine($id_ordine).
"
<div class=\"rg_widett rg_widget_helper\">
<h3>Attiva la modalità \"prenotazione\"</h3>
<p>Spiegazioni</p>
<a class=\"awesome green medium\" href=\"?id_ordine=$id_ordine&do=attiva_prenotazione\">ATTIVA LA PRENOTAZIONE !</a>
</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);   
