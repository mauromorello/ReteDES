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
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Merge Ditte";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();



if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}


$c = "<form method=\"POST\" action=\"\">
        <input type=\"text\" name=\"ditta_1\" value=\"$ditta_1\"><--
        <input type=\"text\" name=\"ditta_2\" value=\"$ditta_2\">
        <input type=\"submit\" value=\"GO\">
        <input type=\"hidden\" name=\"do\" value=\"do_prepare\">
        </form>";

if($do=="do_prepare"){
  
  $ditta_1 = CAST_TO_INT($ditta_1,0);
  $ditta_2 = CAST_TO_INT($ditta_2,0);  
  
  $l1 .="<p><strong>MASTER</strong></p>";  
  $l1 .="<p>Nome : ".ditta_nome($ditta_1)."</p>";
  $l1 .="<p>Data : ".conv_date_from_db(ditta_data_creazione($ditta_1))."</p>";
  $l1 .="<p>Proponente : ".fullname_from_id(ditta_user($ditta_1))."</p>";   
  $l1 .="<p>Retegas_ditte : ".db_nr_q("id_ditte",$ditta_1,"retegas_ditte")."</p>";
  $l1 .="<p>Retegas_listini : ".db_nr_q("id_ditte",$ditta_1,"retegas_listini")."</p>";
  $l1 .="<p>Retegas_options : ".db_nr_q("id_ditta",$ditta_1,"retegas_options")."</p>";
  $l1 .="<p>Retegas_bacheca : ".db_nr_q("id_ditta",$ditta_1,"retegas_bacheca")."</p>";
  
  $l2 .="<p><strong>SLAVE</strong></p>"; 
  $l2 .="<p>Nome : ".ditta_nome($ditta_2)."</p>";
  $l2 .="<p>Data : ".conv_date_from_db(ditta_data_creazione($ditta_2))."</p>";
  $l2 .="<p>Proponente : ".fullname_from_id(ditta_user($ditta_2))."</p>"; 
  $l2 .="<p>Retegas_ditte : ".db_nr_q("id_ditte",$ditta_2,"retegas_ditte")."</p>";
  $l2 .="<p>Retegas_listini : ".db_nr_q("id_ditte",$ditta_2,"retegas_listini")."</p>";
  $l2 .="<p>Retegas_options : ".db_nr_q("id_ditta",$ditta_2,"retegas_options")."</p>";
  $l2 .="<p>Retegas_bacheca : ".db_nr_q("id_ditta",$ditta_2,"retegas_bacheca")."</p>";

  $confirm = "
        <div class=\"ui-widget ui-state-error ui-corner-all\">
        <form method=\"POST\" action=\"\">
        <input type=\"text\" name=\"ditta_1\" value=\"$ditta_1\" readonly=\"readonly\"><--
        <input type=\"text\" name=\"ditta_2\" value=\"$ditta_2\" readonly=\"readonly\">
        <input type=\"submit\" value=\"ESEGUI\">
        <input type=\"hidden\" name=\"do\" value=\"do_confirm\">
        </form>
        </div>";

  $t = "<table>
            <tr>
                <td style=\"width:50%\">
                    $l1
                </td>
                <td style=\"width:50%\">
                    $l2
                </td>
            </tr>
        </table>
        $confirm";  
}    

if($do=="do_confirm"){
   
   //PRENDO I PROPRIETARI DI DITTA_1 e DITTA 2 E LE LORO MAIL
   $utente_ditta_1 = fullname_from_id(ditta_user($ditta_1));
   $mail_ditta_1 = email_from_id(ditta_user($ditta_1));
   
   $utente_ditta_2 = fullname_from_id(ditta_user($ditta_2));
   $mail_ditta_2 = email_from_id(ditta_user($ditta_2));
   
   
    
   //UPDATE LISTINI --> LISTINI CON  DITTA_2 diventano con DITTA_1
   $sql_update_listini ="UPDATE retegas_listini set id_ditte='$ditta_1' WHERE id_ditte='$ditta_2';";
   $res_listini = $db->sql_query($sql_update_listini);
   $nr_listini = $db->sql_numrows($res_listini);
   $log .= $sql_update_listini."<br>".
           $nr_listini." records interessati.<br>";
   
   
   //UPDATE OPTIONS ---> OPTIONS CON DITTA 2 diventano con DITTA_1
   $sql_update_options ="UPDATE retegas_options set id_ditta='$ditta_1' WHERE id_ditta='$ditta_2';";
   $res_options = $db->sql_query($sql_update_options);
   $nr_options = $db->sql_numrows($res_options);
   $log .= $sql_update_options."<br>".
           $nr_options." records interessati.<br>";
   
   
   //UPDATE MESSAGGI --->MESSAGGI CON DITTA2 diventano con id_ditta_1
   $sql_update_bacheca ="UPDATE retegas_bacheca set id_ditta='$ditta_1' WHERE id_ditta='$ditta_2';";
   $res_bacheca = $db->sql_query($sql_update_bacheca);
   $nr_bacheca = $db->sql_numrows($res_bacheca);
   $log .= $sql_update_bacheca."<br>".
           $nr_bacheca." records interessati.<br>";
   
   //DELETE DITTA_2
   $sql_delete_ditta = "DELETE FROM retegas_ditte WHERE id_ditte='$ditta_2' LIMIT 1;";
   $res_ditta = $db->sql_query($sql_delete_ditta);
   $nr_ditta = $db->sql_numrows($res_ditta);
   $log .= $sql_delete_ditta."<br>".
           $nr_ditta." records interessati.<br>";
   
   //MAIL AGLI INTERESSATI DEI LISTINI E DELLE DITTE
   
   $soggetto = "["._SITE_NAME."] - Unione ditta $ditta_2 in $ditta_1";
   $messaggio = "<h3>Unione ditte</h3>
                 <p>Gentili utenti,</p>
                 <p>In data odierna ho unito le ditte $ditta_2 e $ditta_1, che erano duplicate all'interno del sistema.</p>
                 <p>Ogni listino e dato riferito ad esse *dovrebbe* essere stato spostato di conseguenza.</p>
                 <p>Questa mail è stata spedita per conoscenza</p>
                 <p>Cordiali saluti, "._USER_FULLNAME.".</p>";
   
   $da_chi = _USER_FULLNAME;
   $mail_da_chi = email_from_id(_USER_ID);
   
   manda_mail($da_chi,$mail_da_chi,$utente_ditta_1,$mail_ditta_1,$soggetto,null,"MAN",0,_USER_ID,$messaggio);
              
   sleep(1);
   manda_mail($da_chi,$mail_da_chi,$utente_ditta_2,$mail_ditta_2,$soggetto,null,"MAN",0,_USER_ID,$messaggio);
                              
   
   //SCRIVO TUTTO IL LOG CON LE QUERY FATTE. 
   log_me(0,_USER_ID,"DIT","MRG","Unione $ditta_2 in $ditta_1",0,$log); 
   
   
   //TORNO ALLA PAGINA DELLE DITTE
   
   go("ditte_table_3",_USER_ID,"UNITE DITTE OK");
    
}

//Contenuto
$h = "<div class=\"rg_widget rg_widget_helper\">
        <h3>Merge di due ditte</h3>
        $c<br>
        $t
        </div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>