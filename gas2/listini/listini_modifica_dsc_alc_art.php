<?php


// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("listini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    exit;     
}    
   // Controllo che il listino sia settato e mio
if(isset($id_listini)){
    if(listino_proprietario($id_listini)<>_USER_ID){
        go("sommario",_USER_ID,"Non puoi operare con un listino non tuo");
    }        
}else{
    pussa_via();
    exit;
}
   
   if($do=="do_mod"){
          $ok=0; $err_db =0;
          while (list ($key,$val) = @each ($box_id)) {
          //echo "Key $key Val $val Box_id $box_id[$key] Box_dsc = $box_dsc[$key], box_old= $box_old[$key]; <br>";
          if(sanitize($box_dsc[$key])<>sanitize($box_old[$key])){
            if(articoli_in_ordine($val)==0){  

                $sql = "UPDATE retegas_articoli SET descrizione_articoli='".sanitize($box_dsc[$key])."'  WHERE id_articoli='$val' LIMIT 1;";      
                //echo $sql."<br>";
                $res = $db->sql_query($sql);
                if(!is_null($res)){
                      $ok++;
                }else{
                  $err_db++;
                  $err_frase_db = "<strong>$err_db</strong> Errori del database.";
                }

            } //SE non è mai stato usato
          } // Se è diverso da quello vecchio      
          }  
          $msg = "Listino $id_listini, Modificata la descrizione di <strong>$ok</strong> articoli;<br>
                  $err_frase<br>
                  $err_frase_db";
          log_me(0,_USER_ID,"LIS","MOD",$msg);
        
        
    }
    
   
   
    $retegas = new sito;
    
    


    $retegas->posizione = "Modifica descrizione alcuni articoli";
    //$retegas->help_page = "https://sites.google.com/site/retegasapwiki/i-menu-del-sito-retegas-ap/pagine-del-sito/i-miei-listini";     

    $retegas->sezioni = $retegas->html_standard;
    $retegas->menu_sito[]=listini_menu($user,$id); 

    $retegas->css = $retegas->css_standard;

    $retegas->java_headers = array("rg","table_sorter");  // editor di testo

      
      $retegas->java_scripts_header[] = java_accordion(null,1); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      $retegas->java_scripts_bottom_body[] = java_tablesorter("table_ref");
      
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
            // qui ci va la pagina vera e proria  
      $retegas->content  =  listini_form($id_listini).listini_render_mod_dsc_articoli($id_listini);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
      
?>