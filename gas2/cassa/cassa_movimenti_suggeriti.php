<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_USA_CASSA)){
     pussa_via();
}

if(!leggi_permessi_utente(_USER_ID)&perm::puo_gestire_la_cassa){
    pussa_via();
}

if($do=="car"){
    
    $da_chi = _USER_FULLNAME;
    $mail_da_chi = id_user_mail(_USER_ID);
    $id=CAST_TO_INT($id); 
    
    $sql = "SELECT * FROM retegas_options WHERE id_option='".$id."' LIMIT 1;";
    $res=$db->sql_query($sql);
    $row=$db->sql_fetchrow($res);
    
    if($row["chiave"]=="PREN_MOV_CASSA" AND _USER_ID_GAS==$row["id_gas"]){
        $importo=$row["valore_real"];
        $descrizione=$row["valore_text"];
        $documento=$row["note_1"];
    
        
        $my_query="INSERT INTO retegas_cassa_utenti (   id_utente ,
                                                        id_gas,
                                                        importo ,
                                                        segno ,
                                                        tipo_movimento ,
                                                        descrizione_movimento ,
                                                        data_movimento ,
                                                        id_cassiere ,
                                                        registrato ,
                                                        data_registrato ,
                                                        contabilizzato ,
                                                        data_contabilizzato,
                                                        numero_documento
                                                      )VALUES(
                                                      '".$row["id_user"]."',
                                                      '"._USER_ID_GAS."',
                                                      '".$importo."',
                                                      '+',
                                                      '1',
                                                      '".$descrizione."',
                                                      NOW(),
                                                      '"._USER_ID."',
                                                      'si',
                                                      NOW(),
                                                      'no',
                                                      NULL,
                                                      '".$numero_documento."'          
                                                      )";                                         
        
        //echo $my_query;
        ///exit;
        //INSERT BEGIN ---------------------------------------------------------
        $result = $db->sql_query($my_query);
        $ok=0;
        if (is_null($result)){
            $msg = "Errore nell'inserimento nella cassa Utenti";
        }else{
            $msg = "OK";
            $ok++;
            write_option_text(_USER_ID,"ADD_CREDIT",time());
        };
        
        if($ok>0){
                
                
                $da_chi = _USER_FULLNAME;
                $mail_da_chi = email_from_id(_USER_ID);
                
                $verso_chi = fullname_from_id($row["id_user"]);
                $mail_verso_chi = email_from_id($row["id_user"]);
                
                $soggetto ="["._SITE_NAME." - RICEVUTA] Carico credito sul tuo conto.";
                $cr = _nf(cassa_saldo_utente_totale($row["id_user"]));
                
                $query_op = "SELECT id_cassa_utenti,
                                    data_movimento
                                    FROM retegas_cassa_utenti 
                                    WHERE
                                    id_utente = '".$row["id_user"]."'
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
                in data odierna ($data_op) ha anticipato un versamento dichiarato effettuato da $verso_chi caricando quindi sul suo conto GAS<br>
                <span style=\"font-size:1.3em\">$importo Euro.</span><br>
                <br>
                Il numero di documento è : $numero_documento<br>
                Il numero dell'operazione è : $n_op <br>
                <br>
                Attualmente, il tuo credito totale disponibile è $cr Eu.<br>
                ma vi potrebbero essere dei movimenti che non sono stati ancora contabilizzati.<br>
                Verifica la tua situazione su <a href=\"http://www.retedes.it\">www.retedes.it</a>";
                
                manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,null,"CAS",0,_USER_ID,$messaggio_html);    
                //go("sommario",$id_user,"Hai correttamente caricato $importo Euri a ".fullname_from_id($id_ute));
                $msg = "CARICO EFFETTUATO; MAIL DI AVVISO INVIATA";
                
                $sql = "DELETE FROM retegas_options WHERE id_option=".$id;
                $db->sql_query($sql);     
        }else{
                //go("sommario",$id_user,"E' successo qualcosa di imprevisto durante questa operazione");
                $msg = "NON E' STATO POSSIBILE TERMINARE CORRETTAMENTE L'OPERAZIONE"; 
            }
        
        
    
    
    }else{
        $msg = "NON POSSIBILE";    
    }
    
    
   
    
}
if($do=="del"){
   $id=CAST_TO_INT($id); 
   $sql = "SELECT * FROM retegas_options WHERE id_option=".$id;
   $res=$db->sql_query($sql);
   $row=$db->sql_fetchrow($res);
   if($row["chiave"]=="PREN_MOV_CASSA" AND _USER_ID_GAS==$row["id_gas"]){
    $sql = "DELETE FROM retegas_options WHERE id_option=".$id;
    $db->sql_query($sql);
    $msg="Tolto";   
   }else{
    $msg="NON POSSIBILE";    
   }
   
   
   
    
    
    
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Suggerisci movimento cassa";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale[] = cassa_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_ckeditor();
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else{if(!is_empty($msg)){$r->messaggio=$msg;}}
//Contenuto

$sql = "SELECT * FROM retegas_options WHERE chiave='PREN_MOV_CASSA' AND id_gas='"._USER_ID_GAS."'";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)){
    $h.="
    <div style=\"border:1px dotted gray;\">".fullname_from_id($row["id_user"])." - ".$row["valore_text"]." - ".$row["note_1"].";<br>
    <strong>&euro; "._nf($row["valore_real"])."</strong><br> 
    <a href=\"?do=car&id=".$row["id_option"]."\" class=\"awesome green option\">ACCETTA</a>
    <a href=\"?do=del&id=".$row["id_option"]."\" class=\"awesome red option\">RIFIUTA</a>
    </div><br>";
    $mov++;
}
 if($mov==0){$h.="<p>(Ma adesso non ci sono prenotazioni)</p>";}

ob_start();
?>
<div class="rg_widget rg_widget_helper">
    <h3>Gestisci suggerimenti carico cassa</h3>
    <p>Qua trovi le richieste di anticipo carico credito dei tuoi utenti.</p>

    <?php echo $h; ?>
           
       



</div>
<?php

//Questo ?? il contenuto della pagina
$r->contenuto = ob_get_contents();
ob_clean();

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);   
