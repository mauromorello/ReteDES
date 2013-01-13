<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("ditte_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id;

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){

	pussa_via();
	exit;     
}    

	//COntrollo permessi
	if(!(_USER_PERMISSIONS & perm::puo_creare_ditte)){
		unset($do);
		$msg = "Non hai i permessi necessari per creare una nuova ditta.";
		include "../index.php";
		
	}

	if($do=="add"){
			      
      
      
      if (empty($descrizione_ditte)){$msg.="Devi almeno inserire il nome della ditta<br>";$e_empty++;};
      if (empty($indirizzo)){$msg.="Se non conosci l'indirizzo almeno inserisci la città<br>";$e_empty++;};
      if (empty($mail_ditte)){$mail_ditte = id_user_mail($id_user);};
      if (empty($website)){$website = "NON DEFINITO";};
      
      
      $msg.="<br>Verifica i dati immessi e riprova";
      
      
      $e_total = $e_empty + $e_logical + $e_numerical;
      
      if($e_total==0){
        //echo "ZERO ERRORI !!!";
        
        //sanitizzo tutto
        $descrizione_ditte= strip_tags(sanitize($descrizione_ditte));
        $indirizzo=         strip_tags(sanitize($indirizzo));
        $website=           strip_tags(sanitize($website));
        $mail_ditte=        strip_tags(sanitize($mail_ditte));
        $note_ditte=                   sanitize($note_ditte);
        $tag_ditte=         strip_tags(sanitize($tag_ditte));
        $telefono=          strip_tags(sanitize($telefono));
        
        
        
        // QUERY INSERT
        $my_query="INSERT INTO retegas_ditte 
                (descrizione_ditte,
                 indirizzo,
                 website,
                 note_ditte,
                 id_proponente,
                 mail_ditte,
                 telefono,
                 tag_ditte) VALUES (
                 '$descrizione_ditte',
                 '$indirizzo',
                 '$website',
                 '$note_ditte',
                 '"._USER_ID."',
                 '$mail_ditte',
                 '$telefono',
                 '$tag_ditte');";
        
        //INSERT BEGIN ---------------------------------------------------------
         $result = $db->sql_query($my_query);
         if (is_null($result)){
            $msg = "Errore nell'inserimento del record";
            go("ditte_table_3",_USER_ID,"DITTA NON INSERITA");
            die();  
        }else{
           
            $msg = "Nuova ditta aggiunta";
            sleep(1);
            $res_geocode = geocode_ditte_table("SELECT * FROM retegas_ditte WHERE descrizione_ditte='$descrizione_ditte' AND indirizzo='$indirizzo';");
            log_me(0,_USER_ID,"DIT","ADD","Aggiunta ditta $descrizione_ditte",0,$res_geocode);
            go("ditte_table_3",_USER_ID,"Nuova ditta aggiunta");
            die();  
        };
        
        //INSERT END --------------------------------------------------------- 
        
        
        
         
          
      }       
 	}
		 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel menù verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sarà indicata nella barra info 
	$retegas->posizione = "Crea una nuova ditta";
	  
	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = ditte_menu_completo();
 
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array( "rg",   // librerie universali
                                    "ckeditor");  // editor di testo
	
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,menu_lat::anagrafiche); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
	  $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

		  // orizzontale                         

	  
		   
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
		
	  //$h =ordine_render_add_simple($id);
	  
	  
	  // qui ci va la pagina vera e proria  
	  $retegas->content  =  ditte_render_form_add();
	  
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>