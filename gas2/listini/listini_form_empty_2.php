<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("listini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    


//CONTROLLO IL PROPRIETARIO DEL LISTINO    
    if(listino_proprietario($id_listino)<>_USER_ID){
        $msg="Questo listino non è stato inserito da te, oppure è già stato svuotato.<br> Impossibile svuotarlo";
        go("sommario",_USER_ID,$msg);   
        exit;
    }
//CONTROLLO CHE SUL LISTINO NON SIANO STATI FATTI ORDINI
if(quanti_ordini_per_questo_listino($id_listino)<>0){
            $msg="Questo listino è già stato usato in un ordine.<br> Impossibile svuotarlo";
            go("sommario",_USER_ID,$msg);   
            exit;
}

if($do=="del"){
    $sql =  $db->sql_query("delete from retegas_articoli where retegas_articoli.id_listini='$id_listino';");   
                
        $msg = "Svuotamento Riuscito";
        log_me("",$id_user,"LIS","SVU","Svuotato il listino $id dal suo proprietario",0,$sql);    
        go("sommario",_USER_ID,$msg);    
        exit;
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Svuotamento listino";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = listini_menu_completo($id_listino);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "<div class=\"rg_widget rg_widget_helper\">";
$h.= "<h3>Listino $id_listino (".listino_nome($id_listino)." - ".ditta_nome_from_listino($id_listino).")</h3>";
$h .= listini_form($id_listino);
$h.= " 
                    <div class=\"ui-state-error ui-corner-all padding_6px\" style=\"margin-bottom:20px\">
                    <span class=\"ui-icon ui-icon-trash\" style=\"float:left; margin:0 7px 16px 0;\"></span>
                    Stai cancellare tutti gli articoli di questo listino : sei sicuro ?
                    <a href=\"".$RG_addr["listini_form_empty"]."?id_listino=$id_listino&do=del\" class=\"awesome red medium\">SI</a> 
                    <a href=\"".$RG_addr["sommario"]."\" class=\"awesome green medium\">NO</a>
                    </div>
                    <div class=\"ui-widget-header ui-corner-tl ui-corner-tr action-icon padding-6px\">$titolo_tabella</div> 
                    <table>
                    ";

$h .= "";
$h .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);