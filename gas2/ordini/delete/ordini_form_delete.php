<?php

//*********************************************

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id;

// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
    
                                        
    // e poi scopro di che gas è l'user
    $gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;     
}    

    



    // se l'id dell'ordine non esiste allora esco
    if(ordine_inesistente($id)){
            pussa_via();         
            exit;       
        }

    if (id_referente_ordine_globale($id)<>$id_user){
        header("Location: ".$RG_addr["ordini_form"]."?id_ordine=$id&msg=5");
        exit;    
    }

    if (dettagli_ordine($id)>0){
        header("Location: ".$RG_addr["ordini_form"]."?id_ordine=$id&msg=6"); 
        exit;   
    }
    
    //-------------------------------------------------DELETE
    if($do=="del"){
            

        //tabella ordini
        $sql = $db->sql_query("delete from  retegas_ordini where retegas_ordini.id_ordini='$id' LIMIT 1;");
        //tabella referenze
        $sql = $db->sql_query("delete from  retegas_referenze where retegas_referenze.id_ordine_referenze='$id';");
            
        $msg = "Eliminazione riuscita";    
        log_me($id,$id_user,"ORD","MOD","Eliminazione tutti articoli dall'ordine $id",0,"");
    
        header("Location: ".$RG_addr["ordini_aperti"]."?msg=1"); 
        exit;     
    }
      
      
      
    //-------------------------------------------------------
       
    // ISTANZIO un nuovo oggetto "retegas"
    // Prenderà come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel menù verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sarà indicata nella barra info 
    $retegas->posizione = "Elimina Ordine";
      
    // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = ordini_menu_all($id_ordine); 
 
    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
      
    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array("rg");  
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null,3); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      
      
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
          // qui ci va la pagina vera e proria  
      $retegas->content  =  schedina_ordine($id).
                            "<div class=\"ui-state-error ui-corner-all padding_6px\" style=\"margin-bottom:20px\">
                                <span class=\"ui-icon ui-icon-trash\" style=\"float:left; margin:0 7px 16px 0;\"></span>
                                Stai per cancellare i dati di questa scheda : sei sicuro ?
                                <a href=\"".$RG_addr["delete_ordine"]."?id=$id&do=del\" class=\"medium red awesome\">SI</a> 
                                <a href=\"".$RG_addr["ordini_form"]."?id=$id\" class=\"medium green awesome\">NO</a>
                                </div>";
      
      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);    
      
      


?>