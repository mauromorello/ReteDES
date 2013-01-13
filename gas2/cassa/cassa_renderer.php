<?php

//AGGIUNGI CREDITO UTENTE
function cassa_add_credits($id_ut){


        global $db;     
        global  $usr,
                $importo,
                $descrizione_movimento;



        $help_descrizione_movimento='Note sul movimento';
        //$help_ditta ='Seleziona una ditta tra quelle con listini disponibili';
        //$help_listino ='Seleziona un listino associato alla ditta scelta in precedenza tra quelli disponibili';
        //$help_data_chiusura='Scegli quando l\'ordine deve chiudersi;<br>Se lasciato vuoto, si chiuderà tra una settimana alle 22.00';
        $help_partenza = 'Qualsiasi errore di carico potrà essere rettificato successivamente';


        $h = '<div class="retegas_form ui-corner-all">
        <h3>Aggiungi credito a '.fullname_from_id($id_ut).'</h3>

        <form name="add_credit" method="POST" action="">

        <div>
        <h4>1</h4>
        <label for="codice">Inserisci il credito da caricare</label>
        <input type="text" name="importo" value="'.$importo.'" size="20"></input>
        <h5 title="'.$help_importo.'">Inf.</h5>
        </div>
        
        <div>
        <h4>2</h4>
        <label for="descrizione">... eventualmente una nota breve (max 200 caratteri)</label>
        <input type="text" name="descrizione_movimento" value="'.$descrizione_movimento.'" size="50"></input>
        <h5 title="'.$help_descrizione_movimento.'">Inf.</h5>
        </div> 
        
        
        
        
        <div>
        <h4>3</h4>
        <label for="submit">infine... </label>
        <input type="submit" name="submit" value="Vai alla pagina di conferma" align="center" >
        <input type="hidden" name="do" value="conf">
        <input type="hidden" name="id_ute" value="'.$id_ut.'"> 
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        ';              


        return $h;      

}
function cassa_add_credits_confirm($id_ute){


        global $db;     
        global  $usr,
                $importo,
                $descrizione_movimento;

        $help_pwd='Per evitare che su un computer lasciato incustodito possano essere effettuate operazioni pericolose.';        
        $help_partenza ='Ricarica il credito dell\'utente';
        $help_contabilizza ='Se clicchi questa opzione il movimento risulterà subito contabilizzato.';
        
        if(read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_USE_PASSWORD_CONFIRM")=="SI"){
            $check_pwd ='<div>
                            <h4>1</h4>
                            <label for="pwd">Inserisci la tua password</label>
                            <input type="password" name="pwd" value="" size="20"></input>
                            <h5 title="'.$help_pwd.'">Inf.</h5>
                        </div>';    
        }else{
            $check_pwd ="";    
        }
                
        $h2 ='
        <div class="retegas_form ui-corner-all">
        <h3>Richiesta di conferma operazione</h3>
        <strong>Stai per aggiungere '.$importo.' Euro al credito di '.fullname_from_id($id_ute).';<br>
        </strong>
        <br>
        
        <form name="add_credit" method="POST" action="">
        
        '.$check_pwd.'
        
        <div>
            <h4>2</h4>
            <label for="contabilizza">Contabilizza subito</label>
            <input type="checkbox" name="contabilizza" value="si" size="20"></input>
            <h5 title="'.$help_contabilizza.'">Inf.</h5>
        </div>
        
        <div>
        <h4>3</h4>
        <label for="submit">infine... </label>
        <input type="submit" name="submit" value="Conferma operazione" align="center" >
        <input type="hidden" name="importo" value="'.$importo.'">
        <input type="hidden" name="descrizione_movimento" value="'.$descrizione_movimento.'">
        <input type="hidden" name="do" value="add">
        <input type="hidden" name="validation" value ="'.mimmo_encode(time()).'">
        <input type="hidden" name="id_ute" value="'.$id_ute.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div>

        </form>
        
        <h5>
        oppure <a class="awesome medium red" href="">Abbandona</a>
        </h5>
        <br>
        <br>
        </div>
        ';        

        return $h2;      

}


//PAGA UNA DITTA
function cassa_pay_ditta(){


        global $db;     
        global  $id_ordine,
                $importo,
                $descrizione_movimento,
                $numero_documento;



        $help_descrizione_movimento='Note sul movimento';
        //$help_ditta ='Seleziona una ditta tra quelle con listini disponibili';
        //$help_listino ='Seleziona un listino associato alla ditta scelta in precedenza tra quelli disponibili';
        //$help_data_chiusura='Scegli quando l\'ordine deve chiudersi;<br>Se lasciato vuoto, si chiuderà tra una settimana alle 22.00';
        $help_partenza = 'Qualsiasi errore di carico potrà essere rettificato successivamente';


        $h = '<div class="retegas_form ui-corner-all">
        <h3>Paga l\'ordine a '.($id_ut).'</h3>

        <form name="add_credit" method="POST" action="">

        <div>
        <h4>1</h4>
        <label for="codice">Inserisci il credito da scaricare dalla cassa GAS</label>
        <input type="text" name="importo" value="'.$importo.'" size="20"></input>
        <h5 title="'.$help_importo.'">Inf.</h5>
        </div>
        
        <div>
        <h4>2</h4>
        <label for="descrizione">... eventualmente una nota breve (max 200 caratteri)</label>
        <input type="text" name="descrizione_movimento" value="'.$descrizione_movimento.'" size="50"></input>
        <h5 title="'.$help_descrizione_movimento.'">Inf.</h5>
        </div> 
        
        <div>
        <h4>3</h4>
        <label for="numero_documento">Se esiste indicare il numero del documento (fattura, bolla)</label>
        <input type="text" name="numero_documento" value="'.$numero_documento.'" size="50"></input>
        <h5 title="'.$help_numero_documento.'">Inf.</h5>
        </div>
        
        
        <div>
        <h4>3</h4>
        <label for="submit">dopodichè... </label>
        <input type="submit" name="submit" value="Vai alla pagina di conferma" align="center" >
        <input type="hidden" name="do" value="conf">
        <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        ';              


        return $h;      

}
function cassa_pay_ditta_confirm(){


        global $db;     
        global  $id_ordine,
                $importo,
                $descrizione_movimento,
                $numero_documento;

        $help_pwd='Per evitare che su un computer lasciato incustodito possano essere effettuate operazioni pericolose.';        
        $help_partenza ='Completa il pagamento. E\' ovvio che con questa operazione la ditta non riceverà realmente i soldini...';
        $help_contabilizza ='Se clicchi questa opzione il movimento risulterà subito contabilizzato.';
                
        $h2 ='
        <div class="retegas_form ui-corner-all">
        <h3>Richiesta di conferma operazione</h3>
        <strong>Stai per sottrarre '.$importo.' Euro alla cassa del '.fullname_from_id($id_ute).';<br>
        </strong>
        <br>
        
        <form name="add_credit" method="POST" action="">
        <div>
            <h4>1</h4>
            <label for="pwd">Inserisci la tua password</label>
            <input type="password" name="pwd" value="" size="20"></input>
            <h5 title="'.$help_pwd.'">Inf.</h5>
        </div>
        
        <div>
            <h4>2</h4>
            <label for="contabilizza">Contabilizza subito</label>
            <input type="checkbox" name="contabilizza" value="si" size="20"></input>
            <h5 title="'.$help_contabilizza.'">Inf.</h5>
        </div>
        
        <div>
        <h4>3</h4>
        <label for="submit">infine... </label>
        <input type="submit" name="submit" value="Conferma operazione" align="center" >
        <input type="hidden" name="importo" value="'.$importo.'">
        <input type="hidden" name="descrizione_movimento" value="'.$descrizione_movimento.'">
        <input type="hidden" name="numero_documento" value="'.$numero_documento.'">
        <input type="hidden" name="do" value="pay">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div>

        </form>
        
        <h5>
        oppure <a class="awesome medium red" href="">Abbandona</a>
        </h5>
        <br>
        <br>
        </div>
        ';        

        return $h2;      

}

//RETTIFICA MOVIMENTO (BETA)
function cassa_rett_credits($id_ut){


        global $db;     
        global  $usr,
                $importo,
                $descrizione_movimento,
                $segno_movimento;

        if($segno_movimento=="+"){
            $chk1="checked";
            $chk2="";
        }else{
            $chk2="checked";
            $chk1="";
        }
                

        $help_descrizione_movimento='Descrizione movimento';
        //$help_ditta ='Seleziona una ditta tra quelle con listini disponibili';
        //$help_listino ='Seleziona un listino associato alla ditta scelta in precedenza tra quelli disponibili';
        //$help_data_chiusura='Scegli quando l\'ordine deve chiudersi;<br>Se lasciato vuoto, si chiuderà tra una settimana alle 22.00';
        $help_partenza = 'Qualsiasi errore di rettifica potrà essere rettificato successivamente';


        $h = '<div class="retegas_form ui-corner-all">
        <h3>Rettifica credito a '.fullname_from_id($id_ut).' (pag 1 di 2)</h3>

        <form name="rett_credit" method="POST" action="">

        <div>
        <h4>1</h4>
        <label for="codice">Inserisci il credito da rettificare</label>
        <input type="text" name="importo" value="'.$importo.'" size="20"></input>
        <h5 title="'.$help_importo.'">Inf.</h5>
        </div>
        
        <div>
        <h4>2</h4>
        <label for="segno_movimento">Il tipo di operazione</label>
        <input type="radio" name="segno_movimento" value="-" '.$chk1.'>Sottrai Credito
        <input type="radio" name="segno_movimento" value="+" '.$chk2.'>Aggiungi Credito
        <h5 title="'.$help_segno_movimento.'">Inf.</h5>
        </div>
  
        <div>
        <h4>3</h4>
        <label for="descrizione">una nota obbligatoria per spiegare il movimento (max 200 caratteri)</label>
        <input type="text" name="descrizione_movimento" value="'.$descrizione_movimento.'" size="50"></input>
        <h5 title="'.$help_descrizione_movimento.'">Inf.</h5>
        </div> 
        
        <div>
        <h4>4</h4>
        <h5 title="'.$help_note_movimento.'">Inf.</h5>
        <label for="note_movimento">Puoi aggiungere delle note estese.</label>
        <textarea id="note_movimento" class ="ckeditor" name="note_movimento" cols="20" style="display:inline-block;">'.$note_movimento.'</textarea>
        </div> 
        
        <div>
        <h4>5</h4>
        <label for="submit">poi... </label>
        <input type="submit" name="submit" value="Prosegui" align="center" >
        <input type="hidden" name="do" value="conf">
        <input type="hidden" name="id_ute" value="'.$id_ut.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        ';              


        return $h;      

}
function cassa_rett_credits_confirm($id_ute){


        global $db;     
        global  $usr,
                $importo,
                $descrizione_movimento,
                $note_movimento,
                $segno_movimento;

        $help_pwd='Per evitare che su un computer lasciato incustodito possano essere effettuate operazioni pericolose.';        
        $help_partenza ='Ricarica il credito dell\'utente';
        $help_contabilizza ='Se clicchi questa opzione il movimento risulterà subito contabilizzato.';
                
        $h2 ='
        <div class="retegas_form ui-corner-all">
        <h3>Rettifica credito (pag 2/2)</h3>
        <strong>Stai per rettificare di '.$importo.' Euro il credito di '.fullname_from_id($id_ute).';<br>
        </strong>
        <br>
        
        <form name="add_credit" method="POST" action="">
        <div>
            <h4>1</h4>
            <label for="pwd">Inserisci la tua password</label>
            <input type="password" name="pwd" value="" size="20"></input>
            <h5 title="'.$help_pwd.'">Inf.</h5>
        </div>
        
        <div>
            <h4>2</h4>
            <label for="registra">Registra subito subito</label>
            <input type="checkbox" name="registra" value="si" size="20" CHECKED></input>
            <h5 title="'.$help_registra.'">Inf.</h5>
        </div>
        
        <div>
            <h4>3</h4>
            <label for="contabilizza">Contabilizza subito</label>
            <input type="checkbox" name="contabilizza" value="si" size="20"></input>
            <h5 title="'.$help_contabilizza.'">Inf.</h5>
        </div>
        
        <div>
        <h4>3</h4>
        <label for="submit">infine... </label>
        <input type="submit" name="submit" value="Conferma operazione" align="center" >
        <input type="hidden" name="importo" value="'.$importo.'">
        <input type="hidden" name="descrizione_movimento" value="'.$descrizione_movimento.'">
        <input type="hidden" name="note_movimento" value="'.$note_movimento.'">
        <input type="hidden" name="segno_movimento" value="'.$segno_movimento.'">
        <input type="hidden" name="do" value="rett">
        <input type="hidden" name="id_ute" value="'.$id_ute.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div>

        </form>
        
        <h5>
        oppure <a class="awesome medium red" href="">Abbandona</a>
        </h5>
        <br>
        <br>
        </div>
        ';        

        return $h2;      

}


function cassa_situazione_users_table($id_gas){
global $db,$RG_addr;      

    $result = mysql_query("SELECT * FROM maaking_users WHERE id_gas='$id_gas' AND isactive=1;");
    $totalrows = mysql_num_rows($result);     
    $gas_name = gas_nome($id_gas);



    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Utenti $gas_name, situazione saldi individuali</h3>
            <table id=\"output\">
         <thead>
         <tr>
         <th>&nbsp;</th>         
        <th>#</th>
        <th>Nome</th>
        <th>Op. Da Registrare</th>
        <th>Op. Da Contabilizzare</th>
        <th>Saldo in attesa</th>
        <th>Saldo Registrato</th>
        <th>Saldo Contabilizzato</th>
        <th>Saldo Attuale</th> 
        </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
            
            if(read_option_text($row["userid"],"_USER_USA_CASSA")){ 
             
                $riga++;
                $d1 = "id_gas";
                $id_utente = $row["userid"];
                $fullname = $row["fullname"];
                $saldo_c = "-"; //cassa_saldo_utente_contabilizzata($id_utente);
                $saldo_a = cassa_saldo_utente_in_attesa($id_utente);
                $saldo_r = cassa_saldo_utente_registrato($id_utente);
                $saldo_t = cassa_saldo_utente_totale($id_utente);
                $op1='<a class="awesome small celeste" href="'.$RG_addr["movimenti_cassa_users"].'?id_utente='.mimmo_encode($id_utente).'">M</a>'; 
                
                $h.= "
                <tr>
                <td $col_1>$op1</td>
                <td $col_1>$id_utente</td> 
                <td $col_2><a href=\"".$RG_addr["pag_users_form"]."?id_utente=".mimmo_encode($id_utente)."\">$fullname</a></td>
                <td $col_3>&nbsp;</td>
                <td $col_4>&nbsp;</td>
                <td $col_5>$saldo_a</td>
                <td $col_5>$saldo_r</td>
                <td $col_5>$saldo_c</td>
                <td $col_5>$saldo_t</td>   
                </tr>";
            }
         }//end while


         $h.= "
         </tbody>
         </table>";
         return $h;    
    
    
}
function cassa_movimenti_users_table($id_user){
global $db,$RG_addr,$__movcas;      

    $result = mysql_query("SELECT * FROM retegas_cassa_utenti WHERE id_utente='$id_user' ORDER BY id_cassa_utenti ASC;");
    $totalrows = mysql_num_rows($result);     
    $fullname = fullname_from_id($id_user);


    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Utente $fullname, situazione movimenti individuali al ".date("d/m/Y H:i")."</h3>
            <table id=\"output\">
         <thead>
         <tr>
        <th>&nbsp;</th>          
        <th>#</th>
        <th data-sorter=\"shortDate\">Data</th>
        <th>Tipo</th>
        <th>Credito</th>
        <th>Debito</th>
        <th>Descrizione</th>
        <th>Ordine</th>
        <th>Cassiere</th>
        <th>REG</th>
        <th>CON</th> 
        </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
         $riga++;

         $id_op = $row["id_cassa_utenti"];
         $data_op = conv_datetime_from_db($row["data_movimento"]);
         $tipo_op = $__movcas[$row["tipo_movimento"]];
         if($row["segno"]=="+"){
             $credito_op = _nf($row["importo"]);
             $debito_op = "&nbsp";
         }else{
             $debito_op = _nf($row["importo"]);
             $credito_op = "&nbsp";
         }
         $descrizione_op = $row["descrizione_movimento"];
         if($row["id_ordine"]<>0){
            $ordine_op = $row["id_ordine"] . "<br><span class=\"small_link\">" . descrizione_ordine_from_id_ordine($row["id_ordine"])."</span>";
         }else{
            $ordine_op = null; 
         }
         $cassiere_op = fullname_from_id($row["id_cassiere"]);
         if($row["registrato"]=="si"){
             $REG = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_registrato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             $REG = "NO";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }
         if($row["contabilizzato"]=="si"){
             $CON = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_contabilizzato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             $CON = "NO";
         }
         
         
            $h.= "
            <tr>
            <td $col_1>$pal</td>
            <td $col_1><a class=\"awesome small blue\"href=\"".$RG_addr["cassa_movimento_singolo"]."?id_movimento=$id_op\">$id_op</a></td> 
            <td $col_1>$data_op</td>
            <td $col_1>$tipo_op</td>
            <td $col_5>$credito_op</td>
            <td $col_5>$debito_op</td>
            <td $col_1>$descrizione_op</td>
            <td $col_1>$ordine_op</td>
            <td $col_1>$cassiere_op</td>
            <td $col_1>$REG</td>
            <td $col_1>$CON</td>   
            </tr>";

         }//end while


         $h.= "
         </tbody>
         </table>";
         return $h;    
    
    
}
function cassa_movimenti_gas_table($id_gas, $filter = null){
global $db,$RG_addr,$__movcas;      

    if(is_empty($filter)){
        $filter = " WHERE id_gas='$id_gas' ";
    }else{
        $filter = $filter ." AND id_gas='$id_gas' "; 
    }

    $result = $db->sql_query("SELECT * FROM retegas_cassa_utenti $filter ORDER BY id_cassa_utenti DESC;");
    $totalrows = mysql_num_rows($result);     
    $fullname = fullname_from_id($id_user);

    
    

    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Tutti i movimenti inseriti al ".date("d/m/Y H:i")."</h3>
            <span class=\"small_link\">Filtro attuale: <strong>$filter</strong></span>
            <form method=\"POST\" action=\"\">
              <label for=\"data_dal\">Dal (compreso)</label>
              <input id=\"data_dal\" type=\"datetime\" name=\"data_dal\">
              <label for=\"data_al\">Al (escluso)</label>
              <input id=\"data_al\" type=\"datetime\" name=\"data_al\">
              <input type=\"submit\" value=\"filtra\">
            
            </form>
            
            <table id=\"output\" class=\"medium_size\">
         <thead>
         <tr>
        <th>&nbsp</th>          
        <th>#</th>
        <th>Utente</th>
        <th data-sorter=\"shortDate\">Data</th>
        <th>Tipo</th>
        <th>Credito</th>
        <th>Debito</th>
        <th>Vis.</th>
        <th>Ordine</th>
        <th>Ditta</th>
        <th>Cassiere</th>
        <th>REG</th>
        <th>CON</th> 
        </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
         $riga++;

         $id_op = $row["id_cassa_utenti"];
         $data_op = conv_datetime_from_db($row["data_movimento"]);
         $tipo_op = $__movcas[$row["tipo_movimento"]];
         if($row["segno"]=="+"){
             $credito_op = number_format($row["importo"],2,".","");
             $debito_op = "&nbsp";
         }else{
             $debito_op = number_format($row["importo"],2,".","");
             $credito_op = "&nbsp";
         }
         $descrizione_op = $row["descrizione_movimento"];
         $ordine_op = "<a ".rg_tooltip(descrizione_ordine_from_id_ordine($row["id_ordine"])).">".$row["id_ordine"]."</a>";
         $cassiere_op = fullname_from_id($row["id_cassiere"]);
         $ditta ="<a ".rg_tooltip(ditta_nome($row["id_ditta"])).">".$row["id_ditta"]."</a>";
         $utente = fullname_from_id($row["id_utente"]);
         
         if($row["registrato"]=="si"){
             $REG = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_registrato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             $REG = "NO";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }
         if($row["contabilizzato"]=="si"){
             $CON = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_contabilizzato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             $CON = "NO";
         }
         
         $op3 = '<a rel="'.$id_op.'" class="display_full_message">Info...</a>';  

         
            $h.= "
            <tr>
            <td $col_1>$pal</td>
            <td $col_1><a class=\"awesome small blue\"href=\"".$RG_addr["cassa_movimento_singolo"]."?id_movimento=$id_op\">$id_op</a></td>
            <td $col_1>$utente</td> 
            <td $col_1>$data_op</td>
            <td $col_1>$tipo_op</td>
            <td $col_5>$credito_op</td>
            <td $col_5>$debito_op</td>
            <td $col_1>$op3</td>
            <td $col_1>$ordine_op</td>
            <td $col_1>$ditta</td>
            <td $col_1>$cassiere_op</td>
            <td $col_1>$REG</td>
            <td $col_1>$CON</td>   
            </tr>";

         }//end while


         $h.= "
         </tbody>
         </table>";
         return $h;    
    
    
}
function cassa_movimenti_ordine_utente($id_ordine,$id_user){
global $db,$RG_addr,$__movcas;      

    $result = $db->sql_query("SELECT * FROM retegas_cassa_utenti WHERE id_ordine='$id_ordine' AND id_utente='$id_user';");
    $totalrows = mysql_num_rows($result);     
    $fullname = fullname_from_id($id_user);


    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Tutti i movimenti utente ".fullname_from_id($id_user)." dell 'ordine $id_ordine inseriti al ".date("d/m/Y H:i")."</h3>
            <table id=\"output\" class=\"medium_size\">
         <thead>
         <tr>
        <th>&nbsp</th>          
        <th>#</th>
        <th>Utente</th>
        <th data-sorter=\"shortDate\">Data</th>
        <th>Tipo</th>
        <th>Credito</th>
        <th>Debito</th>
        <th>Vis.</th>
        <th>Ordine</th>
        <th>Ditta</th>
        <th>Cassiere</th>
        <th>REG</th>
        <th>CON</th> 
        </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
         $riga++;

         $id_op = $row["id_cassa_utenti"];
         $data_op = conv_datetime_from_db($row["data_movimento"]);
         $tipo_op = $__movcas[$row["tipo_movimento"]];
         if($row["segno"]=="+"){
             $credito_op = _nf($row["importo"]);
             $debito_op = "&nbsp";
         }else{
             $debito_op = _nf($row["importo"]);
             $credito_op = "&nbsp";
         }
         $descrizione_op = $row["descrizione_movimento"];
         $ordine_op = $row["id_ordine"];
         $cassiere_op = fullname_from_id($row["id_cassiere"]);
         $ditta = $row["id_ditta"];
         $utente = fullname_from_id($row["id_utente"]);
         
         if($row["registrato"]=="si"){
             $REG = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_registrato"])."</span>";
             $pal = pallino("grigio");
         }else{
             $REG = "NO";
             $pal = pallino("rosso");
         }
         if($row["contabilizzato"]=="si"){
             $CON = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_contabilizzato"])."</span>";
             $pal = pallino("verde");
         }else{
             $CON = "NO";
         }
         
         $op3 = '<a rel="'.$id_op.'" class="awesome small blue display_full_message" style="margin:4px;">Info</a>';  

         
            $h.= "
            <tr>
            <td $col_1>$pal</td>
            <td $col_1><a class=\"awesome small blue\"href=\"".$RG_addr["cassa_movimento_sing_ut"]."?id_movimento=$id_op\">$id_op</a></td>
            <td $col_1>$utente</td> 
            <td $col_1>$data_op</td>
            <td $col_1>$tipo_op</td>
            <td $col_5>$credito_op</td>
            <td $col_5>$debito_op</td>
            <td $col_1>$op3</td>
            <td $col_1>$ordine_op</td>
            <td $col_1>$ditta</td>
            <td $col_1>$cassiere_op</td>
            <td $col_1>$REG</td>
            <td $col_1>$CON</td>   
            </tr>";

         }//end while


         $h.= "
         </tbody>
         </table>";
         return $h;    
    
    
}
function cassa_movimenti_ordine_utente_tipo($id_ordine,$id_user,$id_tipo){
global $db,$RG_addr,$__movcas;      

    $result = $db->sql_query("SELECT * FROM retegas_cassa_utenti WHERE id_ordine='$id_ordine' AND id_utente='$id_user' AND tipo_movimento='$id_tipo';");
    $totalrows = mysql_num_rows($result);     
    $fullname = fullname_from_id($id_user);


    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Tutti i movimenti utente ".fullname_from_id($id_user)." dell 'ordine $id_ordine inseriti al ".date("d/m/Y H:i")."</h3>
            <table id=\"output\" class=\"medium_size\">
         <thead>
         <tr>
        <th>&nbsp</th>          
        <th>#</th>
        <th>Utente</th>
        <th data-sorter=\"shortDate\">Data</th>
        <th>Tipo</th>
        <th>Credito</th>
        <th>Debito</th>
        <th>Vis.</th>
        <th>Ordine</th>
        <th>Ditta</th>
        <th>Cassiere</th>
        <th>REG</th>
        <th>CON</th> 
        </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
         $riga++;

         $id_op = $row["id_cassa_utenti"];
         $data_op = conv_datetime_from_db($row["data_movimento"]);
         $tipo_op = $__movcas[$row["tipo_movimento"]];
         if($row["segno"]=="+"){
             $credito_op = number_format($row["importo"],2,".","");
             $debito_op = "&nbsp";
         }else{
             $debito_op = number_format($row["importo"],2,".","");
             $credito_op = "&nbsp";
         }
         $descrizione_op = $row["descrizione_movimento"];
         $ordine_op = $row["id_ordine"];
         $cassiere_op = fullname_from_id($row["id_cassiere"]);
         $ditta = $row["id_ditta"];
         $utente = fullname_from_id($row["id_utente"]);
         
         if($row["registrato"]=="si"){
             $REG = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_registrato"])."</span>";
             $pal = pallino("grigio");
         }else{
             $REG = "NO";
             $pal = pallino("rosso");
         }
         if($row["contabilizzato"]=="si"){
             $CON = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_contabilizzato"])."</span>";
             $pal = pallino("verde");
         }else{
             $CON = "NO";
         }
         
         $op3 = '<a rel="'.$id_op.'" class="awesome small blue display_full_message" style="margin:4px;">Info</a>';  

         
            $h.= "
            <tr>
            <td $col_1>$pal</td>
            <td $col_1><a class=\"awesome small blue\"href=\"".$RG_addr["cassa_movimento_singolo"]."?id_movimento=$id_op\">$id_op</a></td>
            <td $col_1>$utente</td> 
            <td $col_1>$data_op</td>
            <td $col_1>$tipo_op</td>
            <td $col_5>$credito_op</td>
            <td $col_5>$debito_op</td>
            <td $col_1>$op3</td>
            <td $col_1>$ordine_op</td>
            <td $col_1>$ditta</td>
            <td $col_1>$cassiere_op</td>
            <td $col_1>$REG</td>
            <td $col_1>$CON</td>   
            </tr>";

         }//end while


         $h.= "
         </tbody>
         </table>";
         return $h;    
    
    
}


function cassa_movimenti_solo_gas_table($id_gas){
global $db,$RG_addr,$__movcas;      

    $result = mysql_query("SELECT * FROM retegas_cassa_utenti WHERE id_gas='$id_gas' AND escludi_gas='0';");
    $totalrows = mysql_num_rows($result);     
    $fullname = fullname_from_id($id_user);


    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Situazione movimenti GAS al ".date("d/m/Y H:i")."</h3>
            <table id=\"output\">
         <thead>
         <tr>
        <th>&nbsp</th>          
        <th>#</th>
        <th>Utente</th>
        <th data-sorter=\"shortDate\">Data</th>
        <th>Tipo</th>
        <th>Credito</th>
        <th>Debito</th>
        <th>Descrizione</th>
        <th>Ordine</th>
        <th>Ditta</th>
        <th>Cassiere</th>
        <th>REG</th>
        <th>CON</th> 
        </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
         $riga++;

         $id_op = $row["id_cassa_utenti"];
         $data_op = conv_datetime_from_db($row["data_movimento"]);
         $tipo_op = $__movcas[$row["tipo_movimento"]];
         if($row["segno"]=="+"){
             $credito_op = _nf($row["importo"]);
             $debito_op = "&nbsp";
         }else{
             $debito_op = _nf($row["importo"]);
             $credito_op = "&nbsp";
         }
         $descrizione_op = $row["descrizione_movimento"];
         $ordine_op = $row["id_ordine"];
         $cassiere_op = fullname_from_id($row["id_cassiere"]);
         $ditta = $row["id_ditta"];
         $utente = fullname_from_id($row["id_utente"]);
         
         if($row["registrato"]=="si"){
             $REG = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_registrato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             $REG = "NO";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }
         if($row["contabilizzato"]=="si"){
             $CON = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_contabilizzato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             $CON = "NO";
         }
         
         
            $h.= "
            <tr>
            <td $col_1>$pal</td>
            <td $col_1>$id_op</td>
            <td $col_1>$utente</td> 
            <td $col_1>$data_op</td>
            <td $col_1>$tipo_op</td>
            <td $col_5>$credito_op</td>
            <td $col_5>$debito_op</td>
            <td $col_1>$descrizione_op</td>
            <td $col_1>$ordine_op</td>
            <td $col_1>$ditta</td>
            <td $col_1>$cassiere_op</td>
            <td $col_1>$REG</td>
            <td $col_1>$CON</td>   
            </tr>";

         }//end while


         $h.= "
         </tbody>
         </table>";
         return $h;    
    
    
}
function cassa_movimenti_registrare($id_gas,$id_ordine=null){
global $db,$RG_addr,$__movcas;      

    if(!is_empty($id_ordine)){
        $id_ordine=CAST_TO_INT($id_ordine);
        $filter_ordine = " AND id_ordine='$id_ordine' ";
        $title_ordine =  " ordine #$id_ordine";
    }

    $result = mysql_query("SELECT * FROM retegas_cassa_utenti WHERE id_gas='$id_gas' AND registrato ='no' $filter_ordine;");
    $totalrows = mysql_num_rows($result);     
    $fullname = fullname_from_id($id_user);

    if($totalrows>0){
    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Movimenti da Registrare al ".date("d/m/Y H:i")." $title_ordine</h3>
            <form id=\"registra_mov\" class=\"retegas_form\">
            <a onClick=\"$('#registra_mov').toggleCheckboxes();\" class=\"awesome small yellow destra\">Seleziona Tutti</a><br>
            <table id=\"output\" class=\"medium_size\">
         <thead>
         <tr>
        <th class=\"filter-false\">&nbsp</th>          
        <th>#</th>
        <th>Utente</th>
        <th data-sorter=\"shortDate\">Data</th>
        <th>Tipo</th>
        <th>Credito</th>
        <th>Debito</th>
        <th>Descrizione</th>
        <th>Ordine</th>
        <th>Ditta</th>
        <th>Cassiere</th>
        <th>REG</th>
        <th>CON</th> 
        </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
         $riga++;

         $id_op = $row["id_cassa_utenti"];
         $data_op = conv_datetime_from_db($row["data_movimento"]);
         $tipo_op = $__movcas[$row["tipo_movimento"]];
         if($row["segno"]=="+"){
             $credito_op = number_format($row["importo"],2,".","");
             $debito_op = "&nbsp";
         }else{
             $debito_op = number_format($row["importo"],2,".","");
             $credito_op = "&nbsp";
         }
         $descrizione_op = $row["descrizione_movimento"];
         $ordine_op = $row["id_ordine"];
         if($row["id_cassiere"]<>0){
            $cassiere_op = fullname_from_id($row["id_cassiere"]);
         }else{
            $cassiere_op = "Automatico"; 
         }
         $ditta = $row["id_ditta"];
         $utente = fullname_from_id($row["id_utente"]);
         
         if(!is_printable_from_id_ord($row["id_ordine"])){
             $disabled=" DISABLED ";
         }else{
             $disabled = "";
         }
         
         if($row["registrato"]=="si"){
             $REG = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_registrato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             $REG = "<input type=\"checkbox\" name=\"box_register[]\" class=\"check_reg\" $disabled value=\"$id_op\">";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }
         if($row["contabilizzato"]=="si"){
             $CON = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_contabilizzato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             $CON = "NO";
         }
         
         
            $h.= "
            <tr>
            <td $col_1>$pal</td>
            <td $col_1>$id_op</td>
            <td $col_1>$utente</td> 
            <td $col_1>$data_op</td>
            <td $col_1>$tipo_op</td>
            <td $col_5>$credito_op</td>
            <td $col_5>$debito_op</td>
            <td $col_1>$descrizione_op</td>
            <td $col_1>$ordine_op</td>
            <td $col_1>$ditta</td>
            <td $col_1>$cassiere_op</td>
            <td $col_1>$REG</td>
            <td $col_1>$CON</td>   
            </tr>
            ";

         }//end while


         $h.= "
         </tbody>
         </table>
         <br>
         <input type=\"hidden\" name=\"do\" value=\"reg\">
         <input type=\"submit\" value=\"Registra questi movimenti\" class=\"awesome destra\">
         <br>
         </form>";
         $h.= "<div class=\"ui-state-highlight ui-corner-all padding_6px\">
         I movimenti degli ordini NON CONFERMATI non possono essere contabilizzati.</div>";   

    }else{
     //SE NON CI SONO MOVIMENTI
        $h= "<div class=\"ui-state-highlight ui-corner-all padding_6px\"><h3>Evviva !!!</h3>..non ci sono movimenti da registrare !!!!</div>";   
    }
 
         return $h;    
    
    
}
function cassa_movimenti_contabilizzare($id_gas){
global $db,$RG_addr,$__movcas;      

    $result = mysql_query("SELECT * FROM retegas_cassa_utenti WHERE id_gas='$id_gas' AND registrato ='si' AND contabilizzato='no';");
    $totalrows = mysql_num_rows($result);     
    $fullname = fullname_from_id($id_user);

    if($totalrows>0){
    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Movimenti da Contabilizzare al ".date("d/m/Y H:i")."</h3>
            <form id=\"registra_mov\" class=\"retegas_form\">
            <a onClick=\"$('#registra_mov').toggleCheckboxes();\" class=\"awesome small yellow destra\">Seleziona Tutti</a><br>
            <table id=\"output\" class=\"medium_size\">
         <thead>
         <tr>
        <th>&nbsp</th>          
        <th>#</th>
        <th>Utente</th>
        <th data-sorter=\"shortDate\">Data</th>
        <th>Tipo</th>
        <th>Credito</th>
        <th>Debito</th>
        <th>Descrizione</th>
        <th>Ordine</th>
        <th>Ditta</th>
        <th>Cassiere</th>
        <th>REG</th>
        <th>CON</th> 
        </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
         $riga++;

         $id_op = $row["id_cassa_utenti"];
         $data_op = conv_datetime_from_db($row["data_movimento"]);
         $tipo_op = $__movcas[$row["tipo_movimento"]];
         if($row["segno"]=="+"){
             $credito_op = number_format($row["importo"],2,".","");
             $debito_op = "&nbsp";
         }else{
             $debito_op = number_format($row["importo"],2,".","");
             $credito_op = "&nbsp";
         }
         $descrizione_op = $row["descrizione_movimento"];
         $ordine_op = $row["id_ordine"];
         $cassiere_op = fullname_from_id($row["id_cassiere"]);
         $ditta = $row["id_ditta"];
         $utente = fullname_from_id($row["id_utente"]);
         
         if($row["registrato"]=="si"){
             $REG = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_registrato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }
         if($row["contabilizzato"]=="si"){
             $CON = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_contabilizzato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="" style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             $CON = "<input type=\"checkbox\" name=\"box_contabila[]\" class=\"check_reg\" value=\"$id_op\">";
         }
         
         
            $h.= "
            <tr>
            <td $col_1>$pal</td>
            <td $col_1>$id_op</td>
            <td $col_1>$utente</td> 
            <td $col_1>$data_op</td>
            <td $col_1>$tipo_op</td>
            <td $col_5>$credito_op</td>
            <td $col_5>$debito_op</td>
            <td $col_1>$descrizione_op</td>
            <td $col_1>$ordine_op</td>
            <td $col_1>$ditta</td>
            <td $col_1>$cassiere_op</td>
            <td $col_1>$REG</td>
            <td $col_1>$CON</td>   
            </tr>
            ";

         }//end while


         $h.= "
         </tbody>
         </table>
         <br>
         <input type=\"hidden\" name=\"do\" value=\"cont\">
         <input type=\"submit\" value=\"Contabilizza questi movimenti\" class=\"awesome destra\">
         <br>
         </form>";
         
    }else{
     //SE NON CI SONO MOVIMENTI
        $h= "<div class=\"ui-state-highlight ui-corner-all padding_6px\"><h3>Evviva !!!</h3>..non ci sono movimenti da contabilizzare !!!!</div>";   
    }
         
         return $h;    
    
    
}

function cassa_movimento_singolo($id_movimento){

          // QUERY

      $my_query="SELECT * FROM retegas_cassa_utenti WHERE  (id_cassa_utenti='$id_movimento') LIMIT 1";

      // TITOLO TABELLA

      $titolo_tabella="Scheda singolo movimento di cassa";

      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------

      global $db,$__movcas;
      $result = $db->sql_query($my_query);
      $row = $db->sql_fetchrow($result);  

    
         // VALORI DELLE CELLE da DB---------------------

              $c1 = $row["id_cassa_utenti"];
              $c2 = $row["id_utente"];
              $c3 = $row["id_gas"];
              $c4 = $row["id_ditta"];
              $c5 = $row["importo"];
              $c6 = $row["segno"];
              $c7 = $row["tipo_movimento"];
              $c8 = $row["escludi_gas"];
              if($c8==1){
                  $c8 = "SI";
              }else{
                  $c8 = "NO";
              }
              $c9 = $row["descrizione_movimento"];
              $c10 = $row["note_movimento"];
              $c11 = $row["data_movimento"];
              $c12 = $row["numero_documento"];
              $c13 = $row["id_ordine"];
              $c14 = $row["id_cassiere"];
              $c15 = $row["registrato"];
              $c16 = $row["data_registrato"];
              $c17 = $row["contabilizzato"];
              $c18 = $row["data_contabilizzato"];

              
              if($c7<>3){
                  $form_rettifica="<form method=\"POST\" action=\"\">
                       <input type=\"radio\" name=\"segno\" value=\"+\" checked>+ (positiva)<br>
                       <input type=\"radio\" name=\"segno\" value=\"-\">- (negativa)<br> 
                       <hr>
                       Importo : <input type=\"text\" name=\"importo\" size=3><br>
                       <hr>
                       <input type=\"hidden\" name=\"id_movimento\" value=\"$c1\">
                       <input type=\"hidden\" name=\"do\" value=\"do_rett\">
                       <input class=\"awesome red medium mb6\" type=\"submit\" value=\"Rettifica\">
                       </form>";
              }else{
                 $form_rettifica = "Questo movimento è già una rettifica"; 
              }
              
              $form_elimina="<form method=\"POST\" action=\"\">
                       <input type=\"hidden\" name=\"id_movimento\" value=\"$c1\">
                       <input type=\"hidden\" name=\"do\" value=\"do_del\">
                       <input type=\"hidden\" name=\"id_utente\" value=\"".mimmo_encode($c2)."\">
                       <input class=\"awesome black medium\" type=\"submit\" value=\"Elimina\">
                       </form>";

$h_table .= "

             <div class=\"ui-widget-header ui-corner-all padding_6px\">
             $titolo_tabella
             <br>
             <table>  
             <tr>
             <td>";         
$h_table .=  "<table>
        <tr class=\"odd\">
            <th $col_1>#</th>
            <td $col_2>$c1</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr  class=\"odd\">
            <th $col_1>Utente</th>
            <td $col_2>$c2, ".fullname_from_id($c2).", (".db_nr_q("id_utente",$c2,"retegas_cassa_utenti")." records)</td>
            <td $col_2><a class=\"awesome green small\" onClick=\"$.ajax({
                                                                      url: '../ajax/movimenti_cassa.php',
                                                                      data : 'q=1&key=$c2&key2=$c3',
                                                                      success: function(data) {
                                                                        $('#result').html(data);
                                                                        $('#micro_table').tablesorter({widgets: ['zebra'],
                                                                        cancelSelection : true,
                                                                        dateFormat: 'uk'                                                               
                                                                        });
                                                                      }
                                                                    });\"
                                                                    href=\"#\"><span class=\"ui-icon ui-icon-arrowthick-1-e\"></span></a></td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Gas</th>
            <td $col_2>$c3, ".gas_nome($c3)."</td>
            <td $col_2>&nbsp;</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Ditta</th>
            <td $col_2>$c4, ".ditta_nome($c4)."</td>
            <td $col_2>&nbsp;</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Importo</th>
            <td style=\"font-size:1.6em\">$c6 $c5</td>
            <td $col_2>&nbsp;</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Tipo Movimento</th>
            <td $col_2>$c7, ".$__movcas[$c7]."</td>
            <td $col_2><a class=\"awesome green small\" onClick=\"$.ajax({
                                                                      url: '../ajax/movimenti_cassa.php',
                                                                      data : 'q=3&key=$c7&key2=$c3',
                                                                      success: function(data) {
                                                                        $('#result').html(data);
                                                                        $('#micro_table').tablesorter({widgets: ['zebra'],
                                                                        cancelSelection : true,
                                                                        dateFormat: 'uk'                                                               
                                                                        });
                                                                      }
                                                                    });\"><span class=\"ui-icon ui-icon-arrowthick-1-e\"></span></a></td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Escludi da saldo GAS</th>
            <td $col_2>$c8</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Descrizione movimento</th>
            <td $col_2>$c9</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Note movimento</th>
            <td $col_2>$c10</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Data Movimento</th>
            <td $col_2>".conv_datetime_from_db($c11)."</td>
            <td $col_2>&nbsp</td>
        </tr>

        <tr class=\"odd\">
            <th $col_1>Rif. Documento</th>
            <td $col_2>$c12</td>
            <td $col_2>&nbsp</td>
        </tr>
        
        <tr class=\"odd\">
            <th $col_1>Ordine</th>
            <td $col_2>$c13, ".descrizione_ordine_from_id_ordine($c13)."</td>
            <td $col_2><a class=\"awesome green small\" onClick=\"$.ajax({
                                                                      url: '../ajax/movimenti_cassa.php',
                                                                      data : 'q=2&key=$c13&key2=$c3',
                                                                      success: function(data) {
                                                                        $('#result').html(data);
                                                                        $('#micro_table').tablesorter({widgets: ['zebra'],
                                                                        cancelSelection : true,
                                                                        dateFormat: 'uk'                                                               
                                                                        });
                                                                      }
                                                                    });\"><span class=\"ui-icon ui-icon-arrowthick-1-e\"></span></a></td>
        </tr>

        <tr class=\"odd\">
            <th $col_1>Cassiere</th>
            <td $col_2>$c14, ".fullname_from_id($c14)."</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Registrato</th>
            <td $col_2>$c15</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Data Registrato</th>
            <td $col_2>".conv_datetime_from_db($c16)."</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Contabilizzato</th>
            <td $col_2>$c17</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Data Contabilizzato</th>
            <td $col_2>".conv_datetime_from_db($c18)."</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Rettifica</th>
            <td $col_2>$form_rettifica</td>
            <td $col_2>&nbsp;</td>
        </tr>
                <tr class=\"odd\">
            <th $col_1>Elimina (NB : istantaneo)</th>
            <td $col_2>$form_elimina</td>
            <td $col_2>&nbsp;</td>
        </tr>
        </table>
        </td>
        <td>
        <table>        
        <tr>
        <td>
        <div class=\"\" id=\"result\" style=\"height:36em;\"></div>
        </td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        </div> ";

return $h_table;
      // END TABELLAGAS -----------------------------------------------------------------------
   

  }
function cassa_movimento_singolo_utente($id_movimento){

          // QUERY

      $my_query="SELECT * FROM retegas_cassa_utenti WHERE  (id_cassa_utenti='$id_movimento') LIMIT 1";

      // TITOLO TABELLA

      $titolo_tabella="Scheda singolo movimento di cassa";

      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------

      global $db,$__movcas;
      $result = $db->sql_query($my_query);
      $row = $db->sql_fetchrow($result);  

    
         // VALORI DELLE CELLE da DB---------------------

              $c1 = $row["id_cassa_utenti"];
              $c2 = $row["id_utente"];
              $c3 = $row["id_gas"];
              $c4 = $row["id_ditta"];
              $c5 = $row["importo"];
              $c6 = $row["segno"];
              $c7 = $row["tipo_movimento"];
              $c8 = $row["escludi_gas"];
              if($c8==1){
                  $c8 = "SI";
              }else{
                  $c8 = "NO";
              }
              $c9 = $row["descrizione_movimento"];
              $c10 = $row["note_movimento"];
              $c11 = $row["data_movimento"];
              $c12 = $row["numero_documento"];
              $c13 = $row["id_ordine"];
              $c14 = $row["id_cassiere"];
              $c15 = $row["registrato"];
              $c16 = $row["data_registrato"];
              $c17 = $row["contabilizzato"];
              $c18 = $row["data_contabilizzato"];

              


$h_table .= " <h3>
             $titolo_tabella
             </h3>";
                      
$h_table .=  "<table>
        <tr class=\"odd\">
            <th $col_1>#</th>
            <td $col_2>$c1</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr  class=\"odd\">
            <th $col_1>Utente</th>
            <td $col_2>$c2, ".fullname_from_id($c2).", (".db_nr_q("id_utente",$c2,"retegas_cassa_utenti")." records)</td>
            <td $col_2></td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Gas</th>
            <td $col_2>$c3, ".gas_nome($c3)."</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Ditta</th>
            <td $col_2>$c4, ".ditta_nome($c4)."</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Importo</th>
            <td style=\"font-size:1.6em\">$c6 $c5</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Tipo Movimento</th>
            <td $col_2>$c7, ".$__movcas[$c7]."</td>
            <td $col_2></td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Escludi da saldo GAS</th>
            <td $col_2>$c8</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Descrizione movimento</th>
            <td $col_2>$c9</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Note movimento</th>
            <td $col_2>$c10</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Data Movimento</th>
            <td $col_2>".conv_datetime_from_db($c11)."</td>
            <td $col_2>&nbsp</td>
        </tr>

        <tr class=\"odd\">
            <th $col_1>Rif. Documento</th>
            <td $col_2>$c12</td>
            <td $col_2>&nbsp</td>
        </tr>
        
        <tr class=\"odd\">
            <th $col_1>Ordine</th>
            <td $col_2>$c13, ".descrizione_ordine_from_id_ordine($c13)."</td>
            <td $col_2></td>
        </tr>

        <tr class=\"odd\">
            <th $col_1>Cassiere</th>
            <td $col_2>$c14, ".fullname_from_id($c14)."</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Registrato</th>
            <td $col_2>$c15</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Data Registrato</th>
            <td $col_2>".conv_datetime_from_db($c16)."</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Contabilizzato</th>
            <td $col_2>$c17</td>
            <td $col_2>&nbsp</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Data Contabilizzato</th>
            <td $col_2>".conv_datetime_from_db($c18)."</td>
            <td $col_2>&nbsp</td>
        </tr>
        </table>
         ";

return $h_table;
      // END TABELLAGAS -----------------------------------------------------------------------
   

  }
  

function cassa_gas_panel($id_gas){
 global $RG_addr;
 $pre_green = '<div style="   padding:6px;
                        margin-bottom:2px; 
                        border:3px solid rgba(60, 210, 40, 1); 
                        background-color:rgba(60, 210, 40, .5)" class="ui-corner-all">';
 $pre_grey = '<div style="   padding:6px;
                        margin-bottom:2px; 
                        border:3px solid rgba(210, 210, 210, 1); 
                        background-color:rgba(210, 210, 210, .5)" class="ui-corner-all">';
 $pre_red = '<div style="   padding:6px;
                        margin-bottom:2px; 
                        border:3px solid rgba(210, 60, 40, 1); 
                        background-color:rgba(210, 60, 40, .5)" class="ui-corner-all">';                       
 $post = '</div>';
 
 //SALDO TOTALE-
 $st = _nf(cassa_saldo_gas_totale($id_gas));
 $pre_st = $pre_grey;
 //-----------------
 
 
 
 //SALDO REGISTRATO-
 $sr = cassa_saldo_gas_registrato($id_gas);
 if($sr<>$st){
    $pre_sr = $pre_red;
    $button_sr = '<a class="awesome silver small destra" href="">Registra</a>';
 }else{
    $pre_sr = $pre_green;
    $button_sr = ""; 
 }
 //-----------------
 
 //SALDO CONTABILIZZATO-
 $sc = cassa_saldo_gas_contabilizzato($id_gas);
 if($sc<>$sr){
    $pre_sc = $pre_red;
    $button_sc = '<a class="awesome silver small destra" href="">Contabilizza</a>';
 }else{
    $pre_sc = $pre_green;
    $button_sc = ""; 
 }
 
 //-----------------  
 
 //Tutti i movimenti GAS
 $mg = db_nr_q("id_gas",$id_gas,"retegas_cassa_utenti");
 $pre_mg = $pre_grey;
 $button_mg = '<a class="awesome silver small destra" href="'.$RG_addr["movimenti_cassa_gas"].'">Visualizza</a>';

 //Movimenti su utenti
 $mu = db_nr_q_2("id_gas",$id_gas,"id_ditta","0","retegas_cassa_utenti");
 $pre_mu = $pre_grey;
 $button_mu = '<a class="awesome silver small destra" href="">Visualizza</a>';
 
  //Movimenti su ditte
 $md = db_nr_q_condition(" id_gas = '$id_gas' AND (id_ditta <> '0') ","retegas_cassa_utenti");
 $pre_md = $pre_grey;
 $button_md = '<a class="awesome silver small destra" href="">Visualizza</a>';
 
 //Movimenti non REGISTRATI
 $mnr = db_nr_q_condition(" id_gas = '$id_gas' AND (registrato = 'no') ","retegas_cassa_utenti");
 if($mnr<>0){
    $pre_mnr = $pre_red;
    $button_mnr = '<a class="awesome silver small destra" href="'.$RG_addr["cassa_movimenti_reg"].'">Registra</a>'; 
 }else{
    $pre_mnr = $pre_green;
    $button_mnr = "";    
 }

 
  //Movimenti non CONTABILIZZATI
 $mnc = db_nr_q_condition(" id_gas = '$id_gas' AND (registrato = 'si') AND (contabilizzato = 'no') ","retegas_cassa_utenti");
 if($mnc<>0){
    $pre_mnc = $pre_red;
    $button_mnc = '<a class="awesome silver small destra" href="'.$RG_addr["cassa_movimenti_con"].'">Contabilizza</a>'; 
 }else{
    $pre_mnc = $pre_green;
    $button_mnc = '';    
 }
 

 
 $h .=' <div class="rg_widget rg_widget_helper">
        <table>
        <tr style="vertical-align:top">
        <td width="50%">';
 
 $h .= $pre_mg.'Saldo in Database : <b>'.$st.'</b>'.$post;       
 $h .= $pre_mg.'Movimenti di CASSA : <b>'.$mg.'</b>'.$button_mg.$post;

 $h .= $pre_sr.'Saldo GAS da mov. Registrati : <b>'.$sr.'</b>'.$button_sr.$post;
 $h .= $pre_sc.'Saldo GAS da mov. Contabilizzati : <b>'.$sc.'</b>'.$button_sc.$post;     
  
 //$h .= $pre.'FULLNAME: <b>'.db_val_q("userid",$id,"fullname","maaking_users").'</b>'.$post;
 //$h .= $pre.'LAST ACT: <b>'.db_val_q("userid",$id,"last_activity","maaking_users").'</b>'.$post;
 //$h .= $pre.'LAST LOGIN: <b>'.db_val_q("userid",$id,"lastlogin","maaking_users").'</b>'.$post;
 //$h .= $pre.'REGDATE: <b>'.db_val_q("userid",$id,"regdate","maaking_users").'</b>'.$post;
 //$h .= $pre.'IP ADDR: <b>'.db_val_q("userid",$id,"ipaddress","maaking_users").'</b>'.$post;
 //$h .= $pre.'RECORDS in messaggi: <b>'.db_nr_q("id_user",$id,"retegas_messaggi").'</b>'.$post;    
 //$h .= $pre.'RECORDS in bacheca: <b>'.db_nr_q("id_utente",$id,"retegas_bacheca").'</b>'.$post;    
 //$h .= $pre.'RECORDS in listini: <b>'.db_nr_q("id_utenti",$id,"retegas_listini").'</b>'.$post;

 
 
 $h .= '</td>
        <td width="50%">';
        $h .= $pre_mnr.'Movimenti NON registrati : <b>'.$mnr.'</b>'.$button_mnr.$post;
        $h .= $pre_mnc.'Movimenti NON contabilizzati : <b>'.$mnc.'</b>'.$button_mnc.$post;
        //$h .= $pre_mu.'Movimenti utenti : <b>'.$mu.'</b>'.$button_mu.$post;
       // $h .= $pre_md.'Movimenti ditte : <b>'.$md.'</b>'.$button_md.$post;
        
         
        
// $h .= $pre.'RECORDS in maaking_users: <b>'.db_nr_q("userid",$id,"maaking_users").'</b>'.$post;
// $h .= $pre.'RECORDS in dettaglio ordini: <b>'.db_nr_q("id_utenti",$id,"retegas_dettaglio_ordini").'</b>'.$post;       
// $h .= $pre.'RECORDS in amici: <b>'.db_nr_q("id_referente",$id,"retegas_amici").'</b>'.$post;    
// $h .= $pre.'RECORDS in ordini: <b>'.db_nr_q("id_utente",$id,"retegas_ordini").'</b>'.$post;    
// $h .= $pre.'RECORDS in distribuzione spesa: <b>'.db_nr_q("id_user",$id,"retegas_distribuzione_spesa").'</b>'.$post;    
// $h .= $pre.'Permessi accordati : '.utenti_scheda_permessi($id).$post;   

 
 $h .='</td>
        </tr>
        </table>
        </div>';
        
  
        
 return $h;    
}  
