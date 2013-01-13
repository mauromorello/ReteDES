<?php

include_once ("../rend.php");




//OPERAZIONI 
function do_salva_modifica_ordine($id_ordine,$id_articolo,$q_min,$nuova_q,$amico,$n_riga){
//echo "Ordine = $id_ordine, Articolo, $id_articolo, Nuova_q $nuova_q, Amico = $amico<br> NRIGA = $n_riga<br>";
global $db,$user,$menu_aperto, $n_articoli_ordinati;
global $RG_addr;

$cookie_read = explode("|", base64_decode($user));
$id_user = $cookie_read[0];

$somma = 0;
$r=0;
$err =0;
while (list ($key,$val) = @each ($amico)) {
		
	if(empty($nuova_q[$r])){
			$nuova_q[$r] = 0;    
		}
		$valore = $nuova_q[$r];
		
		
		$id_amico[$r]=$val;
		$valore_nuovo[$r]= $valore;
		//echo "Amico = $val -- Val = $valore <br>";
		if(!is_numeric($valore)){
			//echo "ERRORE";
			$err++;}                
		$somma = $somma + $valore;            
		$r++;
}

//echo "SOMMA = ".$somma."<br>";

	if(!is_multiplo($q_min,$somma)){
 
		$id = $id_articolo;
		$msg ="La Somma degli articoli assegnati ($somma) non e' multiplo della minima quantita' ordinabile ($q_min)";
		include ("../articoli/articoli_form_core.php");
		$titolo_tabella= "Di questo articolo, nell'ordine n.$id_ordine ne hai ordinate n. $n_articoli_ordinati unità.<br>
						Se vuoi modificare questo numero, fallo ridistribuendo l'articolo ordinato tra i tuoi amici.<br>
						Ricoradi inoltre di rispettare la quantità minima.";
		$h_table .= "<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div> "; 
		$h_table .= modifica_quantita_articoli_ordine($id,$id_ordine,$id_user,$q_min);
		include ("ordini_aperti_main.php");
		die();     
		
	}
		if($err>0){
		$id = $id_articolo;
		$msg ="Qualche valore non è stato digitato correttamente";
		include ("../articoli/articoli_form_core.php");
		$titolo_tabella= "Di questo articolo, nell'ordine n.$id_ordine ne hai ordinate n. $n_articoli_ordinati unità.<br>
						Se vuoi modificare questo numero, fallo ridistribuendo l'articolo ordinato tra i tuoi amici.<br>
						Ricorati inoltre di rispettare la quantità minima.";
		$h_table .= "<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div> "; 
		$h_table .= modifica_quantita_articoli_ordine($id,$id_ordine,$id_user,$q_min);
		include ("ordini_aperti_main.php");
		die();     
		
	}
	

$vecchio_quantitativo = n_articoli_arrivati_da_user($id_ordine,$id_articolo,$id_user) ;


//echo "Vecchio = ".$vecchio_quantitativo."<br>"; 
//echo "Nuovo   = $somma"."<br>"; 
// CONTROLLARE SE SOMMA è superiore di vecchio valore ;
// Cambio la data di inserimento SOLO se è stato aggiunto qualche articolo


			if($somma>0){
			//c'è qualcosa     
			// UPDATO Dettaglio ordini (Q_ord, Q_arr)
			//echo "Somma >0";
			if ($somma<=$vecchio_quantitativo){
			 //SE SOMMA E' MINORE O UGUALE NON CAMBIO LA DATA
			 $query_dettaglio_ordini_uguale ="UPDATE retegas_dettaglio_ordini 
										SET retegas_dettaglio_ordini.qta_ord = '$somma', 
										retegas_dettaglio_ordini.qta_arr = '$somma'
										WHERE (((retegas_dettaglio_ordini.id_utenti)='$id_user')
										AND ((retegas_dettaglio_ordini.id_ordine)='$id_ordine') 
										AND ((retegas_dettaglio_ordini.id_articoli)='$id_articolo'));";               
			}else{
			//SE SOMMA E' MAGGIORE CAMBIO LA DATA 
			$query_dettaglio_ordini_uguale ="UPDATE retegas_dettaglio_ordini 
										SET retegas_dettaglio_ordini.qta_ord = '$somma', 
										retegas_dettaglio_ordini.qta_arr = '$somma', 
										retegas_dettaglio_ordini.data_inserimento = NOW()
										WHERE (((retegas_dettaglio_ordini.id_utenti)='$id_user')
										AND ((retegas_dettaglio_ordini.id_ordine)='$id_ordine') 
										AND ((retegas_dettaglio_ordini.id_articoli)='$id_articolo'));";
			}
			
			$result = $db->sql_query($query_dettaglio_ordini_uguale);
							
			
			
			// Cancello distribuzione spesa
			$sql =  $db->sql_query("DELETE from retegas_distribuzione_spesa WHERE retegas_distribuzione_spesa.id_riga_dettaglio_ordine='$n_riga';") or die ("Errore: ". mysql_error());
			
			// Inserisco distribuzione spesa con nuovi valori    
			
			
			
			for($i=0;$i<$r;$i++) {
				$valore = $valore_nuovo[$i];
				$id_a = $id_amico[$i];
				
				//echo "VALORE === $valore";    
				if ($valore>0){
				//echo "immetto $valore";
				
				
				$query_dettaglio_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
									 id_riga_dettaglio_ordine,
									 id_amico,             
									 qta_ord,
									 qta_arr,
									 data_ins,
									 id_articoli,
									 id_user,
									 id_ordine) 
									 VALUES (
												'$n_riga',
												'$id_a',
												'$valore',
												'$valore',
												 NOW(),
												'$id_articolo',
												'$id_user',
												'$id_ordine'
												);" ;
				
				$query_dettaglio_spesa_ins .= "{RIGA $i --> ".$query_dettaglio_spesa."}  ";	
				
				
				$result_dettaglio_spesa = $db->sql_query($query_dettaglio_spesa);

				}            
				//$r++;
			}    
			
				
				
			}else{
				
			// Cancello Distribuzione spesa            
			// Cancello Riga Dettaglio_ordini    
			
					  // Controllare che non abbia utenti legati all'ordine' in sospeso
		  // Controllare che si il proprietario dell'ordine
		  
			$query_cancella_da_distribuzione="DELETE from retegas_distribuzione_spesa WHERE retegas_distribuzione_spesa.id_riga_dettaglio_ordine='$n_riga';";
			$query_cancella_da_dettaglio="DELETE from retegas_dettaglio_ordini WHERE retegas_dettaglio_ordini.id_dettaglio_ordini='$n_riga' LIMIT 1;";
		  
			$sql =  $db->sql_query($query_cancella_da_distribuzione); 
			$sql =  $db->sql_query($query_cancella_da_dettaglio); 
			
			
			
			
			// USCITA //
			$id = $id_ordine;
			$msg ="Articolo eliminato dall'ordine";
			
			$vo = valore_totale_ordine($id);
			$no = descrizione_ordine_from_id_ordine($id);
			
			log_me($id,$id_user,"ORD","ART","Eliminazione di articoli all'ordine $id ($no), adesso vale $vo",$vo,$query_cancella_da_distribuzione." ----> ".$query_cancella_da_dettaglio);
 
			header("Location: ".$RG_addr["ordini_form"]."?id_ordine=$id&msg=2");
			//include ("ordini_aperti_form.php");
			
			die();
			
				
				
			}
	
	
	// messaggio di buon fine
			$id = $id_ordine;
			
			$vo = valore_totale_ordine($id);
			$no = descrizione_ordine_from_id_ordine($id);
			
			log_me($id,$id_user,"ORD","ART","Modifica quantitativo articoli all'ordine $id ($no), adesso vale $vo",$vo,$query_dettaglio_ordini_uguale." ----> ".$query_dettaglio_spesa_ins);
 
			
			$msg ="Modifica effettuata;";
			header("Location: ".$RG_addr["ordini_form"]."?id_ordine=$id&msg=3");
			//include ("ordini_aperti_form.php");
			
			die();
	
}
function do_salva_modifica_ordine_unico($id_ordine,$id_articolo,$q_min,$nuova_q,$amico,$n_riga){
//echo "Ordine = $id_ordine, Articolo, $id_articolo, Nuova_q $nuova_q, Amico = $amico<br> NRIGA = $n_riga<br>";
global $RG_addr;
global $db,$user,$menu_aperto, $n_articoli_ordinati;

$cookie_read = explode("|", base64_decode($user));
$id_user = $cookie_read[0];

$somma = 0;
$r=0;
$err =0;
while (list ($key,$val) = @each ($amico)) {
		
	if(empty($nuova_q[$r])){
			$nuova_q[$r] = 0;    
		}
		$valore = $nuova_q[$r];
		
		
		$id_amico[$r]=$val;
		$valore_nuovo[$r]= $valore;
		//echo "Amico = $val -- Val = $valore <br>";
		if(!is_numeric($valore)){
			//echo "ERRORE";
			$err++;}                
			$somma = $somma + $valore;            
			$r++;
		}
		if($somma>$q_min){
 
			$id = $id_articolo;
			$msg ="La quantità di un articolo univoco non può essere maggiore di quella già esistente";
			$id=$id_ordine;
			include ("ordini_aperti_form_partecipa.php");
			die();     
			
		}
//echo "SOMMA = ".$somma."<br>";

	if(!is_multiplo($q_min,$somma)){
 
		$id = $id_articolo;
		$msg ="La Somma degli articoli assegnati ($somma) non e' multiplo della minima quantita' ordinabile ($q_min)";
		$id=$id_ordine;
			include ("ordini_aperti_form_partecipa.php");
			die();     
		
	}
		if($err>0){
		$id = $id_articolo;
		$msg ="Qualche valore non è stato digitato correttamente";
		$id=$id_ordine;
			include ("ordini_aperti_form_partecipa.php");
			die();    
		
	}
	

//$vecchio_quantitativo = n_articoli_arrivati_da_user($id_ordine,$id_articolo,$id_user) ;


//echo "Vecchio = ".$vecchio_quantitativo."<br>"; 
//echo "Nuovo   = $somma"."<br>"; 
// CONTROLLARE SE SOMMA è superiore di vecchio valore ;
// Cambio la data di inserimento SOLO se è stato aggiunto qualche articolo


			if($somma>0){
	
			
			// Cancello distribuzione spesa
			$sql =  $db->sql_query("DELETE from retegas_distribuzione_spesa WHERE retegas_distribuzione_spesa.id_riga_dettaglio_ordine='$n_riga';") or die ("Errore: ". mysql_error());
			
			// Inserisco distribuzione spesa con nuovi valori    
			
			
			
			for($i=0;$i<$r;$i++) {
				$valore = $valore_nuovo[$i];
				$id_a = $id_amico[$i];
				
				//echo "VALORE === $valore";    
				if ($valore>0){
				//echo "immetto $valore";
				
				
				$query_dettaglio_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
									 id_riga_dettaglio_ordine,
									 id_amico,             
									 qta_ord,
									 qta_arr,
									 data_ins,
									 id_articoli,
									 id_user,
									 id_ordine) 
									 VALUES (
												'$n_riga',
												'$id_a',
												'$valore',
												'$valore',
												 NOW(),
												'$id_articolo',
												'$id_user',
												'$id_ordine'
												);" ;
				
				$query_dettaglio_spesa_ins .= "{RIGA $i --> ".$query_dettaglio_spesa."}  ";    
				
				
				$result_dettaglio_spesa = $db->sql_query($query_dettaglio_spesa);

				}            
				//$r++;
			}    
			
				
				
			}else{
				
			// Cancello Distribuzione spesa            
			// Cancello Riga Dettaglio_ordini    
			
					  // Controllare che non abbia utenti legati all'ordine' in sospeso
		  // Controllare che si il proprietario dell'ordine
		  
			$query_cancella_da_distribuzione="DELETE from retegas_distribuzione_spesa WHERE retegas_distribuzione_spesa.id_riga_dettaglio_ordine='$n_riga';";
			$query_cancella_da_dettaglio="DELETE from retegas_dettaglio_ordini WHERE retegas_dettaglio_ordini.id_dettaglio_ordini='$n_riga' LIMIT 1;";
		  
			$sql =  $db->sql_query($query_cancella_da_distribuzione); 
			$sql =  $db->sql_query($query_cancella_da_dettaglio); 
			
			
			
			
			// USCITA //
			$id = $id_ordine;
			$msg ="Articolo eliminato dall'ordine";
			
			$vo = valore_totale_ordine($id);
			$no = descrizione_ordine_from_id_ordine($id);
			
			log_me($id,$id_user,"ORD","ART","Eliminazione di articoli all'ordine $id ($no), adesso vale $vo",$vo,$query_cancella_da_distribuzione." ----> ".$query_cancella_da_dettaglio);
 
			
			include ("ordini_aperti_form.php");
			
			die();
			
				
				
			}
	
	
	// messaggio di buon fine
			$id = $id_ordine;
			
			$vo = valore_totale_ordine($id);
			$no = descrizione_ordine_from_id_ordine($id);
			
			log_me($id,$id_user,"ORD","ART","Modifica quantitativo articoli all'ordine $id ($no), adesso vale $vo",$vo,$query_dettaglio_ordini_uguale." ----> ".$query_dettaglio_spesa_ins);
 
			
			$msg ="Modifica effettuata;";
			
			include ("ordini_aperti_form.php");
			
			die();
	
}
function do_delete_articolo_specfico($id_dettaglio_articolo,$id_ordine,$id_user){
   
	
global $RG_addr;	
global $db,$user;





// CONTROLLO SE E' SUA LA RIGA
if(id_user_from_id_dettaglio_ordine($id_dettaglio_articolo)<>$id_user){
			$msg="Sembra che tu non sia il proprietario della riga che vuoi cancellare.";
			$id=$id_ordine;
			include("ordini_aperti_table.php");    
			exit;	  
}	
	
// CANCELLO DALLA TABELLA DETTAGLI ORDINE.
$sql = "delete from retegas_dettaglio_ordini WHERE retegas_dettaglio_ordini.id_dettaglio_ordini='$id_dettaglio_articolo' LIMIT 1;";
$db->sql_query($sql);

// CANCELLO DALLA DISTRIBUZIONE
$sql = "delete from retegas_distribuzione_spesa WHERE retegas_distribuzione_spesa.id_riga_dettaglio_ordine='$id_dettaglio_articolo';";
$db->sql_query($sql);
$righe_cancellate = $db->sql_affectedrows();

		$msg = "Cancellato l'articolo dall'ordine e la sua distribuzione ($righe_cancellate record)";	
		$id = $id_ordine;
		   
			
		$vo = valore_totale_ordine($id);
		$no = descrizione_ordine_from_id_ordine($id);
		
		log_me($id,$id_user,"ORD","ART","Eliminazione singola di articoli all'ordine $id ($no), adesso vale $vo",$vo,$sql);

		header("Location: ".$RG_addr["ordini_form"]."?id_ordine=$id");
		//include ("ordini_aperti_form.php");
		
		die();   
}


function do_add_articolo_specifico($id_arti,$id_ordine,$id_user,$q_to_add,$q_min){
 global $db,$user;    
 
		//AGGIUNGO n Articoli all'ordine
		//per ognuno creo una referenza in distribuzione spesa.
 
		//controllo il multiplo corretto
		if (!is_multiplo($q_min,$q_to_add)){
			
				// ID=ID ORDINE
				$sospendi = $id;
				$id = $id_ordine;
				//echo $id;      
				include("ordini_aperti_menu_core.php");
				//include ("ordini_aperti_form_scheda.php"); 
				$id = $sospendi;
				// ID=ID ARTICOLO
					
					$id = $id_arti;
					$msg ="La quantità da aggiungere deve essere un multiplo della quantità minima $q_min";
					
					include ("../articoli/articoli_form_core.php");
					//$h_table .= "<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div> "; 
					$h_table .= modifica_quantita_articoli_ordine_new($id_arti,$id_ordine,$id_user,$q_min);       
					
					// MENU APERTO
					$menu_aperto=3;
					
					include ("ordini_aperti_main.php");
					die(); 
			
		}
		
 
		//-----------------------------------------------------------------------
		for($i=$q_to_add; $i>0; $i=$i-$q_min){
					//echo "I=".$i." Q_to_Add=".$q_to_add." Q_min=".$q_min."<br>";
					// se non è settato il flag di univocità
					// allora forzo la variabile

					$valore_da_inserire = $q_min;

					$query_inserimento_articolo = "INSERT INTO retegas_dettaglio_ordini ( 
											 id_utenti,
											 id_articoli,             
											 data_inserimento,
											 qta_ord,
											 id_amico,
											 id_ordine,
											 qta_arr) 
											 VALUES (
														'$id_user',
														'$id_arti',
														NOW(),
														'$valore_da_inserire',
														'$ordine_amico',
														'$id_ordine',
														'$valore_da_inserire'
														);";
					$querona .= "INSERIMENTO ARTICOLO n. ".$id_arti ."-->". $query_inserimento_articolo." <-- ";                                      
					$result = $db->sql_query($query_inserimento_articolo);
					$mail_necessaria = "SI";
					// scopro qual'è l'ultimo ID inserito (RIGA Dettaglio_ordine)
					$res = mysql_query("SELECT LAST_INSERT_ID();");
					$row = mysql_fetch_array($res);
					$last_id=$row[0];
					// aggiungo un record in dettaglio_spesa con l'articolo caricato in utente id_user
			
					$query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
											 id_riga_dettaglio_ordine,
											 id_amico,             
											 qta_ord,
											 qta_arr,
											 data_ins,
											 id_articoli,
											 id_user,
											 id_ordine) 
											 VALUES (
														'$last_id',
														0,
														'$valore_da_inserire',
														'$valore_da_inserire',
														 NOW(),
														'$id_arti',
														'$id_user',
														'$id_ordine'
														);";
					$querona .= "DISTRIBUZIONE ARTICOLO n. ".$r ."-->". $query_distribuzione_spesa." <-- ";                                     
					$result_dettaglio_spesa = $db->sql_query($query_distribuzione_spesa);
												
												
					
		
		} // FOR CYCLE
		//-----------------------------------------------------------------------
 
 
		$msg ="Aggiunti $q_to_add articoli cod. $id_arti";
		//$msg = "Aggiunto quantitativo richiesto.";    
		$id = $id_ordine;
		$vo = valore_totale_ordine($id);
		$no = descrizione_ordine_from_id_ordine($id);       
		log_me($id,$id_user,"ORD","ART","Aggiunto quantitativo all'articolo singolo $id_arti all' $ordine $id ($no), adesso vale $vo",$vo,$querona);
		// MENU APERTO
		$menu_aperto=3;
		include ("ordini_aperti_form.php");
		
		die();
 
 
	
}

 



if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);
	
		$n_articoli_ordinati = n_articoli_ordinati_da_user($id_ordine,$id,$id_user);
	
	 // --->ID -- > ID ARTICOLO
	 //---->id:ordine
	 //---->q_min
	 
	 // ---- h_table
	 // ---- msg
	 // --- menu aperto
		   // MENU APERTO
	  $menu_aperto=3;
	
	if($do=="do_add_q"){
		do_add_articolo_specifico($id_arti,$id_ordine,$id_user,$q_to_add,$q_min);
	    if(_USER_USA_CASSA){           
           cassa_update_ordine_utente($id_ordine,$id_user); 
        }
	die();    
	}
	
	if($do=="do_del_riga"){
		do_delete_articolo_specfico($n_riga,$id_ordine,$id_user);
	    if(_USER_USA_CASSA){           
           cassa_update_ordine_utente($id_ordine,$id_user); 
        }
	die();    
	}
	
	if($do=="do_del_all_art"){ 
		do_delete_all_articolo_specfico($id_arti,$id_ordine,$id_user);
        if(_USER_USA_CASSA){
           //SE HA LA PRENOTAZIONE ATTIVA 
           if(read_option_prenotazione_ordine($id_ordine,$id_user)<>"SI"){
                $log .="PRENOTAZIONE ? NO, eseguo update cassa<br>";
                cassa_update_ordine_utente($id_ordine,$id_user);
            }else{
                $log .="PRENOTAZIONE ? SI, salto update cassa<br>";
            }
        }
        go("ordine_partecipa",NULL,NULL,"?id_ordine=$id_ordine");
	die();    
	}
	
	if($do=="do_mod_q"){
		do_salva_modifica_ordine($id_ordine,$id_articolo,$q_min,$nuova_q,$amico,$n_riga);
		if(_USER_USA_CASSA){           
           //SE HA LA PRENOTAZIONE ATTIVA 
           if(read_option_prenotazione_ordine($id_ordine,$id_user)<>"SI"){
                $log .="PRENOTAZIONE ? NO, eseguo update cassa<br>";
                cassa_update_ordine_utente($id_ordine,$id_user);
            }else{
                $log .="PRENOTAZIONE ? SI, salto update cassa<br>";
           } 
        }
	die();    
	}
	if($do=="do_mod_q_uni"){
		do_salva_modifica_ordine_unico($id_ordine,$id_articolo,$q_min,$nuova_q,$amico,$n_riga);
		if(_USER_USA_CASSA){           
           //SE HA LA PRENOTAZIONE ATTIVA 
           if(read_option_prenotazione_ordine($id_ordine,$id_user)<>"SI"){
                $log .="PRENOTAZIONE ? NO, eseguo update cassa<br>";
                cassa_update_ordine_utente($id_ordine,$id_user);
            }else{
                $log .="PRENOTAZIONE ? SI, salto update cassa<br>";
            } 
        }
	die();    
	}

	  // ID=ID ORDINE
	$sospendi = $id;
	$id = $id_ordine;
	//echo $id;      
	include("ordini_aperti_menu_core.php");
	//include ("ordini_aperti_form_scheda.php"); 
	$id = $sospendi;
	// ID=ID ARTICOLO 

	  
	include ("../articoli/articoli_form_core.php");
	  
	  
	  $titolo_tabella= "Di questo articolo ($id $id_dett), nell'ordine n.<b>$id_ordine</b> ne hai ordinate n. <b>$n_articoli_ordinati</b> unità.<br>
						Se si tratta di un articolo univoco, lo vedrai ripetuto in una tabella. Puoi distribuire ogni singola voce tra i tuoi amici.<br>
						Se invece è un articolo normale, puoi dividere con loro la quantità totale ordinata.<br> 
						Sono accettati anche valori decimali (es. 0.5), assicurati però di rispettare il multiplo minimo.<br>
						Nel caso tu voglia aumentare i quantitativi nota che perderai la priorità acquisita 
						se la quantità disponibile ad ordine chiuso fosse inferiore a quella richiesta.";
						
	  $h_table .= "<div class=\"ui-widget-content ui-corner-all padding_6px m6b\">$titolo_tabella</div> "; 

	  if($mode<>"uni"){
		$h_table .= modifica_quantita_articoli_ordine($id,$id_ordine,$id_user,$q_min,$id_dett);
	  }else{
		$h_table .= modifica_quantita_articoli_ordine_new($id,$id_ordine,$id_user,$q_min);       
	  }
	  
	  $posizione ="ORDINI APERTI -> <b>Modifica quantita'</b>";
	  include ("ordini_aperti_main.php");
 
}else{
	pussa_via();
}