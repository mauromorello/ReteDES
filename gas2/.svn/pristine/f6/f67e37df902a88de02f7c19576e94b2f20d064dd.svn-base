<?php
if (eregi("ordini_chiusi_form_scheda_fornitore.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
} 

	  
	  $query_visual = "SELECT
						retegas_ordini.id_ordini,
						retegas_ordini.id_listini,
						retegas_ordini.id_utente,
						retegas_ordini.descrizione_ordini,
						retegas_ordini.data_apertura,
						retegas_ordini.data_chiusura,
						retegas_ordini.data_merce,                        
						retegas_ordini.costo_trasporto,
						retegas_ordini.costo_gestione,
						retegas_ordini.privato,
						retegas_ordini.min_articoli,
						retegas_ordini.min_scatola, 
						retegas_ordini.senza_prezzo, 
						retegas_ordini.is_printable,
						retegas_ordini.note_ordini
						FROM
						retegas_ordini
						WHERE
						retegas_ordini.id_ordini='$id';";					   
				
	  
	  $query_stat ="SELECT
					retegas_ordini.descrizione_ordini,
					retegas_ordini.id_ordini,
					retegas_ordini.costo_trasporto,
					retegas_ordini.costo_gestione,
					Sum(retegas_dettaglio_ordini.qta_arr) AS somma_qta_articoli,
					Count(retegas_dettaglio_ordini.id_dettaglio_ordini) AS conteggio_ordini,
					Sum(retegas_dettaglio_ordini.qta_arr*retegas_articoli.prezzo) AS somma_valore_ordine,
					Sum(retegas_dettaglio_ordini.qta_arr DIV retegas_articoli.qta_scatola) AS somma_scatole,
					Sum(retegas_dettaglio_ordini.qta_arr MOD retegas_articoli.qta_scatola) AS avanzo_articoli_scatole,
					Sum(retegas_dettaglio_ordini.qta_ord) AS somma_qta_articoli_ordinati
					FROM
					retegas_ordini
					Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
					Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
					WHERE
					retegas_ordini.id_ordini =  '$id'
					GROUP BY
					retegas_ordini.id_ordini,
					retegas_ordini.id_listini,
					retegas_ordini.descrizione_ordini,
					retegas_ordini.costo_trasporto,
					retegas_ordini.costo_gestione
					LIMIT 1";
						  
	  $result = $db->sql_query($query_visual);
	  $row = $db->sql_fetchrow($result);
	  
	  $result_stat = $db->sql_query($query_stat);
	  $row_stat = $db->sql_fetchrow($result_stat);                      
	  
	  
			  
	  // TITOLO FORM_ADD
	  
	  $titolo_tabella="Ordine n. $id (CHIUSO)";
	  
	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"30%\"";
	  $col_2="width=\"30%\""; 
	  
	  // OPZIONI

	  // Campi
	  
	  

		 
		 // VALORI DELLE CELLE da DB---------------------
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = $row["$d3"];
			  $c4 = $row["$d4"];
			  $c5 = $row["$d5"];
			  $c6 = $row["$d6"];
			  $c7 = $row["$d7"];
			  $c8 = $row["$d8"];
			  $c9 = $row["$d9"];
			  $c10 = $row["$d10"];
			  $c11 = $row["$d11"];
			  $c12 = $row["$d12"];
			  $c13 = $row["$d13"];
			  $c14 = $row["$d14"];
			  $c15 = $row["$d15"];
			  
		 // Trasformazioni      
			  $ordine = $c1." ($c4)";
			  $fornitore = ditta_nome_from_listino($c2);
			  $mail_fornitore = ditta_mail_from_listino($c2);
			  $indirizzo_fornitore = ditta_indirizzo_from_listino($c2);


			  $gas_nome = gas_user($row["id_utente"]);
			  
			  $articoli_in_ordine = $row_stat["somma_qta_articoli_ordinati"];
			  //$scatole_in_ordine =$row_stat["somma_scatole"];
			  $scatole_in_ordine = q_scatole_intere_ordine($c1);

	  
			  $avanzamento_ordine = avanzamento_ordine_from_id_ordine($id);
			  $per = number_format(($avanzamento_ordine),0);
			  
			  $referente_generale=fullname_from_id($c3);
			  $telefono_referente_generale  = telefono_from_id($c3);
			  $mail_referente_generale = id_user_mail($row["id_utente"]) ;

			  
			  $costo_trasporto = number_format($row_stat['costo_trasporto'],2,",","");
			  $costo_gestione = number_format($row_stat['costo_gestione'],2,",","");
			  $valore_netto= number_format($row_stat['somma_valore_ordine'],2,",","");
			  $totale_ordine=number_format($row_stat['costo_gestione']+$row_stat['costo_trasporto']+$row_stat['somma_valore_ordine'],2,",","");
			  
	   //descrizione
	   // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  
	  //$h_table .= ditte_menu_1();
	  
	 
	  
	  

	 $h_table .=  " 
					<div class=\"ie_scheda_fix\">
					<table>
						<tr>
							<td width=\"50%\">
								<table>
									<tr class=\"titolino\">
										<td colspan=2>
										GAS Proponente
										</td>
									</tr> 
									<tr class=\"scheda\">
										<th $col_1>Nome</th>
										<td $col_2>$gas_nome</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Indirizzo sede</th>
										<td $col_2></td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Mail (GAS)</th>
										<td $col_2></td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Website</th>
										<td $col_2></td>
									</tr>									
									<tr class=\"titolino\">
										<td colspan=2>
										Referente Ordine
										</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Nome</th>
										<td $col_2>$referente_generale</div>
										</td>
									</tr>
										  
									<tr class=\"scheda\">
										<th $col_1>Telefono</th>
										<td $col_2>$telefono_referente_generale</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Mail referente</th>
										<td $col_2>$mail_referente_generale</td>
									</tr>
								</table>
							</td>
							<td width=\"50%\">
								<table>
								<tr class=\"titolino\">
									<td colspan=2>
									Fornitore
									</td>
								</tr>
								<tr class=\"scheda\">
									<th $col_1>Nome</th>
									<td $col_2>$fornitore</div>
									</td>
								</tr>      
								<tr class=\"scheda\">
									<th $col_1>Indirizzo</th>
									<td $col_2>$indirizzo_fornitore</td>
								</tr>
								<tr class=\"scheda\">
									<th $col_1>Mail</th>
									<td $col_2>$mail_fornitore</td>
								</tr>
									<tr class=\"titolino\">
										<td colspan=2>
										Ordine
										</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Cod. ordine (uso gas)</th>
										<td $col_2>$ordine</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Scatole in ordine</th>
										<td $col_2>$scatole_in_ordine</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Articoli ORD / ARR</th>
										<td $col_2>$articoli_in_ordine</td>
									</tr>
									<tr class=\"soldi\">
										<th $col_1>Valore netto attuale</th>
										<td $col_2>$valore_netto</td>
									</tr>										
								   </tr>  
								</table>
							</td>
						</tr>
					</table>
					
					";

	  // END TABELLA ----------------------------------------------------------------------------
	  

?>