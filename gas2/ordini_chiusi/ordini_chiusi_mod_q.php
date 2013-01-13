<?php

include_once ("../rend.php");

function modifica_quantita_articoli_ordine_chiuso($id_arti,$ordine,$id_user,$q_min){

	global $db,$v1,$v2,$v3,$v4,$v5;
	  global $a_hdr,$a_std,$a_tot,$a_nto,$a_cnt;
	  global $stili;
//echo "id arti = ".$id_arti;
//echo "ordine = ".$ordine;
//echo "user = ".$id_user;
	
	
	
$query ="SELECT
retegas_amici.id_amici,
retegas_amici.nome,
retegas_amici.id_referente
FROM
retegas_amici
WHERE
retegas_amici.id_referente =  '$id_user'";
$result = $db->sql_query($query);
		

//$output_html .= "<br />";
//$output_html .= "Multiplo Minimo : ".$q_min;

// N RIGA

$sql_riga="SELECT
*
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_articoli =  '$id_arti' AND
retegas_dettaglio_ordini.id_ordine =  '$ordine' AND
retegas_dettaglio_ordini.id_utenti =  '$id_user';";
$ret_riga = mysql_query($sql_riga);
$row_riga = mysql_fetch_row($ret_riga);
// N RIGA



//$n_riga = n_riga_ordini_dettaglio_distribuzione($ordine,$id_arti,$id_user);
 $n_riga=$row_riga[0];
//echo "Appena munto n_riga = $n_riga";




			
$output_html .= "<table>";    

 //HEADER
		unset ($d);
		$i=0;
		   
		$output_html .= "<tr class=\"odd\">
							<th>Assegnatario</th>
							<th>Quantit? da ordinare</td>
						 </tr>
						 "; 
 // HEADER 

 $output_html .="<form method=\"POST\" action=\"ordini_chiusi_mod_q.php\">"; 
	
$riga=0;



// RIGA DEL ME STESSO 
		//$c0=$row["id_riga_dettaglio_ordine"];
		$c1="<b>Me stesso</b>";

		$c_amico = 0;
		$c2=n_articoli_ordini_dettaglio_distribuzione($ordine,$id_arti,0,$id_user);
		//echo "----$ordine, $id_arti, $id_user, C2 = $c2";  
		$c20="<input type=\"text\" name=nuova_q[] value=\"$c2\" size=\"3\"><input type=\"hidden\" name=amico[] value=\"$c_amico\" size=\"3\">";
		  
		//if(empty($c15)){$c15="0";};
	
	unset ($d);
			   $i=0;
   
			   $output_html .= "<tr class=\"odd\">
								<td>$c1</td>
								<td>$c20</td>
								</tr>";
		
	
			   $riga++;


// RIGA DEL ME STESSO
				
while ($row = mysql_fetch_array($result)){ 
		//$c0=$row["id_riga_dettaglio_ordine"];
		$c1=$row["nome"];
		$c3=$row["id_dettaglio_ordini"];
		//$c15=articoli_per_amici($c0,$c2);
		$c_amico = $row["id_amici"];
		$c2=n_articoli_ordini_dettaglio_distribuzione($ordine,$id_arti,$row["id_amici"],$id_user);
		//echo "----'''''$ordine, $id_arti, $id_user, C2 = $c2";   
		$c20="<input type=\"text\" name=nuova_q[] value=\"$c2\" size=\"3\"><input type=\"hidden\" name=amico[] value=\"$c_amico\" size=\"3\">";
		  
		//if(empty($c15)){$c15="0";};
	
		unset ($d);
			   $i=0;

			   // ----------------$output_html .= r_rt2($cm,$d,$riga,2);
				$output_html .= "<tr class=\"odd\">
								<td>$c1</td>
								<td>$c20</td>
								</tr>";
	
			   $riga++;

}

$output_html .= "</table>";

$output_html .= "<input type=\"hidden\" name=\"id_articolo\" value=\"$id_arti\">
				<input type=\"hidden\" name=\"q_min\" value=\"$q_min\">  
			   <input type=\"hidden\" name=\"do\" value=\"do_mod_q\">
			   <input type=\"hidden\" name=\"id_ordine\" value=\"$ordine\">
			   <input type=\"hidden\" name=\"n_riga\" value=\"$n_riga\"> 
			   <input class=\"large green awesome destra\" style=\"margin:20px;\"type=\"submit\" value=\"Salva i nuovi quantitativi !\"></center>";


   
return $output_html;
	
}

function do_salva_modifica_ordine_chiuso($id_ordine,$id_articolo,$q_min,$nuova_q,$amico,$n_riga){
//echo "Ordine = $id_ordine, Articolo, $id_articolo, Nuova_q $nuova_q, Amico = $amico<br> NRIGA = $n_riga<br>";
global $db,$user,$menu_aperto;

 
$cookie_read = explode("|", base64_decode($user));
$id_user = $cookie_read[0];
$n_articoli_ordinati = n_articoli_ordinati_da_user($id_ordine,$id_articolo,$id_user);

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
	if($somma<>$n_articoli_ordinati){
 
		$id = $id_articolo;
		$msg ="La Somma degli articoli assegnati ($somma) non e' uguale al quantitativo che avevi ordinato ($n_articoli_ordinati)";
		include ("../articoli/articoli_form_core.php");
		$titolo_tabella= "Di questo articolo, nell'ordine n.$id_ordine ne hai ordinate n. $n_articoli_ordinati unit?.<br>
						  Ridistribuire tra i propri amici <b>la stessa</b> quantit?";
		$h_table .= "<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div> "; 
		$h_table .= modifica_quantita_articoli_ordine_chiuso($id,$id_ordine,$id_user,$q_min);
		include ("ordini_aperti_main.php");
		die();     
		
	}
		if($err>0){
		$id = $id_articolo;
		$msg ="Qualche valore non ? stato digitato correttamente";
		include ("../articoli/articoli_form_core.php");
		$titolo_tabella= "Di questo articolo, nell'ordine n.$id_ordine ne hai ordinate n. $n_articoli_ordinati unit?.<br>
						  Ridistribuire tra i propri amici <b>la stessa</b> quantit?";
		$h_table .= "<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div> "; 
		$h_table .= modifica_quantita_articoli_ordine($id,$id_ordine,$id_user,$q_min);
		include ("ordini_aperti_main.php");
		die();     
		
	}
	
//echo "Somma = $somma";
			if($somma==$n_articoli_ordinati){
			//c'? qualcosa     
			// UPDATO Dettaglio ordini (Q_ord, Q_arr)
			//echo "Somma >0";
			$result = $db->sql_query("  UPDATE retegas_dettaglio_ordini 
										SET retegas_dettaglio_ordini.qta_ord = '$somma', 
										retegas_dettaglio_ordini.qta_arr = '$somma', 
										retegas_dettaglio_ordini.data_inserimento = NOW()
										WHERE (((retegas_dettaglio_ordini.id_utenti)='$id_user')
										AND ((retegas_dettaglio_ordini.id_ordine)='$id_ordine') 
										AND ((retegas_dettaglio_ordini.id_articoli)='$id_articolo'));");
							
			
			
			// Cancello distribuzione spesa
			$sql =  $db->sql_query("DELETE from retegas_distribuzione_spesa WHERE retegas_distribuzione_spesa.id_riga_dettaglio_ordine='$n_riga';") or die ("Errore: ". mysql_error());
			
			// Inserisco distribuzione spesa con nuovi valori    
			
			
			
			for($i=0;$i<$r;$i++) {
				$valore = $valore_nuovo[$i];
				$id_a = $id_amico[$i];
				
				//echo "VALORE === $valore";    
				if ($valore>0){
				//echo "immetto $valore";    
				$result_dettaglio_spesa = $db->sql_query("INSERT INTO retegas_distribuzione_spesa ( 
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
												);");

				}            
				//$r++;
			}    
			
				
				
			}else{
				
			// Cancello Distribuzione spesa            
			// Cancello Riga Dettaglio_ordini    
			
					  // Controllare che non abbia utenti legati all'ordine' in sospeso
		  // Controllare che si il proprietario dell'ordine
		  
			//$sql =  $db->sql_query("DELETE from retegas_distribuzione_spesa WHERE retegas_distribuzione_spesa.id_riga_dettaglio_ordine='$n_riga';"); 
			//$sql =  $db->sql_query("DELETE from retegas_dettaglio_ordini WHERE retegas_dettaglio_ordini.id_dettaglio_ordini='$n_riga' LIMIT 1;"); 
			
			
			
			
			// USCITA //
			$id = $id_ordine;
			$msg ="Errore Ansiotropico destroso.";
			include ("ordini_chiusi_form.php");
			
			die();
			
				
				
			}
	
	
	// messaggio di buon fine
			$id = $id_ordine;
			$msg ="Modifica effettuata";
			include ("ordini_chiusi_form.php");
			
			die();
	
}




if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);
	
	 // --->ID -- > ID ARTICOLO
	 //---->id:ordine
	 //---->q_min
	 
	 // ---- h_table
	 // ---- msg
	 // --- menu aperto
		   // MENU APERTO
	  $menu_aperto=3;
	
	if($do=="do_mod_q"){
	do_salva_modifica_ordine_chiuso($id_ordine,$id_articolo,$q_min,$nuova_q,$amico,$n_riga);
		
	die();    
	}
	

	   

	  
	  include ("../articoli/articoli_form_core.php");
	  
	  
	  $titolo_tabella= "Di questo articolo, nell'ordine n.$id_ordine ne hai ordinate n. $n_articoli_ordinati unit?.<br>
						Ridistribuire tra i propri amici <b>la stessa</b> quantit?";
						
	  $h_table .= "<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div> "; 

	  
	  $h_table .= modifica_quantita_articoli_ordine_chiuso($id,$id_ordine,$id_user,$q_min);
	  $posizione ="ORDINI CHIUSI -> Gestione ordini -> <b>Assegna quantita' arrivate</b>";
	  include ("ordini_aperti_main.php");
 
}else{
	pussa_via();
} 
?>