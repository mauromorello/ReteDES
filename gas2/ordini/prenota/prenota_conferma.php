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

if($do=="conferma_prenotazione"){
    

    
    if(_GAS_CASSA_CHECK_MIN_LEVEL){
            
        $vo = valore_totale_mio_ordine($id_ordine,_USER_ID);
        $vo =  round((($vo/100)* _GAS_COPERTURA_CASSA ) + $vo);
        $vc = cassa_utente_tutti_movimenti(_USER_ID);
        
        
        if(($vc-$vo)< _GAS_CASSA_MIN_LEVEL){
            $msg = "Non hai abbastanza crediti per ordinare :( ";
            log_me($id_ordine,_USER_ID,"ORD","PRE","Prenotazione NON confermata",0,$msg);
            go("ordini_form_new",_USER_ID,$msg,"?id_ordine=$id_ordine");
            die(); 
        }    
    }
        
        cassa_update_ordine_utente($id_ordine,_USER_ID);             
        delete_option_prenotazione_ordine($id_ordine,_USER_ID);
        $msg = "Prenotazione confermata, i crediti sono stati scalati.";
        log_me($id_ordine,_USER_ID,"ORD","PRE","Prenotazione confermata",0,"");
        go("ordini_form_new",_USER_ID,$msg,"?id_ordine=$id_ordine");
    
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Conferma prenotazione ordine";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);  



if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = schedina_ordine($id_ordine).
"
<div class=\"rg_widget rg_widget_helper\">
<h3>Conferma la prenotazione</h3>
<p>Cliccando qua sotto confermi la tua prenotazione, ed il valore dell'ordine sarà detratto dal tuo credito.</p>
<a class=\"awesome green medium\" href=\"?id_ordine=$id_ordine&do=conferma_prenotazione\">CONFERMA LA PRENOTAZIONE !</a>
</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);