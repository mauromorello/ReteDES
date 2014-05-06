<?php

function ditte_render_table($ref_table){
	 global $db; 
	 global $id_user;

	  $titolo_tabella = "Tutte le ditte";
	  
	  // INTESTAZIONI
	  $h1="ID";
	  $h2="Nome";      
	  $h3="Sito Web";
	  $h4="Mail";      
	  $h5="Proponente";
	  $h6="Listini";
	  $h7="Opzioni";
	  
	  // TOOLTIPS

	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"5%\" class=\"gas_c1\"";
	  $col_2="width=\"35%\" class=\"gas_c1\"";
	  $col_3="width=\"20%\" class=\"gas_c1\"";
	  $col_4="width=\"10%\" class=\"gas_c1\"";
	  $col_5="width=\"10%\" class=\"gas_c1\"";
	  $col_6="width=\"5%\" class=\"gas_c1\"";  
	  $col_7="width=\"5%\" style=\"vertical-align:middle\" ";    //opzioni

	  
	  // QUERY
	  
					$my_query="SELECT retegas_ditte.*, 
					maaking_users.fullname 
					FROM retegas_ditte 
					INNER JOIN maaking_users ON retegas_ditte.id_proponente = maaking_users.userid 
					ORDER BY id_ditte DESC;";

	  // NOMI DEI CAMPI
	  $d1="id_ditte";
	  $d2="descrizione_ditte";
	  $d3="website";
	  $d4="mail_ditte";
	  $d5="fullname";
	  $e1="id_proponente";
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = mysql_query($my_query);

		  
	  $h_table .= " 
		<div class=\"ui-widget-header ui-corner-all padding_6px\" style =\"margin-bottom:6px;\">
		<h3>$titolo_tabella<h3>
		<table id=\"$ref_table\">
		<thead>
		<tr>
			<th>$h1</th>
			<th>$h2</th>
			<th>$h3</th>
			<th>$h4</th>
			<th>$h5</th>
			<th>Listini attivi</th>
			<th>Listini totali</th>
			<th>$h7</th>         
		</tr>
		</thead>
		<tbody>";
  
	   $riga=0;  
		 while ($row = mysql_fetch_array($result)){
		 $riga++;
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = $row["$d3"];
			  $c4 = $row["$d4"];
			  $c5 = $row["$d5"];              
			  $c6 = listini_ditte($row['id_ditte']);
			  $c6_tot = listini_ditte_totali($row['id_ditte']);
			  $c_e1 =$row["$e1"]; 
			  
			  if($c_e1==$id_user){
				  $c5 ="<div class=\"campo_mio\">
					<a href=\"#\">
					$c5
					</a>
					</div>";
				  
				  $c7 = "<a class=\"option yellow awesome\" title=\"Modifica\" href=\"ditte_form_edit.php?id=$c1\">M</a>";
				  
				  if ($c6_tot==0){
						$c7 .="<a class=\"option red awesome\" title=\"Cancella\" href=\"ditte_form_delete.php?id=$c1\">C</a>";
					}
				  
				  
			  }else{
				$extra="";
				$c7="";    
			  }
			  
			  
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd\">";    // Colore Riga
		}else{
			$h_table.= "<tr>";    
		}
		
		
		
		
		$h_table.= "<td $col_1>$c1</td> 
					<td $col_2><a href=\"ditte_form.php?id=$c1\">$c2</a></td>    
					<td $col_3>$c3</td>
					<td $col_4>$c4</td>
					<td $col_5><a href=\"../utenti/utenti_form.php?id=$c_e1\">$c5</a></td>
					<td $col_6>$c6</td>
					<td $col_6>$c6_tot</td>
					<td $col_7>$c7</td>  
				</tr>
			";
		 }//end while

		 $h_table.= "
		 </tbody>
		 </table>
		 </div>
		 ";
	  // END TABELLA ----------------------------------------------------------------------------
return $h_table;	
	
}
function ditte_render_table_2($ref_table){
    global $db; 
    global $id_user;
    global $RG_addr;
    
   $query = "SELECT * FROM retegas_ditte ORDER BY retegas_ditte.descrizione_ditte ASC";
   $result = $db->sql_query($query);
   
   $h    = '<div class="rg_widget rg_widget_helper">
            <h3>Ditte proposte da utenti di ReteDes.it</h3>
            <span class="small_link" id="header_chiusi">Filtra i tag delle ditte...</span>';
   $h   .= '<ul id="list" style="list-style-type: none; padding: 1em; margin: 0;">'; 
  
         
   while ($row = mysql_fetch_array($result)){
   
   if($id_user==$row["id_proponente"]){
       $backg = ' style="background-image:-webkit-gradient(linear,0 0,0 50,from(#80FF80),to(#FFFFFF)); border-bottom: solid 1px #CCC" ';
   }else{
       $backg = ' style="background-image:-webkit-gradient(linear,0 0,0 50,from(#DDDDDD),to(#FFFFFF)); border-bottom: solid 1px #CCC" ';
   }
   
   
   
       
   $proponente      = fullname_from_id($row["id_proponente"]);
   $gas_proponente  = gas_nome(id_gas_user($row["id_proponente"]));
   $indirizzo = $row["indirizzo"];
   $nome_ditta = $row["descrizione_ditte"];
   $listini_attivi = listini_ditte($row["id_ditte"]);
   
   if($listini_attivi>0){
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Listini attivi presenti" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
   }else{
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="Listini attivi ASSENTI" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
   }
   
   $listini_totali = listini_ditte_totali($row["id_ditte"]);
   if($listini_totalii>0){
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_blu"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
   }
   
   $mail_ditta = $row["mail_ditte"];
   $website = $row["website"];
   $tags = $proponente.", ".$gas_proponente.", ".$nome_ditta.', '.$row["tag_ditte"]; 
   
   
   $h   .='<li class="ui-corner-all padding_6px">';
   $h   .= $pal;
   $h   .='<span style="font-size:1.4em; "><a href = "'.$RG_addr["form_ditta"].'?id='.$row["id_ditte"].'">'.$nome_ditta.'</a> - </span>'.$indirizzo.'<br>';
   $h   .='Proposta da <a href="'.$RG_addr["pag_users_form"].'?id='.mimmo_encode($row["id_proponente"]).'" >'.$proponente.'</a>, del '.$gas_proponente.' con <b>'.$listini_attivi.'</b> listini attivi su '.$listini_totali.' totali.<br>';
   $h   .='<span class="filtrum small_link" style="font-variant: small-caps; text-transform: uppercase;">'.$tags.'</span><br>';
   $h   .='</li>';  
   
   
   }
   
   $h   .= '</ul>';
   $h   .= '</div>';
   
      // END TABELLA ----------------------------------------------------------------------------
return $h;    
    
}


function ditte_render_table_mie($ref_table){
	 global $db; 
	 

	  $titolo_tabella = "Mie ditte";
	  
	  // INTESTAZIONI
	  $h1="ID";
	  $h2="Nome";      
	  $h3="Sito Web";
	  $h4="Mail";      
	  $h5="Proponente";
	  $h6="Listini";
	  $h7="Opzioni";
	  
	  // TOOLTIPS

	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"5%\" class=\"gas_c1\"";
	  $col_2="width=\"35%\" class=\"gas_c1\"";
	  $col_3="width=\"20%\" class=\"gas_c1\"";
	  $col_4="width=\"10%\" class=\"gas_c1\"";
	  $col_5="width=\"10%\" class=\"gas_c1\"";
	  $col_6="width=\"5%\" class=\"gas_c1\"";  
	  $col_7="width=\"5%\" style=\"vertical-align:middle\" ";    //opzioni

	  
	  // QUERY
	  
					$my_query="SELECT retegas_ditte.*, 
				maaking_users.username 
				FROM retegas_ditte 
				INNER JOIN maaking_users ON retegas_ditte.id_proponente = maaking_users.userid
				WHERE retegas_ditte.id_proponente = '"._USER_ID."' 
				ORDER BY id_ditte DESC;";

	  // NOMI DEI CAMPI
	  $d1="id_ditte";
	  $d2="descrizione_ditte";
	  $d3="website";
	  $d4="mail_ditte";
	  $d5="username";
	  $e1="id_proponente";
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = $db->sql_query($my_query);

		  
	  $h_table .= " 
		<div class=\"rg_widget rg_widget_helper\" style =\"margin-bottom:6px;\">
		<h3>$titolo_tabella<h3>
		<table id=\"$ref_table\">
		<thead>
		<tr>
			<th>$h1</th>
			<th>$h2</th>
			<th>$h3</th>
			<th>$h4</th>
			
			<th>Listini attivi</th>
			<th>Listini totali</th>
			<th>$h7</th>         
		</tr>
		</thead>
		<tbody>";
  
	   $riga=0;  
		 while ($row = mysql_fetch_array($result)){
		 $riga++;
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = $row["$d3"];
			  $c4 = $row["$d4"];
			              
			  $c6 = listini_ditte($row['id_ditte']);
			  $c6_tot = listini_ditte_totali($row['id_ditte']);
			  $c_e1 =$row["$e1"]; 
			  
			  if($c_e1==_USER_ID){
				  $c5 ="<div class=\"campo_mio\">
					<a href=\"#\">
					$c5
					</a>
					</div>";
				  
				  $c7 = "<a class=\"option yellow awesome\" title=\"Modifica\" href=\"ditte_form_edit.php?id=$c1\">M</a>";
				  
				  if ($c6_tot==0){
						$c7 .="<a class=\"option red awesome\" title=\"Cancella\" href=\"ditte_form_delete.php?id=$c1\">C</a>";
					}
				  
				  
			  }else{
				$extra="";
				$c7="";    
			  }
			  
			  
			
		if(is_integer($riga/2)){  
			$h_table.= "<tr class=\"odd\">";    // Colore Riga
		}else{
			$h_table.= "<tr>";    
		}
		
		
		
		
		$h_table.= "<td $col_1>$c1</td> 
					<td $col_2><a href=\"ditte_form.php?id=$c1\">$c2</a></td>    
					<td $col_3>$c3</td>
					<td $col_4>$c4</td>
					
					<td $col_6>$c6</td>
					<td $col_6>$c6_tot</td>
					<td $col_7>$c7</td>  
				</tr>
			";
		 }//end while

		 $h_table.= "
		 </tbody>
		 </table>
		 </div>
		 ";
	  // END TABELLA ----------------------------------------------------------------------------
return $h_table;    
	
}
function ditte_render_form($id_ditta){
		  // QUERY
	  global $db,$user,$id_user;
	  
	  $my_query="SELECT * FROM retegas_ditte WHERE  (id_ditte='$id_ditta') LIMIT 1";
	  
	  // SQL NOMI DEI CAMPI
	  $d1="id_ditte";
	  $d2="descrizione_ditte";
	  $d3="indirizzo";
	  $d4="mail_ditte";
	  $d5="website";
	  $d6="note_ditte";
	  $d7="id_proponente";
      $d10="tag_ditte";
	  
	  
	  
	  // INTESTAZIONI CAMPI
	  $h1="ID";
	  $h2="Nome";
	  $h3="Indirizzo";
	  $h4="Mail";
	  $h5="Sito web";
	  $h6="Note";
	  $h7="Proponente";      
	  $h8="Listini associati";
	  $h10 = "Tag associati";
      
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="";
	  $col_2=""; 

	
	  
	  // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
	  global $db;

	  $result = $db->sql_query($my_query);
	  $row = $db->sql_fetchrow($result);  
	  
	  //$h_table .= ditte_menu_2($id,$id_user);
	  

		 
		 // VALORI DELLE CELLE da DB---------------------
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = $row["$d3"];
			  $c4 = $row["$d4"];
			  $c5 = $row["$d5"];
			  $c6 = $row["$d6"];
			  $c7 = fullname_from_id($row["$d7"]);
			  $c8 = listini_ditte($c1);
			  $c10 = $row["$d10"];
              if($row["ditte_gc_lat"]>0){
                   $gc = "Indirizzo riconosciuto"; 
              }else{
                   $gc = "Indirizzo NON riconosciuto"; 
              }
		 // VALORI CELLE CALCOLATE ----------------------      
             // TITOLO TABELLA
             $titolo_tabella=$c2;
             
$h_table .= "
			 
			 <div class=\"rg_widget rg_widget_helper\">
			 <h3>$titolo_tabella</h3>
			 <table>  
			 <tr style=\"vertical-align:top\">
			 <td>";         
$h_table .=  "<table>
		<tr class=\"odd\">
			<th $col_1>$h1</th>
			<td $col_2>$c1</td>
		</tr>
		<tr  class=\"odd\">
			<th $col_1>$h2</th>
			<td $col_2>$c2</td>
		</tr>
        <tr  class=\"odd\">
            <th $col_1>Telefono</th>
            <td $col_2>".$row["telefono"]."</td>
        </tr>
		<tr class=\"odd\">
			<th $col_1>$h3</th>
			<td $col_2>$c3,<br>$gc</td>
		</tr>
        <tr class=\"odd\">
            <th $col_1>Distanza</th>
            <td $col_2><div id=\"distance_road\"></div></td>
        </tr>
		<tr class=\"odd\">
			<th $col_1>$h4</th>
			<td $col_2>$c4</td>
		</tr>
		<tr class=\"odd\">
			<th $col_1>$h5</th>
			<td $col_2>$c5</td>
        <tr class=\"odd\">
            <th $col_1>$h7</th>
            <td $col_2>$c7</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Listini attivi</th>
            <td $col_2>$c8</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Listini totali</th>
            <td $col_2>".listini_ditte_totali($id_ditta)."</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Tag associati</th>
            <td $col_2>$c10</td>
        </tr>
		</table>
		</td>
		
		<td style=\"width:40%;\">
		<table>        
		    <tr>
                <td>
                    <div id=\"map_canvas\" style=\"width:100%; height:300px;\"></div>                
                </td>            
            </tr>
		</table>
		</td>
		</tr>
		</table>
		</div> ";

	  // END TABELLA DITTA -----------------------------------------------------------------------
     if(trim($c6)<>""){
     $toggle_note ="<a class=\"small awesome silver destra\"  onclick=\"
                    $('#note_content').animate({'height': 'toggle'}, { duration: 1000 }); 
                    return false;
                    \"><span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span></a>";
         
         
         $h_table .=" <div class=\"rg_widget rg_widget_helper\">
                    ".$toggle_note."<strong>Note su questa ditta</strong>: (clicca per visualizzarle o nasconderle)
                        
                        <div id=\"note_content\" class=\"ui-helper-hidden\">                    
                            $c6
                        </div>
                    </div>
                    ";
     }
      
      
      return $h_table;	 
}
function ditte_render_form_2($id_ditta){
          // QUERY
      global $db,$user,$id_user;
      
      //DES
      $n_opinioni_sociale = conteggio_opinione_singola_ditta($id_ditta,opinioni::sociale);
      $m_opinioni_sociale = media_opinione_singola_ditta($id_ditta,opinioni::sociale); 

      $n_opinioni_finanza = conteggio_opinione_singola_ditta($id_ditta,opinioni::finanza);
      $m_opinioni_finanza = media_opinione_singola_ditta($id_ditta,opinioni::finanza); 

      $n_opinioni_ambiente = conteggio_opinione_singola_ditta($id_ditta,opinioni::ambiente);
      $m_opinioni_ambiente = media_opinione_singola_ditta($id_ditta,opinioni::ambiente); 

      
      //REFERENTE
      $n_opinioni_logistica =  conteggio_opinione_singola_ditta($id_ditta,opinioni::logistica);
      $m_opinioni_logistica =  media_opinione_singola_ditta($id_ditta,opinioni::logistica);
      
      $n_opinioni_rapporti =  conteggio_opinione_singola_ditta($id_ditta,opinioni::rapporti);
      $m_opinioni_rapporti =  media_opinione_singola_ditta($id_ditta,opinioni::rapporti);
                  
      $n_opinioni_velocita =  conteggio_opinione_singola_ditta($id_ditta,opinioni::velocita);
      $m_opinioni_velocita =  media_opinione_singola_ditta($id_ditta,opinioni::velocita);
      
      
      //UTENTE
      $n_opinioni_qualita =  conteggio_opinione_singola_ditta($id_ditta,opinioni::qualita);
      $m_opinioni_qualita =  media_opinione_singola_ditta($id_ditta,opinioni::qualita);
      
      $n_opinioni_affare =  conteggio_opinione_singola_ditta($id_ditta,opinioni::affare);
      $m_opinioni_affare =  media_opinione_singola_ditta($id_ditta,opinioni::affare);
      
      
      //GAS
      $n_opinioni_pulizia =  conteggio_opinione_singola_ditta($id_ditta,opinioni::pulizia);
      $m_opinioni_pulizia =  media_opinione_singola_ditta($id_ditta,opinioni::pulizia);
      
      $n_opinioni_artigianalita =  conteggio_opinione_singola_ditta($id_ditta,opinioni::artigianalita);
      $m_opinioni_artigianalita =  media_opinione_singola_ditta($id_ditta,opinioni::artigianalita);
      
      $n_opinioni_disponibilita =  conteggio_opinione_singola_ditta($id_ditta,opinioni::disponibilita);
      $m_opinioni_disponibilita =  media_opinione_singola_ditta($id_ditta,opinioni::disponibilita);
      
      
      
      $n_certificazioni = bacheca_n_messaggi_ditta($id_ditta,ruoli::certificante);
      $n_valutazioni = bacheca_n_messaggi_ditta($id_ditta,ruoli::referente);
      $n_commenti   =  bacheca_n_messaggi_ditta($id_ditta,ruoli::partecipante);
      $n_relazioni =   bacheca_n_messaggi_ditta($id_ditta,ruoli::relazionante);
      
      $n_listini_attivi =  listini_ditte($id_ditta);
      $n_listini_totali =  listini_ditte_totali($id_ditta);
      
      //Ordini su questa ditta
      $sql = "SELECT
                Count(retegas_ordini.id_ordini)
                FROM
                retegas_listini
                Inner Join retegas_ordini ON retegas_ordini.id_listini = retegas_listini.id_listini
                WHERE
                retegas_listini.id_ditte =  '$id_ditta'";
      $res = $db->sql_query($sql);
      $row = $db->sql_fetchrow($res);
      $n_ordini_fatti = $row[0];
      
      
      //Gas su questa ditta
      $sql="SELECT
            Count(maaking_users.id_gas),
            maaking_users.id_gas
            FROM
            retegas_ordini
            Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
            Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
            WHERE
            retegas_listini.id_ditte =  '$id_ditta'
            GROUP BY
            maaking_users.id_gas";
      $res = $db->sql_query($sql);
      $row = $db->sql_fetchrow($res);
      $n_gas_ordinanti = $db->sql_numrows($res);
       
      //Gestori su questa ditta :
      $sql ="SELECT
        retegas_ordini.id_utente
        FROM
        retegas_ordini
        Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
        WHERE
        retegas_listini.id_ditte =  '$id_ditta'
        GROUP BY
        retegas_ordini.id_utente";
      $res = $db->sql_query($sql);
      $row = $db->sql_fetchrow($res);
      $n_gestori = $db->sql_numrows($res);
      
      $my_query="SELECT * FROM retegas_ditte WHERE  (id_ditte='$id_ditta') LIMIT 1";
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;

      $result = $db->sql_query($my_query);
      $row = $db->sql_fetchrow($result);  
      
      if($row["ditte_gc_lat"]>0){
                   $gc = "Indirizzo riconosciuto"; 
             }else{
                   $gc = "Indirizzo NON riconosciuto"; 
      }
      
      
      //classi 
      $class_certificazioni = " style=\"font-size:1.3em;font-weight:bold;\" ";
      
 
      $t = "<table>
                        <tr>
                            <td width=\"33.33%\" style=\"vertical-align:top\">
                                <table>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        ANAGRAFICHE
                                        </td>
                                    </tr> 
                                    <tr class=\"scheda\">
                                        <th $col_1><b>ID</b></th>
                                        <td $col_2>".$row["id_ditte"]."</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Descrizione</th>
                                        <td $col_2>".$row["descrizione_ditte"]."</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Indirizzo</th>
                                        <td $col_2>".$row["indirizzo"]."<br>$gc</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Email</th>
                                        <td $col_2>".$row["mail_ditte"]."</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Sito WEB</th>
                                        <td $col_2>".$row["website"]."</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Telefono</th>
                                        <td $col_2>".$row["telefono"]."</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Proponente</th>
                                        <td $col_2>".fullname_from_id($row["id_proponente"]).", ".gas_nome(id_gas_user($row["id_proponente"]))."</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>TAGS</th>
                                        <td $col_2><span class=\"small_link\">".$row["tag_ditte"]."</span></td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        NOTE
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <td colspan=2> ".strip_tags($row["note_ditte"])."</td>
                                    </tr>
                                   
                                    
                                    
                                </table>
                            </td>
                            <td width=\"33.33%\" style=\"vertical-align:top\">
                                <table>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                            Ambito DES
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Valore sociale ($n_opinioni_sociale)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_sociale\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Trasparenza finanziaria ($n_opinioni_finanza)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_finanza\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Rispetto ambientale ($n_opinioni_ambiente)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_ambiente\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Certificazioni</th>
                                        <td $class_certificazioni>$n_certificazioni</td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                            Ambito GAS
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Pulizia ($n_opinioni_pulizia)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_pulizia\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        </div>
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Artigianalità ($n_opinioni_artigianalita)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_artigianalita\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        </div></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Disponibilità ($n_opinioni_disponibilita)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_disponibilita\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        </div></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Relazioni</th>
                                        <td $class_certificazioni>$n_relazioni</td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                            Ambito GESTORI
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Semplicità gestione ($n_opinioni_logistica)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_logistica\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        </div>
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Rapporti con il fornitore ($n_opinioni_rapporti)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_rapporti\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        </div></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Velocità spedizioni ($n_opinioni_velocita)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_velocita\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        </div></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Valutazioni</th>
                                        <td $class_certificazioni>$n_valutazioni</td>
                                    </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                            Ambito PARTECIPANTI
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Qualità merce ($n_opinioni_qualita)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_qualita\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        </div></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Rapporto Qualità/Prezzo ($n_opinioni_affare)</th>
                                        <td $col_2><div class=\"rateit\"    data-rateit-value=\"$m_opinioni_affare\" 
                                                                            data-rateit-ispreset=\"true\" 
                                                                            data-rateit-readonly=\"true\">
                                        </div></td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Commenti</th>
                                        <td $class_certificazioni>$n_commenti</td>
                                    </tr>
                                    
                                </table>
                            </td>
                            <td width=\"33.33%\" style=\"vertical-align:top\">
                                <table>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                            Statistiche
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Listini attivi</th>
                                        <td $class_certificazioni>$n_listini_attivi</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Listini totali</th>
                                        <td $class_certificazioni>$n_listini_totali</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Ordini fatti con questo fornitore</th>
                                        <td $class_certificazioni>$n_ordini_fatti</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Gas che ordinano da lui</th>
                                        <td $class_certificazioni>$n_gas_ordinanti</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Totale Gestori</th>
                                        <td $class_certificazioni>$n_gestori</td>
                                    </tr>
                                    
                                </table>
                            </td>
                        </tr>
                    </table>";
 
 
 
      return $t;     
}

function listini_render_table($ref_table,$id_ditta=null){
	global $db,$uset,$id_user;

	$titolo_tabella="Listini associati";
	  
	  // INTESTAZIONI
	  $h1="ID";
	  $h2="Nome";      
	  $h3="Tipologia";
	  $h4="Scadenza";      
	  $h5="Articoli";
	  $h6="Proponente";
	  $h7="Opzioni";
	  $h8="Caratt.";
	  // TOOLTIPS

	  
	  //  LARGHEZZA E CLASSI COLONNE
	  $col_1="width=\"5%\" class=\"gas_c1\"";
	  $col_2="width=\"25%\" class=\"gas_c1\"";
	  $col_3="width=\"15%\" class=\"gas_c1\"";
	  $col_4="width=\"15%\" class=\"gas_c1\"";
	  $col_5="width=\"10%\" class=\"gas_c1\"";
	  $col_6="width=\"10%\" class=\"gas_c1\"";  
	  $col_7="width=\"15%\" style=\"vertical-align:middle\" ";    //opzioni
      $col_8="width=\"10%\" class=\"gas_c1\" ";    //opzioni

	  
	  // QUERY LISTINI
	  $my_query="SELECT 
				 *    
				 FROM retegas_listini
				 WHERE id_ditte='$id_ditta'
				 AND data_valido > now();";
	  
	  // NOMI DEI CAMPI
	  $d1="id_listini";
	  $d2="descrizione_listini";
	  $d3="id_tipologie";
	  $d4="data_valido";
	  $d5="id_utenti";
	  $d6="";
	  $e1="";
	  
	  // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
	  
	  $result = $db->sql_query($my_query);
		
		  
	  $h_table .= "<br />
	   
	   <div class=\"rg_widget rg_widget_helper\">
       <h3>$titolo_tabella</h3>
	   <br>
	   <table>
		<tr>
			<th>$h1</th>
			<th>$h2</th>
			<th>$h3</th>
			<th>$h4</th>
			<th>$h5</th>
			<th>$h6</th>
			<th>Caratteristiche</th>         
		</tr>";
  
	   $riga=0;  
		 while ($row = mysql_fetch_array($result)){
		 $riga++;
			  $c1 = $row["$d1"];
			  $c2 = $row["$d2"];
			  $c3 = tipologia_nome_from_listino($row["$d1"]);
			  $c4 = conv_date_from_db($row["$d4"]);
			  $c5 = articoli_n_in_listino($row["$d1"]);              
			  $c6 = fullname_from_id($row["$d5"]);
			  $c_e1 = $row["$d5"]; 
			  
			  if($c_e1==$id_user){
				  $c6 ="<div class=\"campo_mio\">
					<a href=\"#\">
					$c6
					</a>
					</div>";
				  
				  //$c7 = "<a class=\"option yellow awesome\" title=\"Modifica\" href=\"../listini/listini_form_edit.php?id=$c1\">M</a>";
				  
				 // if ($c5==0){
				//        $c7 .="<a class=\"option red awesome\" title=\"Cancella\" href=\"../listini/listini_form_delete.php?id=$c1\">C</a>";
				//    }
				  
				  
			  }else{
				$extra="";
			//    $c7="";    
			  }
			  if(listino_tipo($c1)==0){
			    $c7="Normale";    
			  }else{
			    $c7="<b>Magazzino</b>";    
			  }
			  if($row["is_privato"]<>0){
                $c7 .= "<br><b>PRIVATO</b>";    
              }else{
                  
              }
			  
		
        //CONTROLLO SE E' PRIVATO'
        unset($show);
        if($row["is_privato"]<>0){
            if(id_gas_user($id_user)==id_gas_user($row["id_utenti"])){
                 $show= true;
            }             
        }else{
            $show= true; 
        }
        
        if($show){	
		    if(is_integer($riga/2)){  
			    $h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
		    }else{
			    $h_table.= "<tr class=\"$extra\">";    
		    }
		    $h_table.= "<td $col_1>$c1</td> 
					    <td $col_2><a href=\"../listini/listini_form.php?id=$c1\">$c2</a></td>    
					    <td $col_3>$c3</td>
					    <td $col_4>$c4</td>
					    <td $col_5>$c5</td>
					    <td $col_6><a href=\"../utenti/utenti_form.php?id=$c_e1\">$c6</a></td>
					    <td $col_7>$c7</td>  
				    </tr>
			    ";
        }
        // FINE CONTROLLO SE E' PRIVATO'    
            
            
		 }//end while

		 $h_table.= "</table>
					 </div>
		 ";
	  // END TABELLA LISTINI----------------------------------------------------------------------------

return $h_table;

// ---------------END LISTINI	
	
}
function listini_render_table_2($ref_table,$id_ditta=null){
    global $db; 
    global $id_user;
    global $RG_addr;
   
   
   
   
    
   $query = "SELECT * FROM retegas_listini WHERE id_ditte = '$id_ditta' ORDER BY retegas_listini.data_valido DESC";
   $result = $db->sql_query($query);
   
   $h    = '<div>
            <br>
            <span class="small_link" id="header_chiusi">Filtra i tag dei listini...</span>';
   $h   .= '<ul id="list" style="list-style-type: none; padding: 1em; margin: 0;">'; 
  
         
   while ($row = mysql_fetch_array($result)){
   

   if(gas_mktime(conv_date_from_db($row["data_valido"]))<gas_mktime(date("d/m/Y"))){
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
       $scaduto = "SCADUTO il ".conv_only_date_from_db($row["data_valido"]);
   }else{
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
       $scaduto = "<strong>ATTIVO fino al ".conv_only_date_from_db($row["data_valido"])."</strong>";
   }  
   
   
       
   $proponente      = fullname_from_id($row["id_utenti"]);
   $gas_proponente  = gas_nome(id_gas_user($row["id_utenti"]));
   $tipologia       = tipologia_nome_from_listino($row["id_listini"]);    
   $descrizione_listini = $row["descrizione_listini"];
   $articoli_listino = articoli_n_in_listino($row["id_listini"]);
   $elimina = "";
   
   if($row["is_privato"]>0){
       $privato = '<strong>PRIVATO</strong>';
       if(_USER_ID_GAS==id_gas_user(listino_proprietario($row["id_listini"]))){
            $show = true;     
       }else{
            $show = false;    
       }
       
       if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
           $show = true;
       }
       
       
   }else{
       $privato = 'PUBBLICO';
       $show = true;
   } 
   
   
   if(listino_proprietario($row["id_listini"])==$id_user){
        
       if($articoli_listino==0){
            $elimina = "<a class=\"awesome red small destra\" href=\"".$RG_addr["listini_delete"]."?id=".$row["id_listini"]."\">Elimina</a>";            
        }
   }
   
   if($row["tipo_listino"]>0){
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_blu"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
       $tipo_listino = '<strong>MAGAZZINO</strong>';
   }else{
       $tipo_listino = 'STANDARD';
   }
   
  

   
   
   //$listini_totali = listini_ditte_totali($row["id_ditte"]);
   //if($listini_totalii>0){
   //    $pal = '<IMG SRC="'.$RG_addr["img_pallino_blu"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
   //}
   
   //$mail_ditta = $row["mail_ditte"];
   //$website = $row["website"];
   $tags = $proponente.", ".$gas_proponente.", ".$descrizione_listini.", ".$tipo_listino.", ".$privato.", ".$scaduto.", ".$tipologia; 
   
       if($show){
           $h   .='<li class="ui-corner-all padding_6px">';
           $h   .= $pal;
           $h   .='<span style="font-size:1.4em; "><a href = "'.$RG_addr["listini_scheda"].'?id_listino='.$row["id_listini"].'">'.$descrizione_listini.'</a> - </span>'.$tipologia.', '.$tipo_listino.', '.$privato.', '.$scaduto.'<br>';
           $h   .='Proposto da <a href="'.$RG_addr["pag_users_form"].'?id='.mimmo_encode($row["id_utenti"]).'" >'.$proponente.'</a>, del '.$gas_proponente.' con <b>'.$articoli_listino.'</b> articoli inseriti.<br>';
           $h   .='<span class="filtrum small_link" style="font-variant: small-caps; text-transform: uppercase;">'.$tags.'</span>'.$elimina.'';
           $h   .='</li>';  
       }
   
   }
   
   $h   .= '</ul>';
   $h   .= '</div>';
   
   $h2 = rg_toggable("Listini associati","lisass",$h,true);
      
      
   return $h2;    
    
}
function listini_render_table_3($ref_table,$id_ditta=null){
    global $db; 
    global $id_user;
    global $RG_addr;
   
   
   
   
    
   $query = "SELECT * FROM retegas_listini WHERE id_ditte = '$id_ditta' ORDER BY retegas_listini.data_valido DESC";
   $result = $db->sql_query($query);
   
   $h    = '<div>
            <br>
            <span class="small_link" id="header_chiusi">Filtra i tag dei listini...</span>';
   $h   .= '<ul id="list" style="list-style-type: none; padding: 1em; margin: 0;">'; 
  
         
   while ($row = $db->sql_fetchrow($result)){
   

   if(gas_mktime(conv_date_from_db($row["data_valido"]))<gas_mktime(date("d/m/Y"))){
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
       $scaduto = "SCADUTO il ".conv_only_date_from_db($row["data_valido"]);
   }else{
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
       $scaduto = "<strong>ATTIVO fino al ".conv_only_date_from_db($row["data_valido"])."</strong>";
   }  
   
   
       
   $proponente      = fullname_from_id($row["id_utenti"]);
   $gas_proponente  = gas_nome(id_gas_user($row["id_utenti"]));
   $tipologia       = tipologia_nome_from_listino($row["id_listini"]);    
   $descrizione_listini = $row["descrizione_listini"];
   $articoli_listino = articoli_n_in_listino($row["id_listini"]);
   $elimina = "";
   
   if($row["is_privato"]>0){
       $privato = '<strong>PRIVATO</strong>';
       if(_USER_ID_GAS==id_gas_user(listino_proprietario($row["id_listini"]))){
            $show = true;     
       }else{
            $show = false;    
       }
       
       if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
           $show = true;
           $privato .= "(*)";
       }
       
       
   }else{
       $privato = 'PUBBLICO';
       $show = true;
   } 
   
   
   if(listino_proprietario($row["id_listini"])==$id_user){
        
       if($articoli_listino==0){
            $elimina = "<a class=\"awesome red small destra\" href=\"".$RG_addr["listini_delete"]."?id=".$row["id_listini"]."\">Elimina</a>";            
        }
   }
   
   if($row["tipo_listino"]>0){
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_blu"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
       $tipo_listino = '<strong>MAGAZZINO</strong>';
   }else{
       $tipo_listino = 'STANDARD';
   }
   

   $tags = $proponente.", ".$gas_proponente.", ".$descrizione_listini.", ".$tipo_listino.", ".$privato.", ".$scaduto.", ".$tipologia; 
   
       if($show){
           $h   .='<li class="ui-corner-all padding_6px">';
           $h   .= $pal;
           $h   .='<span style="font-size:1.4em; "><a href = "'.$RG_addr["listini_scheda"].'?id_listino='.$row["id_listini"].'">'.$descrizione_listini.'</a> - </span>'.$tipologia.', '.$tipo_listino.', '.$privato.', '.$scaduto.'<br>';
           $h   .='Proposto da <a href="'.$RG_addr["pag_users_form"].'?id='.mimmo_encode($row["id_utenti"]).'" >'.$proponente.'</a>, del '.$gas_proponente.' con <b>'.$articoli_listino.'</b> articoli inseriti.<br>';
           $h   .='<span class="filtrum small_link" style="font-variant: small-caps; text-transform: uppercase;">'.$tags.'</span>'.$elimina.'';
           $h   .='</li>';  
       }
   
   }
   
   $h   .= '</ul>';
   $h   .= '</div>';
     
      
   return $h;    
    
}


// ADD SIMPLE 
function ditte_render_form_add(){

        global $db;     
        global 
        $descrizione_ditte,
        $indirizzo,
        $website,
        $note_ditte,
        $id_proponente,
        $mail_ditte,
        $telefono,
        $tag_ditte;

        

        $help_descrizione_ditte='Il nome della ditta.';
        $help_indirizzo='Indirizzo della ditta, se non si sa mettere almeno la città'; 
        $help_website ='Se la sita ha un indirizzo internet inserirlo qua';
        $help_note_ditte ='Si possono mettere immagini facendo il copia e incolla dal sito della ditta in questione. Le immagini saranno collegate, non incorporate.';
        $help_mail_ditte='Mail della ditta, se si lascia vuoto allora sarà inserita la mail del proponente';
        $help_tag_ditte = 'I tag sono delle parole che si possono liberamente associare alla ditta stessa, separate da una virgola, 
        che permettono di filtrarla più agevolmente in mezzo alle altre e quindi di ritrovarla subito.<br>Ad esempio, i tag di una ditta che vende miele possono essere : miele, api, arnie, vasetti, acacia, castagno, biologico, artigianale ';


        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Inserisci una nuova ditta</h3>

        <form name="Nuova ditta" method="POST" action="ditte_form_add.php" class="retegas_form">

        
        <div>
        <h4>1</h4>
        <label for="descrizione">Scrivi il nome della nuova ditta...</label>
        <input type="text" name="descrizione_ditte" value="'.$descrizione_ditte.'" size="50"></input>
        <h5 title="'.$help_descrizione_ditte.'">Inf.</h5>
        </div>

        <div>
        <h4>2</h4>
        <label for="indirizzo">...indica il suo indirizzo e la sua città...</label>
        <input type="text" name="indirizzo" value="'.$indirizzo.'" size="50"></input>
        <h5 title="'.$help_indirizzo.'">Inf.</h5>
        </div>

        <div>
        <h4>3</h4>
        <label for="website">...scrivi l\'indirizzo internet del suo sito (se ne ha uno)...</label>
        <input type="text" name="website" value="'.$website.'" size="50"></input>
        <h5 title="'.$help_website.'">Inf.</h5>
        </div>
        
        <div>
        <h4>4</h4>
        <label for="mail_ditte">...ma soprattutto la sua mail...</label>
        <input type="text" name="mail_ditte" value="'.$mail_ditte.'" size="50"></input>
        <h5 title="'.$help_mail_ditte.'">Inf.</h5>
        </div>

        <div>
        <h4>5</h4>
        <label for="telefono">..il suo telefono</label>
        <input type="text" name="telefono" value="'.$telefono.'" size="50"></input>
        <h5 title="'.$help_telefono.'">Inf.</h5>
        </div>
        
        <div>
        <h4>6</h4>
        <h5 title="'.$help_note_ditte.'">Inf.</h5>
        <label for="note_ordine">Qua puoi mettere delle note che saranno visibili a tutti :</label>
        <textarea id="note_ditte" class ="ckeditor" name="note_ditte" cols="28" style="display:inline-block;">'.$note_ditte.'</textarea>
        </div>
        
        <div>
        <h4>7</h4>
        <label for="tag_ditte">..e qua scrivi i tag che identificano questa ditta</label>
        <input type="text" name="tag_ditte" value="'.$tag_ditte.'" size="50"></input>
        <h5 title="'.$help_tag_ditte.'">Inf.</h5>
        </div>        
                        
        <div>
        <h4>8</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Aggiungi questa ditta !" align="center" >
        <input type="hidden" name="do" value="add">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        </div>';              


        return $h;      

    }
function ditte_render_form_edit($id_ditte){

        global $db;     
        
        

        $query = "SELECT * FROM retegas_ditte WHERE retegas_ditte.id_ditte='$id_ditte' LIMIT 1;";
        $res = $db->sql_query($query);
        $row = $db->sql_fetchrow($res);
        
        $descrizione_ditte = $row["descrizione_ditte"];
        $indirizzo= $row["indirizzo"];
        $website= $row["website"];
        $note_ditte= $row["note_ditte"];
        
        $mail_ditte= $row["mail_ditte"];
        $tag_ditte= $row["tag_ditte"];
        $telefono = $row["telefono"];
        $lat = $row["ditte_gc_lat"];
        $lng = $row["ditte_gc_lng"];
        
        if($lat<>0){
            $indirizzo_OK = "INDIRIZZO RICONOSCIUTO";
        }else{
            $indirizzo_OK = "INDIRIZZO NON RICONOSCIUTO";
        }

        $help_descrizione_ditte='Il nome della ditta.';
        $help_indirizzo='Indirizzo della ditta, se non si sa mettere almeno la città'; 
        $help_website ='Se la ditta ha un indirizzo internet inserirlo qua';
        $help_telefono ='Se la ditta ha un telefono inserirlo qua';
        
        $help_note_ditte ='Si possono mettere immagini facendo il copia e incolla dal sito della ditta in questione. Le immagini saranno collegate, non incorporate.';
        $help_mail_ditte='Mail della ditta, se si lascia vuoto allora sarà inserita la mail del proponente';
        $help_tag_ditte = 'I tag sono delle parole che si possono liberamente associare alla ditta stessa, separate da una virgola, 
        che permettono di filtrarla più agevolmente in mezzo alle altre e quindi di ritrovarla subito.<br>Ad esempio, i tag di una ditta che vende miele possono essere : miele, api, arnie, vasetti, acacia, castagno, biologico, artigianale ';


        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Modifica questa ditta</h3>

        <form name="modifica ditta" method="POST" action="ditte_form_edit.php" class="retegas_form">

        
        <div>
        <h4>1</h4>
        <label for="descrizione">Scrivi il nome della nuova ditta...</label>
        <input type="text" name="descrizione_ditte" value="'.$descrizione_ditte.'" size="50"></input>
        <h5 title="'.$help_descrizione_ditte.'">Inf.</h5>
        </div>

        <div>
        <h4>2</h4>
        <label for="indirizzo">...indica il suo indirizzo e la sua città, fai click su "Cerca" per capire se l\'indirizzo è stato riconosciuto o meno;</label>
        <div id="panel" style="display:inline;">
            <input id="address" type="text" name="indirizzo" value="'.$indirizzo.'" size="50"></input>
            <input type="button" value="Cerca" onclick="codeAddress()">
            <input id="lat" type="hidden" name="lat" value="">
            <input id="lng" type="hidden" name="lng" value="">
            
        </div>
        <h5 title="'.$help_indirizzo.'">Inf.</h5>
        <div id="ir" style="display:block;">'.$indirizzo_OK.'</div>
        </div>

        <div id="map-canvas" style="width:200px;height:200px;display:inline-block;"></div>
        

        <div>
        <h4>3</h4>
        <label for="website">...scrivi l\'indirizzo internet del suo sito (se ne ha uno)...</label>
        <input type="text" name="website" value="'.$website.'" size="50"></input>
        <h5 title="'.$help_website.'">Inf.</h5>
        
        </div>
            
        
        
        <div>
        <h4>4</h4>
        <label for="mail_ditte">...ma soprattutto la sua mail...</label>
        <input type="text" name="mail_ditte" value="'.$mail_ditte.'" size="50"></input>
        <h5 title="'.$help_mail_ditte.'">Inf.</h5>
        </div>

        <div>
        <h4>5</h4>
        <label for="telefono">..il suo telefono</label>
        <input type="text" name="telefono" value="'.$telefono.'" size="50"></input>
        <h5 title="'.$help_telefono.'">Inf.</h5>
        </div>
        
        <div>
        <h4>6</h4>
        <h5 title="'.$help_note_ditte.'">Inf.</h5>
        <label for="note_ordine">Qua puoi mettere delle note che saranno visibili a tutti :</label>
        <textarea id="note_ditte" class ="ckeditor" name="note_ditte" cols="28" style="display:inline-block;">'.$note_ditte.'</textarea>
        </div>
        
        <div>
        <h4>7</h4>
        <label for="tag_ditte">..e qua scrivi i tag che identificano questa ditta</label>
        <input type="text" name="tag_ditte" value="'.$tag_ditte.'" size="50"></input>
        <h5 title="'.$help_tag_ditte.'">Inf.</h5>
        </div>        
                        
        <div>
        <h4>8</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Salva le modifiche !" align="center" >
        <input type="hidden" name="do" value="mod">
        <input type="hidden" name="id" value="'.$id_ditte.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        </div>';              


        return $h;      

    }

//STAT ARTICOLI DITTA
function statistiche_articoli_listini($id_ditta,$ref_table){
    global $db, $RG_addr;
    
    $sql = "SELECT
                Count(retegas_articoli.codice) AS artcont,
                retegas_articoli.descrizione_articoli,
                retegas_articoli.codice,
                round(Sum(retegas_dettaglio_ordini.qta_arr * prezzo),2) as prz
                FROM
                retegas_articoli
                Inner Join retegas_listini ON retegas_listini.id_listini = retegas_articoli.id_listini
                Inner Join retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte
                Inner Join retegas_dettaglio_ordini ON retegas_articoli.id_articoli = retegas_dettaglio_ordini.id_articoli
                WHERE
                retegas_ditte.id_ditte =  '$id_ditta'
                GROUP BY
                retegas_articoli.codice
                ORDER BY
                count(retegas_articoli.codice) DESC
                LIMIT 100";
    $result = $db->sql_query($sql);
    
        $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Statistiche Articoli</h3>
            <h4>Questa tabella raggruppa i primi 100 articoli più venduti di questa ditta. Può tornare utile nel caso di molti listini con articoli con lo stesso codice, in modo da capire su uno spettro di diversi ordini quali sono stati gli articoli più richiesti.</h4>
            <table id=\"$ref_table\" class=\"medium_size\">
         <thead>
         <tr>
        <th>Codice</th>          
        <th>Descrizione</th>
        <th>Quantità Acquistata</th>
        <th>Importo</th>
        <th>Opzioni</th>
        </tr>
        </thead>
        <tbody>";
    
    while ($row = mysql_fetch_array($result)){
        $h.= "<tr>";
        $h.= "<td>".$row["codice"]."</td>";
        $h.= "<td>".$row["descrizione_articoli"]."</td>";
        $h.= "<td>".$row["artcont"]."</td>";
        $h.= "<td>".$row["prz"]."</td>";
        $h.= "<td>&nbsp</td>"; 
        $h.= "</tr>"; 
    }
    
    $h.= "
         </tbody>
         </table>";
         return $h; 
    
}

