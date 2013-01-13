<?php



include_once ("../rend.php");  




function rompi_le_balle($ordine,$id_user=0,$nome_partecipante=null,$id_partecipante=null){

		

		$verso_chi = fullname_referente_ordine_globale($ordine);
		$mail_verso_chi = mail_referente_ordine_globale($ordine);
		$descrizione = descrizione_ordine_from_id_ordine($ordine);
		$valore_ordine_netto = valore_totale_ordine($ordine);
		$utenti_ordine = ordine_bacino_utenti_part($ordine);
		$articoli_ordinati = articoli_in_ordine($ordine);
		$nome_partecipante = fullname_from_id($id_user);
        $gas_partecipante = gas_nome(id_gas_user($id_user));

		$da_chi = "ReteGas AP";
		$mail_da_chi = "retegas@altervista.org";


		if(livello_rompimento_ordine($ordine)>1){

		$eol ="<br>"; 

		$message = "Ordine - $ordine ($descrizione).$eol
                    Qualcuno ha partecipato.
                    ($nome_partecipante del $gas_partecipante)$eol
                    ------------------------------------------------------ $eol
                    Valore netto merce ordinata : $valore_ordine_netto Eu.$eol
                    Utenti totali : $utenti_ordine;$eol
                    ------------------------------------------------------ $eol
                    $eol
                    ATTENZIONE l'ordine NON e' ancora chiuso, queste informazioni$eol
                    sono da considerarsi incomplete.$eol  ";
		  $message .= "-------------------------------------------- $eol";
		  $message .= "RETEGASAP. (Rete dei GAS Alto Piemonte)$eol";
		  $message .= "SITO : www.retegas.info$eol";
		  $message .= "MAIL : retegas.ap@gmail.com $eol"; 
		  $message .= "$eol";
		  $message .= "$eol";
		  $message .= "Questa mail viene generata automaticamente.  $eol" ;                 


		  $soggetto = "[RETEGAS AP] - Rapporto attivita' su ordine $ordine ($descrizione)";

		  //echo $message;

		 manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,$message,"AUT",$ordine,$id_user);
					 

		} // mail_level	

}
function do_salva_carrello($box_id,$box_value,$ordine,$ordine_amico,$box_q_att,$box_q_min,$poi,$box_q_uni){

	

   global $user, $db;
   global $RG_addr;
   

   

   //echo "Sono in ORDINE: $ordine <br>";

							

   if (is_logged_in($user)) {   

	$cookie_read = explode("|", base64_decode($user));

	$id_user = $cookie_read[0];

	$username =$cookie_read[1];

	$gas = id_gas_user($id_user);

	$gas_name = gas_nome($gas);

	$fullname = fullname_from_id($id_user);

	$r=0;

	$msg="";

	$mail_necessaria = "NO";

	

	while (list ($key,$val) = @each ($box_id)) {

		$qarti=0;
		$arti=0;
		if(empty($box_q_att[$r])){$box_q_att[$r]=0;}

		

		//echo "Val $val , Valore $box_value[$r], Q att $box_q_att[$r], Ordine $ordine , Amico $ordine_amico, Q min $box_q_min[$r] <br>";

		

				

		if(is_numeric($box_value[$r])){
		if($box_value[$r]>0){

			//---------------Controllo se articolo doppio

			$ar_dopp =$db->sql_query("SELECT Count(retegas_dettaglio_ordini.id_articoli) AS ConteggioDiid_articoli, 

										Sum(retegas_dettaglio_ordini.qta_ord) AS SommaDiqta_ord,

									  retegas_dettaglio_ordini.id_utenti,

									  retegas_dettaglio_ordini.id_amico,

									  retegas_dettaglio_ordini.id_ordine,

									  retegas_dettaglio_ordini.id_articoli,

									  retegas_dettaglio_ordini.id_dettaglio_ordini                                      

										FROM retegas_dettaglio_ordini

										GROUP BY retegas_dettaglio_ordini.id_utenti, retegas_dettaglio_ordini.id_amico, retegas_dettaglio_ordini.id_ordine, retegas_dettaglio_ordini.id_articoli

										HAVING (((retegas_dettaglio_ordini.id_utenti)='$id_user') AND ((retegas_dettaglio_ordini.id_amico)='$ordine_amico') AND ((retegas_dettaglio_ordini.id_ordine)='$ordine') AND ((retegas_dettaglio_ordini.id_articoli)='$val'));");

			

			$r_ar_dopp = mysql_fetch_row($ar_dopp);

			$key=$r_ar_dopp[6];

			if(empty($r_ar_dopp[0])){$arti=0;}else{

													  $arti=$r_ar_dopp[0];

													  $qarti=$r_ar_dopp[1];

			}

			//---------------------------------------  

			if($arti==0){// ------------------------------------------------ARTICOLO NUOVO



			

			// Metto q_arr = q_ord

			if(is_multiplo($box_q_min[$r],$box_value[$r])){

				// CICLO Che immette le quantità degli articoli come se fosser singole
				// a meno che il flag di univocità non esista
				// allora forzo la variabile ed esco

				

				for($i=$box_value[$r]; $i>0; $i=$i-$box_q_min[$r]){


					// se non è settato il flag di univocità
					// allora forzo la variabile

					if($box_q_uni[$r]<>1){  

						//$i=$box_value[$r];

						$i=0;

						$valore_da_inserire = $box_value[$r];							

						//echo "FORZATO CONTATORE per quantità $box_value[$r]<br>";

					}else{

						$valore_da_inserire = $box_q_min[$r];

						//echo "$i ciclo per Articolo unico Q 1<br>"; 

					}

				

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

														'$val',

														NOW(),

														'$valore_da_inserire',

														'$ordine_amico',

														'$ordine',

														'$valore_da_inserire'

														);";

					$querona .= "INSERIMENTO ARTICOLO n. ".$r ."-->". $query_inserimento_articolo." <-- ";                                      
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

														'$val',

														'$id_user',

														'$ordine'

														);";

					$querona .= "DISTRIBUZIONE ARTICOLO n. ".$r ."-->". $query_distribuzione_spesa." <-- ";                                     

					$result_dettaglio_spesa = $db->sql_query($query_distribuzione_spesa);

												

												

					//$output_html .= "INSERITO- - - - - - - - - - - - - -  - -- - <br>";

					$msg .="Ins. Q. $valore_da_inserire, Articolo:  $val, <br>";



					} // FINCE CICLO FOR PER GLI ARTICOLI      

				}else{

					$non_riuscito++;
                    
					$msg .="Articolo $val , Quantità ''$box_value[$r]'' inserita non accettata. <br>"; 

				}//$output_html .= "Q < Q min- - - - - - ";   } // > q minima

			$querona .= $msg;

			}else{  // -----------------------------------------------------ARTICOLO ESISTENTE

					//$nq= $box_value[$r]+$qarti;

			

					// se la quantita' vecchia è diversa da quella nuova

					$nq= $box_value[$r]; 

					if($nq<>$qarti){

						if(is_multiplo($box_q_min[$r],$nq)){

							$result = $db->sql_query("  UPDATE retegas_dettaglio_ordini 

														SET retegas_dettaglio_ordini.qta_ord = '$nq', retegas_dettaglio_ordini.qta_arr = '$nq', retegas_dettaglio_ordini.data_inserimento = NOW()

															WHERE (((retegas_dettaglio_ordini.id_utenti)='$id_user') AND ((retegas_dettaglio_ordini.id_amico)='$ordine_amico') AND ((retegas_dettaglio_ordini.id_ordine)='$ordine') AND ((retegas_dettaglio_ordini.id_articoli)='$val'));");

							$msg .="Mod. Articolo $box_value[$r], nuova q. $nq. <br>";

							// $output_html .= "MODIFICATO- - - - - - - - - - - - - -  - -- - <br>";

							//echo "STO RIDISTRIBUENDO ". $key;

							ridistribuisci_quantita_amici($key,$nq);

						}else{

							$msg .="Ins. Articolo $box_value[$r], q. $nq NON RIUSCITO. Q/Qmin <>0.  <br>";

							$non_riuscito++;

						}// qtaà non multipla    

					}

			}

			

					   

		} // is >0

		} // is numeric                                        

		

		

		$r++;

	

	}

	



   if($non_riuscito>0){
        $msg .= "<br> n. $non_riuscito articoli non sono stati aggiunti all'ordine";
        $poi=1;     
   }else{
        $msg .= "Tutti gli articoli sono stati inseriti correttamente"; 
   }

   

   // MENU APERTO
   $menu_aperto=3;
   $id = $ordine; 

   $vo = valore_totale_ordine($id);
   $no = descrizione_ordine_from_id_ordine($id);
   log_me($id,$id_user,"ORD","ART","Aggiunta di articoli all'ordine $id ($no), adesso vale $vo",$vo,$querona);

   cassa_update_ordine_utente($id,$id_user);
   
   if($mail_necessaria=="SI"){
		rompi_le_balle($id,$id_user);
   }

       go("ordini_form",$id_user,$msg,"?id_ordine=$id"); 
       //header("Location: ".$RG_addr["ordini_form"]."?id_ordine=$id&msg=1");
 	   //exit;

   }else{

		header("Location: ../index.php");  
		die();   

   }    

	

	

}
function ridistribuisci_quantita_amici($key,$nq, &$msg=null){

global $db, $user,$a_hdr,$a_std,$a_alt; 

// Ho la lista degli amici riferita all'articolo KEY

//	echo r_t_l2("DENTRO $key, $nq",$a_alt);    



$qry ="SELECT

retegas_distribuzione_spesa.id_distribuzione,

retegas_distribuzione_spesa.id_riga_dettaglio_ordine,

retegas_distribuzione_spesa.qta_ord,

retegas_distribuzione_spesa.qta_arr,

retegas_distribuzione_spesa.id_amico

FROM

retegas_distribuzione_spesa

WHERE

retegas_distribuzione_spesa.id_riga_dettaglio_ordine =  '$key'

ORDER BY

retegas_distribuzione_spesa.id_distribuzione DESC";





// Adesso la popolo con la nuova quantità partendo dall'ultima riga immessa;

// in realtà cancellando e ripopolando tutto ho sempre lo stesso utente penalizzato;    



$result = $db->sql_query($qry);

	$totalrows = mysql_num_rows($result);

	$rimasto=$nq;

	while ($row = mysql_fetch_array($result)){

	//echo r_t_l2("°°°°°°°° $key rimasto $rimasto ",$a_alt);    

		$a = $rimasto;// - $row['qta_ord'];

		$id_q = $row['id_distribuzione'];

		

		if($a>0){

			$q_a = $row['qta_ord'];

			$rimasto=$a;

		}else{

			$q_a = $rimasto;

			$rimasto=0;

		}    

	

	// update

	$result2 = mysql_query("UPDATE retegas_distribuzione_spesa 

							SET retegas_distribuzione_spesa.qta_arr = '$q_a',

							SET retegas_distribuzione_spesa.qta_ord = '$q_a', 

								retegas_distribuzione_spesa.data_ins = NOW()

							WHERE (retegas_distribuzione_spesa.id_distribuzione='$id_q');");

	

	 if($row['qta_ord']<>$q_a){

		// le quantita' sono diverse, ricalcolo le assegnazioni sugli amici

		$msg .= "";

	$amico = $row["id_amico"];    

		//echo r_t_l2("-------------$key AMICO $amico ID DETT:".$row['id_distribuzione']." ORD. = ".$row['qta_ord']." ARR. = ".$q_a,$a_alt);    

	}else{

		//echo r_t_l2("-------------$key AMICO $amico ID DETT:".$row['id_distribuzione']." ORD. = ".$row['qta_ord']." ARR. = ".$q_a,$a_std);    

	}

	

		//echo r_t_l2("OPERAZIONE CONCLUSA: TORNA INDIETRO",$a_hdr,"ordini_chiusi_dettaglio_codice.php?do=vis1&id_ord=$id_ord");     

	

	

	// CICLO DI UPDATE

	}





	

}









if (is_logged_in($user)){

		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
        $gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);

	

	

		//permessi

		$permission = (int)$cookie_read[6]; 

		 if(!($permission & perm::puo_partecipare_ordini)){
			$q = "not_allowed";
			include ("../index.php");
			exit;            
			}

		if(ordine_io_cosa_sono($id,$id_user)==0){
			//echo "ORDINE CHE NON MI COMPETE";
			pussa_via();       
		exit;    
		}

		if(!ordine_partecipabile($id)){

			//echo "ORDINE CHIUSO";  

			pussa_via();         

		exit; 

		}

		if(ordine_inesistente($id)){
			pussa_via();         
		exit;    

		}

	

	 // --->ID

	 // ---- h_table

	 // ---- msg

	 // --- menu aperto

	 if($do=="salva_carrello"){
			 do_salva_carrello($box_id,$box_value,$id,0,$box_q_att,$box_q_min,$poi,$box_q_uni);
	         
     }

	

	

	  // MENU APERTO

	  $menu_aperto=3;

	   

	  // Campi e intestazioni

	  include ("ordini_aperti_sql_core.php");
	  include("ordini_aperti_menu_core.php");


      $h_table .= schedina_ordine($id);
   	  $table_sorter_name = "partecipazioni"; 

	   

	  include ("ordini_aperti_form_articoli_partecipa.php");

	  $posizione ="ORDINI APERTI -> <b>Partecipa</b>";
	  $has_fg ="YES";
	  $qtip_on="TRUE";
	  $qtip_ajax="../articoli/articoli_form_note.php";

	  

	  include ("ordini_aperti_main.php");

 

}else{

	pussa_via();

} 

?>