<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id;
(int)$id_ordine;

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();     
}    

	//COntrollo permessi

		//COntrollo permessi

	if(id_referente_ordine_globale($id_ordine)<>_USER_ID){
		pussa_via();
		exit;     
	}
	
	if(ordine_inesistente($id_ordine)){
		pussa_via();
		exit;
	}
	
	//if(stato_from_id_ord($id_ordine)==2){
	//	$msg="Non si può (per ora) modificare un listino con l'ordine aperto";
    //		unset($do);
    //	}

	
	if($do=="mod"){
			
			$msg = 	ordine_render_do_edit_switch_listino($id_ordine);
			// MSG contiene il risultato del cambiamento, se ci sono problemi continua su questa pagina
            // e visualizza l'errore
            if($msg=="OK"){
                
                $msg="Dati modificati correttamente";
                    
                //manda mail a tutti i partecipanti solo se l'ordine non è futuro
               // if(stato_from_id_ord($id_ordine)>1){
           
                       // CONTROLLARE SE USER E' REFERENTE ORDINE   
                        $da_chi = _USER_FULLNAME;
                        $mail_da_chi = id_user_mail(_USER_ID);
                        
                                                  
                        $descrizione_ordine = descrizione_ordine_from_id_ordine($id_ordine);
                        
                        $qry=" SELECT
                        maaking_users.fullname,
                        maaking_users.email
                        FROM
                        maaking_users
                        Inner Join retegas_dettaglio_ordini ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
                        WHERE
                        retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
                        GROUP BY
                        maaking_users.fullname,
                        maaking_users.email;
                        ";
                        $result = $db->sql_query($qry); 
                        while ($row = mysql_fetch_array($result)){
                            $verso_chi[] = $row[0] ;
                            $mail_verso_chi[] = $row[1] ;
                            $lista_destinatari .= $row[0]."<br>"; 
                        }

                        $soggetto = "[ReteDes.it] - [REFERENTE ORDINE] ATTENZIONE !! Cambio listino ordine $id_ordine ($descrizione_ordine)";
                        
                        manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,null,"MAN",$id_ordine,_USER_ID,$note_switch);
                            
                        $msg="mail correttamente inviata a : <br>$lista_destinatari";
                        
                     // } 
                    //Esce
			        go("ordini_form_new",_USER_ID,"Listino cambiato, ".$msg,"?id_ordine=$id_ordine");
                    die();
                }							   
						   
	}
	

		 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender? come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men? verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Cambia il listino";
	  
	// Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = ordini_menu_all($id_ordine); 
 
	// dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovr? caricare
	$retegas->java_headers = array("rg","ckeditor");  // editor di testo
	
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion("",menu_lat::ordini); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
	  $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

		   
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  

	  
	  
			// QUa butto fuori chi usa EXPLORER !!!
	  $h =  schedina_ordine($id_ordine).
			ordine_render_edit_switch_listino($id_ordine);
	  
	  
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