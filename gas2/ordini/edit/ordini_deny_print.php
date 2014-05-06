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

if(!posso_gestire_ordine_full($id_ordine,_USER_ID)){
    go("ordini_form",_USER_ID,"Questa operazione ti è preclusa.","?id_ordine=$id_ordine");
    exit;
}

if(stato_from_id_ord($id_ordine)<>3){
       go("ordini_form",_USER_ID,"Questa ordine non è validabile.","?id_ordine=$id_ordine");

    die();
}

if ($do=="do_deny_print"){
    $sql = "UPDATE `my_retegas`.`retegas_ordini` SET `is_printable` = '0' WHERE `retegas_ordini`.`id_ordini` = '$id_ordine' LIMIT 1;";
    $db->sql_query($sql);
    log_me($id_ordine,_USER_ID,"ORD","MOD","Riesumato ordine $id_ordine, ",0);


    $msg = "Ordine Riesumato con successo<br>".$msg_cassa;
    go("ordini_form_new",_USER_ID,$msg,"?id_ordine=$id_ordine");
    die();


}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Conferma convalida ordine";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h .= " <div class=\"rg_widget rg_widget_helper\">
        <div>
        <h3>ATTENZIONE</h3>
        </div>
            <form method=\"POST\" class=\"retegas_form\" action=\"\">
                <h3>Cliccando su \"riesuma\" Questo ordine tornerà nello stato CHIUSO MA NON CONVALIDATO;<br>
                Questa operazione è da eseguire SOLO in caso di reale necessità, in quanto gli utenti dell'ordine hanno già potuto controllare i loro quantitativi e i loro importi in maniera UFFICIALE.<br>
                La riesumazione non influisce sugli importi eventualmente già scalati dalla cassa degli utenti.
                </h3>
            <input type=\"hidden\" name=\"do\" value=\"do_deny_print\">
            <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">
            <input class =\"large green awesome\" style=\"margin:20px;\" type=\"submit\" value=\"Riesuma\">
            oppure <a class =\"large red awesome\" style=\"margin:20px;\" href=\"".$RG_addr["ordini_form_new"]."?id_ordine=$id_ordine\"><strong>Abbandona</strong></a>
            </form>
             <br>


        </div>";

//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).$h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);