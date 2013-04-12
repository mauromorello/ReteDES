<?php

include_once ("../rend.php");

function modifica_quantita_articoli_ordine_new($id_arti,$ordine,$id_user,$q_min){

	global $db,$v1,$v2,$v3,$v4,$v5;
	  global $a_hdr,$a_std,$a_tot,$a_nto,$a_cnt;
	  global $stili;

	  
	  
	  
	  
// QUI HO TUTTI I MIEI AMICI    
$query ="SELECT
retegas_dettaglio_ordini.id_dettaglio_ordini,
retegas_dettaglio_ordini.id_articoli,
retegas_articoli.codice,
retegas_articoli.descrizione_articoli,
retegas_dettaglio_ordini.qta_ord,
retegas_articoli.qta_minima
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_utenti =  '$id_user' AND
retegas_dettaglio_ordini.id_ordine =  '$ordine' AND
retegas_dettaglio_ordini.id_articoli =  '$id_arti'";
$result = $db->sql_query($query);

$titolo_tabella = "Articolo unico";


$h_table .="<form method=\"POST\" action=\"ordini_aperti_mod_q.php\">"; 
$h_table .= "
	  <div class=\"rg_widget rg_widget_helper\"> 
	  <div style =\"margin-bottom:10px;\">$titolo_tabella</div>
	  <table id=\"spesa\" style=\"background-image:-webkit-gradient(linear,1250 225,0 255,from(#E0E0E0),to(#FFFFFF));\">        

		<tr class=\"odd\">
			<th>Codice</th>
			<th>Descrizione</th>
			<th>Quantità Ordinata</th>
			<th>Assegnatari</th>
			<th>Operazioni</th>                     
		</tr>";    
		
while ($row = mysql_fetch_array($result)){
$do_del = "ordini_aperti_mod_q.php?do=do_del_riga&n_riga=".$row["id_dettaglio_ordini"]."&id_ordine=".$ordine;    
$assegnatari = lista_assegnatari_articolo_dettaglio($row["id_dettaglio_ordini"]);    
$operazione = ' <a class="awesome yellow medium" href="ordini_aperti_mod_q.php?id='.$row["id_articoli"].'&id_ordine='.$ordine.'&q_min='.$q_min.'&id_dett='.$row["id_dettaglio_ordini"].'">Mod.</a> 
				<a class="awesome black medium" href="'.$do_del.'">El.</a>';  
$h_table .='<tr class="odd">
		  <td>'.$row["id_dettaglio_ordini"].'-'.$row["id_articoli"].'</td>
		  <td>'.$row["descrizione_articoli"].'</td>
		  <td>'.$row["qta_ord"].'</td>
		  <td>'.$assegnatari.'</td>
		  <td style="text-align:right;" width="15%">'.$operazione.'</td>
		  </tr>';		
	
}
 

			

	








$output_html .= "<input type=\"hidden\" name=\"id_articolo\" value=\"$id_arti\">
				<input type=\"hidden\" name=\"q_min\" value=\"$q_min\">  
			   <input type=\"hidden\" name=\"do\" value=\"do_mod_q\">
			   <input type=\"hidden\" name=\"id_ordine\" value=\"$ordine\">
			   <input type=\"hidden\" name=\"n_riga\" value=\"$n_riga\"> 
			   <input class=\"large green awesome destra\" style=\"margin:20px;\"type=\"submit\" value=\"Salva i nuovi quantitativi !\"></center>";
$h_table .= "</table>
			 </div>";  

   
return $h_table;
	
}
function do_salva_modifica_ordine($id_ordine,$id_articolo,$q_min,$nuova_q,$amico,$n_riga){
//echo "Ordine = $id_ordine, Articolo, $id_articolo, Nuova_q $nuova_q, Amico = $amico<br> NRIGA = $n_riga<br>";
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
		do_salva_modifica_ordine($id_ordine,$id_articolo,$q_min,$nuova_q,$amico,$n_riga);
		
	die();    
	}
	

	   

	  
	  include ("../articoli/articoli_form_core.php");
	  
	  
	  $titolo_tabella= "Questo è un' \"Articolo Unico\" cioè vuol dire che viene trattato singolarmente e non 
	  raggruppato, ma può essere comunque diviso tra i tuoi amici.<br>
	  Se però quando l'ordine chiude non sono confermate le quantità esatte, la differenza di prezzo andrà a ricadere
	  solo sul tuo nominativo.";
						
	  $h_table .= "<div class=\"ui-widget-content ui-corner-all padding_6px m6b\">$titolo_tabella</div> "; 

	  
	  
	  
	  
	  $h_table .= modifica_quantita_articoli_ordine_new($id,$id_ordine,$id_user,$q_min);
	  
	  
	  $posizione ="ORDINI APERTI -> <b>Modifica quantita'</b>";
	  include ("ordini_aperti_main.php");
 
}else{
	pussa_via();
} 
?>