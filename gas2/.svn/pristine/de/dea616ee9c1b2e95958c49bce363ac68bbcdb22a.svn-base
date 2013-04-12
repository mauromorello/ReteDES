<?php
if (eregi("ordini_aperti_form_scheda.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

$H_app = $h_table; 
		// QUERY VISUAL
	  $query_visual = "SELECT * FROM retegas_ordini WHERE id_ordini='$id';";
	  
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
	  
	  $titolo_tabella="Ordine n. $id";
	  
	  
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
			  $note = $row["note_ordini"];
			  
		 // Trasformazioni      
			  $ordine_nome = $c1." ($c4)";
			  $fornitore = ditta_nome_from_listino($c2);
			  $mail_fornitore = ditta_mail_from_listino($c2);
			  $listino = listino_nome($c2);
			  $tipologia = tipologia_nome_from_listino($c2);
			  
			  $minimo_articoli = $c14;
			  $minimo_scatole = $c15;
			  $articoli_in_ordine = (float)round($row_stat["somma_qta_articoli_ordinati"],2);
			  
			  $scatole_in_ordine = q_scatole_intere_ordine($c1);
			  if($scatole_in_ordine < $minimo_scatole){
				  $style_scatole =" style=\"background-image:-webkit-gradient(linear,0 20,50 50,from(#FF0000),to(#FFFFFF));\"";
			  }
			  
			  
			  
			  $avanzo_articoli = q_articoli_avanzo_ordine($c1);
			 
              if($avanzo_articoli>0){
				  $style_avanzo =" style=\"background-image:-webkit-gradient(linear,0 20,50 50,from(#FF0000),to(#FFFFFF));\"";
			  }
			  
			  $bacino_tot = ordine_bacino_utenti($id);
			  $bacino_part = ordine_bacino_utenti_part($id);
			  $bacino_non_part = $bacino_tot-$bacino_part;
			  $bacino_percentuale = number_format((($bacino_part/$bacino_tot)*100),1,",","")."%";
			  
			  $bacino_tot_mio_gas = gas_n_user($gas);
			  $bacino_part_mio_gas = ordine_bacino_utenti_part_gas($id,$gas);
			  $bacino_non_part_mio_gas = $bacino_tot_mio_gas-$bacino_part_mio_gas;
			  $bacino_percentuale_mio_gas = number_format((($bacino_part_mio_gas/$bacino_tot_mio_gas)*100),1,",","")."%";
			  
			  $gas_coinvolti=ordine_gas_coinvolti($id);
			  
			  $costo_mio_gas = number_format(valore_assoluto_costo_mio_gas($id,$gas),2,",","");
			  $maggiorazione_percentuale_mio_gas = number_format(valore_percentuale_maggiorazione_mio_gas($id,$gas),2,",",""); 
			  $maggiorazione_mio_gas = number_format(valore_maggiorazione_mio_gas($id,$gas,valore_totale_ordine_qarr($id)),2,",","");
			  
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
			  $data_chiusura=conv_date_from_db($c8);
			  $data_arrivo_merce = conv_date_from_db($c9);
			  
			  $costo_trasporto = number_format($row_stat['costo_trasporto'],2,",","");
			  $costo_gestione = number_format($row_stat['costo_gestione'],2,",","");
			  $valore_netto= number_format($row_stat['somma_valore_ordine'],2,",","");
			  $totale_ordine=number_format($row_stat['costo_gestione']+$row_stat['costo_trasporto']+$row_stat['somma_valore_ordine'],2,",","");
			  if($totale_ordine>0){
				$percentuale_costo_trasporto = number_format(($costo_trasporto/$totale_ordine)*100,2);		
				$percentuale_costo_gestione = number_format(($costo_gestione/$totale_ordine)*100,2); 
			  }
			  //echo $valore_netto;
			  if($valore_netto>0){
			  //$img_grafico = crea_grafico_google_1($id);
			  
			  $zona_grafici="<td width=\"20%\">
								<table>
									<tr class=\"titolino\">
										<td colspan=2>
										GRAFICI
										</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Partecipazione GLOBALE</th>
										<td $col_2><img SRC=\"http://chart.apis.google.com/chart?chs=160x60&chd=t:$bacino_part,$bacino_non_part&cht=p3&chl=$bacino_percentuale\" align=\"center\"></td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Partecipazione MIO GAS</th>
										<td $col_2><img SRC=\"http://chart.apis.google.com/chart?chs=160x60&chd=t:$bacino_part_mio_gas,$bacino_non_part_mio_gas&cht=p3&chl=$bacino_percentuale_mio_gas\" align=\"center\"></td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Ord. / Trasp. / Gest.</th>
										<td $col_2><img SRC=\"http://chart.apis.google.com/chart?chs=160x60&chd=t:".intval($costo_gestione).",".intval($costo_trasporto).",".intval($valore_netto)."&chds=0,10000&cht=p3&chl=G|T|N\" align=\"center\"></td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Valore Ordine</th>
										<td $col_2>$img_grafico</td>
									</tr>                                
									
									
								</table>
							</td>";
			  //echo $zona_grafici;
			  
			  }
	   //descrizione
	   // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  
	  //$h_table .= ditte_menu_1();
	  
	 
	  
	  
	  
	  

					

				   
	 //<div class=\"ui-widget-header ui-corner-all padding_6px m6b\">$titolo_tabella</div>
					
	 $h_table .=  " <div class=\"ie_scheda_fix\">
					<table>
						<tr>
							<td width=\"39%\">
								<table>
									<tr class=\"titolino\">
										<td colspan=2>
										Anagrafiche
										</td>
									</tr> 
									<tr class=\"scheda\">
										<th $col_1>Ordine</th>
										<td $col_2>$ordine_nome</td>
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
										<th $col_1>Avanzamento ($avanzamento_ordine%)</th>
										<td $col_2>
										<div id=\"progressbar\"></div>
										</td>
									</tr>
										  
									<tr class=\"scheda\">
										<th $col_1>Minimo Articoli</th>
										<td $col_2>$minimo_articoli</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Minimo scatole</th>
										<td $col_2>$minimo_scatole</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Articoli in ordine :										</th>
										<td $col_2>$articoli_in_ordine</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Scatole in ordine</th>
										<td $col_2 $style_scatole>$scatole_in_ordine</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Avanzo articoli</th>
										<td $col_2 $style_avanzo>$avanzo_articoli</td>
									</tr>
									<tr class=\"titolino\">
										<td colspan=2>
										Bacino utenze
										</td>
									</tr>
									<tr class=\"scheda\">
										<th $col_1>Gas coinvolti:</th>
										<td $col_2><b>$gas_coinvolti</b> ($bacino_tot Utenti)</div>
										</td>
									</tr>      
									<tr class=\"scheda\">
										<th $col_1>Part. MIO GAS / Tutti i GAS</th>
										<td $col_2>$bacino_part_mio_gas / $bacino_part</td>
									</tr>
								</table>
							</td>
							<td width=\"39%\">
								<table>
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
										<th $col_1>Costo trasporto   <b class=\"small_link\">($percentuale_costo_trasporto%)</b></th>
										<td $col_2>$costo_trasporto</td>
									</tr>                                    
									<tr class=\"soldi\">
										<th $col_1>Costo Gestione   <b class=\"small_link\">($percentuale_costo_gestione%)</b></th>
										<td $col_2>$costo_gestione</td>
									</tr>
									<tr class=\"soldi\">
										<th $col_1>Costo mio gas</th>
										<td $col_2>".$costo_mio_gas."</td>
									</tr>
									<tr class=\"soldi\">
										<th $col_1>Maggiorazione mio gas del ".$maggiorazione_percentuale_mio_gas."%<br>(".testo_maggiorazione_mio_gas($id,$gas).")</th>
										<td $col_2>$maggiorazione_mio_gas</td>
									</tr>
									<tr class=\"soldi grosso\">
										<th $col_1>TOTALE ORDINE</th>
										<td $col_2>$totale_ordine</td>
									</tr>
								</table>
							</td>
							
							$zona_grafici
							
						</tr>
					</table>";
	 if(trim($note)<>""){
	 $h_table .="   <div class=\"ui-widget-content ui-corner-all padding_6px\">
					<h3>Note ordine :</h3>
					$note
					</div>
					</div>
					<br>
					";
	 }				
	
			//#FF0000  rosso
			//#FFFF00 giallo
			//#008000
//--------------------------CALCOLO RGB


$r=200;
$g=0;
$b=0;

if($per<70){ 
$r=255;
$g=255;
$b=0;
}

if($per<30){ 
$r=0;
$g=230;
$b=30;
}

//--------------------------------FINE CALCOLO RGB
			
			
			
					
		   $h_table .= " <script type=\"text/javascript\"> 
							$(function() {
								$(\"#progressbar\").progressbar({
									value: $per
								});
								 $(\"#progressbar\").css({ 'background': '#fff' });
								 $(\"#progressbar > div\").css({ 'background': 'rgba($r,$g,$b,1)' });
							});
								
					 </script>
					";              

	  // END TABELLA ----------------------------------------------------------------------------
$h_table = $H_app.schedina_ordine($id,$id_user);	  

?>