<?php
 function tutti_gli_amici($id_user, $tabella_da_ordinare=null,$id_ordine=null,$go_back=null){
     
         // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;  
      global $RG_addr;
      
      $query="SELECT * FROM  retegas_amici WHERE id_referente = '$id_user' 
                AND retegas_amici.is_visible = '1'";
 
      $result= $db->sql_query($query);
      $titolo_tabella = "Amici in rubrica";
      
      $h_table .= "<div class=\"rg_widget rg_widget_helper\"> 
      <h3>$titolo_tabella</h3>";
      
      $h_table .= "
      <form action=\"\" method=\"post\" class=\"retegas_form\">
      <table id=\"$tabella_da_ordinare\">
      <thead>
      <tr class=\"sinistra\">";
      //$h_table .= '<th> OPZ </th>';
      $h_table .="<th>Attivo</th>";
      $h_table .="<th>Nome</th>";
      $h_table .="<th>Indirizzo</th>"; 
      $h_table .="<th>Telefono</th>"; 
      $h_table .="<th>Opzioni</th>"; 
      $h_table .= "</tr>
      </thead>
      <tbody>";
   
     
   
      while ($row = mysql_fetch_array($result)) 
      {
      
      if($row["status"]==1){
          $status = "<input type=\"checkbox\" name=\"box_amici_on[]\" value=\"".$row["id_amici"]."\" CHECKED>";
      }else{
          $status = "<input type=\"checkbox\" name=\"box_amici_on[]\" value=\"".$row["id_amici"]."\">";
      }
          
      $c5 = "<a class=\"option yellow awesome\" title=\"Modifica\" href=\"amici_form_edit.php?ida=".$row["id_amici"]."\">M</a></span>
             <a class=\"option red awesome\" title=\"Cancella\" href=\"amici_form_delete.php?ida=".$row["id_amici"]."\">C</a></span>";   
      
      $h_table.= '<tr>
                        <td>'.$status.'
                        </td>
                        <td>
                        <a href="amici_form.php?ida='.$row["id_amici"].'">
                        '.$row["nome"].'
                        </a>
                        </td>
                        <td>
                        '.$row["indirizzo"].'
                        </td>
                        <td>
                        '.$row["telefono"].'
                        </td>
                        <td>
                        '.$c5.'
                        </td>

                    </tr>';
      
       
      //$h_table .= '<tr><td></td><td  style="background-color:#'.$colo["$row[2]"].';display: block;">'.implode($row,'</td><td>')."</td></tr>\n"; 
      }
      $h_table .= "
      </tbody>
      </table>
      <input type=\"hidden\" name=\"do\" value=\"do_mod\">
      <input type=\"hidden\" name=\"go_back\" value=\"$go_back\">
      <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">
      <br />
      <input type=\"submit\" value=\"Salva le modifiche\">
      </form>
      </div>";
  
       
      // END TABELLA ----------------------------------------------------------------------------
      return $h_table;     
     
     
 }
 function amici_render_scheda($id_amico){

       // QUERY
      $my_query="SELECT * FROM retegas_amici WHERE  (id_amici='$id_amico')  LIMIT 1";
      // TITOLO TABELLA
      $titolo_tabella="Scheda amico";

      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;
      $result = $db->sql_query($my_query);
      $row = $db->sql_fetchrow($result);  

         // VALORI DELLE CELLE da DB---------------------

              $c1 = $row["id_amici"];
              $c2 = $row["nome"];
              $c3 = $row["indirizzo"];
              $c4 = $row["telefono"];
              $c5 = $row["note"];

$h_table .= "
             <div class=\"rg_widget rg_widget_helper\">
             <h3>$titolo_tabella</h3>
             
             <table>  
                <tr>
                    <td>";         

$h_table .=  "<table>
                <tr class=\"odd\">
                    <th $col_1>#</th>
                    <td $col_2>$c1</td>
                </tr>
                <tr class=\"odd\">
                    <th $col_1>Nome</th>
                    <td $col_2>$c2</td>
                </tr>
                <tr class=\"odd\">
                    <th $col_1>Indirizzo</th>
                    <td $col_2>$c3</td>
                </tr>
                <tr class=\"odd\">
                    <th $col_1>Telefono</th>
                    <td $col_2>$c4</td>
                </tr>
                <tr class=\"odd\">
                    <th $col_1>Note</th>
                    <td $col_2>$c5</td>
                </tr>
                </table>";
                
$h_table .="</td>
                <td>


                </td>

        </tr>
        </table>
        </div> ";



        

return $h_table;

  }
 function amici_render_delete($ida){
           
     $h_table .= " 
                    <div class=\"ui-state-error ui-corner-all padding_6px\" style=\"margin-bottom:20px\">
                    <span class=\"ui-icon ui-icon-trash\" style=\"float:left; margin:0 7px 16px 0;\"></span>
                    Stai per cancellare i dati di questa scheda : sei sicuro ?
                    <a href=\"amici_form_delete.php?ida=$ida&do=del\" class=\"medium red awesome\">SI</a> 
                    <a href=\"amici_form.php?ida=$ida\" class=\"medium green awesome\">NO</a><br> (in realtà i dati inseriti rimarranno nel database per poter correttamente vedere anche gli ordini già chiusi)
                    </div>
                    ";
     return $h_table;                   
 } 
 function amici_render_edit($ida){
         global $db;     
        
        
        (int)$id_option;
        
        $query = "SELECT * FROM retegas_amici WHERE retegas_amici.id_amici='$ida' LIMIT 1;";
        $res = $db->sql_query($query);
        $row = $db->sql_fetchrow($res);
        
        $nome           =   $row["nome"];
        $indirizzo      =   $row["indirizzo"];
        $telefono       =   $row["telefono"];
        $note_amici     =   $row["note"];
        
        

        $help_descrizione_ditte='Il nome della ditta.';
        $help_indirizzo='Indirizzo della ditta, se non si sa mettere almeno la città'; 
        $help_website ='Se la sita ha un indirizzo internet inserirlo qua';
        $help_note_ditte ='Si possono mettere immagini facendo il copia e incolla dal sito della ditta in questione. Le immagini saranno collegate, non incorporate.';
        $help_mail_ditte='Mail della ditta, se si lascia vuoto allora sarà inserita la mail del proponente';
        $help_tag_ditte = 'I tag sono delle parole che si possono liberamente associare alla ditta stessa, separate da una virgola, 
        che permettono di filtrarla più agevolmente in mezzo alle altre e quindi di ritrovarla subito.<br>Ad esempio, i tag di una ditta che vende miele possono essere : miele, api, arnie, vasetti, acacia, castagno, biologico, artigianale ';


        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Modifica questa scheda</h3>

        <form name="modifica_options" method="POST" action="amici_form_edit.php" class="retegas_form ui-corner-all">

        
        <div>
        <h4>1</h4>
        <label for="nome">Nome</label>
        <input type="text" name="nome" value="'.$nome.'" size="50"></input>
        <h5 title="'.$help_nome.'">Inf.</h5>
        </div>

        <div>
        <h4>2</h4>
        <label for="indirizzo">Indirizzo</label>
        <input type="text" name="indirizzo" value="'.$indirizzo.'" size="50"></input>
        <h5 title="'.$help_indirizzo.'">Inf.</h5>
        </div>

        <div>
        <h4>3</h4>
        <label for="telefono">Telefono</label>
        <input type="text" name="telefono" value="'.$telefono.'" size="50"></input>
        <h5 title="'.$help_telefono.'">Inf.</h5>
        </div>
 
        <div>
        <h4>4</h4>
        <h5 title="'.$help_note.'">Inf.</h5>
        <label for="note_option">Qua puoi mettere delle note</label>
        <textarea id="note_amici" class ="ckeditor" name="note_amici" cols="28" style="display:inline-block;">'.$note_amici.'</textarea>
        </div>
        
                      
        <div>
        <h4>5</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Salva le modifiche !" align="center" >
        <input type="hidden" name="do" value="mod">
        <input type="hidden" name="ida" value="'.$ida.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        </div>';              


        return $h;     
     
     
 }
 function amici_render_add($ida){
         global $db;     
        
        
        (int)$id_option;
        
        $query = "SELECT * FROM retegas_amici WHERE retegas_amici.id_amici='$ida' LIMIT 1;";
        $res = $db->sql_query($query);
        $row = $db->sql_fetchrow($res);
        
        $nome           =   $row["nome"];
        $indirizzo      =   $row["indirizzo"];
        $telefono       =   $row["telefono"];
        $note_amici     =   $row["note"];
        
        

        $help_nome='Il nome dell\'amico.';
        $help_indirizzo='Campo facoltativo, l\'indirizzo. (in un futuro potrebbe essere geolocalizzato)'; 
        $help_telefono ='Se devi contattare questa persona.';
        $help_note ='Campo libero, mettici cosa vuoi.';


        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Aggiungi un posto a tavola....</h3>

        <form id="rg" name="modifica_options" method="POST" action="amici_form_add.php" class="retegas_form ui-corner-all">

        
        <div>
        <h4>1</h4>
        <label for="nome">Nome</label>
        <input type="text" name="nome" value="'.$nome.'" size="50"></input>
        <h5 title="'.$help_nome.'">Inf.</h5>
        </div>

        <div>
        <h4>2</h4>
        <label for="indirizzo">Indirizzo</label>
        <input type="text" name="indirizzo" value="'.$indirizzo.'" size="50"></input>
        <h5 title="'.$help_indirizzo.'">Inf.</h5>
        </div>

        <div>
        <h4>3</h4>
        <label for="telefono">Telefono</label>
        <input type="text" name="telefono" value="'.$telefono.'" size="50"></input>
        <h5 title="'.$help_telefono.'">Inf.</h5>
        </div>
 
        <div>
        <h4>4</h4>
        
        <label for="note_option">Qua puoi mettere delle note</label>
        <h5 title="'.$help_note.'">Inf.</h5>
        <textarea id="note_amici" class ="ckeditor" name="note_amici" cols="28" style="display:inline-block;">'.$note_amici.'</textarea>
        </div>
        
                      
        <div>
        <h4>5</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="...che c\'è un amico in più..." align="center" >
        <input type="hidden" name="do" value="add">
        <input type="hidden" name="ida" value="'.$ida.'">
        
        </div> 


        </form>
        
        <div>Le istruzioni su cosa siano gli "amici" le trovi cliccando <a href="https://sites.google.com/site/retegasapwiki/meccanismi-di-funzionamento/la-sezione-amici" target="_blank">QUA</a></div>
        
        </div>';              


        return $h;     
     
     
 }
 
 
 function menu_nuovo_amico($id_user){
     global $RG_addr;
      
     $permissions = leggi_permessi_utente($id_user);
      
      if($permissions && perm::puo_avere_amici){
            $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["add_amico"].'">'._MO_AGGIUNGI_AMICO.'</a></li>';

          
      }
     
   return $h_menu;  
 }
 function menu_visualizza_amici($id_user){
     global $RG_addr;
      
     $permissions = leggi_permessi_utente($id_user);
      
      if($permissions && perm::puo_avere_amici){
            
          $h_menu .='<li><a class="medium green awesome" href="#">Visualizza</a>';
          $h_menu .='<ul>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["pag_amici_table"].'">Rubrica Amici</a></li>';
            $h_menu .='</ul>';
          $h_menu .='</li>';
          
      }
     
   return $h_menu;  
 }
 function menu_operazioni_amici($id_user,$ida=null){
     global $RG_addr;
      
     $permissions = leggi_permessi_utente($id_user);
      
      if($permissions && perm::puo_avere_amici){
          if(isset($ida)){
            
          $h_menu .='<li><a class="medium yellow awesome" href="#">Operazioni</a>';
          $h_menu .='<ul>';
            $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["pag_amici_mod"].'?ida='.$ida.'">Modifica scheda</a></li>';
            $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["pag_amici_canc"].'?ida='.$ida.'">Elimina scheda</a></li>';
            $h_menu .='</ul>';
          $h_menu .='</li>';
      } 
          
      }
     
   return $h_menu;  
 }
   
?>