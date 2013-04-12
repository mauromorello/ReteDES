<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("listini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
   if(listino_is_privato($id_listino)){
        if(id_gas_user(listino_proprietario($id_listino))<>_USER_ID_GAS){
          go("sommario",_USER_ID,"Listino privato");
       }
   }

if($do=="upload"){
            if($tipo_file=="CSV"){  
                $msg = do_upload($fname,$id_listino);
                go("listini_form_2",_USER_ID,$msg,"?id_listino=$id_listino");
                exit;
            }
      
            if($tipo_file=="XLS"){  
                $msg = do_upload_xls($fname,$id_listino);
                go("listini_form_2",_USER_ID,$msg,"?id_listino=$id_listino");
                exit;
            }
            
            if($tipo_file=="GOO"){  
                
                //http://retegas.altervista.org/gas2/listini/listini_form.php?do=upload&fname=https%3A%2F%2Fdocs.google.com%2Fspreadsheet%2Fpub%3Fkey%3D0An0LoUdzBJs0dDZ4UENCSVpvZ21yWVhaUHRla1JaVkE%26output%3Dcsv&listino=67&tipo_file=GOO&quanti_caricarne=9
                $msg = do_upload_goo(urldecode($fname),$listino,$quanti_caricarne);
                unset($do);
                $id=$listino;
                include("listini_form.php");
                exit;
            }
            
      }   


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Scheda listino";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = listini_menu_completo($id_listino);;

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "Listini form 2";

//Questo ?? il contenuto della pagina
$h = listini_form($id_listino,false).
        "<div id=\"container_articolo\"></div>"   
        .listini_articoli_table("output_1",$id_listino);
        
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);