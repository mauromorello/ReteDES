<?php
if (eregi("ordini_aperti_form_articoli_partecipa.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: index.php"); die();
} 
		 
         $euro = "&#0128";
         
         
         // table_sorter_name NOME DELLA TABELLA DA ORDINARE
		 
         
         
		 $valore = id_listino_from_id_ordine($id);
		 $ordine = $id;
		 $ord_query = "ORDER BY retegas_articoli.descrizione_articoli ASC,
								retegas_articoli.articoli_opz_1 ASC,
								retegas_articoli.articoli_opz_2 ASC,
								retegas_articoli.articoli_opz_3 ASC";
		 $cod_tag = "cod_desc";
		 $cod_forn_tag = "cod_forn_asc";
		 $descrizione_tag = "descrizione_asc";
		 
		 //SE è presente SOLO UN ARTICOLO la tabella è composta solo dall'articolo selezionato
		 if(!empty($solo_un_articolo)){
			 $solo_un_articolo = "AND (((retegas_listini.id_articoli)= '$solo_un_articolo'))";
		 }
		 

		 
			$qry="SELECT retegas_articoli.id_articoli, 
			retegas_articoli.codice, 
			retegas_articoli.descrizione_articoli, 
			retegas_articoli.prezzo, 
			retegas_articoli.u_misura, 
			retegas_articoli.misura, 
			retegas_listini.id_listini,
			 retegas_listini.descrizione_listini, 
			 maaking_users.userid, 
			 maaking_users.username, 
			 retegas_tipologia.id_tipologia, 
			 retegas_tipologia.descrizione_tipologia, 
			 retegas_ditte.id_ditte, 
			 retegas_ditte.descrizione_ditte, 
			 retegas_articoli.qta_scatola, 
			 retegas_articoli.qta_minima,
			 retegas_articoli.articoli_note,
			 retegas_articoli.articoli_unico,
			 retegas_articoli.articoli_opz_1,
			 retegas_articoli.articoli_opz_2,
			 retegas_articoli.articoli_opz_3
				  FROM ((((retegas_articoli LEFT JOIN retegas_listini ON retegas_articoli.id_listini = retegas_listini.id_listini) LEFT JOIN maaking_users ON retegas_listini.id_utenti = maaking_users.userid) LEFT JOIN retegas_tipologia ON retegas_listini.id_tipologie = retegas_tipologia.id_tipologia) LEFT JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) LEFT JOIN retegas_dettaglio_ordini ON retegas_articoli.id_articoli = retegas_dettaglio_ordini.id_articoli
				  GROUP BY retegas_articoli.id_articoli, retegas_articoli.codice, retegas_articoli.descrizione_articoli, retegas_articoli.prezzo, retegas_articoli.u_misura, retegas_articoli.misura, retegas_listini.id_listini, retegas_listini.descrizione_listini, maaking_users.userid, maaking_users.username, retegas_tipologia.id_tipologia, retegas_tipologia.descrizione_tipologia, retegas_ditte.id_ditte, retegas_ditte.descrizione_ditte, retegas_articoli.qta_scatola, retegas_articoli.qta_minima
				  HAVING (((retegas_listini.id_listini)=$valore))
				  $ord_query;";    
			$result = $db->sql_query($qry);
			$totalrows = mysql_num_rows($result);

		 
		 
		$titolo_tabella = "Inserisci a destra della tabella le quantità desiderate per ogni articolo, cliccando poi su '<b>SALVA LA SPESA</b>' in fondo alla tabella.
		<br>Se si cliccano le intestazioni delle prime colonne si ordineranno i loro rispettivi valori prima in senso crescente e poi in senso decrescente.
		<br>Prima di cambiare ordinamento assicurarsi di salvare gli articoli prenotati con il pulsante ''salva la spesa'', altrimenti andranno persi.
		<br>Eliminare gli articoli già prenotati dal proprio ordine cliccando su ''<b>E</b>'' (Elimina).
		<br>Se l'articolo è evidenziato in verde vuol dire che ci sono già ordinate (complessivamente) scatole intere.
		<br>Se l'articolo è evidenziato in arancione significa che l'ultima scatola non è completa. Passandoci sopra con il mouse si può vedere
		il quantitativo necessario per completarla.";
		 
		
		$output_html = "<div class=\"ui-widget-content ui-corner-all padding_6px\" style = \"margin-bottom:6px; font-weigth:normal\">
		<h3 id=\"istruzioni\"> Istruzioni : </h3>
		$titolo_tabella 
		</div>
		
		<div class=\"ui-widget-header ui-corner-all padding_6px\">
		<div style=\"margin-bottom:10px;\">
		Articoli ordinabili
		</div>";

		$output_html .="<form method=\"POST\" action=\"ordini_aperti_form_partecipa.php\">"; 
		$output_html .= "<table id=\"$table_sorter_name\">";
		$output_html .= "<thead>
						 <tr>";
		//	<th width=\"6%\"><a href=\"ordini_aperti_form_partecipa.php?id=$id&ordinamento=$cod_tag\">Cod. GASAP</a></th>
	   //                     <th width=\"7%\"><a href=\"ordini_aperti_form_partecipa.php?id=$id&ordinamento=$cod_forn_tag\">Cod. Art. Fornitore</a></th>
	   //                     <th><a href=\"ordini_aperti_form_partecipa.php?id=$id&ordinamento=$descrizione_tag\">Descrizione</a></th>			 
	
		
						 
		$output_html .="    <th width=\"6%\">Cod. GASAP</th>
							<th width=\"7%\">Cod. Art. Fornitore</th>
							<th>Descrizione</th>
							<th>&nbsp</th>
							<th width=\"13%\">Prezzo x quantità</th>
							<th width=\"5%\">Q.<br>Scat.</th>  
							<th width=\"5%\">Q.<br>Min </th>  
							<th width=\"6%\">Q.<br>Ord.</th>
							<th width=\"16%\">&nbsp</th>
						 </tr>
						 </thead>
						 <tbody>
						 ";
		 
 
	   $riga=0;       // Contatore per pari e dispari e per id_riga
		 
		 while ($row = mysql_fetch_array($result)){
		 $id_box++;
		 
		 
			  $c0 = $row[0]; //id_articoli
			  $c1 = $row[1]; //codice
			  $c2 = $row[2]; //descrizione_articolo
			  $c3 = (float)$row[3]; //prezzo
			  $c4 = $row[4]; //U_misura
			  $c5 = $row[5]; //misura 
			  $c6 = $row[6]; //id_listini
			  $c7 = $row[7]; //descrizione_listini
			  $c8 = $row[8]; //userid
			  $c9 = $row[9]; //username
			  $c10 = $row[10]; //id_tipologia
			  $c11 = $row[11]; //descrizione_tipologia
			  $c12 = $row[12]; //id_ditte
			  $c13 = $row[13]; //descrizione_ditte
			  $c14 = (float)round($row[14],2); //qta scatola  
			  $c15 = (float)round($row[15],2); //qta minima
			  $c17 = strip_tags($row[16]); //qta minima
			  $c18 = $row["articoli_unico"];
		//---------------Controllo se articolo doppio
			$ar_dopp =$db->sql_query("SELECT Count(retegas_dettaglio_ordini.id_articoli) AS ConteggioDiid_articoli, 
									  Sum(retegas_dettaglio_ordini.qta_ord) AS SommaDiqta_ord,
									  retegas_dettaglio_ordini.id_utenti,
									  retegas_dettaglio_ordini.id_amico,
									  retegas_dettaglio_ordini.id_ordine,
									  retegas_dettaglio_ordini.id_articoli                                      
									  FROM retegas_dettaglio_ordini
									  GROUP BY retegas_dettaglio_ordini.id_utenti, retegas_dettaglio_ordini.id_amico, retegas_dettaglio_ordini.id_ordine, retegas_dettaglio_ordini.id_articoli
									  HAVING (((retegas_dettaglio_ordini.id_utenti)='$id_user') AND ((retegas_dettaglio_ordini.id_amico)='0') AND ((retegas_dettaglio_ordini.id_ordine)='$ordine') AND ((retegas_dettaglio_ordini.id_articoli)='$c0'));");
			
			$r_ar_dopp = mysql_fetch_row($ar_dopp);
			$c16 = $r_ar_dopp[1];
			$nascondi="";
			
			$scatole_intere = (int)q_scatole_intere_articolo_ordine($ordine,$c0);
			$avanzo_articolo = (float)round(q_articoli_avanzo_articolo_ordine($ordine,$c0),2);
			
			
			if($scatole_intere>0){
				$colore = "campo_ok";
			}else{
				$colore = "";
			} 
			if($avanzo_articolo>0){
				$colore = "campo_alert";
			}
			
			
			$info_complete="";
			
			
			
			if(($avanzo_articolo >0) & ($scatole_intere>0)){
			$info_complete ='<b>Situazione articolo nell\'ordine</b><br><br>'.
							'Scatole intere: <b>'.$scatole_intere.'</b><br>'.
							'Avanzo articoli: <b>'.$avanzo_articolo.'</b><br><br>'.
							'Occorrenti per completare una scatola : <b>'.($row["qta_scatola"] - $avanzo_articolo).'</b><br>';
			}else{
				 if($avanzo_articolo == 0){
				$info_complete ='<b>Situazione articolo nell\'ordine</b><br><br>'.
								'Scatole intere: <b>'.$scatole_intere.'</b><br>';                         'Occorrenti per completare una scatola : <b>'.($row["qta_scatola"] - $avanzo_articolo).'</b><br>';
				}
					
				if($scatole_intere == 0){
				$info_complete ='<b>Situazione articolo nell\'ordine</b><br><br>'.
								'Scatole intere: <b> Nessuna </b><br>'.
								'Avanzo articoli: <b>'.$avanzo_articolo.'</b><br><br>'.
								'Occorrenti per completare una scatola : <b>'.($row["qta_scatola"] - $avanzo_articolo).'</b><br>';
				}
				
				
			}
			
			$tb_plus = '
			<script type="text/javascript">
			$(document).ready(function(){
				$("a.click_plus_'.$id_box.'").click(function(event){
					event.preventDefault();
						var valoreAttuale = $("a#min_'.$id_box.'").html();
						var valoreDaSommare = $("input#textbox_'.$id_box.'").val();
						var valoreSomma = +valoreAttuale + +valoreDaSommare;
						valoreSomma = valoreSomma.toFixed(2); 
						$("input#textbox_'.$id_box.'").val(valoreSomma);
					});
					
				   
				   $("a.click_minus_'.$id_box.'").click(function(event){
					event.preventDefault();
						var valoreAttuale = $("a#min_'.$id_box.'").html();					
						var valoreDaSommare = $("input#textbox_'.$id_box.'").val();
						var valoreSomma = +valoreDaSommare - +valoreAttuale;
						
						if (valoreSomma < 0) {
						  valoreSomma=0;
						}
						valoreSomma = valoreSomma.toFixed(2);
						
						$("input#textbox_'.$id_box.'").val(valoreSomma);
					});
					  
				}); 
			</script>
			';
			
	
			
			
			
			if(empty($r_ar_dopp[0])){
			   // se non è stato ancora ordinato nessun articolo
			   //echo "ARTICOLO Doppio ".$r_ar_dopp[0]."<br>";
			   // NUOVO PEZZO
			   // CONTROLLO SE E' UN ARTICOLO COMPOSTO
			   // SE E' COMPOSTO PER ORA LO NASCONDO
			   
			   
			   if((!empty($row["articoli_opz_1"]))){
				   // Ci devono essere tutte le OPZ1 compilata, altrimenti non funczione
				   
				   if ($codice_attuale<>$row["descrizione_articoli"]){
							$mostra_intestazione = "SI";
				   }else{
							$mostra_intestazione = "NO";
				   }
				   
				   
				   $elemento_nascosto ++;
				   $nascondi = "ui-helper-hidden";
				   
				   $codice_attuale = $row["descrizione_articoli"];
				   // se cambia il codice articolo allora inserisco una riga riepilogativa
				   
		   
			   }else{
				   //$elemento_nascosto =0;
				   $nascondi = "";
				   $mostra_intestazione = "NO";
			   }
			   
			   if ($mostra_intestazione=="SI"){
				
				   
				   //echo "Elemento nascosto = $elemento_nascosto, Cod.attuale = $codice_attuale, id_listini =".$row["id_listini"]."<br>";
				   //COSTRUISCO LA UL LI
				   $query_ul = "SELECT retegas_articoli.*
								FROM retegas_articoli
								WHERE retegas_articoli.descrizione_articoli='".$row["descrizione_articoli"]."'
								AND retegas_articoli.id_listini='".$row["id_listini"]."'
								ORDER BY
								retegas_articoli.articoli_opz_1 ASC,
								retegas_articoli.articoli_opz_2 ASC,
								retegas_articoli.articoli_opz_3 ASC;";
				   $resu = $db->sql_query($query_ul);
				   
				   $sulli = "<a href=\"#\" id=\"selector_".$row["id_articoli"]."\">".$row["descrizione_articoli"]."</a>
				   <div id=\"content_".$row["id_articoli"]."\" class=\"ui-helper-hidden\">
				   <ul>
				   ";
				   
				   $nuova_riga=1;
				   $conto =0;
				   unset ($aulli_op1);
				   unset ($aulli_op2);
				   unset ($aulli_op3);
				   unset ($aulli_art);
				   
				   while ($row_ulli = mysql_fetch_array($resu)){     
				   $aulli_op1[]= $row_ulli["articoli_opz_1"];
				   $aulli_op2[]= $row_ulli["articoli_opz_2"]; 
				   $aulli_op3[]= $row_ulli["articoli_opz_3"];
				   $aulli_art[]= $row_ulli["id_articoli"];
				   $conto++; 
				   }
				   //echo  "OP 1: ".$row_ulli["articoli_opz_1"]." , op2:".$row_ulli["articoli_opz_2"]." , op3;".$row_ulli["articoli_opz_3"]."<br>";
				   for($i=0; $i<$conto ;$i++){
				   $op1=$aulli_op1[$i];
				   $op2=$aulli_op2[$i];
				   $op3=$aulli_op3[$i];
				   
				   $op1p=$aulli_op1[$i-1];
				   $op2p=$aulli_op2[$i-1];
				   $op3p=$aulli_op3[$i-1];
				   
				   $op1f=$aulli_op1[$i+1];
				   $op2f=$aulli_op2[$i+1];
				   $op3f=$aulli_op3[$i+1];	   
				   
				   
				   if ($op1<>$op1p){
				   $sulli .="<li><a href=\"#\">$op1</a>   
								<ul>
				   ";    
				   }
				   if ($op2<>$op2p){
				   $sulli .="<li><a href=\"#\">$op2</a>
								<ul>
				   ";    
				   }
				   $sulli .= "<li><a href=\"".$aulli_art[$i]."\">$op3</a></li><! OP3 !>
					 ";   
				   if ($op2<>$op2f){
				   $sulli .="</ul>
						 </li>
				   ";
				   }
				   if ($op1<>$op1f){
				   $sulli .="</ul>
						 </li>
				   ";
				   }
				   
				   
				   
				   
				   }
					
				   
				   
				   
				   $sulli .= "</ul>	   
				   ";
				   $descri = $row["descrizione_articoli"];
				   $sulli .="
							<script type=\"text/javascript\">
							$(document).ready(function(){
												$('#selector_".$row["id_articoli"]."').menu({
													content: $('#content_".$row["id_articoli"]."').html(),
													flyOut : true        
												});
											});
						   </script>
						   </div>";
				   
				   
				   
				   unset($resu);
				   
				   $output_html .= '<tr class="raggruppato">
									<td>-----</td>
									<td>-----</td>
									<td style="text-align:left;">'.$sulli.'</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>';
									   
			   }
			   
			   // NUOVO PEZZO
			   
			   
				
			   $c20=" 
			   <a href=\"#\" class=\"click_plus_$id_box awesome small transparent\">+</a>
			   <input type=\"text\" name=box_value[] value=\"0\" size=\"2\" id=\"textbox_$id_box\">
			   <a href=\"#\" class=\"click_minus_$id_box awesome small transparent\">-</a>
			   <input type=\"hidden\" name=box_id[] value=$c0>
			   <input type=\"hidden\" name=box_q_att[] value=$c16>
			   <input type=\"hidden\" name=box_q_min[] value=$c15>
			   <input type=\"hidden\" name=box_q_uni[] value=$c18>
			   ".$tb_plus;
			   $c21="";
			}else{
				// da sistemare	
				$c20="<input type=\"hidden\" name=box_value[] value=\"0\" size=\"3\">
				<input type=\"hidden\" name=box_id[] value=$c0>
				<input type=\"hidden\" name=box_q_att[] value=$c16>
				<input type=\"hidden\" name=box_q_min[] value=$c15>
				<input type=\"hidden\" name=box_q_uni[] value=$c18>";
				
				if($c18<>"1"){
					$url_mod="ordini_aperti_mod_q.php?id=$c0&id_ordine=$ordine&q_min=$c15"; //&n_riga=
					$color="yellow";
					$url_add=$url_mod;
				}else{
					$url_mod="ordini_aperti_mod_q.php?id=$c0&id_ordine=$ordine&q_min=$c15&mode=uni"; //ARTICOLO UNICO              
					$color="orange";
					$url_add=$url_mod;
				}
				$url_eli="ordini_aperti_mod_q.php?id_arti=$c0&id_ordine=$ordine&do=do_del_all_art"; 
				$add= "<a class=\"option marrone awesome\" href=\"$url_add\" title=\"Aggiungi\">A</a>";
				$mod= "<a class=\"option $color awesome\" href=\"$url_mod\" title=\"Modifica\">M</a>";
				$eli= "<a class=\"option black awesome\" href=\"$url_eli\" title=\"Elimina\">E</a>";
				
				$c20 .="$mod
						$eli";
				$c21= (float)round($r_ar_dopp[1],2);
			}
			if(!empty($c17)){
			$c17 = "<a TITLE=\"Funzionalità per il momento disattivata.\">".substr($c17,0,12)."....</a>";
			}else{
			$c17 = "";
			}
	
			if($c18==1){$unico = "-U";}
	
			//---------------------------------------        
			$op3 = '<a rel="'.$c0.'" class="awesome small blue display_full_message" style="margin:4px;">Info</a>';  
			  
			if(is_integer($riga/2)){
			$output_html .= "<tr class=\"odd $nascondi\" id=\"".$row["id_articoli"]."\">";    // Colore Riga
			}else{
			$output_html .= "<tr class=\"$nascondi\" id=\"".$row["id_articoli"]."\">";    
			}
			
			$opzioni = "( ".$row["articoli_opz_1"]." ".$row["articoli_opz_2"]." ".$row["articoli_opz_3"]." )";
			
			$output_html .="<td width=\"7%\"><a class=\"$colore\" title=\"$info_complete\">$c0$unico</a></td>";
			$output_html .="<td width=\"7%\">$c1</td>";    
			$output_html .="
				 <td style=\"text-align:left;\"><a href=\"../articoli/articoli_form.php?id=$c0\">$c2 $opzioni</a></td>
				 <td width=\"5%\">$op3</td>
				 <td width=\"15%\">$c3 $euro x $c4 $c5</td>
				 <td width=\"5%\">$c14</td> 
				 <td width=\"5%\"><a id=\"min_$id_box\">$c15</a></td> 
				 <td width=\"5%\" style=\"text-align:right;\">$c21</td>
				 <td width=\"12%\" style=\"text-align:right; vertical-align:50%;\">$c20</td>
				 </tr>";
		  $riga++;  
		 }//end while
		 $poi=1;
		 $output_html .= "  </tbody>
							</table>";
		 $output_html .= "<input type=\"hidden\" name=\"id\" value=\"$id\">  
			   <input type=\"hidden\" name=\"do\" value=\"salva_carrello\">
			   <input type=\"submit\" class = \"large green awesome\" style=\"margin:20px;\" value=\"Salva la spesa !\">
			   </div>
			   <hr>";

	   
			   
			   
		 $h_table .= $output_html;
	
	  // END TABELLA ----------------------------------------------------------------------------
	  

?>