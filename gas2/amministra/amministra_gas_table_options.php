<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//




// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    exit;     
}    

if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
    pussa_via();
    exit;     
}
    
     
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Opzioni settate dai GAS";
      
    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = "";
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
      
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // ordinatore di tabelle
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[]=java_accordion("#accordion",2); // laterale    
      $retegas->java_scripts_header[]=java_tablesorter("gas_table");
      $retegas->java_scripts_header[]=java_superfish();
          // orizzontale                         

      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
      
            $h .= " <div class=\"rg_widget rg_widget_helper\">
                <div style=\"margin-bottom:16px;\">Gas aderenti a "._SITE_NAME."</div>
                <table id=\"gas_table\">

                <thead>     

                <tr> 
                <th>Descrizione</th>
                
                <th>GG INA</th> 
                <th>MSG INA</th> 
                <th>CASSA</th> 
                <th>PART</th>
                <th>COND</th>
                </tr>

                 <thead>

                 <tbody>";

       //$o1 =   $db->sql_query("SELECT id_gas FROM maaking_users WHERE userid = ". $id_user );

       $result = $db->sql_query("SELECT * FROM retegas_gas;");

         

       //$outp = mysql_fetch_row($o1);  

       $riga=0;  

         while ($row = $db->sql_fetchrow($result)){

         $riga++;

            $d1 = "id_gas";

         

              $idgas = $row["$d1"];
              $descrizionegas = $row['descrizione_gas'];
              $gg_ina = read_option_gas_text($idgas,"_GAS_SITE_INATTIVITA");
              $msg_ina = read_option_gas_text($idgas,"_GAS_SITE_FRASE_INATTIVITA");
              $ha_cassa = read_option_gas_text($idgas,"_GAS_USA_CASSA");
              $puo_part = read_option_gas_text_new($idgas,"_GAS_PUO_PART_ORD_EST");
              $puo_cond = read_option_gas_text_new($idgas,"_GAS_PUO_COND_ORD_EST");
              
                $h.= "<tr>";    
                $h.= "<td>$descrizionegas</td>";
                $h.="<td>$gg_ina</td> 
                     <td>$msg_ina</td>
                     <td>$ha_cassa</td>
                     <td>$puo_part</td>
                     <td>$puo_cond</td>  
                     </tr>";

            }//end while



         $h.= "</tbody></table>";
      
      
      
      
      
      
      
      
      
      
      
      
      
          // qui ci va la pagina vera e proria  
      $retegas->content  =  $h;

      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);      
      
        