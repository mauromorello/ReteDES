<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Relazioni DB";


//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}  
	  
	  
	  switch ($do){
        
        case "del_lis":
             if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
                if(isset($id)){ 
                    $db->sql_query( "DELETE FROM `retegas_listini` WHERE `retegas_listini`.`id_listini` = '$id' LIMIT 1;");                                  
                } 
             }  
          
		case "lis":
            $delete_command = "?do=del_lis&id=";
			$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">LISTINI SENZA DITTA</div><br></hr>';
			$query="SELECT
					retegas_listini.id_listini,
					retegas_listini.descrizione_listini,
					retegas_listini.id_utenti,
					retegas_listini.id_tipologie,
					retegas_listini.id_ditte,
					retegas_listini.data_valido
					FROM
					retegas_listini
					Left Join retegas_ditte ON retegas_ditte.id_ditte = retegas_listini.id_ditte
					WHERE
					retegas_ditte.id_ditte IS NULL";
		break;
		
		
		
		case "del_art":
			 if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
				if(isset($id)){ 
					$db->sql_query( "DELETE FROM `retegas_articoli` WHERE `retegas_articoli`.`id_articoli` = '$id' LIMIT 1;");                                  
				} 
			 }
	   // SENZA BREAK PER VISUALIZZARE ALTRI RECORDS
		
		case "art":
		$delete_command = "?do=del_art&id=";
		$intestazione = '<div class="ui-state-error ui-corner-all padding_6px">Articoli Orfani</div><br></hr>';
			$query="SELECT
					retegas_articoli.id_articoli,
					retegas_articoli.id_listini,
					retegas_articoli.codice,
					retegas_articoli.u_misura,
					retegas_articoli.misura,
					retegas_articoli.descrizione_articoli,
					retegas_articoli.qta_scatola,
					retegas_articoli.prezzo,
					retegas_articoli.ingombro,
					retegas_articoli.qta_minima,
					retegas_articoli.qta_multiplo,
					retegas_articoli.articoli_note
					FROM
					retegas_articoli
					Left Join retegas_listini ON retegas_listini.id_listini = retegas_articoli.id_listini
					WHERE
					retegas_listini.id_listini IS NULL";
		break;
	   
	   case "ami":
	   $intestazione = '<div class="ui-state-error ui-corner-all padding_6px">Amici senza Referente</div><br></hr>';
			$query="SELECT
					retegas_amici.id_amici,
					retegas_amici.id_referente,
					retegas_amici.nome,
					retegas_amici.indirizzo,
					retegas_amici.telefono,
					retegas_amici.note,
					retegas_amici.is_visible
					FROM
					retegas_amici
					Left Join maaking_users ON maaking_users.userid = retegas_amici.id_referente
					WHERE
					maaking_users.userid IS NULL ";
	   break;
	   
	   case "del_dis":
			 if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
				if(isset($id)){ 
					$db->sql_query( "DELETE FROM `retegas_distribuzione_spesa` WHERE `retegas_distribuzione_spesa`.`id_distribuzione` = '$id' LIMIT 1;"); 				 				
				} 
			 }
	   // SENZA BREAK PER VISUALIZZARE ALTRI RECORDS
	   case "dis":
	   $delete_command = "?do=del_dis&id=";
	   $intestazione = '<div class="ui-state-error ui-corner-all padding_6px">Distribuzione Orfani di Dettagli</div><br></hr>';
			$query="SELECT
					retegas_distribuzione_spesa.id_distribuzione,
					retegas_distribuzione_spesa.id_riga_dettaglio_ordine,
					retegas_distribuzione_spesa.id_amico,
					retegas_distribuzione_spesa.qta_ord,
					retegas_distribuzione_spesa.qta_arr,
					retegas_distribuzione_spesa.data_ins,
					retegas_distribuzione_spesa.id_articoli,
					retegas_distribuzione_spesa.id_user,
					retegas_distribuzione_spesa.id_ordine,
					retegas_distribuzione_spesa.id_gas
					FROM
					retegas_distribuzione_spesa
					Left Join retegas_dettaglio_ordini ON retegas_distribuzione_spesa.id_riga_dettaglio_ordine = retegas_dettaglio_ordini.id_dettaglio_ordini
					WHERE
					retegas_dettaglio_ordini.id_dettaglio_ordini IS NULL";
	   break;
	   
	   
	   
		 case "del_det":
		 if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
			if(isset($id)){
			
				//echo "DELETE FROM `retegas_dettaglio_ordini WHERE `retegas_dettaglio_ordini`.`id_dettaglio_ordini` = '$id' LIMIT 1;"; 
				$db->sql_query("DELETE FROM `retegas_dettaglio_ordini` WHERE `retegas_dettaglio_ordini`.`id_dettaglio_ordini` = '$id' LIMIT 1;");                                  
			} 
		 } 
	   
	   case "det":
	   $delete_command = "?do=del_det&id=";
	   $intestazione = '<div class="ui-state-error ui-corner-all padding_6px">Dettagli orfani di Ordini</div><br></hr>';
			$query="SELECT
					retegas_dettaglio_ordini.id_dettaglio_ordini,
					retegas_dettaglio_ordini.id_utenti,
					retegas_dettaglio_ordini.id_articoli,
					retegas_dettaglio_ordini.id_stati,
					retegas_dettaglio_ordini.data_inserimento,
					retegas_dettaglio_ordini.data_convalida,
					retegas_dettaglio_ordini.qta_ord,
					retegas_dettaglio_ordini.id_amico,
					retegas_dettaglio_ordini.id_ordine,
					retegas_dettaglio_ordini.qta_conf,
					retegas_dettaglio_ordini.qta_arr,
					retegas_dettaglio_ordini.timestamp_ord
					FROM
					retegas_ordini
					Right Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
					WHERE
					retegas_ordini.id_ordini IS NULL;";
	   break;
	   
	   case "del_ref":
		 if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
			if(isset($id)){
			
				//echo "DELETE FROM `retegas_dettaglio_ordini WHERE `retegas_dettaglio_ordini`.`id_dettaglio_ordini` = '$id' LIMIT 1;"; 
				$db->sql_query("DELETE FROM `retegas_referenze` WHERE `retegas_referenze`.`id_referenze` = '$id' LIMIT 1;");                                  
			} 
		 }
	   
	   case "ref":
	   $delete_command = "?do=del_ref&id=";
	   $intestazione = '<div class="ui-state-error ui-corner-all padding_6px">Dettagli orfani di Ordini</div><br></hr>';
			$query='SELECT
					 *
					FROM
					retegas_referenze
					Left Join retegas_ordini ON retegas_referenze.id_ordine_referenze = retegas_ordini.id_ordini
					WHERE
					retegas_ordini.id_ordini IS NULL';
	   break;
	   
       //ORDINI SENZA LISTINI
       case "del_ord":
         if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
            if(isset($id)){
            
                //echo "DELETE FROM `retegas_dettaglio_ordini WHERE `retegas_dettaglio_ordini`.`id_dettaglio_ordini` = '$id' LIMIT 1;"; 
                $db->sql_query("DELETE FROM `retegas_ordini` WHERE `retegas_ordini`.`id_ordini` = '$id' LIMIT 1;");                                  
            } 
         }
       
       case "o_s_l":
       $delete_command = "?do=del_ord&id=";
       $intestazione = '<div class="ui-state-error ui-corner-all padding_6px">Ordini senza listini</div><br></hr>';
                        $query="SELECT
                                retegas_ordini.id_ordini,
                                retegas_ordini.id_listini,
                                retegas_ordini.id_utente,
                                retegas_ordini.descrizione_ordini,
                                retegas_ordini.data_scadenza1,
                                retegas_ordini.data_scadenza2,
                                retegas_ordini.data_apertura,
                                retegas_ordini.data_chiusura
                                FROM
                                retegas_ordini
                                Left Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
                                WHERE
                                    retegas_listini.id_listini IS NULL";
       break;
       
       
	   default:
			$query="";
			pussa_via();
			exit;
	   break;
		
	  }
	  
	  
 
	  
	  $result= mysql_query($query);
	  $numfields = mysql_num_fields($result);
	  $h_table .= "<div class=\"rg_widget rg_widget_helper\">";
	  $h_table .= "<h3>".$intestazione."</h3>";
	  $h_table .= "<table id=\"output_1\">
                    <thead>
                    <tr>";
	  //$h_table .= '<th> OPZ </th>';
	  for ($i=0; $i < $numfields; $i++) 
	  { 
	  $h_table .= '<th>'.mysql_field_name($result, $i).'</th>'; 
	  }
	  $h_table .= "</tr>
                    </thead>
                    <tbody>";
   
	 
   
	  while ($row = mysql_fetch_row($result)) 
	  {
		  
		 
	  $h_table.= '<tr>
						<td style="font-weight:bold;vertical-align:middle;">
							<a href="'.$delete_command.$row[0].'">
								'.$row[0].'
							</a>
						</td>
						<td>
						    '.$row[1].'
						</td>
						<td>
						    '.$row[2].'
						</td>
						<td>
							'.$row[3].'
						</td>
						<td>
							'.$row[4].'
						</td>
						<td>
							'.$row[5].'
						</td>
						<td>
							'.$row[6].'
						</td>
						<td>
							'.$row[7].'
						</td>
						<td>
							'.$row[8].'
						</td>
						<td>
							'.$row[9].'
						</td>
						<td>
							'.$row[10].'
						</td>
	  
					</tr>';
	  
	   
	  //$h_table .= '<tr><td></td><td  style="background-color:#'.$colo["$row[2]"].';display: block;">'.implode($row,'</td><td>')."</td></tr>\n"; 
	  }
	  $h_table .= "</tbody>
                    </table>";
	  $h_table .= '<div class="ui-state-default ui-corner-all padding_6px"><h3>Query:</h3><br>'.$query.'</div><br><hr />'; 
	  $h_table .="</div>";  
	   
	  // END TABELLA ----------------------------------------------------------------------------
//Questo è¨ il contenuto della pagina
$r->contenuto = $h_table;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);