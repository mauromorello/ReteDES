<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("utenti_render.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi � che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1]; 
    $permission =$cookie_read[6];                           
    // e poi scopro di che gas � l'user
    $gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;    
}    




    
(int)$id; 

if(strlen(read_option_text($id_user,"WGO"))==0){
    $data = base64_encode(serialize(array(7,9)));
    write_option_text($id_user,"WGO",$data);
    write_option_integer($id_user,"WG_C",8);    
}

  
         
if($do=="change_widgets"){
//print_r($wdg);

if(in_array(7,$wdg)){

        //Array ( [0] => 2 [1] => 1 [2] => 3 [3] => 7 )
        while(array_search(7,$wdg)>0){

            //echo "POSIZIONE ".array_search(7,$rgw)."<br>";  
            $wdg= moveDown($wdg,array_search(7,$wdg));
           // echo "POSIZIONE NUOVA ".array_search(7,$rgw)."<br>";
        }       
 }else{
     $wdg = array_merge(array(7,9),$wdg);
 }    
//print_r($wdg);
$data = base64_encode(serialize($wdg));
write_option_text($id_user,"WGO",$data);
go("sommario",_USER_ID,"Nuova configurazione salvata");
//Header("Location: ../index.php?q=7"); die();
   
          
}
  
    
    
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    $ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sar� indicata nella barra info 
    $retegas->posizione = "Gestione Widget";
      
          // Dico a retegas come sar� composta la pagina, cio� da che sezioni � composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale � pronto ma � vuoto. Con questa istruzione lo riempio con un elemento
    
   // $retegas->menu_sito[] = menu_visualizza_user($id_user);
    //$retegas->menu_sito[] = menu_gestisci_user($id_user,$id);
    
    // dico a retegas quali sono i fogli di stile che dovr� usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr� caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      //$retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      


      $retegas->content  =    //utenti_form_public_small($id_user,$gas).
                              utenti_gestione_widgets($id_user);
  
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);