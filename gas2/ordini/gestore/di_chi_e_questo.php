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

if (ordine_inesistente($id_ordine)){
     go("sommario",_USER_ID,"Ordine insesistente");
}

//Se non sono almeno referente GAS allora non posso vedere nulla.
$mio_Stato = ordine_io_cosa_sono($id_ordine,_USER_ID);
if ($mio_Stato<3){
    go("sommario",_USER_ID,"Questo ordine non mi compete");
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
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Di chi è questo ?";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);;


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Di chi è questo ? scopri a chi va un determinato articolo</h3>";
$h .= "<p>";
$h .= "</p>";
$h .= "<div>";


//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);