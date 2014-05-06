<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
	pussa_via();
	exit;     
}    

	//COntrollo permessi

		//COntrollo permessi

	if(!posso_gestire_ordine_full($id_ordine,_USER_ID)){
        go("ordini_form",_USER_ID,"Questa operazione ti è preclusa.","?id_ordine=$id_ordine");
        exit;
    }
	
	if(ordine_inesistente($id_ordine)){
		pussa_via();
		exit;
	}
	
	
	if($do=="switch"){
        $id_utente_target = CAST_TO_INT($id_utente_target);
        $note_switch_gestore = sanitize($note_switch_gestore);
        $id_ordine = CAST_TO_INT($id_ordine);
        
        //CREO UN CODICE RANDOM
        $codice_switch = random_string(30);
    
        
        //CREO UNA OPZIONE CON IL CODICE RANDOM COME TESTO
    
         $settings=array("id_utente_target"=>$id_utente_target,
                         "id_ordine"=>$id_ordine);
         
          write_option_text(0,"SWITCH_".$codice_switch,base64_encode(serialize($settings)));
        
        
        
          $da_chi = _USER_FULLNAME;
          $mail_da_chi = id_user_mail(_USER_ID);
        
          $verso_chi = fullname_from_id($id_utente_target); 
          $mail_verso_chi = email_from_id($id_utente_target);
            
          $soggetto = "[RETEGAS AP] - da $da_chi - Supplica di referenza ordine";
          $msg_mail = "<h3>Supplica di richiesta referenza ordine.</h3>
                       <p>L'utente $da_chi, che attualmente sta gestendo l'ordine
                          $id_ordine, (".descrizione_ordine_from_id_ordine($id_ordine)."), 
                          chiede umilmente il vostro aiuto relativo alla sua gestione.</p>
                       <p>Cliccando sul link riportato sotto, accetterete la sua richiesta, e voi
                          diventerete il nuovo referente.</p>
                       <p>Se invece non volete aiutare $da_chi, è sufficiente ignorare o cancellare questa mail.</p>
                       <h3><a href=\"".$RG_addr["confirm_switch_gestore"]."?codice=$codice_switch\">Clicca qua per accettare questo gravoso incarico</a></h3>
                       <p>L'utente $da_chi, ha anche un messaggio per te:</p>
                       <h4>".stripcslashes($note_switch_gestore)."</h4>";
          
          
          manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id,$id_user,$msg_mail);
            
          $msg="Supplica di referenza ordine correttamente inoltrata.";
   
        
        
        //COMUNICO ALL'UTENTE IL CODICE
  
							   
						   
	}
	

		 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel menù verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sarà indicata nella barra info 
	$retegas->posizione = "Supplica un utente";
	  
	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = ordini_menu_all($id_ordine); 
 
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array("rg","ckeditor");  // editor di testo
	
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion("",3); // laterale   
	  $retegas->java_scripts_header[] = java_superfish();
	  $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

		   
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
      
      //Controllo se c'è qualcun altro
     $sql = "SELECT * FROM retegas_options WHERE chiave LIKE 'SWITCH_%';";
     $result = $db->sql_query($sql);
     //ASSSEGNO il vecchio id_ordine
     $check_ordine = $id_ordine;
     while ($row = mysql_fetch_array($result)){
         $settings = unserialize(base64_decode($row["valore_text"]));
         //Estraendo i settings ottengo la variabile "ID ordine" nuova
         extract($settings);
         $id_ordine = CAST_TO_INT($id_ordine);
         if($id_ordine==$check_ordine){
            $f.="<p>Già interpellato: <strong>".fullname_from_id($id_utente_target)."</strong>, il ".conv_date_from_db($row["timbro"])."</p>";
         }   
     }
     $id_ordine = $check_ordine; 
      
      
      
      
      
      
      
      
      
      $h = '<div class="rg_widget rg_widget_helper">
        <h3>Cambia il gestore di questo ordine</h3>
        '.$f.'
        <form class="retegas_form" name="cambia_gestore" method="POST" action="'.$RG_addr["edit_switch_gestore"].'">
        
        <div class="ui-state-highlight rg_widget_helper ui-corner-all">
        <h3>Istruzioni</h3>
        <p>Selezionare l\'utente che diventerà il referente di quest\'ordine. Egli riceverà una mail con un link da confermare
        per attivare lo scambio, e tu riceverai una notifica. Se l\'utente invece non accetta la richiesta allora tu rimarrai il referente di questo ordine.
        Se invii più di una richiesta, il primo utente che l\'accetta farà decadere tutte le altre.</p>
        </div>
        
        
        <div>
        <h4>1</h4>
        <span>
        <label for="selection">Scegli un utente del tuo GAS</label>
        <select name= "id_utente_target">';
        $result = $db->sql_query("SELECT * FROM maaking_users WHERE isactive=1 AND id_gas='"._USER_ID_GAS."' ORDER BY fullname ASC");
        $totalrows = mysql_num_rows($result);
        $content .= "<option value=\"-1\">Selezionare Utente</option>";
        while ($row = mysql_fetch_array($result)){
                $idgas = $row['userid'];
                $descrizionegas = $row['fullname'];
                if ($idgas==_USER_ID){$agg=" selected ";}else{$agg=null;}
        $h .= "<option value=\"".$idgas ."\" $agg>".$descrizionegas ."  </option>";   
        }//end while
        $h .='</select>
        </span>
        <h5 title="L\'utente riceve una mail di richiesta. Se la conferma diventerà lui il gestore.">Inf.</h5>    
        </div>
   
        <div>
        <h4>2</h4>
        <h5 title="Messaggio di supplica">Inf.</h5>
        <label for="note_switch_gestore">qua metti un messaggio di supplica che convinca il prescelto ad accettare questa grana.</label>
        <textarea id="note_switch_gestore" class ="ckeditor" name="note_switch_gestore" cols="28" style="display:inline-block;">'.$note_switch_gestore.'</textarea>
        </div>
   
   
        <div>
        <h4>3</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Richiedi lo scambio" align="center" >
        <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
        <input type="hidden" name="do" value="switch">
        <h5 title="">Inf.</h5>
        </div>
  

        </form>

        </div>';
	  
	  
			// QUa butto fuori chi usa EXPLORER !!!
	  $h =  schedina_ordine($id_ordine).$h;
	  
	  
		  // qui ci va la pagina vera e proria  
	  $retegas->content  =  $h;
	  
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  //echo "<br>Scheda : ".(array_sum(explode(' ', microtime())) - $start);
	  
?>
