<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
 include_once ("utenti_render.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_gestire_utenti)){
     pussa_via();
}

$id_utente = mimmo_decode($id_utente);

if($do=="save_note_suspended"){
    
    
    $note_suspended = sanitize($note_suspended);
    write_option_text($id_utente,"_NOTE_SUSPENDED",$note_suspended);

    sleep(1);
    $msg= "Messaggio che vedrà ".fullname_from_id($id_utente).":<br> $note_suspended";
    go("gas_users",_USER_ID,$msg);
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Nota di sospensione account";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale[] = menu_visualizza_user(_USER_ID);
$r->menu_orizzontale[] = menu_gestisci_user(_USER_ID,$id_utente);
$r->menu_orizzontale[] = menu_gestisci_user_cassa(_USER_ID,$id_utente); 


$h .='<div class="rg_widget rg_widget_helper">';
$h .='<h3>Inserisci un messaggio che comparirà all\'utente quando prova a connettersi</h3>';
//DECIMALI
$h .= "
        <form method=\"post\" action=\"\" class=\"retegas_form\">
        
        <label for=\"note_suspended\">Comunica a ".fullname_from_id($id_utente).":</label>
        <input type=\"text\" id=\"note_suspended\" name=\"note_suspended\" value=\"".read_option_text($id_utente,"_NOTE_SUSPENDED")."\" size=\"50\"></input>
        <input type=\"hidden\" name=\"do\" value=\"save_note_suspended\"></inupt>
        <input type=\"hidden\" name=\"id_utente\" value=\"".mimmo_encode($id_utente)."\"></inupt>
        <input type=\"submit\" value=\"salva\"></input>
        <br>
        <div class=\"ui-state-error ui-corner-all padding_6px\">
        NB: Questo messaggio comparirà all'utente quando cercherà di effettuare un login.</div>
        </form>
      ";
$h .='</div>';

//Questo è¨ il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>