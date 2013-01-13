<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    


if(!(_USER_PERMISSIONS & perm::puo_gestire_la_cassa)){
        unset($do);
        go("sommario",_USER_ID,_OP_NOT_PERMITTED);
    }

if($do=="del"){
    $sql = "DELETE FROM retegas_cassa_utenti WHERE id_gas='"._USER_ID_GAS."';";
    $h .="Query : ".$sql."<br>";
    $res = $db->sql_query($sql);
    $n_r = $db->sql_affectedrows();
    log_me(0,_USER_ID,"CAS","ERA","Cancellati tutti $n_r movimenti della cassa Gas",$n_r,$sql);
    $h.="<h3>Cancellati $n_r movimenti.</h3>";
}    
    

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Cancella tutti i movimenti cassa del tuo gas.";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale[] = gas_menu_gestisci_cassa();





if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Creo la pagina dell'aggiunta

$h .="<div class=\"rg_widget rg_widget_helper\">
        <h3>CANCELLA I MOVIMENTI DEL ".gas_nome(_USER_ID_GAS).";<br>
        <h2>OPERAZIONE IRREVERSIBILE</h2>
        </h3>
        
        <h4>
            sei sicuro ? <a class=\"awesome red medium\" href=\"?do=del\">SI</a>
        </h4>
        </div>";

//Questo è¨ il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);