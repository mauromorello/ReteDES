<?php
  
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("listini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    
        $id_listino = CAST_TO_INT($id_listino);
        
        if($id_listino>0){
            $id_ditta = ditta_id_from_listino($id_listino);
            
            
            if(listino_proprietario($id_listino)<>_USER_ID){
                $msg="Questo listino non � stato inserito da te, oppure � gi� stato cancellato.<br> Impossibile eliminarlo";
                go("form_ditta",_USER_ID,$msg,"?id_ditta=$id_ditta");
                die();
            }
            if(articoli_n_in_listino($id_listino)<>0){
                $msg="Questo listino non � vuoto.<br> Impossibile eliminarlo";
                go("form_ditta",_USER_ID,$msg,"?id_ditta=$id_ditta");
                die();
            }
	      //-------------------------------------------------DELETE
		    if($do=="del"){

                
		    $sql =  "delete from  retegas_listini where retegas_listini.id_listini=$id_listino LIMIT 1;";    
		    $res = $db->sql_query($sql);
        		    
		    $msg = "Eliminazione riuscita";	
            go("form_ditta",_USER_ID,$msg,"?id_ditta=$id_ditta");
            die();
		    }
	  
        }
	  
	  //-------------------------------------------------------
	  
	  
	  

   




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Cancellazione listino";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h .= listini_form($id_listino,true);
$h .="<div class=\"rg_widget rg_widget_helper\">
        <h3>CANCELLA questo listino</h3>
        
        <h4>
            sei sicuro ? <a class=\"awesome red medium\" href=\"?do=del&id_listino=$id_listino\">SI</a>
        </h4>
        </div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r) 
?> 