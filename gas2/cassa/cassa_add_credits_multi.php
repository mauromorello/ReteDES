<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("utenti_render.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_gestire_la_cassa)){
     pussa_via();
}


if($do=="confirm_multi"){
    
    if($contabilizza=="si"){
            $cont = "si";
            $date_cont = "NOW()";
        }else{
            $cont = "no";
            $date_cont = "NULL";
    }
    
    while (list ($key,$val) = @each ($box_id_utente)) {    
        $id_utente = mimmo_decode($val);
        $doc = sanitize($box_doc[$key]);
         
        
        $importo = CAST_TO_FLOAT(trim(str_replace(array(",","€"),array(".",""),$box_val[$key])));
        $log .= "KEY : $key, id_utente : $id_utente, DOC : $doc, IMPORTO : $importo<br>";
        //echo "key = $key ID_utente = $id_utente DOC = $doc Soldi = $importo<br>";
        if($importo>0){
            
              $log .= "IMPORTO > 0 -->EXECUTE INSERT<br>";
              $my_query="INSERT INTO retegas_cassa_utenti (   id_utente ,
                                                        id_gas,
                                                        importo ,
                                                        segno ,
                                                        tipo_movimento ,
                                                        numero_documento ,
                                                        data_movimento ,
                                                        id_cassiere ,
                                                        registrato ,
                                                        data_registrato ,
                                                        contabilizzato ,
                                                        data_contabilizzato
                                                      )VALUES(
                                                      '".$id_utente."',
                                                      '"._USER_ID_GAS."',
                                                      '".$importo."',
                                                      '+',
                                                      '1',
                                                      '".$doc."',
                                                      NOW(),
                                                      '"._USER_ID."',
                                                      'si',
                                                      NOW(),
                                                      '".$cont."',
                                                      ".$date_cont."          
                                                      )";
              //INSERT BEGIN ---------------------------------------------------------
            $result = $db->sql_query($my_query);
            unset($ok);
            
            if (is_null($result)){
                $msg = "Errore nell'inserimento nella cassa Utenti";
                $log .= "INSERT ERROR<br>";
            }else{
                $msg = "OK";
                $ok++;
            };  
            
            if($ok==1){
                
                
                $da_chi = _USER_FULLNAME;
                $mail_da_chi = email_from_id(_USER_ID);
                
                $verso_chi = fullname_from_id($id_utente);
                $mail_verso_chi = email_from_id($id_utente);
                
                $soggetto ="[RETEDES.IT - CASSA] E' stato caricato credito sul tuo conto.";
                $cr = _nf(cassa_saldo_utente_totale($id_utente));
                
                $query_op = "SELECT id_cassa_utenti,
                                    data_movimento,
                                    numero_documento
                                    FROM retegas_cassa_utenti 
                                    WHERE
                                    id_utente = '$id_utente'
                                    AND tipo_movimento= '1'
                                    AND importo = '$importo'
                                    ORDER BY data_movimento DESC
                                    LIMIT 1;";
                $res_op = $db->sql_query($query_op);                    
                $row_op = $db->sql_fetchrow($res_op);
                $n_op = $row_op["id_cassa_utenti"];
                $data_op =  conv_datetime_from_db($row_op["data_movimento"]);
                $intestazione_gas = gas_estremi(_USER_ID_GAS);
                $descrizione_movimento = $row_op["numero_documento"];
                
                $messaggio_html = "<h2>RICEVUTA CARICO CREDITO</h2>
                <hr>
                $intestazione_gas
                <hr>
                <strong>$da_chi</strong>, cassiere del tuo gas,<br>
                in data odierna ($data_op) ha ricevuto da $verso_chi e caricato sul suo conto gas<br>
                <span style=\"font-size:1.3em\">$importo Euro.</span><br>
                <br>
                Il numero di documento è : $descrizione_movimento <br>
                Il numero dell'operazione è : $n_op <br>
                <br>
                Attualmente, il tuo credito totale disponibile è $cr Eu.<br>
                ma vi potrebbero essere dei movimenti che non sono stati ancora contabilizzati.<br>
                Verifica la tua situazione su <a href=\"http://www.retedes.it\">www.retedes.it</a>";
                
                manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,null,"CAS",0,_USER_ID,$messaggio_html);    
                $log .= "MAIL SENT $da_chi TO $verso_chi<br>";
                usleep(600000);
                //go("sommario",$id_user,"Hai correttamente caricato $importo Euri a ".fullname_from_id($id_ute));
            }
            
            
            
        }
        
    
    
    }
    
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Aggiungi credito multiplo";

//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale[] = gas_menu_gestisci_cassa();
//$r->javascripts[]=java_tablesorter("output");

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

//--------------------------------------------CONTENUTO - PRIMO PASSO
    $result = $db->sql_query("SELECT * FROM maaking_users WHERE id_gas='"._USER_ID_GAS."' AND isactive=1 ORDER BY userid ASC;");
    $totalrows = $db->sql_numrows($result);     
    $gas_name = gas_nome(_USER_ID_GAS);



    $h .= " <div class=\"rg_widget rg_widget_helper\">
        
            <h3>Utenti $gas_name, Carico credito collettivo, solo utenti che hanno abilitato la cassa.</h3>
        <!--<div class=\"rg_widget rg_widget_helper ui-state-error\">
        <h3>Attenzione</h3>
        <p>Per inserire valori con decimali usare LA VIRGOLA e non il punto. Prima di confermare la seconda pagina del caricamento, verificare se gli importi assegnati agli utenti corrispondano
        a quelli inseriti.</p></div>-->
        <form method=\"POST\"  action=\"\" class=\"retegas_form\">
        <div>
        <table>
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
                <th>Saldo Totale</th>
                <th>Da caricare</th>
                <th>Doc</th> 
            </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
             
             
             if(read_option_text($row["userid"],"_USER_USA_CASSA")=="SI"){
                
                 if(($riga%2)==0){
                     $class="odd";
                 }else{
                     $class="";
                 }
                 //$box_val[$riga-1] = CAST_TO_FLOAT($box_val[$riga-1]);
                
                
                $riga++;
                
                
                $box_val[$riga-1] = (str_replace(array(",","€"),array(".",""),$box_val[$riga-1]));
                $box_val[$riga-1] = CAST_TO_FLOAT($box_val[$riga-1],0,1000);
                
                
                $d1 = "id_gas";
                $id_utente = $row["userid"];
                $fullname = $row["fullname"];
                $saldo_c = cassa_saldo_utente_contabilizzata($id_utente);
                $saldo_a = cassa_saldo_utente_in_attesa($id_utente);
                $saldo_r = cassa_saldo_utente_registrato($id_utente);
                $saldo_t = cassa_saldo_utente_totale($id_utente);
                $op1='<a class="awesome small celeste" href="'.$RG_addr["movimenti_cassa_users"].'?id_utente='.mimmo_encode($id_utente).'">M</a>'; 
                
                if($do=="save_multi"){
                    $log .= " id_utente : $id_utente, IMPORTO :".$box_val[$riga-1]."<br>";
                    $carico_credito = " <strong>".$box_val[$riga-1]." Eu.</strong>
                                        <input type=\"hidden\"  name=\"box_val[]\" value=\"".$box_val[$riga-1]."\">
                                        <input type=\"hidden\"  name=\"box_id_utente[]\" value=\"".mimmo_encode($id_utente)."\">";   
                    $doc ="<span class=\"small_link\">".$box_doc[$riga-1]."</span>
                            <input type=\"hidden\"  name=\"box_doc[]\" value=\"".$box_doc[$riga-1]."\">";
                }else{
                    $carico_credito = "<input type=\"TEXT\"  style=\"width:7em\" name=\"box_val[]\" value=\"0\">
                                        <input type=\"hidden\"  name=\"box_id_utente[]\" value=\"".mimmo_encode($id_utente)."\">
                                        <input type=\"hidden\"  name=\"box_clean_id_utente[]\" value=\"".($id_utente)."\">";   
                    $doc = "<input type=\"text\" size=\"10\" name=\"box_doc[]\" value=\"\">";                    
                }
                
                if($box_val[$riga-1]>0 OR ($do<>"save_multi")){
                    $log .= "TABLE id_utente : $id_utente $fullname, RIGA : $riga; [-1 = ".($riga-1)."] IMPORTO: ".$box_val[$riga-1]."<br>";
                    $h.= "
                    <tr class=\"$class\">
                    <td $col_1>$op1</td>
                    <td $col_1>$id_utente</td> 
                    <td $col_2><a href=\"".$RG_addr["pag_users_form"]."?id_utente=".mimmo_encode($id_utente)."\">$fullname</a></td>
                    <td $col_3>&nbsp;</td>
                    <td $col_4>&nbsp;</td>
                    <td class=\"destra\">$saldo_a</td>
                    <td class=\"destra\">$saldo_r</td>
                    <td class=\"destra\">$saldo_c</td>
                    <td class=\"destra\">$saldo_t</td>
                    <td class=\"destra\">$carico_credito</td>
                    <td>$doc</td>   
                    </tr>";
                }
            }
         }//end while


         $h.= "
         </tbody>
         </table>
         </div>";
         
         if($do<>"save_multi"){
            $h.= "  <input type=\"hidden\" name=\"do\" value=\"save_multi\">
                    <input type=\"submit\" value=\"Carica questi importi\">";
         }else{
            
             
            if(read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_USE_PASSWORD_CONFIRM")=="SI"){
                $check_pwd="
                <div>
                    <h4>1</h4>
                    <label for=\"pwd\">Inserisci la tua password</label>
                    <input type=\"password\" name=\"pwd\" value=\"\" size=\"20\"></input>
                </div>";
            }else{
                $check_pwd="";
            } 
             
             
            $h.= "  <input type=\"hidden\" name=\"do\" value=\"confirm_multi\">
                
            $check_pwd

            <div>
                <h4>2</h4>
                <label for=\"contabilizza\">Contabilizza subito</label>
                <input type=\"checkbox\" name=\"contabilizza\" value=\"si\"></input>
            </div>
                
             <div>
                <h4>3</h4>
                <label for=\"submit\">infine... </label>    
                <input type=\"submit\" name=\"submit\" value=\"Conferma questi importi\">
             </div>   
             ";
         }
         
         $h.= "</form>";
        // log_me(0,_USER_ID,"CAS","CAR","Carico multiplo cassa",0,$log);
//--------------------------------------------CONTENUTO


//$r->contenuto = rg_toggable("Alcune novit?","poio",$bla,false).$h;
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);