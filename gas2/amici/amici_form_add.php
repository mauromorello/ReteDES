<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("amici_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
    $permission = $cookie_read[6];
                               
    // e poi scopro di che gas è l'user
    $gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;    
}    

    
   CAST_TO_INT($ida);
   
   
     if(!($permission & perm::puo_avere_amici)){
            $q = "not_allowed";
            include ("../index.php");
            exit;            
    }
   
   if ($do=="add"){
      //echo "EVALUTATE <br>";
      
      if (empty($nome)){$msg.="Devi almeno inserire il nome<br>";$e_empty++;};

      $msg.="<br>Verifica i dati immessi e riprova";
      
      
      $e_total = $e_empty + $e_logical + $e_numerical;
      
      if($e_total==0){
        //echo "ZERO ERRORI !!!";
        $nome = sanitize($nome);
        $indirizzo = sanitize($indirizzo);
        $telefono = sanitize($telefono);
        
        // QUERY INSERT
        $my_query="INSERT INTO retegas_amici 
                (id_referente,
                 nome,
                 indirizzo,
                 telefono,
                 note,
                 is_visible,
                 status) VALUES (
                 '$id_user',
                 '$nome',
                 '$indirizzo',
                 '$telefono',
                 '$note_amici',
                 '1',
                 1);";
              
        $result = $db->sql_query($sql);
        
        //INSERT BEGIN ---------------------------------------------------------
         $result = $db->sql_query($my_query);
         if (is_null($result)){
            $msg = "Errore nel salvataggio dei dati";
            include ("../index.php");
            exit;  
        }else{
            $msg = "..se sposti un po' la seggiola, stai comodo anche tu !! Gli amici a questo servono, a stare in compagniaaa, sorridi al nuovo ospite, non farlo andare 
            <a href=\"http://www.youtube.com/watch?v=DuK23O38REE\">viaaaaa</a> dividi il companatico, raddoppia l'allegriaaaaa ";
            include("amici_table.php");
            exit;  
        };
        
        //INSERT END --------------------------------------------------------- 
        
        
      }
         
          
      } // se non ci sono errori
   
   
   
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    $ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sarà indicata nella barra info 
    $retegas->posizione = "Modifica scheda amico";
      
          // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito[] = menu_nuovo_amico($id_user);
    $retegas->menu_sito[] = menu_visualizza_amici($id_user);
    $retegas->menu_sito[] = menu_operazioni_amici($id_user,$ida);
 
    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array("rg","ckeditor");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      //$retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
      // qui ci va la pagina vera e proria  
      $retegas->content  =  amici_render_add($ida);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);