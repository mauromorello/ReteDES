<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_gestire_utenti)){
     pussa_via();
}

$id_utente = CAST_TO_INT(mimmo_decode($id_utente));
if($id_utente<=0){
    pussa_via();
}


if($do=="new_password"){
    $new_password=sanitize($new_password);
    $np = $new_password;
    $new_password=md5($new_password);
    $qry = "UPDATE maaking_users SET password='$new_password' WHERE userid='$id_utente' LIMIT 1";
    log_me(0,_USERID,"NEW","PWD","E' stata cambiata la pwd di $id_utente");
    $db->sql_query($qry);
    
    
    manda_mail(_USER_FULLNAME,
    email_from_id(_USER_ID),
    fullname_from_id($id_utente),
    email_from_id($id_utente),
    "["._SITE_NAME."] Nuova Password !!",
    null,
    "PWD",
    0,
    _USER_ID,
    "<h3>"._USER_FULLNAME.",</h3>
     <p>Ha modificato la tua password di accesso al sito, ora è :</p>
     <p><b>$np</b></p>
     <p style=\"color:red\">ATTENZIONE : Appena accedi al sito ricordati di cambiarla.</p>
     <p>Le istruzioni per farlo le trovi <a href=\"https://sites.google.com/site/retegasapwiki/come-fare-per/gestire-i-propri-dati/modificare-la-propria-password\">qua</a></p>
     <p>"._USER_FULLNAME."<br>(Utente con i permessi richiesti per gestire gli altri utenti del tuo GAS.)</p>");

    go("sommario",_USER_ID,"Password impostata, una mail è stata inviata all'utente per avvisarlo.");
}
if($do=="new_username"){
    $new_username=sanitize($new_username);
    $qry = "UPDATE maaking_users SET username='$new_username' WHERE userid='$id_utente' LIMIT 1";
    log_me(0,_USERID,"NEW","USN","E' stata cambiato l'username di $id_utente : $new_username");
    $db->sql_query($qry);
    
    
    manda_mail(_USER_FULLNAME,
    email_from_id(_USER_ID),
    fullname_from_id($id_utente),
    email_from_id($id_utente),
    "["._SITE_NAME."] Nuovo Username !!",
    null,
    "USN.",
    0,
    _USER_ID,
    "<h3>"._USER_FULLNAME.",</h3>
     <p>Ha modificato il tuo username di accesso al sito, ora è :</p>
     <p><b>$new_username</b></p>
     <p>"._USER_FULLNAME."<br>(Utente con i permessi richiesti per gestire gli altri utenti del tuo GAS.)</p>");

    go("sommario",_USER_ID,"Username impostato, una mail è stata inviata all'utente per avvisarlo.");
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Impostazione nuova password e Username";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale[] = menu_visualizza_user(_USER_ID);
$r->menu_orizzontale[] = menu_gestisci_user(_USER_ID,$id_utente);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Imposta nuovi dati di accesso per ".fullname_from_id($id_utente)."</h3>";
$h .= "<form class=\"retegas_form\" method=\"POST\" action=\"\">";
$h .= '<div>
        <h4>1</h4>
        <label for="password">Imposta qua la nuova password per '.fullname_from_id($id_utente).'</label>
        <input id="password" type="text" name="new_password" value="'.$new_password.'" size="20"></input>
        <h5 title="Occhio alle maiuscole e minuscole">Inf.</h5>
        </div>';
$h .= "<input type=\"submit\" value=\"Fatto !\">";
$h .= "<input type=\"hidden\" name=\"do\" value=\"new_password\">";
$h .= "<input type=\"hidden\" name=\"id_utente\" value=\"".mimmo_encode($id_utente)."\">";
$h .= "";
$h .= "</form>";
$h .= "<form class=\"retegas_form\" method=\"POST\" action=\"\">";
$h .= '<div>
        <h4>2</h4>
        <label for="username">.... oppure imposta qua il nuovo username per '.fullname_from_id($id_utente).'</label>
        <input id="username" type="text" name="new_username" value="'.$new_username.'" size="20"></input>
        <h5 title="Occhio alle maiuscole e minuscole">Inf.</h5>
        </div>';
$h .= "<input type=\"submit\" value=\"Fatto !\">";
$h .= "<input type=\"hidden\" name=\"do\" value=\"new_username\">";
$h .= "<input type=\"hidden\" name=\"id_utente\" value=\"".mimmo_encode($id_utente)."\">";
$h .= "";
$h .= "</form>";
$h .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);