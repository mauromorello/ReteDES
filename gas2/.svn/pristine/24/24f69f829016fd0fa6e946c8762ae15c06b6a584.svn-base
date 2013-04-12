<?php
if (eregi("ordini_chiusi_form_scheda.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
} 


//APPOGGIO PER SALTARE LA VECCHIA SCHEDA
$H_app = $h_table;

	
	//echo " DENTRO FORM A QUESTO PUNTO ID = ".$id;  
		// QUERY VISUAL
	  //$query_visual = "SELECT * FROM retegas_ordini WHERE id_ordini='$id';";
	  
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
			  $listino = listino_nome($c2);
			  $tipologia = tipologia_nome_from_listino($c2);
			  
			  
			  
			  $minimo_articoli = $c14;
			  $minimo_scatole = $c15;
			  $articoli_in_ordine = $row_stat["somma_qta_articoli_ordinati"]." / ".$row_stat["somma_qta_articoli"];
			  
			  $scatole_in_ordine_ordinate = q_scatole_intere_ordine($c1);
			  $scatole_in_ordine_arrivate = q_scatole_intere_ordine_arr($c1);
			  
			  
			  $avanzo_articoli_ordinati=q_articoli_avanzo_ordine($c1);
			  $avanzo_articoli_arrivati=q_articoli_avanzo_ordine_arr($c1);
			  
			  if($row["is_printable"]==1){
				$stampabile = "STAMPABILE";                  
			  }else{
				$stampabile = "<b>DA CONFERMARE</b>";  
			  }
			  
			  //user level
			  $user_level = "Utente Semplice;<br> "; 
			  if (id_referente_ordine_proprio_gas($id,id_gas_user($id_user))==$id_user){
				$user_level .= "Referente Proprio GAS;<br> "; 
				}
				if (id_referente_ordine_globale($id)==$id_user){  
					$user_level .= "Referente ORDINE; "; 
			  }
			  
			  $avanzamento_ordine = avanzamento_ordine_from_id_ordine($id);
			  $per = number_format(($avanzamento_ordine),0);
			  
			  $referente_generale=fullname_from_id($c3)." (".telefono_from_id($c3).")";
			  $referente_gas = fullname_referente_ordine_proprio_gas($id,id_gas_user($id_user))." (".tel_referente_ordine_proprio_gas($id,id_gas_user($id_user)).")";
			  
			  $data_apertura=conv_date_from_db($c7);
			  $data_chiusura=conv_datetime_from_db($c8);
			  $data_arrivo_merce = conv_date_from_db($c9);
			  
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
										Anagrafiche
										</td>
									</tr> 
									<tr class=\"scheda\">
										<th $col_1>Ordine</th>
										<td $col_2>$ordine</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Fornitore</th>
										<td $col_2>$fornitore</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Mail Fornitore</th>
										<td $col_2>$mail_fornitore</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Listino</th>
										<td $col_2>$listino</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Merce trattata</th>
										<td $col_2>$tipologia</td>
									</tr>
									
									<tr class=\"titolino\">
										<td colspan=2>
										Situazione Ordine
										</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Avanzamento:</th>
										<td $col_2>Ordine CHIUSO - $stampabile</div>
										</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Articoli in ordine :<br>
												   Ordinati / Arrivati        
										</th>
										<td $col_2>$articoli_in_ordine</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Scatole in ordine</th>
										<td $col_2>$scatole_in_ordine_ordinate / $scatole_in_ordine_arrivate</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Avanzo articoli</th>
										<td $col_2>$avanzo_articoli_ordinati / $avanzo_articoli_arrivati</td>
									</tr>
									<tr class=\"titolino\">
										<td colspan=2>
										Referenti
										</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Condizione utente corrente ($fullname)</th>
										<td $col_2>$user_level</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Referente generale</th>
										<td $col_2>$referente_generale</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Referente del tuo gas ($gas_name)</th>
										<td $col_2>$referente_gas</td>
									</tr>

								</table>
							</td>
							<td width=\"50%\">
								<table>
									
									<tr class=\"titolino\">
										<td colspan=2>
										Scadenze
										</td>
									</tr>                                    
									<tr class=\"scheda\">
										<th $col_1>Data apertura</th>
										<td $col_2>$data_apertura</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Data Chiusura</th>
										<td $col_2>$data_chiusura</td>
									</tr>
	
									<tr class=\"titolino\">
										<td colspan=2>
										Valore Attuale Ordine
										</td>
									</tr>
									<tr class=\"soldi\">
										<th $col_1>Valore netto attuale</th>
										<td $col_2>$valore_netto</td>
									</tr>
									<tr class=\"soldi\">
										<th $col_1>Costo trasporto</th>
										<td $col_2>$costo_trasporto</td>
									</tr>                                    
									<tr class=\"soldi\">
										<th $col_1>Costo Gestione</th>
										<td $col_2>$costo_gestione</td>
									</tr>
									<tr class=\"soldi\">
										<th $col_1>Costo Proprio GAS</th>
										<td $col_2>---</td>
									</tr>
									<tr class=\"soldi\">
										<th $col_1>Maggiorazione proprio GAS</th>
										<td $col_2>---</td>
									</tr>
									<tr class=\"soldi grosso\">
										<th $col_1>TOTALE ORDINE</th>
										<td $col_2>$totale_ordine</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Note:</th>
										<td $col_2 style=\"padding:6px; text-align:left; border: solid 1px;\">
										".$row["note_ordini"]."
										</td>
										
								   </tr>  
								</table>
							</td>
						</tr>
					</table>
					
					";

	  // END TABELLA ----------------------------------------------------------------------------
$h_table = $H_app.schedina_ordine($id,$id_user);	  
//$h_table .= "<hr>".schedona_ordine($id,$id_user);    


?>
