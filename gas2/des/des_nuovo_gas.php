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




//-------------------------------------------------Check
if($do=="add"){

    $descrizione_gas = sanitize($descrizione_gas);
    (int)$default_permission = 283;//DEFAULT
    (int)$gas_permission = 7; //DEFAULT
    (int)$id_referente_gas;
    (int)$id_des = _USER_ID_DES;
    if($id_referente_gas>0){

    $my_query="INSERT INTO retegas_gas
                (descrizione_gas,
                 default_permission,
                 gas_permission,
                 id_referente_gas,
                 id_des) VALUES (
                 '$descrizione_gas',
                 '$default_permission',
                 '$gas_permission',
                 '$id_referente_gas',
                 '$id_des');";
            $result = $db->sql_query($my_query);
            //PERICOLOSO
            $res = mysql_query("SELECT LAST_INSERT_ID();");
            $row = mysql_fetch_array($res);
            $last_id=$row[0];

    if (is_null($result)){
            $msg = "Errore nell'inserimento del record";
            pussa_via();
            exit;
        }else{
            $act_perm = leggi_permessi_utente($id_referente_gas);
            $new_perm = $act_perm |  perm::puo_eliminare_messaggi;
            $new_perm = $new_perm |  perm::puo_gestire_la_cassa;
            $new_perm = $new_perm |  perm::puo_mod_perm_user_gas;
            $new_perm = $new_perm |  perm::puo_gestire_utenti;
            $new_perm = $new_perm |  perm::puo_vedere_tutti_ordini;
            $new_perm = $new_perm |  perm::puo_creare_gas;
            log_me(0,_USER_ID,"GAS","NEW","$descrizione_gas Creato -> user id_referente_gas",0,"<br>
                                                                            USER ID : $id_referente_gas<br>
                                                                            OLD : $act_perm<br>
                                                                            NEW : $new_perm");


            $query_update = "UPDATE maaking_users SET id_gas='".$last_id."', user_permission = '$new_perm', isactive='1' WHERE userid='".$id_referente_gas."' LIMIT 1;";
            $result = $db->sql_query($query_update);

              $da_chi = _USER_FULLNAME;
              $mail_da_chi = id_user_mail(_USER_ID);

              $verso_chi = fullname_from_id($id_referente_gas);
              $mail_verso_chi = email_from_id($id_referente_gas);

              $msg_mail ="<h3>$descrizione_gas</h3>
                          <p>Ciao $a_chi, $descrizione_gas è stato creato da $da_chi, e tu sembra allo stato attuale delle cose che sia la persona più indicata per guidarlo responsabilmente.</p>
                          <p>Come potrai notare dal tuo prossimo accesso a <a href=\"http://www.retedes.it\">www.retedes.it</a>, ti ritrovi solo soletto.</p>
                          <p>I nuovi utenti dovranno scegliere $descrizione_gas al momento della loro registrazione, per poter popolarlo</p>
                          <p>Noterai anche che (se non li avevi già) ti sono stati conferiti alcuni superpoteri, grazie ai quali potrai gestire al meglio la grana che ti è stata regalata.</p>
                          <p>Per prima cosa devi fare alcune modifiche:
                          <ul>
                          <li>Da : Il mio gas -> gestisci gas -> Modifica dati potrai cambiare le anagrafiche.</li>
                          <li>Da : Il mio gas -> gestisci gas -> Opzioni potrai settare le caratteristiche del tuo gas.</li>
                          <li>RICORDATI di salvare ogni valore singolarmente !!</li>
                          <li>Una raccomandazione : Se attivi la cassa, leggi BENE tutto il materiale informativo su wiki.retedes.it</li>

                          </ul>
                          </p>
                          <p>Mi sembra di averti detto tutto, buona fortuna (e buon lavoro)</p>
                          <p><strong>$da_chi</strong></p>";


              $soggetto = "["._SITE_NAME." $descrizione_gas] - da $da_chi - E' nato, E' nato !!";
              manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",0,_USER_ID,$msg_mail);

            $msg = "Nuovo Gas Aggiunto, referenze aggiornate.";
            go("gas_table",_USER_ID,$msg);
        };

    }else{
        $msg = "Scegliere il referente GAS";
    }



}









//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menÃ¹ verticale dovrÃ  essere aperta
$r->voce_mv_attiva = menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Inserimento Nuovo Gas";

$r->javascripts_header[] = java_head_select2();
$r->javascripts[]= "<script>
        $(document).ready(function() { $('#id_referente_gas').select2(); });
    </script>";

//Messaggio popup;
$r->messaggio = $msg;
//Dico quale menÃ¹ orizzontale dovrÃ  essere associato alla pagina.
$r->menu_orizzontale =  des_menu_completo();

//------------------------------TABELLA INSERIMENTO
        $help_descrizione_gas='Il nome  nuovo gas.';
        $help_id_referente_gas = '';
        $help_default_permission ='';
        $help_gas_permission ='';

        $query_users = "SELECT retegas_gas . * , maaking_users . *
                        FROM maaking_users
                        INNER JOIN retegas_gas ON retegas_gas.id_gas = maaking_users.id_gas
                        WHERE (id_des ='"._USER_ID_DES."'
                        OR id_des='0')
                        AND retegas_gas.id_referente_gas<>maaking_users.userid";
        $res_users = $db->sql_query($query_users);

        while ($row = $db->sql_fetchrow($res_users)){

            if(isset($id_referente_gas)){
                if($id_referente_gas==$row["userid"]){$selected = " SELECTED ";}else{$selected="";}
            }
            $user_select .= '<option value="'.$row["userid"].'" '.$selected.'>'.$row["fullname"].' - '.$row["descrizione_gas"].'</option>\\n';
        }



        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Crea un nuovo GAS (sei sicuro?)</h3>

        <div>
        <h4>ISTRUZIONI</h4>
        <ul>
        <li>Si può creare un nuovo gas solo staccando un utente dal proprio e mettendolo a capo del nuovo gas appena creato.</li>
        <li>Dopo aver impstato l\'utente a capo del nuovo gas, questo sarà subito associato ad esso, e non comparirà più nel gas attuale.</li>
        <li>Appena creato il nuovo GAS, Il nuovo utente dovrà aggiungere le opzioni relative ad esso e modificare le anagrafiche.</li>
        <li>Il nuovo gas comparirà nella lista dei gas disponibili al momento dell\'iscrizione dei nuovi utenti</li>
        <li>Appena creato il nuovo gas sarà inviata una mail con le istruzioni "base" al nuovo referente.</li>
        <li>Se il prescelto è ancora in attesa verrà contestualmente attivato, e gli verranno automaticamente appioppati nuovi superpoteri.</li>

        </ul>
        </div>

        <form name="nuovo_gas" method="POST" action="" class="retegas_form">


        <div>
        <h4>1</h4>
        <label for="descrizione_gas">Scrivi il nome del nuovo GAS</label>
        <input type="text" name="descrizione_gas" value="'.$descrizione_gas.'" size="50"></input>
        <h5 title="'.$help_descrizione_gas.'">Inf.</h5>
        </div>

        <div>
        <h4>2</h4>
        <span>
        <label for="id_referente_gas">Utente referente</label>
        <select id="id_referente_gas" name="id_referente_gas">
        <option value="0">Nessun utente selezionato</OPTION>
        '.$user_select.'
        </select>
        <h5 title="'.$help_id_referente_gas.'">Inf.</h5>
        </span>

        </div>



        <div>
        <h4>3</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Crea un nuovo GAS !" align="center" >
        <input type="hidden" name="do" value="add">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div>


        </form>
        </div>';


//------------------------------TABELLA INSERIMENTO




//Questo Ã¨ il contenuto della pagina
$r->contenuto = $h;
//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);