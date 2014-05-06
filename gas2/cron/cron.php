<?php

//L'update viene effettuato includendo "rend"
include_once("../rend.php");


//$do= $_GET["do"];

switch ($do){


    case "db_update_32453rfwdf2343214erwfr2353":


    // UPDATE ORDINI SENZA ART_CODICE
    $sql = "update retegas_dettaglio_ordini
    SET `art_codice`=(SELECT codice from retegas_articoli where id_articoli=retegas_dettaglio_ordini.id_articoli),
    `art_desc` = (SELECT descrizione_articoli from retegas_articoli where id_articoli=retegas_dettaglio_ordini.id_articoli),
    `art_um` = (SELECT CONCAT(u_misura, ' ', misura) from retegas_articoli where id_articoli=retegas_dettaglio_ordini.id_articoli)
    WHERE art_codice IS NULL";
    $db->sql_query($sql);



    // UPDATE-----------------------------------------------------QUESTA VIENE ESEGUITA SEMPRE

    $log_ordini_chiusi = update_ordini_chiusi();
    $log_ordini_aperti = update_ordini_aperti();

    if($log_ordini_chiusi<>""){
		Echo  $log_ordini_chiusi;
	}else{
		Echo  "ORDINI CHIUSI NESSUNO\n";
	}
	if($log_ordini_aperti<>""){
		Echo  $log_ordini_aperti;
	}else{
		Echo  "ORDINI APERTI NESSUNO\n";
	}

	break;


    case "check_users_outdated_dsfkiuhg43983hd":


        //ciclo tutti i gas
        $sql_gas = "SELECT * FROM retegas_gas;";
        $result_gas = $db->sql_query($sql_gas);
        while ($row_gas = $db->sql_fetchrow($result_gas)){

            $r .="------------------------------------------<br>";
            $r .="GAS: ".$row_gas["descrizione_gas"]."<br>";

            //per ogni gas sospendo gli utenti
            $days_for_suspend = CAST_TO_INT(read_option_gas_text($row_gas["id_gas"],"_GAS_SITE_INATTIVITA"));
            if($days_for_suspend>0){
                $sql_users= "SELECT *, DATEDIFF(NOW(),last_activity) as diff_date FROM `maaking_users`
                WHERE DATEDIFF(NOW(),last_activity)>'$days_for_suspend'
                AND id_gas='".$row_gas["id_gas"]."' AND isactive='1';";

                $result_users = $db->sql_query($sql_users);

                $frase = read_option_gas_text($row_gas["id_gas"],"_GAS_SITE_FRASE_INATTIVITA");
                if($frase==""){$frase="Account sospeso per prolungata inattività";}

                while ($row_users = $db->sql_fetchrow($result_users)){
                    $r .= "-----".$row_users["fullname"]." --> ".$days_for_suspend." -->".$row_users["diff_date"]."<br>";

                    $sql_susp = "UPDATE maaking_users SET isactive=2 WHERE userid='".$row_users["userid"]."' LIMIT 1;";

                    $res_susp = $db->sql_query($sql_susp);
                    write_option_text($row_users["userid"],"_NOTE_SUSPENDED",$frase);

                    $suspended ++;
                }

                if($db->sql_numrows($result_users)>0){

                    //Dovrei mandare una mail ?
                }

                $r.= "totale utenti interessati: ".$db->sql_numrows($result_users)."<br>";

                $r.= "Frase agli utenti: ".$frase."<br>";
                $r.= "---------------------------------------------<br>";
            }else{
                $r.= "Nessun valore impostato<br>";
            }
            //Per ogni utente sospendo e setto la frase di sospensione

        }

        if($suspended>0){
            log_me(0,0,"CRO","SSP","Trovati $suspended utenti da sospendere",$suspended,$r);
        }


        Echo $r;

    break;

    case "this_is_the_end_3204723hfjwehr23ji2jr3lkj3k3k":

    //PASSO TUTTI GLI ORDINI APERTI

    $days_to_consider = CAST_TO_INT($days_to_consider,1,3);


    $days_to_consider_plus = $days_to_consider + 1;
        $query_msg = "SELECT * from retegas_ordini
                     WHERE (retegas_ordini.id_stato='2')
                     AND
                     NOW() > DATE_ADD(retegas_ordini.data_chiusura, INTERVAL -$days_to_consider_plus DAY) AND
                     NOW() < DATE_ADD(retegas_ordini.data_chiusura, INTERVAL -$days_to_consider DAY);";
        $result_msg = $db->sql_query($query_msg);

        // se ci sono righe da modificare allora
        if($db->sql_numrows($result_msg)>0){
            $l .= "Ci sono ordini da chiudere tra $days_to_consider giorno/i<br>";
            while ($row = $db->sql_fetchrow($result_msg)){
                 $l .= "<strong>Ord. #".$row["id_ordini"].", ".descrizione_ordine_from_id_ordine($row["id_ordini"])."</strong>, chiude il ".conv_datetime_from_db($row["data_chiusura"])."<br>";

                 //PARTECIPANTI A QUESTO ORDINE :
                 $qry="SELECT
                maaking_users.fullname,
                maaking_users.email,
                maaking_users.user_site_option,
                maaking_users.userid
                FROM
                retegas_ordini
                Inner Join retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze
                Inner Join maaking_users ON retegas_referenze.id_gas_referenze = maaking_users.id_gas
                Inner Join retegas_gas ON retegas_referenze.id_gas_referenze = retegas_gas.id_gas
                WHERE
                retegas_ordini.id_ordini =  '".$row["id_ordini"]."'
                AND
                maaking_users.isactive = 1;";
                  $result_part = $db->sql_query($qry);
                  while ($row_part = $db->sql_fetchrow($result_part)){

                      $user_days = read_option_integer($row_part["userid"],"_USER_ALERT_DAYS");
                      if($user_days==$days_to_consider){
                            $l .= "OK ! -> GIORNI : $days_to_consider POTENZIALE PARTECIPANTE : ".$row_part["fullname"].", ".$row_part["email"]."<br>";
                            $verso_chi[] = $row_part[0] ;
                            $mail_verso_chi[] = $row_part[1] ;
                            $lista_destinatari .= $row_part[0]."<br>";

                      }else{
                            //$l .= "IGNORATO : ".$row_part["fullname"].", ".$row_part["email"]."<br>";
                      }
                  }


            }
        }

        $soggetto = "["._SITE_NAME."] - [ALLERTA ORDINI] Ci sono ordini in scadenza...";
        if($days_to_consider>1){$plurale="i";}else{$plurale="o";}
        $messaggio ="<h3>Caro utente,</h3>
                     <p>Ci risulta che tra $days_to_consider giorn$plurale si chiuderà un ordine al quale tu potresti partecipare o stai già partecipando.</p>
                     <p>Se ti interessa comperare qualcosa, o modificare la spesa già fatta vai alla pagina <a href=\"".$RG_addr["ordini_aperti"]."\">ordini aperti</a>, dove potrai
                     di persona verificare la situazione.</p>
                     <p>Per modificare le impostazioni personali e non ricevere più avvisi clicca <a href=\"".$RG_addr["user_option_sito"]."\">qua.</a></p>";
        $da_chi = _SITE_NAME;
        $mail_da_chi = _SITE_MAIL_REAL;
        manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($messaggio),"AUT",0,0,$messaggio);

        log_me(0,0,"CRO","ALR","Eseguito this is the end",0,$l."<br>MAIL a </br>".$lista_destinatari);
        echo $l;
        //PER OGNI ORDINE
            //CONTO DA UNO A QUATTRO GIORNI
            //SE UN UTENTE HA L'AVVISO A X GIORNI LO METTO IN UN ARRAY


    break;

    case "alert_unconfirmed_orders_sdjfsgdf98dsfiuohsdfjhwef98fewhj";
        Echo  "ALERT SENT UNCONFIRMED ORDERS OK\n";
    break;
}