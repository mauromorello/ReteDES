<?php
  /**
 * produce l'html che costruisce la scheda del listino
 *
 *
 * @param int $id_listino variabile che identifica il listino da mostrare
 * @param mixed $h_table la tabella in html della scheda
 *
 */
  function listini_form($id_listino,$open=false){
      Global $db,$user,$id_user;
      
      $my_query="SELECT * FROM retegas_listini WHERE  (id_listini='$id_listino') LIMIT 1";
      
      // SQL NOMI DEI CAMPI
      $d1="id_listini";
      $d2="descrizione_listini";
      $d3="id_utenti";
      $d4="id_tipologie";
      $d5="id_ditte";
      $d6="data_valido";
      
              
      // INTESTAZIONI CAMPI
      $h1="ID";
      $h2="Nome";
      $h3="Proponente";
      $h4="Tipologia";
      $h5="Valido fino al";
      $h6="Ditta";
      //$h7="Proponente";      
      //$h8="Listini associati";
      


     
      
      
      // OPZIONI
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      

      $result = $db->sql_query($my_query);
      $row = mysql_fetch_array($result);  
      
      

         
         // VALORI DELLE CELLE da DB---------------------
              $c1 = $row["$d1"];
              $c2 = $row["$d2"];
              $c3 = fullname_from_id($row["$d3"]);
              $c4 = tipologia_nome_from_listino($row["$d1"]);
              $c5 = conv_date_from_db($row["$d6"]);
              $c6 = ditta_nome_from_listino($c1);
              $c7 = $row["tipo_listino"];
              if($c7==1){$c7="<b>MAGAZZINO</b>";}else{$c7="NORMALE";};
              if($row["is_privato"]<>0){
                  $c8 = "<b>PRIVATO</b>";
              }else{
                  $c8 = "PUBBLICO";
              }
               
              //$c7 = fullname_from_id($row["$d7"]);
              //$c8 = listini_ditte($c1);
              
             // VALORI CELLE CALCOLATE ----------------------      

         // TITOLO TABELLA
            $titolo_tabella="Listino cod. $c1 ditta $c6";
         
         
         
$h_table .= " 
            <table>
                <tr style=\"vertical-align:top\">
                    <td>         
                        <table  cellspacing=\"2\">
                            <tr class=\"odd sinistra\">
                                <th>$h1</th>
                                <td>$c1</td>
                            </tr>
                            <tr  class=\"odd sinistra\">
                                <th>$h2</th>
                                <td>$c2</td>
                            </tr>
                            <tr class=\"odd sinistra\">
                                <th $col_1>$h3</th>
                                <td $col_2>$c3</td>
                            </tr>
                            <tr class=\"odd sinistra\">
                                <th>Tipo</th>
                                <td>$c7</td>
                            </tr>
                            <tr class=\"odd sinistra\">
                                <th>Ordini con questo listino :</th>
                                <td>".listini_ordini_con_questo_listino($id_listino)."</td>
                            </tr>
        
                        </table>
                    </td>
        
                    <td  style=\"padding:0px\">
                        <table cellpadding=\"0\" style=\"padding:0px\">        
        
                            <tr class=\"odd\">
                                <th>$h4</th>
                                <td>$c4</td>
                            </tr>
                            <tr class=\"odd\">
                                <th>$h5</th>
                                <td>$c5</td>
                            </tr>
                            <tr class=\"odd\">
                                <th>$h6</th>
                                <td>$c6</td>
                            </tr>
                            <tr class=\"odd\">
                                <th>Visibilità</th>
                                <td>$c8</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table> ";

      // END TABELLA DITTA -----------------------------------------------------------------------
      
$h2 = rg_toggable($titolo_tabella,"scheda_listino",$h_table,$open);
  
return $h2;  
}

  function listini_articoli_table($ref_table, $id_listino){
      
      global $RG_addr;
      
      // --------------START LISTINI
      // TITOLO TABELLA
      $nome_listino=listino_nome($id_listino);
      //$numero_articoli_in_listino = articoli_n_in_listino($id);
      
      $titolo_tabella="ARTICOLI del listino ''$nome_listino''";
      
      // INTESTAZIONI
      
      $h1="Codice";      
      $h2="Descrizione";
      $h3="Misura";      
      $h4="Prezzo";
      $h5="Scatola/Multiplo";
      $h6="Note";
      $h7="Opzioni";
      

      
      // QUERY LISTINI
      $my_query="SELECT 
                 *    
                 FROM retegas_articoli
                 WHERE id_listini='$id_listino'
                 ORDER BY retegas_articoli.codice ASC;";
      
      // NOMI DEI CAMPI
      $d1="codice";
      $d2="descrizione_articoli";
      $d3="u_misura";
      $d4="misura";
      $d5="prezzo";
      $d6="qta_scatola";
      $d7="qta_minima";
      $d8="id_listini";
      $d9="articoli_note";
      $d10 = "id_articoli";
      
      // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
      
      $result = mysql_query($my_query);
        
          
      $h_table .= "<br /> 
            <div class=\"rg_widget rg_widget_helper\" style = \"margin-bottom:6px;\">
            <h3>$titolo_tabella</h3>

            <table id=\"$ref_table\">
            <thead>
        <tr>
            <th class=\"sinistra\">$h1</th>
            <th class=\"sinistra\">$h2</th>
            <th class=\"sinistra\">$h3</th>
            <th class=\"sinistra\">$h4</th>
            <th class=\"sinistra\">$h5</th>
            <th class=\"sinistra\">$h6</th>
            <th class=\"sinistra\">UNIVOCO</th>
            <th class=\"sinistra\">RAGGR.</th>         
        </tr>
        </thead>
        <tbody>";
  
       $riga=0;  
         while ($row = mysql_fetch_array($result)){
         $riga++;
              $c1 = $row["$d1"];
              $c2 = $row["$d2"];
              $c3 = $row["$d3"]." ".  $row["$d4"];
              $c4 = _nf($row["$d5"])." $euro";
              $c5 = _nf($row["$d6"])." / "._nf($row["$d7"]);              
              if(!empty($row["$d9"])){$c6=trim(substr(strip_tags($row["$d9"]),0,15)) ." ...";}else{$c6="";}
              $c6_alt = htmlentities($row["$d9"]); 
              $c8 = $row["$d8"];
              $c10=  $row["$d10"];       // ID articolo
              $c7 = $row["articoli_opz_1"]." - ".$row["articoli_opz_2"]." - ".$row["articoli_opz_3"];

              
            
        if(is_integer($riga/2)){  
            $h_table.= "<tr class=\"odd $extra\">";    // Colore Riga
        }else{
            $h_table.= "<tr class=\"$extra\">";    
        }
        
        if($row["articoli_unico"]==1){$au="SI";}else{$au="";};
        
        
        $h_table.= "<td><a class=\"awesome option green\" onclick=\"
                                        $.ajax({
                                          url: '".$RG_addr["ajax_schedina_articolo"]."?id_articolo=$c10',
                                          success: function(data) {
                                            $('#container_articolo').html(data);
                                          }
                                        });
                    \">$c1</a></td> 
                    <td><a href=\"../articoli/articoli_form_new.php?id_articolo=$c10\">$c2<a></td>    
                    <td>$c3</td>
                    <td>$c4</td>
                    <td>$c5</td>
                    <td><a title=\"$c6_alt\">$c6</a></td>
                    <td>$au</td>
                    <td>$c7</td>  
                </tr>
            ";
         }//end while

         $h_table.= "
         </tbody>
         </table>
         </div>
         ";
  return $h_table;
  }
    
  
  
  function listini_select_maga($id_listino){
      global $db;
      
    //select_listini
        $query_listini = "SELECT *
        FROM
        retegas_listini
        WHERE
        retegas_listini.data_valido >  now() AND tipo_listino='1';";
        $res_listini = $db->sql_query($query_listini);

        while ($row = $db->sql_fetchrow($res_listini)){

            if(isset($id_listino)){
                if($id_listino==$row["id_listini"]){$selected = " SELECTED ";}else{$selected="";}    
            }   

            $listini_select .= '<option value="'.$row["id_listini"].'" '.$selected.'>'.$row["descrizione_listini"].' ~ Di '.fullname_from_id($row["id_utenti"]).', articoli : '.articoli_n_in_listino($row["id_listini"]).' (scadenza : '.conv_only_date_from_db($row["data_valido"]).')</option>\\n';     
        }
        
                $h = '<div class="rg_widget rg_widget_helper">
        <h3>Scegli il listino dal quale aggiungere gli articoli</h3>

        <form name="Scelta magazzino" method="POST" action="listini_form_maga.php" class="retegas_form">

        <div>
        <h4>1</h4>
        <label for="selection">Scegli il listino magazzino...</label>
        <select id="selection" name="id_listino">
        <option value="0">Nessun listino magazzino selezionato</OPTION>
        '.$listini_select.'        
        </select>
        <h5 title="'.$help_listini.'">Inf.</h5>    
        </div>
        
        <div>
        <h4>2</h4> 
        <label for="selectionresult">..e poi spunta gli articoli che vuoi inserire nel nuovo listino</label>
        <table id="selectionresult">&nbsp;      
        </table>
        <h5 title="'.$help_articoli_table.'">Inf.</h5>    
        </div>
        
        <div>
        <h4>3</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Aggiungi gli articoli selezionati" align="center" >
        <input type="hidden" name="id_listino" value="'.$id_listino.'">
        <input type="hidden" name="do" value="add">
        </div>
        </form>
        <br>

        </div>'; 
          
    return $h;  
  }
  function listini_add_selected_maga($id_listino_target,$box_id,$box_prezzi,$box_codici,$box_scatola,$box_descrizione,$box_minimo){
      global $db;
      
      //print_r($box_prezzi);
    //print_r($box_id);
      
      $quanti = 0;
      $err=0;
      
      while (list ($key,$val) = @each ($box_id)) {
         $no=0;
         
         $box_prezzi[$val]=floatval(trim(str_replace(array(",","€"),array(".",""),$box_prezzi[$val]))); 
         if(!valuta_valida($box_prezzi[$val])){$no++;$msg_err.="Cod. $val Valuta non valida <br>";}
         //controllo che nel listino nuovo non esista gi? un articolo con lo stesso codice
         if(articolo_codice_quanti_in_listino($box_codici[$val],$id_listino_target)>0){$no++; $msg_err.="Cod. $val già in listino <br>";}
         //echo "NO = $no , KEY = $key ID = $val, Codice = ".$box_codici[$val]." Prezzo nuovo = ".$box_prezzi[$val]." Descrizione = ".$box_descrizione[$val]."<br>";    
         
         if($no==0){
         
         $box_descrizione[$val]=sanitize($box_descrizione[$val]);
         //copio l'articolo indicato, e lo associo al listino nuovo variando il prezzo
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
                                        '$id_listino_target', 
                                        u_misura, 
                                        misura, 
                                        '".$box_descrizione[$val]."', 
                                        qta_scatola,
                                        '".$box_prezzi[$val]."',
                                        ingombro,
                                          qta_minima,
                                          qta_multiplo,
                                          articoli_note,
                                          articoli_unico,
                                          articoli_opz_1,
                                          articoli_opz_2,
                                          articoli_opz_3
                                FROM 
                                        retegas_articoli WHERE id_articoli = '".$box_id[$key]."' LIMIT 1;";    
                $result = $db->sql_query($query_copia);
                
                if (is_null($result)){$err++;}else{$quanti++;}
                unset($query_copia);
         }else{
            $err++; 
         }        
                
      }
    return "$quanti articoli inseriti (con $err errori) <br>$msg_err";  
  }
  
  function listini_render_table_miei($id_user){
    global $db; 
    global $RG_addr;
    
   $query = "SELECT * FROM retegas_listini WHERE id_utenti = '$id_user' ORDER BY retegas_listini.data_valido DESC";
   $result = $db->sql_query($query);
   
   $h    = '<div class="rg_widget rg_widget_helper">
            <h3>Listini proposti da '.fullname_from_id($id_user).'</h3>
            <span class="small_link" id="header_chiusi">Filtra i tag dei listini...</span>';
   $h   .= '<ul id="list" style="list-style-type: none; padding: 1em; margin: 0;">'; 
  
         
   while ($row = mysql_fetch_array($result)){
   
       
   
       
   if($id_user==$row["id_utenti"]){
      // $backg = ' style="background-image:-webkit-gradient(linear,0 0,0 50,from(#80FF80),to(#FFFFFF)); border-bottom: solid 1px #CCC" ';
   }else{
     //  $backg = ' style="background-image:-webkit-gradient(linear,0 0,0 50,from(#DDDDDD),to(#FFFFFF)); border-bottom: solid 1px #CCC" ';
   }
   if(gas_mktime(conv_date_from_db($row["data_valido"]))<gas_mktime(date("d/m/Y"))){
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
       $scaduto = "SCADUTO il ".conv_only_date_from_db($row["data_valido"]);
   }else{
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
       $scaduto = "<strong>ATTIVO fino al ".conv_only_date_from_db($row["data_valido"])."</strong>";
   }  
   
   
       
   $ditta      = ditta_nome_from_listino($row["id_listini"]);
   $gas_proponente  = gas_nome(id_gas_user($row["id_utenti"]));
   $tipologia       = tipologia_nome_from_listino($row["id_listini"]);    
   $descrizione_listini = $row["descrizione_listini"];
   $articoli_listino = articoli_n_in_listino($row["id_listini"]);
   if($articoli_listino==0){
   $elimina = "<a class=\"awesome red small destra\" href=\"".$RG_addr["listini_delete"]."?id_listino=".$row["id_listini"]."\">Elimina</a>"; 
   }else{
   $elimina = "";    
   }
   
   if($row[tipo_listino]>0){
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_blu"].'" ALT="Ditta nuova" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
       $tipo_listino = '<strong>MAGAZZINO</strong>';
   }else{
       $tipo_listino = 'STANDARD';
   }
   
   if($row[is_privato]>0){
       $privato = '<strong>PRIVATO</strong>';
   }else{
       $privato = 'PUBBLICO';
   }   

   

   $tags = $ditta.", ".$gas_proponente.", ".$descrizione_listini.", ".$tipo_listino.", ".$privato.", ".$scaduto.", ".$tipologia; 
   
   
   $h   .='<li '.$backg.' class="ui-corner-all padding_6px">';
   $h   .= $pal;
   $h   .='<span style="font-size:1.4em; "><a href = "'.$RG_addr["listini_scheda"].'?id_listino='.$row["id_listini"].'">'.$descrizione_listini.'</a> - </span>'.$tipologia.', '.$tipo_listino.', '.$privato.', '.$scaduto.'<br>';
   $h   .='Della ditta <a href="'.$RG_addr["form_ditta"].'?id_ditta='.$row["id_ditte"].'" >'.$ditta.'</a>, con <b>'.$articoli_listino.'</b> articoli inseriti.<br>';
   $h   .='<span class="filtrum small_link" style="font-variant: small-caps; text-transform: uppercase;">'.$tags.'</span>'.$elimina.'';
   $h   .='</li>';  
   
   
   }
   
   $h   .= '</ul>';
   $h   .= '</div>';
   
      // END TABELLA ----------------------------------------------------------------------------
return $h;    
    
}

function listini_render_delete_articoli($id_listini){
    global $db; 
    global $RG_addr;
    
      // INTESTAZIONI
      
      $h1="Codice";      
      $h2="Descrizione";
      $h3="Misura";      
      $h4="Prezzo";
      $h5="Scatola/Multiplo";
      $h6="Note";
      $h7="Opzioni";
      
      // TOOLTIPS

      
      //  LARGHEZZA E CLASSI COLONNE
      $col_1="width=\"5%\" class=\"gas_c1\"";
      $col_2="width=\"25%\" class=\"gas_c1\"";
      $col_3="width=\"15%\" class=\"gas_c1\"";
      $col_4="width=\"15%\" class=\"gas_c1\"";
      $col_5="width=\"10%\" class=\"gas_c1\"";
      $col_6="width=\"10%\" class=\"gas_c1\"";  
      $col_7="width=\"15%\" style=\"vertical-align:middle\" ";    //opzioni

      
      // QUERY LISTINI
      $my_query="SELECT 
                 *    
                 FROM retegas_articoli
                 WHERE id_listini='$id_listini'
                 ORDER BY retegas_articoli.codice ASC;";
      
      // NOMI DEI CAMPI
      $d1="codice";
      $d2="descrizione_articoli";
      $d3="u_misura";
      $d4="misura";
      $d5="prezzo";
      $d6="qta_scatola";
      $d7="qta_minima";
      $d8="id_listini";
      $d9="articoli_note";
      $d10 = "id_articoli";
      
      // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
      
      $result = mysql_query($my_query);
        
          
      $h_table .= "<br /> 
            <div class=\"rg_widget rg_widget_helper\">
            <h3>Seleziona gli articoli che vuoi eliminare da questo listino, poi clicca sul pulsante in fondo alla pagina.</h3>
            <h4>NB: Puoi selezionare solo articoli che non sono mai comparsi in nessun ordine</h4>
            <form id=\"del_art\" class=\"retegas_form ui-corner-all\" action=\"\" method=\"POST\">
            <a onClick=\"$('#del_art').toggleCheckboxes();\" class=\"awesome small yellow\">Seleziona Tutti</a><br>
            <table id=\"table_ref\">
            
        <thead>
            <tr>
            <th>&nbsp</th>
            <th>$h1</th>
            <th>$h2</th>
            <th>$h3</th>
            <th>$h4</th>
            <th>$h5</th>
            <th>$h6</th>
            <th>UNIVOCO</th>
            <th>RAGGR.</th>         
        </tr>
        </thead>
        <tbody>";
  
       $riga=0;  
         while ($row = mysql_fetch_array($result)){
         $riga++;
              $c1 = $row["$d1"];
              $c2 = $row["$d2"];
              $c3 = $row["$d3"]." ".  $row["$d4"];
              $c4 = $row["$d5"];
              $c5 = $row["$d6"]." / ".$row["$d7"];              
              if(!empty($row["$d9"])){$c6=trim(substr(strip_tags($row["$d9"]),0,15)) ." ...";}else{$c6="";}
              $c6_alt = htmlentities($row["$d9"]); 
              $c8 = $row["$d8"];
              $c10=  $row["$d10"];       // ID articolo
              $c7 = $row["articoli_opz_1"]." - ".$row["articoli_opz_2"]." - ".$row["articoli_opz_3"];
              
              

              
              
            
         
       $h_table.= "<tr class=\"$extra\">";    

        
        if($row["articoli_unico"]==1){$au="SI";}else{$au="";};
        
        if(articoli_in_ordine($row["id_articoli"])==0){
            $delart = "<input type =\"checkbox\" name=\"box_delete[]\" value=\"".$row["id_articoli"]."\">";
        }else{
            $delart ="&nbsp";
        }
        
        $h_table.= "<td>$delart</td>
                    <td $col_1>$c1</td> 
                    <td $col_2><a href=\"../articoli/articoli_form.php?id=$c10\">$c2<a></td>    
                    <td $col_3>$c3</td>
                    <td $col_4>$c4</td>
                    <td $col_5>$c5</td>
                    <td $col_6><a title=\"$c6_alt\">$c6</a></td>
                    <td $col_5>$au</td>
                    <td $col_7>$c7</td>  
                </tr>
            ";
         }//end while

         $h_table.= "   </tbody>
                        </table>
                        <br/>
                        <input type=\"hidden\" name=\"do\" value=\"do_del\">
                        <input type=\"hidden\" name=\"id_listini\" value=\"$id_listini\">
                        <input type=\"submit\" value=\"Cancella gli articoli selezionati\">
                        </form>
                        </div>
         ";

   
      // END TABELLA ----------------------------------------------------------------------------
return $h_table;    
    
}
function listini_render_mod_prz_articoli($id_listini){
    global $db; 
    global $RG_addr;
    
      // INTESTAZIONI
      
      $h1="Codice";      
      $h2="Descrizione";
      $h3="Misura";      
      $h4="Prezzo";
      $h5="Scatola/Multiplo";
      $h6="Note";
      $h7="Opzioni";


      
      // QUERY LISTINI
      $my_query="SELECT 
                 *    
                 FROM retegas_articoli
                 WHERE id_listini='$id_listini'
                 ORDER BY retegas_articoli.codice ASC;";
      
      // NOMI DEI CAMPI
      $d1="codice";
      $d2="descrizione_articoli";
      $d3="u_misura";
      $d4="misura";
      $d5="prezzo";
      $d6="qta_scatola";
      $d7="qta_minima";
      $d8="id_listini";
      $d9="articoli_note";
      $d10 = "id_articoli";
      
      // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
      
      $result = mysql_query($my_query);
        
          
      $h_table .= "
             
            <div class=\"rg_widget rg_widget_helper\">
            <h3>Modifica il prezzo degli articoli interessati, poi clicca sul pulsante in fondo alla pagina.</h3>
            <h4>Puoi modificare i prezzi degli articoli che non sono mai comparsi in nessun ordine</h4>
            
            <form id=\"mod_art\" class=\"retegas_form ui-corner-all\" action=\"\" method=\"POST\">
            <table id=\"table_ref\">
            
        <thead>
            <tr>
            
            <th class=\"sinistra\">$h1</th>
            <th class=\"sinistra\">$h2</th>
            <th class=\"sinistra\">$h3</th>
            <th>$h4</th>
            <th>Nuovo prezzo</th>
            <th>$h5</th>
            <th>$h6</th>
            <th>UNIVOCO</th>
            <th>RAGGR.</th>         
        </tr>
        </thead>
        <tbody>";
  
       $riga=0;  
         while ($row = mysql_fetch_array($result)){
         $riga++;
              $c1 = $row["$d1"];
              $c2 = $row["$d2"];
              $c3 = $row["$d3"]." ".  $row["$d4"];
              $c4 = $row["$d5"];
              $c5 = $row["$d6"]." / ".$row["$d7"];              
              if(!empty($row["$d9"])){$c6=trim(substr(strip_tags($row["$d9"]),0,15)) ." ...";}else{$c6="";}
              $c6_alt = htmlentities($row["$d9"]); 
              $c8 = $row["$d8"];
              $c10=  $row["$d10"];       // ID articolo
              $c7 = $row["articoli_opz_1"]." - ".$row["articoli_opz_2"]." - ".$row["articoli_opz_3"];
              
              

              
              
            
         
       $h_table.= "<tr class=\"$extra\">";    

        
        if($row["articoli_unico"]==1){$au="SI";}else{$au="";};
        
        if(articoli_in_ordine($row["id_articoli"])==0){
            $modart = "<input type =\"hidden\" name=\"box_id[]\" value=\"".$row["id_articoli"]."\">
                       <input type =\"hidden\" name=\"box_old[]\" value=\"".$row["prezzo"]."\" size=\"4\">
                       <input type =\"text\" name=\"box_prz[]\" value=\"".$row["prezzo"]."\" size=\"4\">";
        }else{
            $modart ="&nbsp";
        }
        
        $h_table.= "
                    <td class=\"sinistra\">$c1</td> 
                    <td class=\"sinistra\"><a href=\"../articoli/articoli_form.php?id=$c10\">$c2<a></td>    
                    <td class=\"sinistra\">$c3</td>
                    <td>$c4</td>
                    <td>$modart</td>
                    <td>$c5</td>
                    <td><a title=\"$c6_alt\">$c6</a></td>
                    <td>$au</td>
                    <td>$c7</td>  
                </tr>
            ";
         }//end while

         $h_table.= "   </tbody>
                        </table>
                        <br/>
                        <input type=\"hidden\" name=\"do\" value=\"do_mod\">
                        <input type=\"hidden\" name=\"id_listini\" value=\"$id_listini\">
                        <input type=\"submit\" value=\"Salva i nuovi prezzi\">
                        </form>
                        </div>
         ";

   
      // END TABELLA ----------------------------------------------------------------------------
return $h_table;    
    
}
function listini_render_mod_dsc_articoli($id_listini){
    global $db; 
    global $RG_addr;
    
      // INTESTAZIONI
      
      $h1="Codice";      
      $h2="Descrizione";
      $h3="Misura";      
      $h4="Prezzo";
      $h5="Scatola/Multiplo";
      $h6="Note";
      $h7="Opzioni";
      
      // TOOLTIPS

      
      //  LARGHEZZA E CLASSI COLONNE
      $col_1="width=\"5%\" class=\"gas_c1\"";
      $col_2="width=\"25%\" class=\"gas_c1\"";
      $col_3="width=\"15%\" class=\"gas_c1\"";
      $col_4="width=\"15%\" class=\"gas_c1\"";
      $col_5="width=\"10%\" class=\"gas_c1\"";
      $col_6="width=\"10%\" class=\"gas_c1\"";  
      $col_7="width=\"15%\" style=\"vertical-align:middle\" ";    //opzioni

      
      // QUERY LISTINI
      $my_query="SELECT 
                 *    
                 FROM retegas_articoli
                 WHERE id_listini='$id_listini'
                 ORDER BY retegas_articoli.codice ASC;";
      
      // NOMI DEI CAMPI
      $d1="codice";
      $d2="descrizione_articoli";
      $d3="u_misura";
      $d4="misura";
      $d5="prezzo";
      $d6="qta_scatola";
      $d7="qta_minima";
      $d8="id_listini";
      $d9="articoli_note";
      $d10 = "id_articoli";
      
      // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
      
      $result = mysql_query($my_query);
        
          
      $h_table .= "<br /> 
           
            <h3>Modifica la descrizione agli articoli interessati, poi clicca sul pulsante in fondo alla pagina.</h3>
            <h4>Puoi modificare la descrizione degli articoli che non sono mai comparsi in nessun ordine</h4>
            <h4>NB: Nel caso di articoli soggetti a raggruppamento, se si vuole conservare il raggruppamento occorre modificare la descrizione a tutti gli articoli dello stesso gruppo.</h4>
            <form id=\"mod_art\" class=\"retegas_form ui-corner-all\" action=\"\" method=\"POST\">
            <table id=\"table_ref\">
            
        <thead>
            <tr>
            
            <th>$h1</th>
            <th>$h2</th>
            <th>$h3</th>
            <th>$h4</th>
            <th>$h5</th>
            <th>$h6</th>
            <th>UNIVOCO</th>
            <th>RAGGR.</th>         
        </tr>
        </thead>
        <tbody>";
  
       $riga=0;  
         while ($row = mysql_fetch_array($result)){
         $riga++;
              $c1 = $row["$d1"];
              $c2 = $row["$d2"];
              $c3 = $row["$d3"]." ".  $row["$d4"];
              $c4 = $row["$d5"];
              $c5 = $row["$d6"]." / ".$row["$d7"];              
              if(!empty($row["$d9"])){$c6=trim(substr(strip_tags($row["$d9"]),0,15)) ." ...";}else{$c6="";}
              $c6_alt = htmlentities($row["$d9"]); 
              $c8 = $row["$d8"];
              $c10=  $row["$d10"];       // ID articolo
              $c7 = $row["articoli_opz_1"]." - ".$row["articoli_opz_2"]." - ".$row["articoli_opz_3"];
              
              

              
              
            
         
       $h_table.= "<tr class=\"$extra\">";    

        
        if($row["articoli_unico"]==1){$au="SI";}else{$au="";};
        
        if(articoli_in_ordine($row["id_articoli"])==0){
            $modart = "<input type =\"hidden\" name=\"box_id[]\" value=\"".$row["id_articoli"]."\">
                       <input type =\"text\" name=\"box_dsc[]\" value=\"".$row["descrizione_articoli"]."\" size=\"15\">
                       <input type =\"hidden\" name=\"box_old[]\" value=\"".$row["descrizione_articoli"]."\">";
        }else{
            $modart ="&nbsp";
        }
        
        $h_table.= "
                    <td $col_1>$c1</td> 
                    <td $col_2>$modart</td>    
                    <td $col_3>$c3</td>
                    <td $col_4>$c4</td>
                    <td $col_5>$c5</td>
                    <td $col_6><a title=\"$c6_alt\">$c6</a></td>
                    <td $col_5>$au</td>
                    <td $col_7>$c7</td>  
                </tr>
            ";
         }//end while

         $h_table.= "   </tbody>
                        </table>
                        <br/>
                        <input type=\"hidden\" name=\"do\" value=\"do_mod\">
                        <input type=\"hidden\" name=\"id_listini\" value=\"$id_listini\">
                        <input type=\"submit\" value=\"Salva i nuovi prezzi\">
                        </form>
                     
         ";

   
      // END TABELLA ----------------------------------------------------------------------------
return $h_table;    
    
}

function listini_ordini_con_questo_listino($id_listino){
    global $db,$RG_addr;
    $query = "SELECT * FROM retegas_ordini WHERE ((retegas_ordini.id_listini ='$id_listino'));";    
    $res = $db->sql_query($query);
    $h = "<ul>";
    while ($row = mysql_fetch_array($res)){
    
        $h .= "<li><a href=\"".$RG_addr["ordini_form"]."?id_ordine=".$row["id_ordini"]."\" >".$row["descrizione_ordini"]."</a>, di ".fullname_from_id($row["id_utente"])." chiuso il ".conv_only_date_from_db($row["data_chiusura"])."</li>";
        
    }
    $h .= "</ul>";
    
   return $h; 
}
