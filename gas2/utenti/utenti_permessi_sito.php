<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("utenti_render.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
    $permission =$cookie_read[6];                           
    // e poi scopro di che gas ? l'user
    $gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;    
}    

    if(is_null($id) | $id==''){
        $id = ($id_user);
    }else{
        $id = mimmo_decode($id);
    }

        $allow = false;
        // SE SONO UN USER CON PERMESSI PER FARLO APRO LA TABELLA MODIFICA PERMESSI
        if(id_gas_user($id_user)==$gas){
            if((int)$permission & (int)perm::puo_mod_perm_user_gas){
                $allow=true;
            }
         } 
       //Se sono un gestore di retegas allora Apro la tabella modifica permessi completi
       if(leggi_permessi_utente($id_user) & perm::puo_gestire_retegas){
                $allow=true;
       }
       
 
    if(!$allow){
        pussa_via();
        exit;    
    }
    
    
(int)$id;   
          
if($do=="change_user_permissions"){

            
                //echo "PCO : ".$p_c_o."<br>";
                //echo "PPO : ".$p_p_o."<br>";
                //echo "PCG : ".$p_c_g."<br>";
                //echo "PCD : ".$p_c_d."<br>";
                //echo "PCL : ".$p_c_l."<br>";
                
                
                $UP =   (int)$p_c_o | 
                        (int)$p_p_o | 
                        (int)$p_c_g | 
                        (int)$p_c_d | 
                        (int)$p_c_l | 
                        (int)$p_m_p | 
                        (int)$p_a_a | 
                        (int)$p_s_b |
                        (int)$p_e_m |
                        (int)$i_n_u |
                        (int)$p_v_t_o |
                        (int)$p_g_c|
                        (int)$p_o_c|
                        (int)$p_v_rg|
                        (int)$p_g_rg; 
                
                
                //echo "UP : ".$UP."<br>";
              
                $db->sql_query("UPDATE maaking_users SET user_permission = '$UP' WHERE userid='$id_utente_permessi'");
    
                $msg="Permessi modificati";
                unset($do);
                $id=$id_utente_permessi;
        
      
      
      //cambio i permessi;      
          
          
      }
      
if($do=="change_user_permissions_zeus"){
      
          
          //controllo di essere zeus
            if(!leggi_permessi_utente($id_user) & perm::puo_gestire_retegas){
                go("sommario",$id_user,"Non puoi fare cose simili"); 
            }
            
            //controllo di avere i privilegi
            
                //echo "PCO : ".$p_c_o."<br>";
                //echo "PPO : ".$p_p_o."<br>";
                //echo "PCG : ".$p_c_g."<br>";
                //echo "PCD : ".$p_c_d."<br>";
                //echo "PCL : ".$p_c_l."<br>";
                
                
                $UP =   (int)$p_c_o | 
                        (int)$p_p_o | 
                        (int)$p_c_g | 
                        (int)$p_c_d | 
                        (int)$p_c_l | 
                        (int)$p_m_p | 
                        (int)$p_a_a | 
                        (int)$p_s_b | 
                        (int)$p_e_m |
                        (int)$i_n_u |
                        (int)$p_v_t_o |
                        (int)$p_g_c|
                        (int)$p_o_c|
                        (int)$p_v_rg|
                        (int)$p_g_rg;   
                
                //echo "UP : ".$UP."<br>";
                
                $db->sql_query("UPDATE maaking_users SET user_permission = '$UP' WHERE userid='$id_utente_permessi'");
    
                $msg="Permessi modificati da un super super super user";
                unset($do);
                $id=$id_utente_permessi;

      }
    
    
    
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    $ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Permessi Utente";
      
          // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    
    $retegas->menu_sito[] = menu_visualizza_user($id_user);
    $retegas->menu_sito[] = menu_gestisci_user($id_user,$id);
    
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      //$retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
            if((leggi_permessi_utente($id_user) & perm::puo_gestire_retegas) OR (user_level($id_user)==5)){
                $retegas->content  =    utenti_form_public_small($id,$gas).
                                        utenti_permessi_sito_zeus($id);
            }else{
                $retegas->content  =    utenti_form_public_small($id,$gas).
                                        utenti_permessi_sito($id);
            }   
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
    
?>