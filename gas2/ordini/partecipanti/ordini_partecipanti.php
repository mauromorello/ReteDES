<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");

(int)$id_ordine;

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    exit;     
}    
   // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito;

    $retegas->posizione = "Partecipanti";


    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    
    // Menu specifico per l'output  

   
    $retegas->menu_sito = ordini_menu_all($id_ordine);  
    
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
    //$retegas->css[]  = "datetimepicker"; 
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $ref_table = "lista_utenti";
      
      $retegas->java_scripts_header[] = java_accordion(null,1); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      $retegas->java_scripts_bottom_body[]=  '<script type="text/javascript">                
                        $(document).ready(function() 
                            {
                                $("#lista_utenti").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\'],
                                                        cancelSelection : true,
                                                        dateFormat : \'ddmmyyyy\',                                                               
                                                        }); 
                                } 
                            );
</script>';
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
            // qui ci va la pagina vera e proria  
      $retegas->content  =  schedina_ordine($id_ordine).
                            ordine_render_partecipazione($ref_table,$id_ordine);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas); 