<?php

// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

//controlla se l'utente ha i permessi necessari
if(!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     pussa_via();
}

         $gasappartenenza = CAST_TO_INT($gasappartenenza,0);
         if($do=="assign"){
            if(isset($id_new_user)){
                 if($gasappartenenza>0){


                         $res = $db->sql_query("UPDATE maaking_users SET
                                                 isactive = '1',
                                                 code='0',
                                                 profile='',
                                                 id_gas='$gasappartenenza'
                                                 WHERE
                                                 userid = '$id_new_user'
                                                 LIMIT 1;");

                         $msg = 'Utente attivato;<br> Una mail è stata mandata per avvisarlo.';
                         $soggetto = "Avvenuta attivazione account ReteDes.it";
                         $messaggio = 'Ciao, '.fullname_from_id($id_new_user).'<br>
                                       con questa mail ti avvisiamo che il tuo account su <a href="http://www.retedes.it">www.retedes.it</a> è stato attivato.<br>
                                       Da ora puoi accedere al sito con la tua username e password, ed iniziare ad usarlo.<br>
                                       Il responsabile di ReteDes ti ha assegnato al gas '.gas_nome($gasappartenenza).'.
                                       <br>
                                       Se hai bisogno di aiuto prova anche a consultare <a href="http://wiki.retedes.it">le istruzioni :)</a>
                                       <br>
                                       Buoni acquisti GAS !!<br>
                                       ';
                         usleep(5000);
                         tweet("#retedes EVVIVA, nel ".gas_nome($gasappartenenza)." c'? un nuovo utente !!");

                         usleep(5000);
                         $ris = manda_mail("ReteDes.it - NO_REPLY",_SITE_MAIL_REAL,fullname_from_id($id_new_user),email_from_id($id_new_user),$soggetto,"","ACT",0,_USER_ID,$messaggio);
                         log_me(0,$id_new_user,"USR","ACT","Attivato ".fullname_from_id($id_new_user),$ris,"");


                 }else{$msg="E' necessario indicare il gas di appartenenza dell'utente scelto.";}
            }
         }




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menÃ¹ verticale dovrÃ  essere aperta
$r->voce_mv_attiva = menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Inserimento Nuovo Gas";
$r->tabella_da_ordinare = "output_1";

$r->javascripts_header[] = java_head_select2();
$r->javascripts[]= "<script>
        $(document).ready(function() { $('#id_referente_gas').select2(); });
    </script>";

//Messaggio popup;
$r->messaggio = $msg;
//Dico quale menÃ¹ orizzontale dovrÃ  essere associato alla pagina.
$r->menu_orizzontale =  des_menu_completo();


 $t.='   <div>
        <h4>1</h4>
        <label for="gasappartenenza">Gas assegnazione nuovo utente</label>
        <select name= "gasappartenenza" id="lista_gas" >';

        $result = mysql_query("SELECT * FROM retegas_gas WHERE id_gas>0 ORDER BY id_gas ASC ");
        $totalrows = mysql_num_rows($result);
        $t .= "<option value=\"-1\">Selezionare GAS</option>";

        while ($row = mysql_fetch_array($result)){
                $idgas = $row['id_gas'];
                $descrizionegas = $row['descrizione_gas'];
                if ($idgas==$gasappartenenza){$agg=" selected ";}else{$agg=null;}
        $t .= "<option value=\"".$idgas ."\" $agg>".$descrizionegas ."  </option>";
         }//end while
        $t .='</select>
        <h5 title="'.$help_gasappartenenza.'">Inf.</h5>
        </div>';




    $result = $db->sql_query("SELECT * FROM maaking_users WHERE id_gas='0' AND isactive='0';");
    $totalrows = mysql_num_rows($result);
    $gas_name = gas_nome(0);


    if($totalrows==0){

    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Tutti gli utenti sono già attivi.</h3>
            </div>";
    }else{

    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Utenti $gas_name in attesa attivazione</h3>
            <p>Selezionare un gas e poi premere il pulsante riferito ad ogni utente per attivarlo.</p>
            <form class=\"retegas_form\" METHOD=\"POST\" ACTIONE=\"\">
            $t
            <input type=\"hidden\" name=\"do\" value=\"assign\">
            <table id=\"output_1\">
            <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>E-Mail</th>
                <th>Telefono</th>
                <th>Msg</th>

                </tr>
         </thead>
         <tbody>";

       //$o1 =   mysql_query("SELECT id_gas FROM maaking_users WHERE userid = ". $id_user );

       //$outp = mysql_fetch_row($o1);

       $riga=0;

         while ($row = mysql_fetch_array($result)){

         $riga++;

            $d1 = "id_gas";
            $id_utente  = $row["userid"];
            $fullname   = $row["fullname"];
            $mail       = $row["email"];
            $tel        = $row["tel"];
            $msg        = $row["profile"];
            //$op = '<a class="awesome red small" href="gas_user_activate.php?do=act&id_new_user='.$id_utente.'">ATTIVA</a>';
            $op = '<input type="submit" name="id_new_user" value="'.$id_utente.'">';

            $h.= "
            <tr>
                <td $col_1>$op</td>
                <td $col_2><a href=\"".$RG_addr["pag_users_form"]."?id_utente=".mimmo_encode($id_utente)."\">$fullname</a></td>
                <td $col_3><a href=\"mailto:$mailgas\" a>$mail</td>
                <td $col_4>$tel</td>
                <td $col_5>$msg</td>

            </tr>";

         }//end while



         $h.= "
         </tbody>
         </table>
         </form>
         </div>";

    }

//Questo Ã¨ il contenuto della pagina
$r->contenuto = $h;
//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);