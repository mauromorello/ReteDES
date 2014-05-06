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

if(!isset($id_ordine)){
     pussa_via();
}

if(!posso_gestire_ordine_full($id_ordine,_USER_ID)){
        go("ordini_form",_USER_ID,"Questa operazione ti è preclusa.","?id_ordine=$id_ordine");
        exit;
    }


    
if(ordine_inesistente($id_ordine)){
        go("sommario",_USER_ID,"Questo ordine non esiste");
        exit;
}
    
    $res = $db->sql_query("SELECT * FROM retegas_ordini WHERE id_ordini='$id_ordine' LIMIT 1");
    $old_row = $db->sql_fetchrow($res);
    $old_data_apertura = conv_datetime_from_db($old_row["data_apertura"]); 
    $old_data_chiusura = conv_datetime_from_db($old_row["data_chiusura"]);
    
    if($do=="mod"){
            
            $msg =     ordine_render_do_edit_date($id_ordine);
            if($msg=="OK"){$msg="Dati modificati correttamente";
            go("sommario",_USER_ID,$msg);
            die();
                          
            }else{
                $msg="Dati NON modificati correttamente";
            }
                               
                           
    }
    
    
    $data_chiusura = $old_data_chiusura;
    $data_apertura = $old_data_apertura;

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


//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine)
                .ordine_render_edit_date($id_ordine);

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);