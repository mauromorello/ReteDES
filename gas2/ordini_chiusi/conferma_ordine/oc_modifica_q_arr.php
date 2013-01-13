<?php

include_once ("../../rend.php");   

function ridistribuisci_quantita_amici($key,$nq, &$msg){
global $db, $user,$a_hdr,$a_std,$a_alt; 
// Ho la lista degli amici riferita all'articolo KEY
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
retegas_distribuzione_spesa.id_distribuzione ASC";


// Adesso la popolo con la nuova quantit? partendo dall'ultima riga immessa;
// in realt? cancellando e ripopolando tutto ho sempre lo stesso utente penalizzato;    

$result = $db->sql_query($qry);
	$totalrows = mysql_num_rows($result);
	$rimasto=$nq;
	while ($row = mysql_fetch_array($result)){
		
		$a = $rimasto - $row['qta_ord'];
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
function do_mod1($id_user,$id_ord,$box_q_ord,$box_q_arr,$box_art_id){
	
   global $user, $db,$a_hdr,$a_std; 
	//echo "ORD = ".$id_ord." User:".$id_user."<br>";
	$r=0;
	$msg="";
	//echo r_t_l2("RISULTATO OPERAZIONE (provvisorio)",$a_hdr);
	
	
	while (list ($key,$val) = @each ($box_art_id)) { // PASSO LA LISTA DEGLI ARTICOLI
	//echo r_t_l2("KEY ".$key." ID :".$val." Q_ord: ".$box_q_ord[$key]." Q_Arr :".$box_q_arr[$key],$a_std);     
	// PER OGNI ARTICOLO CREO UN RECORDSET CON GLI ORDINI FATTI SULL'ARTICOLO
	// DA CUI TRAGGO l'ID DETTAGLIO_ORDINE
	// POI FACCIO UN CICLO A RITROSO PARTENDO DAL TOTALE ARTICOLO ARRIVATO
	// E SCALANDO LE QUANTITA' PARTENDO DALL?ORDINE PIU' VECCHIO
	// GLI ORDINI PIU' RECENTI SE NON CI SONO ABBASTANZA ARTICOLI VENGONO PENALIZZATI
	$qry="SELECT
				retegas_dettaglio_ordini.id_dettaglio_ordini,
				retegas_dettaglio_ordini.data_inserimento,
				retegas_dettaglio_ordini.qta_ord
		   FROM
				retegas_dettaglio_ordini
		   WHERE
				retegas_dettaglio_ordini.id_ordine =  '$id_ord' AND
				retegas_dettaglio_ordini.id_articoli =  '$val'
		   ORDER BY
				retegas_dettaglio_ordini.data_inserimento ASC";    
	$result = $db->sql_query($qry);
	$totalrows = mysql_num_rows($result);
	$rimasto=$box_q_arr[$key];
	while ($row = mysql_fetch_array($result)){
		
		$a = $rimasto - $row['qta_ord'];
		$id_q = $row['id_dettaglio_ordini'];
		
		if($a>0){
			$q_a = $row['qta_ord'];
			$rimasto=$a;
		}else{
			$q_a = $rimasto;
			$rimasto=0;
		}    
	
	// update
	$result2 = mysql_query("UPDATE retegas_dettaglio_ordini 
							SET retegas_dettaglio_ordini.qta_arr = '$q_a', 
								retegas_dettaglio_ordini.data_convalida = NOW()
							WHERE (retegas_dettaglio_ordini.id_dettaglio_ordini='$id_q');");
	// CICLO DI UPDATE
	
	
	
	
	if($row['qta_ord']<>$q_a){
		// le quantita' sono diverse, ricalcolo le assegnazioni sugli amici
		
		//echo r_t_l2("ARTICOLO:".$row['id_dettaglio_ordini']." ORDINATI :".$row['qta_ord']." ARRIVATI: = ".$q_a,$a_hdr); 
		
		
		   
	}else{
		
		//OK
		//echo r_t_l2("MANCANZA ARTICOLI:".$row['id_dettaglio_ordini']." q_arr = ".$q_a,$a_std);
		//echo r_t_l2("ARTICOLO:".$row['id_dettaglio_ordini']." ORDINATI :".$row['qta_ord']." ARRIVATI: = ".$q_a,$a_std);      
	}
	
	// RIPRISTINA ANCHE QUANTITA' INTERE                       
	ridistribuisci_quantita_amici($row['id_dettaglio_ordini'],$q_a,$msg);
	
	 
			
	}// CICLO PER LE ASSEGNAZIONI
	

	
	}

	//echo r_t_l2("OPERAZIONE CONCLUSA: TORNA INDIETRO",$a_hdr,"ordini_chiusi_dettaglio_codice.php?do=vis1&id_ord=$id_ord");     
	
	return "Modifiche effettuate";
	
}     // Modifica quantit? arrivata
function modifica_quantita_arrivate_form($ordine){
global $db,$v1,$v2,$v3,$v4,$v5;
	  global $a_hdr,$a_std,$a_tot,$a_nto,$a_cnt;
	  global $stili;

	  $valore = id_listino_from_id_ordine($ordine);
 
		$qry="SELECT
				retegas_dettaglio_ordini.id_articoli,
				Sum(retegas_dettaglio_ordini.qta_ord) AS tot_q_ord,
				retegas_articoli.codice,
				retegas_articoli.descrizione_articoli,
				Sum(retegas_dettaglio_ordini.qta_arr) AS tot_q_arr,
				retegas_articoli.u_misura,
				retegas_articoli.misura,
				retegas_articoli.articoli_unico,
				retegas_articoli.qta_minima,
				retegas_articoli.qta_scatola
				FROM
				retegas_dettaglio_ordini
				Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
				WHERE
				retegas_dettaglio_ordini.id_ordine =  '$ordine'
				GROUP BY
				retegas_dettaglio_ordini.id_articoli,
				retegas_articoli.codice,
				retegas_articoli.descrizione_articoli
				ORDER BY
				retegas_articoli.codice ASC";    
		$result = $db->sql_query($qry);
		$totalrows = mysql_num_rows($result);
	 
		 

		//----------------nuovo form
	  
		
		$titolo_tabella="Conferma articoli ordinati";
		
		$output_html .="<form method=\"POST\" action=\"oc_modifica_q_arr.php\">";
		$output_html .= " 
							<div class=\"rg_widget rg_widget_helper\" style =\"margin-bottom:6px;\">$titolo_tabella</div>
							<table>        

							<tr>
							<th>Univoco</th>
							<th>&nbsp</th>
							<th>Codice</th>
							<th>Descrizione</th>
							<th>Quantit? ordinata</th>
							<th>Composizione<br>Scat / minimo</th>													
							<th>Prenotate<br>Scat / Avanzo</th>
							<th>QUANTITA'<br>ARRIVATA</th>
											   
							</tr>";
   
		//$totale_ordine = valore_totale_ordine_qarr($ordine);  
		$riga=0;
		$somma_amico = 0;
	 
		 while ($row = mysql_fetch_array($result)){

		
					 
			  $c0 = $row[0]; 
			  $c1 = $row[1]; // ordinata
			  $c2 = $row[2]; // codice 
			  $c3 = $row[3]; // Descrizione
			  $c4 = $row[4]; // arrivata
			  
			  if($row["articoli_unico"]==1){
				  $univoco_class = "class =\"campo_alert\" ";
				  $univoco = "ARTICOLO UNIVOCO";
				  $style_univoco =" style=\"background-image:-webkit-gradient(linear,0 20,40 40,from(#008080),to(#FFFFFF)); text-align:left; padding-left:6px;\"";
			  }else{
				  $univoco_class ="";
				  $univoco = "";
				  $style_univoco ="";
			 
			  }
			  
			  unset($scatole_avanzo);
			  unset($scatole_intere);
			  unset($avanzo_articolo);
			  
			  $composizione_scatola = "( ".$row['qta_scatola']." / ".$row['qta_minima']." )";
			  
			  $scatole_intere = q_scatole_intere_articolo_ordine($ordine,$c0);
			  $avanzo_articolo = q_articoli_avanzo_articolo_ordine($ordine,$c0);
			  $scatole_avanzo = "( $scatole_intere / $avanzo_articolo )";
			  
			  if($avanzo_articolo > 0){
				$div_avanzo = '<div class="campo_alert">'.$scatole_avanzo.'</div>';
			  }else{
				$div_avanzo = '<div class="campo_ok">'.$scatole_avanzo.'</div>'; 
			  }
			  
			  
			  
			  $misu = "(". $row['u_misura'] ." ". $row['misura'].")"; // misura
			  $c3.= "<I> $misu</I>"; // Descrizione + Peso 
			  //$c5 = $row[5]; //ID GAS
			  //$gas_percent = ($c4/$totale_ordine)*100;

			  //$trasporto = valore_trasporto($ordine,$gas_percent);
			  //$gestione =  valore_gestione($ordine,$gas_percent);
			  
			  $tag_articoli ="";
			  $tag_field="<input type=\"text\" name=box_q_arr[] value=$c4 size=\"4\"><input type=\"hidden\" name=box_art_id[] value=$c0><input type=\"hidden\" name=box_q_ord[] value=$c1>";
			 //RIGA
			 
			  // RIEMPIO I CAMPI      
			   unset ($d);
			   $i=0;
			   $cm = $a_std;   // CLASSE MADRE = STANDARD

			   
				if(is_integer($riga/2)){  
					$output_html .= "<tr class=\"odd $extra\">";    // Colore Riga
				}else{
					$output_html .= "<tr class=\"$extra\">";    
				}
			   //$d[$i][0]="";                    $d[$i][1]="";           $d[$i][2]="";           $i++;
			   //$d[$i][0]="Destinatari";         $d[$i][1]=$tag_articoli;$d[$i][2]="";           $i++; 
			   //$d[$i][0]=$c2;                   $d[$i][1]="";           $d[$i][2]="";           $i++;
			   //$d[$i][0]=$c3;                   $d[$i][1]="";           $d[$i][2]="";           $i++;
			   //$d[$i][0]=$c1;                   $d[$i][1]="";           $d[$i][2]="";           $i++;
			   //$d[$i][0]=$tag_field;            $d[$i][1]="";           $d[$i][2]="";      $i++;
			   //$d[$i][0]="";                    $d[$i][1]="";             $d[$i][2]="";      $i++;
			   //$d[$i][0]="";                    $d[$i][1]="";             $d[$i][2]="";      $i++; 
			   //$output_html .= r_rt2($cm,$d,$riga,8); 
			$output_html .="
					<td $col_1 $univoco_class width=\"10%\">$univoco</td>
					<td>&nbsp</td>    
					<td>$c2</td>
					<td>$c3</td>
					<td>$c1</td>
					<td width=\"10%\">$composizione_scatola </td>
					<td width=\"10%\">$div_avanzo</td>
					<td width=\"10%\">$tag_field</td>
					 
				</tr>
			"; 
			 
			 
			 
			$somma_totalone = $somma_totalone+$c4;
				   
		  $riga++;  
		 }//end while
				   
		
		//TOTALONE
		unset ($d);
		$cm= $a_tot; // HEADER - CLASSE MADRE
		//$d[4][0]="TOTALE";                 
		$output_html .="<input type=\"hidden\" name=\"id\" value=\"$ordine\">  
			   <input type=\"hidden\" name=\"do\" value=\"do_mod\">
			   </table>
			   <input class=\"large green awesome destra\" style=\"margin:20px;\" type=\"submit\" value=\"Salva le nuove quantita'\">
			   
			   </form>
			";      

		  

		  
  return $output_html;      
	
}






if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
		$fullname = fullname_from_id($id_user);
	
	  //SE SONO IL PROPRIETARIO
     (int)$id;
     if($id_user<>id_referente_ordine_globale($id)){
        c1_go_away("?q=no_permission");  
        exit;    
      }
     // --->ID
	 // ---- h_table
	 // ---- msg
	 // --- menu aperto
	 if($do=="do_mod"){
		 $msg .= do_mod1($id_user,$id,$box_q_ord,$box_q_arr,$box_art_id);
		 
	 }
	
	
	  // MENU APERTO
	  $menu_aperto=3;
	   
	  // Campi e intestazioni
	  include ("../ordini_chiusi_sql_core.php");
	  
	  // menu      
	  include("../ordini_chiusi_menu_core.php");
	  
	  // inclusione scheda
	  // ID = ORDINE
	  
	  include ("../ordini_chiusi_form_scheda.php");
	  
	  //INCLUSIONE LISTA ARTICOLI
	  
	  $h_table .=  modifica_quantita_arrivate_form($id);
	  
	  // HEADER HTML
	  //$msg ="Pagina non ancora funzionante";
	  include ("../ordini_chiusi_main.php");
 
}else{
	pussa_via();
} 

?>
