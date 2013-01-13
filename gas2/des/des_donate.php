<?php


// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("des_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Offrimi una birra !!";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;

log_me(0,_USER_ID,"DES","DON",_USER_FULLNAME. " è arrivato qua.",0,null);

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = des_render_donate();;

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);