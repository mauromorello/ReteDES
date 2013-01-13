<?php
 

 function amministra_opzioni_table($tabella_da_ordinare=null, $id_user=null){
       global $db;  
       global $RG_addr;
      
      if(!is_numeric($id_user)){ 
        $query="SELECT * FROM  retegas_options";
      }else{
        $query="SELECT * FROM  retegas_options WHERE id_user='$id_user'";  
      }
      $result= mysql_query($query);

      $h_table .="</ hr>";
      $h_table .= "<div class=\"rg_widget rg_widget_helper\">";
      $h_table .= "<table id=\"$tabella_da_ordinare\">
      <thead>
      <tr>";
      //$h_table .= '<th> OPZ </th>';
      $h_table .="<th>ID</th>";
      $h_table .="<th>OWNER</th>";
      $h_table .="<th>CHIAVE</th>"; 
      $h_table .="<th>TEXT</th>"; 
      $h_table .="<th>INT</th>"; 
      $h_table .="<th>REAL</th>"; 
      $h_table .="<th>TIMBRO</th>"; 
      $h_table .="<th>NOTE</th>"; 
      $h_table .="<th>OPZ</th>";
      $h_table .= "</tr>
      </thead>
      <tbody>";
   
      $url = getCurrentPageURL();
      $url = urlencode($url);
   
      while ($row = mysql_fetch_array($result)) 
      {
          
         
      $h_table.= '<tr>
                        <td>
                        '.$row["id_option"].'
                        </td>
                        <td>
                        <a href="'.$RG_addr["user_form_public"].'?id='.mimmo_encode($row["id_user"]).'">
                        '.$row["id_user"].'
                        </a>
                        </td>
                        <td>
                        '.$row["chiave"].'
                        </td>
                        <td>
                        '.substr($row["valore_text"],0,20).'...
                        </td>
                        <td>
                        '.$row["valore_int"].'
                        </td>
                        <td>
                        '.$row["valore_real"].'
                        </td>
                        <td>
                        '.conv_datetime_from_db($row["timbro"]).'
                        </td>
                        <td>
                        '.substr($row["note_1"],0,20).'...<hr>
                        '.substr(print_r(unserialize(base64_decode($row["valore_text"])),true),0,20).'...
                        </td>
                        <td style="width:8em">
                        <a class="awesome black small" href="'.$RG_addr["amministra_option_edit"].'?id_option='.$row["id_option"].'">Mod</a>
                        <a class="awesome red small" href="'.$RG_addr["amministra_option_edit"].'?id_option='.$row["id_option"].'&do=del&url='.$url.'">C</a>
                        </td>
                    </tr>';
      
       
      //$h_table .= '<tr><td></td><td  style="background-color:#'.$colo["$row[2]"].';display: block;">'.implode($row,'</td><td>')."</td></tr>\n"; 
      }
      $h_table .= "
      </tbody>
      </table>
      </div>";
  
       
      // END TABELLA ----------------------------------------------------------------------------
      return $h_table;    
 }
 function amministra_opzioni_edit($id_option){
         global $db;     
        
        
        (int)$id_option;
        
        $query = "SELECT * FROM retegas_options WHERE retegas_options.id_option='$id_option' LIMIT 1;";
        $res = $db->sql_query($query);
        $row = $db->sql_fetchrow($res);
        
        $id_owner   =   $row["id_user"];
        $chiave     =   $row["chiave"];
        $valore_text=   $row["valore_text"];
        $valore_real=   $row["valore_real"];
        $valore_int =   $row["valore_int"];
        $note_option=   $row["note_1"];
        
        

        $help_descrizione_ditte='Il nome della ditta.';
        $help_indirizzo='Indirizzo della ditta, se non si sa mettere almeno la città'; 
        $help_website ='Se la sita ha un indirizzo internet inserirlo qua';
        $help_note_ditte ='Si possono mettere immagini facendo il copia e incolla dal sito della ditta in questione. Le immagini saranno collegate, non incorporate.';
        $help_mail_ditte='Mail della ditta, se si lascia vuoto allora sarà inserita la mail del proponente';
        $help_tag_ditte = 'I tag sono delle parole che si possono liberamente associare alla ditta stessa, separate da una virgola, 
        che permettono di filtrarla più agevolmente in mezzo alle altre e quindi di ritrovarla subito.<br>Ad esempio, i tag di una ditta che vende miele possono essere : miele, api, arnie, vasetti, acacia, castagno, biologico, artigianale ';


        $h = '<div class="retegas_form ui-corner-all">
        <h3>Modifica questa opzione</h3>

        <form name="modifica_options" method="POST" action="amministra_options_edit.php">

        
        <div>
        <h4>1</h4>
        <label for="id_owner">Id proprietario Option</label>
        <input type="text" name="id_owner" value="'.$id_owner.'" size="50"></input>
        <h5 title="'.$help_id_owner.'">Inf.</h5>
        </div>

        <div>
        <h4>2</h4>
        <label for="chiave">Chiave</label>
        <input type="text" name="chiave" value="'.$chiave.'" size="50"></input>
        <h5 title="'.$help_chiave.'">Inf.</h5>
        </div>

        <div>
        <h4>3</h4>
        <label for="valore_text">VALORE TEXT</label>
        <input type="text" name="valore_text" value="'.$valore_text.'" size="50"></input>
        <h5 title="'.$help_text.'">Inf.</h5>
        </div>
        
        <div>
        <h4>4</h4>
        <label for="valore_int">VALORE INT</label>
        <input type="text" name="valore_int" value="'.$valore_int.'" size="50"></input>
        <h5 title="'.$help_valore_int.'">Inf.</h5>
        </div>
        
        <div>
        <h4>5</h4>
        <label for="valore_real">VALORE REAL</label>
        <input type="text" name="valore_real" value="'.$valore_real.'" size="50"></input>
        <h5 title="'.$help_valore_real.'">Inf.</h5>
        </div>

        <div>
        <h4>6</h4>
        <h5 title="'.$help_note_option.'">Inf.</h5>
        <label for="note_option">Qua puoi mettere delle note</label>
        <textarea id="note_option" class ="ckeditor" name="note_option" cols="28" style="display:inline-block;">'.$note_option.'</textarea>
        </div>
        
                      
        <div>
        <h4>7</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Salva le modifiche !" align="center" >
        <input type="hidden" name="do" value="mod">
        <input type="hidden" name="id_option" value="'.$id_option.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        </div>';              


        return $h;     
     
     
 }
 function amministra_user_info($id){
 
 global $RG_addr,$db;
 
 $new_id = mimmo_encode($id);
 $full = fullname_from_id($id);
 
 $ass = lista_assonanze($full,75);
 
 $don ="<span style=\"display:inline-block;margin:0\">
        <form method=\"post\" action=\"".$RG_addr["amministra_user_donate"]."\"><input size=3 type=\"text\" name=\"euros\" value=0>
              <input type=\"hidden\" name=\"id_utente\" value=\"".mimmo_encode($id)."\">
              <input type=\"hidden\" name=\"do\" value=\"donate\">
              <input type=\"submit\" value=\"GO\">
              </form>
              </span>";
 
 
 $pre = '<div style="   padding:6px;
                        margin-bottom:2px; 
                        border:3px solid rgba(60, 210, 40, 1); 
                        background-color:rgba(60, 210, 40, .5)" class="ui-corner-all">';


 $post = '</div>';
 
 
 $h .=' <div class="rg_widget rg_widget_helper">
        <table>
        <tr>
        <td width="50%" style="vertical-align:top">';
        
 $h .= $pre.'ID: <b>'.$id.'</b>'.$post;     
 $h .= $pre.'USERNAME: <b>'.db_val_q("userid",$id,"username","maaking_users").'</b>'.$post;
 $h .= $pre.'DONATION: <b> : '._nf(read_option_decimal($id,"DONATE")).' -->  '.$don.'</b>'.$post;
 
 $h .= $pre.'MAIL: <b class="edit_email" id="'.$new_id.'">'.db_val_q("userid",$id,"email","maaking_users").'</b>'.$post;
 $h .= $pre.'STATE: <b>'.db_val_q("userid",$id,"isactive","maaking_users").'</b>'.$post;   
 $h .= $pre.'FULLNAME: <b class="edit_fullname" id="'.$new_id.'">'.db_val_q("userid",$id,"fullname","maaking_users").'</b>'.$post;
 $h .= $pre.'LAST ACT: <b>'.db_val_q("userid",$id,"last_activity","maaking_users").'</b>'.$post;
 $h .= $pre.'LAST LOGIN: <b>'.db_val_q("userid",$id,"lastlogin","maaking_users").'</b>'.$post;
 $h .= $pre.'REGDATE: <b>'.db_val_q("userid",$id,"regdate","maaking_users").'</b>'.$post;
 
 $utente_id_gas = db_val_q("userid",$id,"id_gas","maaking_users");
 $h .= $pre.'ID_GAS: <b>'.$utente_id_gas.'</b>'.$post;
 $h .= $pre.'GAS CHECK MIN CASSA: <b>'._GAS_CASSA_CHECK_MIN_LEVEL.'</b>'.$post;
 $h .= $pre.'ID_DES: <b>'.db_val_q("id_gas",$utente_id_gas,"id_des","retegas_gas").'</b>'.$post;
 
 
 $h .= $pre.'IP ADDR: <b>'.db_val_q("userid",$id,"ipaddress","maaking_users").'</b>'.$post;
 $h .= $pre.'USER AGENT: <b>'.db_val_q("userid",$id,"user_Start_page","maaking_users").'</b>'.$post;
 $h .= $pre.'LAT: <b>'.db_val_q("userid",$id,"user_gc_lat","maaking_users").'</b>'.$post;
 $h .= $pre.'LNG: <b>'.db_val_q("userid",$id,"user_gc_lng","maaking_users").'</b>'.$post;
 $h .= $pre.'ADDRESS 1: <b class="edit_address_1" id="'.$new_id.'">'.db_val_q("userid",$id,"country","maaking_users").'</b>'.$post;
 $h .= $pre.'ADDRESS 2: <b class="edit_address_2" id="'.$new_id.'">'.db_val_q("userid",$id,"city","maaking_users").'</b>'.$post;

 
 $h .= $pre.'Operations : <a class="awesome black small" href="#" onclick="$(\'#confirm_delete\').dialog(); return false;">Elimina</a>'.$post;
 
 $h .= '</td>
        <td width="50%" style="vertical-align:top">';
 $h .= $pre.'RECORDS in messaggi: <b>'.db_nr_q("id_user",$id,"retegas_messaggi").'</b>'.$post;    
 $h .= $pre.'RECORDS in bacheca: <b>'.db_nr_q("id_utente",$id,"retegas_bacheca").'</b>'.$post;    
 $h .= $pre.'RECORDS in listini: <b>'.db_nr_q("id_utenti",$id,"retegas_listini").'</b>'.$post;
 $h .= $pre.'RECORDS in maaking_users: <b>'.db_nr_q("userid",$id,"maaking_users").'</b>'.$post;
 $h .= $pre.'RECORDS in dettaglio ordini: <b>'.db_nr_q("id_utenti",$id,"retegas_dettaglio_ordini").'</b>'.$post;       
 $h .= $pre.'RECORDS in amici: <b>'.db_nr_q("id_referente",$id,"retegas_amici").'</b>'.$post;    
 $h .= $pre.'RECORDS in ordini: <b>'.db_nr_q("id_utente",$id,"retegas_ordini").'</b>'.$post;    
 $h .= $pre.'RECORDS in distribuzione spesa: <b>'.db_nr_q("id_user",$id,"retegas_distribuzione_spesa").'</b>'.$post;    
 $h .= $pre.'RECORDS in Opzioni: <b>'.db_nr_q("id_user",$id,"retegas_options").'</b>'.$post;
 $h .= $pre.'ASSONANZE con '.$full.': <br>'.$ass."".$post;
 $h .= $pre.'Permessi accordati : '.utenti_scheda_permessi($id).$post;   
   

 
 $h .='</td>
        </tr>
        </table>
        </div>
        <div id="confirm_delete" style="display:none">Questa azione è irrimediabile. Sei sicuro ? <br><br><br>
        <a class="awesome small black" href="'.$RG_addr["amministra_user_info"].'?id_utente='.mimmo_encode($id).'&do=del'.mimmo_encode($id).'">ELIMINA</a>
        </div>';
        
  
        
 return $h;
     
 } 

function amministra_infiltrati(){
    global $db;
    
    $h ='<div class="rg_widget rg_widget_helper">
        <h3>
        Cambia gas di appartenenza.
        </h3>
        <form method="POST" action ="" class="retegas_form">
        
        
        <fieldset>
        <label for="gasappartenenza">Utente Target</label>
        <select name= "id_utente_target">';
        $result = $db->sql_query("SELECT * FROM maaking_users ORDER BY fullname ASC");
        $totalrows = mysql_num_rows($result);
        $content .= "<option value=\"-1\">Selezionare Utente</option>";
        while ($row = mysql_fetch_array($result)){
                $idgas = $row['userid'];
                $descrizionegas = $row['fullname']." - ".gas_nome($row["id_gas"]);
                if ($idgas==_USER_ID){$agg=" selected ";}else{$agg=null;}
        $h .= "<option value=\"".$idgas ."\" $agg>".$descrizionegas ."  </option>";   
        }//end while
        $h .='</select>
        </fieldset>
        <fieldset>
        <label for="gasappartenenza">Gas di futura appartenenza</label>
        <select name= "gasappartenenza">';
        $result = $db->sql_query("SELECT * FROM retegas_gas ORDER BY id_gas ASC");
        $totalrows = mysql_num_rows($result);
        $content .= "<option value=\"-1\">Selezionare GAS</option>";
        while ($row = mysql_fetch_array($result)){
                $idgas = $row['id_gas'];
                $descrizionegas = $row['descrizione_gas'];
                if ($idgas==_USER_ID_GAS){$agg=" selected ";}else{$agg=null;}
        $h .= "<option value=\"".$idgas ."\" $agg>".$descrizionegas ."  </option>";   
        }//end while
        $h .="</select></fieldset>
        <input type=\"hidden\" name=\"do\" value=\"do_change\">
        <br>
        <center>
        <input type=\"submit\" value=\"Salva\">
        </center>
        </form>
        </div>";
        
        return $h;
    
} 
                     