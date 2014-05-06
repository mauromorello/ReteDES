<?php

function manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,$messaggio,$tipo_log = null,$ordine=0,$id_user=0,$messaggio_html = null){

    global $RG_addr,$db;
    //  SANITIZE ?



    if(empty($messaggio_html)){
           // CONVERSIONE ACCENTI - > TESTO
    $conversion_chars = array ("&agrave;" => "a'",
                               "&egrave;" => "e'",
                               "&egrave;" => "e'",
                               "&igrave;" => "i'",
                               "&ograve;" => "o'",
                               "<br>" => "\r\n",
                               "&ugrave;" => "u'");

    $msg_plain = str_replace (array_keys ($conversion_chars), array_values  ($conversion_chars), $messaggio);

    // CONVERSIONE ACCENTI - PLAIN TEXT
    $messaggio .="<br>
                  <br>
                  <img src=\"".$RG_addr["img_logo_retedes"]."\" border=\"0\" width=\"75\" height=\"75\">
                  <b><a href=\"http://www.retedes.it\">www.retedes.it</a></b><br>
                  ReteDes.it - La rete dei distretti delle economie solidale<br>
                  <br>
                  <hr>
                  ";


    }else{


      //$messaggio_html= sistema_accenti($messaggio_html);
      $msg_plain = strip_tags($messaggio_html);
      $messaggio = $messaggio_html;
      $messaggio .="<br>
                  <br>
                  <img src=\"".$RG_addr["img_logo_retedes"]."\" border=\"0\" width=\"75\" height=\"75\">
                  <b><a href=\"http://www.retedes.it\">www.retedes.it</a></b><br>
                  ReteDes.it - La rete dei distretti delle economie solidale<br>
                  <br>
                  <hr>
                  ";
    }




    $transport = Swift_MailTransport::newInstance();

    $mailer = Swift_Mailer::newInstance($transport);



    $message = Swift_Message::newInstance()
      ->setSubject($soggetto)
      ->setFrom(array($mail_da_chi => $da_chi))
      ->setTo(array($mail_verso_chi => $verso_chi))
      ->setReturnPath(_SITE_MAIL_REAL)
      ->setBody($messaggio, 'text/html', 'utf-8')
      ->addPart($msg_plain, 'text/plain')
      ;
    $message->setSender('retegas@altervista.org');
    $message->addBcc(_SITE_MAIL_LOG);


    $headers = $message->getHeaders();


    if(read_option_text(0,"MAILER")=="ON"){

            $result = $mailer->send($message);

            $array_dati = array(  "subject"         => $soggetto,
                              "from"            => $da_chi,
                              "from_mail"       => $mail_da_chi,
                              "to"              => $verso_chi,
                              "to_mail"         => $mail_verso_chi,
                              "cc"              => "",
                              "cc_mail"         => "",
                              "ccn"             => "[ReteGas]",
                              "ccn_mail"        => _SITE_MAIL_LOG,
                              "plain_text"      => $msg_plain,
                              "html_text"       => sanitize($messaggio),
                              "send_from"       => _SITE_MAIL_REAL,
                              "priority"        => "",
                              "id_utente"       => $id_user);


            // se non riesce a mandarla subito allora la accoda
            if($result==0){
                accoda_mail($array_dati);
                log_me($ordine,$id_user,"EML",$tipo_log,"ACCODATA posta da: $da_chi a: $verso_chi",$result,$soggetto."<br>".$msg_plain);

            }else{
                log_me($ordine,$id_user,"EML",$tipo_log,"posta da: $da_chi a: $verso_chi",$result,$soggetto."<br>".$msg_plain);
            }
            //echo "RESULT -- > ".$result."<br>";
    }

    return $result;
    }
function manda_mail_multipla($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,$messaggio,$tipo_log=null,$ordine=0,$id_user=0,$messaggio_html = null){
    global $RG_addr;

    $transport = Swift_MailTransport::newInstance();
    //$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587)
  //->setUsername('retegas.ap')

  //;

    if(empty($messaggio_html)){
           // CONVERSIONE ACCENTI - > TESTO
    $conversion_chars = array ("&agrave;" => "a'",
                               "&egrave;" => "e'",
                               "&egrave;" => "e'",
                               "&igrave;" => "i'",
                               "&ograve;" => "o'",
                               "<br>" => "\r\n",
                               "&ugrave;" => "u'");

    $msg_plain = str_replace (array_keys ($conversion_chars), array_values  ($conversion_chars), $messaggio);

    // CONVERSIONE ACCENTI - PLAIN TEXT


    $messaggio .="<br>
                  <br>
                  <img src=\"".$RG_addr["img_logo_retedes"]."\" border=\"0\" width=\"50\" height=\"75\">
                  <b><a href=\"http://www.retedes.it\">www.retedes.it</a></b><br>
                  ReteDES.it - La rete dei distretti di economie solidali<br>
                  <br>
                  <hr>
                  ";
    }else{
      $msg_plain = strip_tags($messaggio);
      $messaggio = $messaggio_html;
    }


    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance()
      ->setSubject($soggetto)
      ->setFrom(array($mail_da_chi => $da_chi))
      ->setBody($messaggio, 'text/html', 'iso-8859-1')
      ->addPart($msg_plain, 'text/plain')
      ;
    $message->setSender($mail_da_chi);

    for($a=0;$a<count($verso_chi);$a++){

    //echo "A:". $mail_verso_chi[$a]."<br/>";
    $verso_chi_log .= $mail_verso_chi[$a]."<br>";
    $message->addTo($mail_verso_chi[$a], $verso_chi[$a]);

    $array_dati = array("subject"       => sanitize($soggetto),
                        "from"          => $da_chi,
                      "from_mail"       => $mail_da_chi,
                      "to"              => $verso_chi[$a],
                      "to_mail"         => $mail_verso_chi[$a],
                      "cc"              => "",
                      "cc_mail"         => "",
                      "ccn"             => "[ReteGas]",
                      "ccn_mail"        => _SITE_MAIL_LOG,
                      "plain_text"      => sanitize($msg_plain),
                      "html_text"       => sanitize($messaggio),
                      "send_from"       => _SITE_MAIL_REAL,
                      "priority"        => "",
                      "id_user"         => $id_user);


    accoda_mail($array_dati);
    unset($array_dati);







    }   // for
    $message->addBcc(_SITE_MAIL_LOG,'ReteDes.it');




      $headers = $message->getHeaders();


    // NON manda la mail multipla ma la accoda solamente
    //$result = $mailer->send($message);






    //log_me($ordine,$id_user,"EML",$tipo_log,"posta da: $da_chi<br> a: $verso_chi_log",$result,$soggetto."<br>".$msg_plain);

    }
function manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,$messaggio,$tipo_log=null,$ordine=0,$id_user=0,$messaggio_html = null){
    global $RG_addr;
    $transport = Swift_MailTransport::newInstance();
    //$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587)
  //->setUsername('retegas.ap')

  //;

    if(empty($messaggio_html)){
           // CONVERSIONE ACCENTI - > TESTO
    $conversion_chars = array ("&agrave;" => "à",
                               "&egrave;" => "è",
                               "&egrave;" => "é",
                               "&igrave;" => "ì",
                               "&ograve;" => "ò",
                               //"<br>" => "\r\n",
                               "&ugrave;" => "ù");

    $msg_plain = str_replace (array_keys ($conversion_chars), array_values  ($conversion_chars), $messaggio);

    // CONVERSIONE ACCENTI - PLAIN TEXT


    $messaggio .="<br>
                    <br>
                    <img src=\"".$RG_addr["img_logo_retedes"]."\" border=\"0\" width=\"75\" height=\"75\">
                    <b><a href=\"http://www.retedes.it\">www.retedes.it</a></b><br>
                    ReteDes.it - La rete dei Distretti di economie solidali.<br>
                    <br>
                  <hr>
                  ";
    }else{
      $msg_plain = strip_tags($messaggio);
      $messaggio = $messaggio_html;
      $messaggio .="<br>
              <br>
              <img src=\"".$RG_addr["img_logo_retedes"]."\" border=\"0\" width=\"75\" height=\"75\">
              <b><a href=\"http://www.retedes.it\">www.retedes.it</a></b><br>
              ReteDes.it - La rete dei Distretti di economie solidali.<br>
              <br>
              <hr>
              ";
    }


    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance()
      ->setSubject($soggetto)
      ->setFrom(array($mail_da_chi => $da_chi))
      ->setBody($messaggio, 'text/html', 'UTF-8')
      ->addPart($msg_plain, 'text/plain')
      ;
    $message->setSender($mail_da_chi);

    $message->addTo(_SITE_MAIL_LOG,'ReteDes.it');

    for($a=0;$a<count($verso_chi);$a++){

    //echo "A:". $mail_verso_chi[$a]."<br/>";
        $verso_chi_log .= $mail_verso_chi[$a].", ";
        $message->addBcc($mail_verso_chi[$a], $verso_chi[$a]);

    }   // for





      $headers = $message->getHeaders();


     //manda la mail multipla e non la accoda
    if(read_option_text(0,"MAILER")=="ON"){
        $result = $mailer->send($message);
    }

    //echo "RESULT =". $result."<br>";




    log_me($ordine,$id_user,"EML",$tipo_log,"posta da: $da_chi" ,$result,"a:  $verso_chi_log<br>".$soggetto."<hr>".$messaggio);

    }
function manda_mail_retegas_allegato($allegato,$nome_allegato){
    global $RG_addr;

    $transport = Swift_MailTransport::newInstance();

    $mailer = Swift_Mailer::newInstance($transport);



    $message = Swift_Message::newInstance()
      ->setSubject("[RETEGAS BACKUP] $nome_allegato")
      ->setFrom(array(_SITE_MAIL_REAL => _SITE_NAME))
      ->setTo(array(_SITE_MAIL_LOG => _SITE_NAME))
      ->setReturnPath(_SITE_MAIL_LOG)
      ->setBody($messaggio, 'text/html', 'iso-8859-1')
      ->addPart($msg_plain, 'text/plain')
      ;
    $message->setSender(_SITE_MAIL_REAL);
    $message->addBcc(_SITE_MAIL_LOG);

    //Create the attachment
    // * Note that you can technically leave the content-type parameter out
    $attachment = Swift_Attachment::fromPath($RG_addr["backup_temp"].$allegato);


    //Attach it to the message
    $message->attach($attachment);


    $headers = $message->getHeaders();


            $result = $mailer->send($message);




            // se non riesce a mandarla subito allora la accoda
            if($result==0){
                echo "Problemi nell'invio della mail";
            }else{
                return "OK";
            }


    }

function quante_mail_coda_totale(){
 global $db;
 $sql = "SELECT * FROM retegas_postino";
 $ret = $db->sql_query($sql);
 return $db->sql_numrows($ret);

}
function quante_mail_coda_effettiva(){
 global $db;
 $sql = "SELECT * FROM retegas_postino WHERE send_from < NOW();";
 $ret = $db->sql_query($sql);
 return $db->sql_numrows($ret);

}
function id_gas_mail($idu){
  //ID gas --> Mail_Gas_Owner

  $sql = "SELECT maaking_users.email
			FROM retegas_gas INNER JOIN maaking_users ON retegas_gas.id_referente_gas = maaking_users.userid
			WHERE (((retegas_gas.id_gas)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function id_user_mail($idu){
  //ID gas --> Mail_Gas_Owner

  $sql = "SELECT maaking_users.email
			FROM maaking_users
			WHERE (((maaking_users.userid)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}

// POSTINO

function check_coda_mail(){
global $db;

$sql = "SELECT retegas_postino.* FROM retegas_postino WHERE retegas_postino.send_from < NOW() LIMIT 1;";
$ret = $db->sql_query($sql);
$row = $db->sql_fetchrow($ret);
$num_rows = $db->sql_numrows();

if($num_rows>0){
			  log_me(0,0,"POS","COD","Il postino ha coda");
			  //controllo se l'utente vuole ricevere la mail
              if(read_option_text($row["id_utente"],"_USER_OPT_SEND_MAIL")<>"NO"){
                  log_me(0,0,"POS","SND","Da Postino mando a ".$row["to"]);
                  manda_mail($row["from"],$row["from_mail"],$row["to"],$row["to_mail"],$row["subject"],$row["plain_text"],0,0,$row["id_utente"],$row["html_text"]);
			  }
			  // Cancella il vecchio messaggio
			  $sql = "DELETE FROM retegas_postino WHERE retegas_postino.id_postino = '".$row["id_postino"]."' LIMIT 1;";
			  $ret = $db->sql_query($sql);

	}

}
function accoda_mail($array_info){
	//$array_dati[] = array("from"            => $da_chi,
	//                     "from_mail"       => $mail_da_chi,
	//                      "to"              => $verso_chi,
	 //                     "to_mail"         => $mail_verso_chi,
	  //                    "cc"              => "",
	   //                   "cc_mail"         => "",
		//                  "ccn"             => "[ReteGas]",
		 //                 "ccn_mail"        => _SITE_MAIL_LOG,
		  //                "plain_text"      => $msg_plain,
		   //               "html_text"       => $messaggio,
			//              "send_from"       => "",
			 //             "priority"        => "");

global $db;

$from = $array_info["from"];


$query = "INSERT INTO retegas_postino (postino_prenotazione,
		retegas_postino.subject,
		retegas_postino.id_utente,
		retegas_postino.from,
		retegas_postino.from_mail,
		retegas_postino.to,
		retegas_postino.to_mail,
		retegas_postino.ccn,
		retegas_postino.ccn_mail,
		retegas_postino.plain_text,
		retegas_postino.html_text
		) VALUES (
		NOW(),'".$array_info["subject"]."','".$array_info["id_user"]."',
		'".$array_info["from"]."','".$array_info["from_mail"]."',
		'".$array_info["to"]."','".$array_info["to_mail"]."',
		'".$array_info["ccn"]."','".$array_info["ccn_mail"]."',
		'".$array_info["plain_text"]."','".$array_info["html_text"]."');";

//echo "SQL QUERY : <br>".$query."<br>";

$res = $db->sql_query($query);


}


function mail_ai_cassieri($id_gas,$oggetto,$messaggio){
global $db;
$qry="SELECT
                    maaking_users.fullname,
                    maaking_users.email,
                    maaking_users.id_gas,
                    userid
                    FROM
                    maaking_users
                    WHERE
                    maaking_users.id_gas = '$id_gas'
                    ";
        $result = $db->sql_query($qry);
        $lista_destinatari ="";
        while ($row = $db->sql_fetchrow($result)){
            if(leggi_permessi_utente($row["userid"])& perm::puo_gestire_la_cassa){
                $verso_chi[] = $row["fullname"] ;
                $mail_verso_chi[] = $row["email"] ;
                $lista_destinatari .= $row["fullname"]."<br>";
            }

        }// END WHILE

        if(is_empty($oggetto)){$soggetto="["._SITE_NAME."]";}else{$soggetto=$oggetto;}
        manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($messaggio),"MAN",0,_USER_ID,$messaggio);
        log_me(0,_USER_ID,"CAS","EML","$soggetto",0,"Messaggio inviato a:<br>$lista_destinatari<br>".$msg_mail);

};