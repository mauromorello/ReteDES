<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("amministra_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
                               
    // e poi scopro di che gas ? l'user
    $gas = id_gas_user($id_user);
    
}else{
     
}    

if(user_level($id_user)<5){
        pussa_via();
    exit;    
}

(int)$id_option;      

//Echo "Do = $do id = $id_option, url : $url";

if ($do=="mod"){
      //echo "EVALUTATE <br>";
      
     
      
      $id_owner      =           CAST_TO_INT($id_owner);
      $chiave        =           strip_tags(sanitize($chiave));
      $valore_text   =           strip_tags(sanitize($valore_text));
      $valore_int    =           CAST_TO_INT($website);
      $valore_real   =           CAST_TO_FLOAT($valore_float);
      $note_option   =           sanitize($note_option);
      
      if (empty($chiave)){$msg.="Devi almeno inserire il nome della chiave<br>";$e_empty++;};
 //     if (empty($indirizzo)){$msg.="Se non conosci l'indirizzo almeno inserisci la citt?<br>";$e_empty++;};
 //     if (empty($mail_ditte)){$mail_ditte = id_user_mail($id_user);};
 //     if (empty($website)){$website = "NON DEFINITO";};
      
   
      $msg.="<br>Verifica i dati immessi e riprova";
      
      
      $e_total = $e_empty + $e_logical + $e_numerical;
      
      if($e_total==0){

        $sql = "UPDATE retegas_options 
              SET 
              retegas_options.id_user = '$id_owner',
              retegas_options.chiave = '$chiave',
              retegas_options.valore_text = '$valore_text',
              retegas_options.valore_int = '$valore_int',
              retegas_options.valore_real = '$valore_real',
              retegas_options.note_1 = '$note_option' 
              WHERE 
              retegas_options.id_option = $id_option LIMIT 1;";
              
        $result = $db->sql_query($sql);
        //echo $result;
        //EDIT BEGIN ---------------------------------------------------------
         
         if (is_null($result)){
            $msg = "Errore nella modifica dei dati";
            include ("../index.php");
            exit;  
        }else{
            $msg = "Dati modificati";
            include("amministra_options_table.php");
            exit;  
        };
        
        //EDIT END --------------------------------------------------------- 
        
        
        
        include("amministra_options_table.php");
        exit; 
          
      } // se non ci sono errori
      
}

if ($do=="del"){

    $url = urldecode($url);
        
    
        $sql = "DELETE FROM retegas_options 
              WHERE 
              retegas_options.id_option = $id_option LIMIT 1;";
              
        $result = $db->sql_query($sql);
        
        header("Location: $url");
        exit;
}

   
   
   
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    //$ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Modifica Opzioni sito";
      
          // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito[] = amministra_menu_users($user);
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg","ckeditor");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null, menu_lat::user); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      //$retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
            // qui ci va la pagina vera e proria  
      $retegas->content  =  amministra_opzioni_edit($id_option);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
     
?>