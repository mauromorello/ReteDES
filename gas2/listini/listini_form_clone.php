<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("listini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//controlla se l'utente ha i permessi necessari
if(!(_USER_PERMISSIONS & perm::puo_creare_listini)){
     pussa_via();
}

if(!is_empty($id)){
    if(db_nr_q("id_listini",$id,"retegas_listini")==0){
      go("sommario",_USER_ID,"Listino inesistente");  
    }
    if(db_val_q("id_listini",$id,"is_privato","retegas_listini")==1){
      if(listino_proprietario($id)<>_USER_ID){  
        go("sommario",_USER_ID,"Un listino privato può essere clonato soltanto dal suo proprietario.");  
      }
    }
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Clona un listino";

 
//Dico quale men? orizzontale dovr essere associato alla pagina.
//$r->menu_orizzontale = des_menu_completo(_USER_ID); 
  
	  
if ($do=="clone"){
	  
	//echo "Sono in clone<br>";	  
		  
		  
	  // se ? vuoto
	  if (empty($data_2)){$msg.="Devi almeno inserire il nome del listino<br>";$e_empty++;};
  // data di scadenza maggiore di oggi


	  $msg.="<br>Verifica i dati immessi e riprova";
	  
	  
	  $e_total = $e_empty;
	  
	  if($e_total==0){
		//echo "ZERO ERRORI !!!";
		//echo "Sono in clone<br>";
		//prendo i dati del vecchio listino : 
		$res = $db->sql_query("SELECT * FROM retegas_listini WHERE id_listini='$id'");
		$row_old = $db->sql_fetchrow($res);
		
		$data_3 = $row_old["id_tipologie"];
		$data_4 = $row_old["data_valido"];
		$data_5 = $row_old["id_ditte"];
		$data_6 = $row_old["tipo_listino"];

		// QUERY INSERT
		$my_query="INSERT INTO retegas_listini 
				(descrizione_listini,
				 id_tipologie,
				 data_valido,
				 id_ditte,
				 tipo_listino,
				 id_utenti) VALUES (
				 '$data_2',
				 '$data_3',
				 '$data_4',
				 '$data_5',
				 '$data_6',
				 '"._USER_ID."');";
		
		//INSERT BEGIN ---------------------------------------------------------
		 $result = $db->sql_query($my_query);
		// INSERITO IL NUOVO LISTINO, PRENDO l'ULTIMO ID; 
		//echo "Fatta query nuyovo listino $my_query<br>";       
		
		//scopri l'ultimo indice del nuovo listino           
		$res = mysql_query("SELECT LAST_INSERT_ID();");
		$row = mysql_fetch_array($res);
		$indice_nuovo_listino = $row[0]; 
		(float)$variazione_prezzo;
        
        if(is_empty($variazione_prezzo)){
            $variazione="no";
        }
        
		//passo tutti gli articoli che erano associati al vecchio listino
		$res_articoli = $db->sql_query("SELECT * FROM retegas_articoli WHERE retegas_articoli.id_listini='$id';");
		while ($row = mysql_fetch_array($res_articoli)){
		
        		
        // Se scelgo la variazione
        if ($variazione=="si"){
            $price = "round(prezzo + ((prezzo / 100)* $variazione_prezzo),4)";
        }else{     
            $price = "prezzo";
        }
        
        $q_mul="qta_minima";
        if ($scelta=="piene"){
            $q_mul = "qta_scatola";
        }
        
        if($scelta=="singolo"){
            $q_mul = "'1'";
        }    
            
                $query_copia="INSERT INTO 
								retegas_articoli (codice,
												  id_listini, 
												  u_misura, 
												  misura, 
												  descrizione_articoli, 
												  qta_scatola,
												  prezzo,
												  ingombro,
												  qta_minima,
												  qta_multiplo,
												  articoli_note,
												  articoli_unico,
												  articoli_opz_1,
												  articoli_opz_2,
												  articoli_opz_3)
								SELECT 
										codice,
										'$indice_nuovo_listino', 
										u_misura, 
										misura, 
										descrizione_articoli, 
										qta_scatola,
										$price,
										ingombro,
										  $q_mul,
										  qta_multiplo,
										  articoli_note,
										  articoli_unico,
										  articoli_opz_1,
										  articoli_opz_2,
										  articoli_opz_3
								FROM 
										retegas_articoli WHERE id_articoli = '".$row["id_articoli"]."' LIMIT 1;";    
				$result = $db->sql_query($query_copia);    
				//echo "Fatta query nuovo articolo $query_copia<br>";
				if (is_null($result)){$err++;} 	
			
		} // end while
		 
		 
		 
		 
		 
		 if ($err>0){
			$msg = "Errore nell'inserimento del record";
			include ("../index.php");
			exit;  
		}else{
			$nome_ditta = ditta_nome($data_5);
			log_me(0,_USER_ID,"LIS","CLN","CLONATO il listino ($data_2) riferito alla ditta ($nome_ditta)",0,$my_query);
			$msg = "Listino clonato aggiunto";
			$id = $data_5;
			include("../ditte/ditte_form.php");
			exit;  
		};
		
		//INSERT END --------------------------------------------------------- 
		
		
		
		 
		  
	  }
	  
	  } // Se do = add
	  
      
      //Messaggio popup;
      $r->messaggio = $msg;
      
      
	  // Controlli sui dati --------------------------------- CCCC

			  
	  // TITOLO FORM_ADD
	  
	  $titolo_nuovo_listino = listino_nome($id);  
	  $titolo_tabella="Clona un listino da quello \"".$titolo_nuovo_listino."\"";
	  $titolo_nuovo_listino = $titolo_nuovo_listino ." (Clone)";
	  
	  
	  
	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"30%\"";
	  $col_2=""; 
	  
	  // OPZIONI
	  
	  // FORM -------------------------------------------
	  
	  $title_form = "<form name=\"Clona Listino\" method=\"POST\" action=\"\" class=\"retegas_form\">";
	  $submit_form ="<input type=\"submit\" value=\"Clona\" class=\"large green awesome\" style=\"margin:20px; text-align:center;\">";  
	  
	  // Campi
	  
      
      
	  $input_2 = "<input type=\"text\" name=\"data_2\" size=\"48\" value=\"$titolo_nuovo_listino\">"; //descrizione
      $input_3 = "<input type=\"checkbox\" name=\"variazione\" value=\"si\"";
      $input_4 = "<input type=\"text\" name=\"variazione_prezzo\" size=\"18\" value=\"\">"; //descrizione

	  $input_hidden =   "<input type=\"hidden\" name=\"do\"  value=\"clone\">";
	  $input_hidden_2 = "<input type=\"hidden\" name=\"id\" value=\"$id\">";   //id_listino
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  
	  //$h_table .= ditte_menu_1();
	  
	  $h_table .= " <div class=\"rg_widget rg_widget_helper\">
					<h3>$titolo_tabella</h3>
	  
					$title_form
					
					
					<table>
					";
	 
	 $h_table .=  "
					<tr class=\"odd\">
						<th $col_1>Nome listino clonato</th>
                        <td>&nbsp</td>
						<td $col_2>$input_2 $input_hidden $input_hidden_2</td>
					</tr>
                    <tr class=\"odd\">
                        <th $col_1>Effettua maggiorazione </th>
                        <td>$input_3</td>                                   
                        <td $col_2>$input_4 % da applicare sul prezzo di orni articolo</td>
                    </tr>
                    <tr class=\"odd\">
                        <th $col_1>Minimo ordinabile = Come il listino vecchio</th>
                        <td><input type=\"radio\" name=\"scelta\" value=\"\"checked></td>                                   
                        <td $col_2>Con questa opzione si mantengono le quantità minime del listino clonato</td>
                    </tr>
                    <tr class=\"odd\">
                        <th $col_1>Minimo ordinabile = Scatola piena</th>
                        <td><input type=\"radio\" name=\"scelta\" value=\"piene\"></td>                                   
                        <td $col_2>Con questa opzione si modifica la quantità minima ordinabile portandola uguale a quella della scatola. (quindi solo scatole piene)</td>
                    </tr>
                    <tr class=\"odd\">
                        <th $col_1>Minimo ordinabile = 1</th>
                        <td><input type=\"radio\" name=\"scelta\" value=\"singolo\"></td>                                   
                        <td $col_2>Con questa opzione la quantità minima di ogni articolo sarà fissata a 1 (UNO)</td>
                    </tr>
					</table>
					$submit_form
					</form>
					<div class=\"ui-state-error ui-corner-all padding_6px\">
					Il listino clonato prenderà tutti i dati e gli articoli da quello di origine
					<br>
                    Se è presente una variazione (PERCENTUALE) questa verrà applicata al prezzo di ogni articolo.
                    </div>
					</div>
					";

	  // END TABELLA ----------------------------------------------------------------------------
//Questo ? il contenuto della pagina
$r->contenuto = $h_table;
//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)

?>