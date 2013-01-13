<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("utenti_render.php");


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

if($do=="change_user_options"){
      //controllo che sia un utente del mio gas
                        
            
            
                //echo "PCO : ".$p_c_o."<br>";
                //echo "PPO : ".$p_p_o."<br>";
                //echo "PCG : ".$p_c_g."<br>";
                //echo "PCD : ".$p_c_d."<br>";
                //echo "PCL : ".$p_c_l."<br>";
                //echo "SSI : ".$s_s_i."<br>";
                
                $UP =   (int)$v_m_g |
                        (int)$v_t_r |
                        (int)$a_n_o |
                        (int)$a_s_3 |
                        (int)$a_c_t |
                        (int)$s_s_i |
                        (int)$s_s_h ; 
                
                
                //echo "UP : ".$UP."<br>";
              
                $db->sql_query("UPDATE maaking_users SET user_site_option = '$UP' WHERE userid='$id_utente_opzioni'");
    
                $msg="Opzioni modificate;<br>Alcune opzioni si attivano solo al prossimo login;<br>Altre opzioni sono sperimentali e pertanto magari non funzionanti su alcune pagine.";
                unset($do);
                $id=$id_utente_opzioni;
                
                include ("utenti_form_mia.php");
                exit;
                
            
      
      
      //cambio i permessi;      
          
          
      }

    
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    $ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sarà indicata nella barra info 
    $retegas->posizione = "Opzioni sito";
      
          // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
    
    $retegas->menu_sito[] = menu_visualizza_user($id_user);
    $retegas->menu_sito[] = menu_gestisci_user($id_user);
    
    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_header[] = java_head_jquery_iphone_switch();       
      //$retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
 
      if(_USER_OPT_SEND_MAIL=="SI"){$opt_mail="on";}else{$opt_mail="off";}
      if(_USER_OPT_NO_HEADER=="SI"){$opt_snh="on";}else{$opt_snh="off";}
      
      
      //RICORDARSI DI RIATTIVARE SITO SENZA HEADER 
      $retegas->java_scripts_bottom_body[] = "<script type=\"text/javascript\">
                                                $('#opt_mail').iphoneSwitch(\"$opt_mail\",
                                                function() {
                                                $('#ajax_opt_mail').load('".$RG_addr["ajax_user_opt"]."',{key_opt: \"_USER_OPT_SEND_MAIL\", key_val : \"SI\"});
                                                },
                                                function() {
                                                $('#ajax_opt_mail').load('".$RG_addr["ajax_user_opt"]."',{key_opt: \"_USER_OPT_SEND_MAIL\", key_val : \"NO\"});
                                                },
                                                {
                                                switch_on_container_path: 'iphone_switch_container_off.png'
                                                });
                                                
                                                $('#opt_snh').iphoneSwitch(\"$opt_snh\",
                                                function() {
                                                $('#ajax_opt_snh').load('".$RG_addr["ajax_user_opt"]."',{key_opt: \"OPT_NOHEADER\", key_val : \"SI\"});
                                                },
                                                function() {
                                                $('#ajax_opt_snh').load('".$RG_addr["ajax_user_opt"]."',{key_opt: \"OPT_NOHEADER\", key_val : \"NO\"});
                                                },
                                                {
                                                switch_on_container_path: 'iphone_switch_container_off.png'
                                                });
                                                
                                                </script>
                                                ";
 
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
      // qui ci va la pagina vera e proria  
      $retegas->content  =      utenti_form_public_small($id_user,$gas).
                                utenti_option_sito($id_user);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
    
?>