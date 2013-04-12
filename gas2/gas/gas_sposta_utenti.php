<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//controlla se l'utente ha i permessi necessari
if(!(_USER_PERMISSIONS & perm::puo_gestire_utenti)){
     pussa_via();
}
    
if($do=="do_change"){
   if(isset($gasappartenenza)){
       if((int)($id_utente_target)>0){    
         (int)$gasappartenenza;
         $sql="UPDATE maaking_users SET id_gas='$gasappartenenza' WHERE userid='".$id_utente_target."' LIMIT 1;";
         $res = $db->sql_query($sql);
         if($res == TRUE ){ 
             $messaggio = "L'utente ".fullname_from_id($id_utente_target). " si è convertito al gas $gasappartenenza (".gas_nome($gasappartenenza)."), prima apparteneva al GAS "._USER_ID_GAS." (".gas_nome(_USER_ID_GAS).")";
         }else{
             $messaggio = "L'utente ".fullname_from_id($id_utente_target). " voleva convertirsi al gas $gasappartenenza ma c'è stato un disguido.";
         }
         log_me(0,_USER_ID,"USR","CNG","Utente $id_utente_target cambio gas",0,$messaggio);
         go("gas_form",_USER_ID,$messaggio); 
       }
   } 
    
}
   
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    $ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Spostamento utente";
      
          // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = gas_menu_completo();
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null, menu_lat::user); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      $retegas->java_scripts_bottom_body[] = java_tablesorter($ref_table);
 
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      
      
            // qui ci va la pagina vera e proria  
       $h ='<div class="rg_widget rg_widget_helper">
        <h3>
        Cambia gas ad un utente.
        </h3>
        <form method="POST" action ="" class="retegas_form">
        
        
        <fieldset>
        <label for="gasappartenenza">Utente Target</label>
        <select name= "id_utente_target">';
        $result = $db->sql_query("SELECT * FROM maaking_users WHERE id_gas='"._USER_ID_GAS."' AND userid<>'"._USER_ID."' ORDER BY fullname ASC");
        $totalrows = mysql_num_rows($result);
        $content .= "<option value=\"-1\">Selezionare Utente</option>";
        while ($row = mysql_fetch_array($result)){
                $idgas = $row['userid'];
                $descrizionegas = $row['fullname']." - ".gas_nome($row["id_gas"]);
                if ($idgas==_USER_ID){$agg=" selected ";}else{$agg=null;}
        $h .= "<option value=\"".$idgas ."\" $agg>".$descrizionegas ."  </option>";   
        }//end while
        $h .='</select>
        </fieldset>
        <fieldset>
        <label for="gasappartenenza">Gas di futura appartenenza</label>
        <select name= "gasappartenenza">';
        $result = $db->sql_query("SELECT * FROM retegas_gas ORDER BY id_gas ASC");
        $totalrows = mysql_num_rows($result);
        $content .= "<option value=\"-1\">Selezionare GAS</option>";
        while ($row = mysql_fetch_array($result)){
                $idgas = $row['id_gas'];
                $descrizionegas = $row['descrizione_gas'];
                if ($idgas==_USER_ID_GAS){$agg=" selected ";}else{$agg=null;}
        $h .= "<option value=\"".$idgas ."\" $agg>".$descrizionegas ."  </option>";   
        }//end while
        $h .="</select></fieldset>
        <input type=\"hidden\" name=\"do\" value=\"do_change\">
        <br>
        <center>
        <input type=\"submit\" value=\"Salva\">
        </center>
        </form>
        </div>";;
      $retegas->content  = $h;  
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
