<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     pussa_via();
}

if(!isset($id_ordine)){
     pussa_via();
}

if($do=="do_del_all_ord"){
    if(stato_from_id_ord($id_ordine)==2){
        
        //CANCELLO GLI ARTICOLI 
        do_delete_all_ordine_user($id_ordine,_USER_ID);
        
        //CANCELLO LA PRENOTAZIONE SE C'E'
        delete_option_prenotazione_ordine($id_ordine,_USER_ID);
        
        $msg = "OK, cancellàti.";
        go("sommario",_USER_ID,$msg);
    }
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Conferma eliminazione ordine";


//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale =  ordini_menu_all($id_ordine); 


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h .= " <div class=\"rg_widget rg_widget_helper\">
            <form method=\"POST\" class=\"retegas_form\" action=\"\"> 
                <h3>Cliccando su \"Cancella\" tutti gli articoli ordinati da te in questo ordine saranno eliminati.
                </h3>
            <input type=\"hidden\" name=\"do\" value=\"do_del_all_ord\">
            <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">
            
            <input class =\"large green awesome\" style=\"margin:20px;\" type=\"submit\" value=\"Cancella\">
            oppure <a class =\"large red awesome\" style=\"margin:20px;\" href=\"".$RG_addr["sommario"]."\"><strong>Abbandona</strong></a>

            </form>

        </div>";

//Questo è¨ il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).$h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);   