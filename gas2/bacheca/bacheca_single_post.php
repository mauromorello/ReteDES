<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_postare_messaggi)){
     pussa_via();
}

if(!isset($id_messaggio)){
    pussa_via();
}

if($do=="del_msg"){
    if(bacheca_proprietario_messaggio($id_messaggio)<>_USER_ID){
        go("sommario",_USER_ID,"Messaggio non tuo");    
    }else{
        bacheca_delete_messaggio($id_messaggio);
        if(isset($url_back)){
            
        }else{
            go("sommario",_USER_ID,"Messaggio Cancellato");
            die();    
        }
        
        
    }
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Post singolo";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;




if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else if($msg)$r->messaggio=$msg;

//Contenuto

$h = bacheca_render_fullwidth_messaggio($id_messaggio);

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);   
