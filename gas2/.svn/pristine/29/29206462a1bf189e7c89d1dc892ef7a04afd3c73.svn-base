<?php   

$_FUNCTION_LOADER=array("mobile",
                                "ordini",
                                "ordini_valori",
                                "gas",
                                "listini",
                                "ditte");

include_once ("../rend.php");
include_once ("../jqm.class.php");


//Controllo su login
if(!_USER_LOGGED_IN){   
    go("sommario_mobile");       
}


if(!_USER_PERMISSIONS & perm::puo_gestire_la_cassa){
    go("sommario_mobile"); 
}

if($do=="add_credit"){
    
    if(!valuta_valida($credit_to_add)){
        $msg .= "Valore non riconosciuto.<br>";
        $e++;
    }
    if($credit_to_add==0){
        $msg .= "Valore nullo.<br>";
        $e++;
    }
    
    
    $id_utente=CAST_TO_INT(mimmo_decode($id_utente));
    if($id_utente==0){
        $msg .= "Utente non riconosciuto.<br>";
        $e++;
    }
    if(id_gas_user($id_utente)<>_USER_ID_GAS){
        $msg .= "Utente non del tuo gas.<br>";
        $e++;
    }
    
    
    $numero_documento = sanitize($numero_documento);
    
    if($e==0){
         //INSERT IN CASSA UTENTI
        $cont = "no";
        $date_cont = "NULL";
        
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
                                                      '".$credit_to_add."',
                                                      '+',
                                                      '1',
                                                      '".$numero_documento."',
                                                      NOW(),
                                                      '"._USER_ID."',
                                                      'si',
                                                      NOW(),
                                                      '".$cont."',
                                                      ".$date_cont."          
                                                      )";
        $result = $db->sql_query($my_query);
        if (is_null($result)){
            $msg .= "Errore nell'inserimento nella cassa Utenti";
        }else{
            $msg = "<h3>AGGIUNTO CREDITO<h3>
                    <h4>Euro "._nf($credit_to_add)." a ".fullname_from_id($id_utente)."</h4>";   
                            $da_chi = _USER_FULLNAME;
                $mail_da_chi = email_from_id(_USER_ID);
                
                $verso_chi = fullname_from_id($id_utente);
                $mail_verso_chi = email_from_id($id_utente);
                
                $soggetto ="[RETEGAS.AP - RICEVUTA] Carico credito sul tuo conto.";
                $cr = _nf(cassa_saldo_utente_totale($id_utente));
                
                $query_op = "SELECT id_cassa_utenti,
                                    data_movimento
                                    FROM retegas_cassa_utenti 
                                    WHERE
                                    id_utente = '$id_utente'
                                    AND tipo_movimento= '1'
                                    AND importo = '$importo'
                                    ORDER BY data_movimento DESC
                                    LIMIT 1;";
                $res_op = $db->sql_query($query_op);                    
                $row_op = $db->sql_fetchrow($res_op);
                $n_op = $row_op[0];
                $data_op =  conv_datetime_from_db($row_op[1]);
                $intestazione_gas = gas_estremi(_USER_ID_GAS);
                
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
                Verifica la tua situazione su <a href=\"http://www.retegas.info\">www.retegas.info</a>";
                
                manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,null,"CAS",0,_USER_ID,$messaggio_html);        
        }
    
    }else{
        $msg .= "<h3>CREDITO NON AGGIUNTO<h3>";
    }
    
    
    
    
    
}



//IMPOSTO le cose uguali per tutte le pagine_
$footer_title = _USER_FULLNAME.", ".gas_nome(_USER_ID_GAS);

       
                
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());

//-------------------------------------------------------PAG 1                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param("RG-Cassa","cassa_panel"));

//Negli attributi assegno un ID
$p->jqm_footer_hide= true;

//Assegno la navbar
//Con solo un pulsate per tornare indietro
//$n = new jqm_navbar(load_scheda_ditta_navbar(null,$id_ditta));
//$n->jqm_navbar_set_item_attrib(0,"class=\"ui-btn-active ui-state-persist\"");
//$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno i contenuti
$h .= $msg;
$h .="<a href=\"#page_add_credit\" data-role=\"button\" data-rel=\"dialog\">Aggiungi credito a utente</a>";
$h .="<a href=\"\" data-role=\"button\">Scarica credito a Utente</a>";

$h .="<a href=\"".$RG_addr["m_cassa_user_mov"]."\" data-role=\"button\">Movimenti Utente</a>";
$h .="<a href=\"".$RG_addr["m_cassa_users"]."\" data-role=\"button\">Cassa Utenti</a>";
$h .="<a href=\"\" data-role=\"button\">Movimenti GAS</a>";



if(_USER_PERMISSIONS & perm::puo_gestire_la_cassa){
    $p->jqm_page_content = $h;
}else{
    $p->jqm_page_content = "Non sei abilitato per questa pagina";
}
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 1

//-------------------------------------------------------PAG 1                                
//Nuova pagina con relativi parametri
$p = new jqm_page();
$p->jqm_page_attrib=" id=\"page_add_credit\"";
//Negli attributi assegno un ID
$p->jqm_footer_hide= true;

//Assegno la navbar
//Con solo un pulsate per tornare indietro
//$n = new jqm_navbar(load_scheda_ditta_navbar(null,$id_ditta));
//$n->jqm_navbar_set_item_attrib(0,"class=\"ui-btn-active ui-state-persist\"");
//$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno i contenuti

$h= "<div data-role=\"collapsible\">
           <h3>Note</h3>
           <p>Il credito viene caricato subito come \"registrato\" ma non \"contabilizzato\". Si può opzionalmente inserire un numero di documento di ricevuta.</p>
        </div>";



$h .="<form action=\"\" method=\"post\">
     <div data-role=\"fieldcontain\">
        <label for=\"select-choice-1\" class=\"select\">Utente</label>
        <select name=\"id_utente\" id=\"select-choice-1\">
           <option value=\"\">Scegli un utente</option>";
$qry="SELECT * FROM maaking_users WHERE id_gas='"._USER_ID_GAS."' AND isactive='1' ORDER BY fullname ASC;";
$res = $db->sql_query($qry);
while ($row = mysql_fetch_array($res)){
    if(read_option_text($row["userid"],"_USER_USA_CASSA")=="SI"){
    $h .="<option value=\"".mimmo_encode($row["userid"])."\">".$row["fullname"]."</option>";           
    }
}           

$h.="   </select>
        </div>
        <div data-role=\"fieldcontain\">
            <label for=\"credit_to_add\">Credito</label>
            <input type=\"text\" name=\"credit_to_add\" id=\"credit_to_add\" value=\"\"  />
        </div>
        <div data-role=\"fieldcontain\">
            <label for=\"numero_documento\">Num. Documento</label>
            <input type=\"text\" name=\"numero_documento\" id=\"numero_documento\" value=\"\"  />
        </div>
        <div data-role=\"fieldcontain\">
            <input type=\"hidden\" name=\"do\" value=\"add_credit\">
            <input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Salva\"  data-theme=\"b\">
        </div>
        
     </form>";





$p->jqm_page_content = $h;

//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 1



//La visualizzo
echo $j->jqm_render();
unset($j);  
?>