<?php


  
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){
     pussa_via();
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Ordini Dashboard";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = extra_menu_all();


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Link e operazioni su ordini</h3>";
if(!is_empty($id_ordine)){
    $desc = descrizione_ordine_from_id_ordine($id_ordine);
    $h .= "<p>Dettaglio ordine $id_ordine ($desc) proprio gas <a class=\"awesome blue medium\" href=\"".$RG_addr["gas_ordine_dett_users"]."?id_ordine=$id_ordine\">VISUALIZZA REPORT</a></p><hr>";
    $h .= "<p>Riepilogo utenti ordine $id_ordine ($desc) proprio gas <a class=\"awesome blue medium\" href=\"".$RG_addr["gas_ordine_riep_users"]."?id_ordine=$id_ordine\">VISUALIZZA REPORT</a></p><hr>";
    $h .= "<p>Riepilogo articoli ordine $id_ordine ($desc) proprio gas <a class=\"awesome blue medium\" href=\"".$RG_addr["gas_ordine_riepilogo"]."?id_ordine=$id_ordine\">VISUALIZZA REPORT</a></p><hr>";

}
$h .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);