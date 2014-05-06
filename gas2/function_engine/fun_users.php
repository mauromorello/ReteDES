<?php


/**
 * Esegue il login, settando il cookie sulla macchina di USER
 * Ritorna :
 * 1 = LOGIN OK
 * 2 = EMPTY USERNAME OR PASSWORD
 * 3 = INCORRECT USERNAME OR PASSWORD
 * 4 = USER NON ANCORA ATTIVATO
 * 5 = USER BLOCCATO
 * @param string $username nome utente
 * @param string $password password
 * @param integer $remember 1 = ricorda l'utente per i prossimi login
 * @return integer
 */
function do_login($username,$password,$remember){

         global $db,$id_user;

         if((!$username) || (!$password)){
                if(trim(empty($username))){
                   $user_err= $reqmsg;
                }
                if(empty($password)){
                   $pass_err= $reqmsg;
                }
                //EMPTY USERNAME OR PASSWORD
                return 2;
                exit();
         }


         //CONTROLLO SE ESISTE GIA' PIU' DI UN IP DAL QUALE SI STA CERCANDO DI ACCEDERE
         $RA = $_SERVER['REMOTE_ADDR'];

         $md5_pass = md5($password);
         $sql = $db->sql_query("SELECT * FROM maaking_users WHERE username='$username' AND password='$md5_pass'");
         $login_check = $db->sql_numrows($sql);
         ///////////////////////////////////////////////////////////////////////

         if($login_check > 0){
                 $row = $db->sql_fetchrow($sql);

                 $userid = $row['userid'];
                 $username = $row['username'];
                 $password = $row['password'];
                 $ipaddress = $row['ipaddress'];
                 $isactive = $row['isactive'];
                 $user_permission = $row['user_permission'];
                 $user_options = $row['user_site_option'];

                 //USER NON ANCORA ATTIVATO
                 if($isactive == 0){
                    return 4;
                    exit();
                 }


                 //Utente Sospeso
                 if($isactive == 2){
                    $id_user = $row['userid'];
                    return 5;
                    exit();
                 }

                 //Utente Cancellato
                 if($isactive == 99){
                    return 6;
                    exit();
                 }



                 $lastlogin = explode(" ", $row['lastlogin']);
                 $lastlogin_date =  $lastlogin[0];
                 $lastlogin_time = $lastlogin[1];

                 $info = base64_encode("$userid|$username|$password|$ipaddress|$lastlogin_date|$lastlogin_time|$user_permission|$user_options");
                 if (isset($remember)){
                     //setcookie("user","$info",time()+1209600);
                     setcookie("user","$info",time()+1209600,"/");
                     //setcookie("user","$info",time()+1209600,"/gas2/");
                     //setcookie("user","$info",time()+1209600,"/gas2/ptp/");
                     //setcookie("user","$info",time()+1209600,"/gas2/mobile/");
                 }else{
                     //setcookie("user","$info",0);
                     setcookie("user","$info",0,"/");
                     //setcookie("user","$info",0,"/gas2/");
                     //setcookie("user","$info",0,"/gas2/ptp/");
                     //setcookie("user","$info",0,"/gas2/mobile/");
                 }



                 $db->sql_query("UPDATE maaking_users SET ipaddress='$RA', lastlogin=NOW() WHERE userid='$userid'");


                 //LOGIN OK
                 Return 1;
                 exit;


         }else{


                // LOGIN ERRATO
                return 3;
                exit();
         }
}
function do_login_3($username,$password,$remember){

         global $db,$id_user;

         if((!$username) || (!$password)){
                if(trim(empty($username))){
                   $user_err= $reqmsg;
                }
                if(empty($password)){
                   $pass_err= $reqmsg;
                }
                //EMPTY USERNAME OR PASSWORD
                return 2;
                exit();
         }


         //CONTROLLO SE ESISTE GIA' PIU' DI UN IP DAL QUALE SI STA CERCANDO DI ACCEDERE
         $RA = $_SERVER['REMOTE_ADDR'];

         $md5_pass = ($password);
         $sql = $db->sql_query("SELECT * FROM maaking_users WHERE username='$username' AND password='$md5_pass'");
         $login_check = $db->sql_numrows($sql);
         ///////////////////////////////////////////////////////////////////////

         if($login_check > 0){
                 $row = $db->sql_fetchrow($sql);

                 $userid = $row['userid'];
                 $username = $row['username'];
                 $password = $row['password'];
                 $ipaddress = $row['ipaddress'];
                 $isactive = $row['isactive'];
                 $user_permission = $row['user_permission'];
                 $user_options = $row['user_site_option'];

                 //USER NON ANCORA ATTIVATO
                 if($isactive == 0){
                    return 4;
                    exit();
                 }


                 //Utente Sospeso
                 if($isactive == 2){
                    $id_user = $row['userid'];
                    return 5;
                    exit();
                 }

                 //Utente Cancellato
                 if($isactive == 99){
                    return 6;
                    exit();
                 }



                 $lastlogin = explode(" ", $row['lastlogin']);
                 $lastlogin_date =  $lastlogin[0];
                 $lastlogin_time = $lastlogin[1];

                 $info = base64_encode("$userid|$username|$password|$ipaddress|$lastlogin_date|$lastlogin_time|$user_permission|$user_options");
                 if (isset($remember)){
                     //setcookie("user","$info",time()+1209600);
                     setcookie("user","$info",time()+1209600,"/");
                     //setcookie("user","$info",time()+1209600,"/gas2/");
                     //setcookie("user","$info",time()+1209600,"/gas2/ptp/");
                     //setcookie("user","$info",time()+1209600,"/gas2/mobile/");
                 }else{
                     //setcookie("user","$info",0);
                     setcookie("user","$info",0,"/");
                     //setcookie("user","$info",0,"/gas2/");
                     //setcookie("user","$info",0,"/gas2/ptp/");
                     //setcookie("user","$info",0,"/gas2/mobile/");
                 }



                 $db->sql_query("UPDATE maaking_users SET ipaddress='$RA', lastlogin=NOW() WHERE userid='$userid'");


                 //LOGIN OK
                 Return 1;
                 exit;


         }else{


                // LOGIN ERRATO
                return 3;
                exit();
         }
}


function do_login_4($email,$password,$remember){

         global $db,$id_user;

         if((!$email) || (!$password)){
                if(trim(empty($email))){
                   $user_err= $reqmsg;
                }
                if(empty($password)){
                   $pass_err= $reqmsg;
                }
                //EMPTY USERNAME OR PASSWORD
                return 2;
                exit();
         }


         //CONTROLLO SE ESISTE GIA' PIU' DI UN IP DAL QUALE SI STA CERCANDO DI ACCEDERE
         $RA = $_SERVER['REMOTE_ADDR'];

         $md5_pass = ($password);
         $sql = $db->sql_query("SELECT * FROM maaking_users WHERE (email='$email' OR username='$email') AND password='$md5_pass'");
         $login_check = $db->sql_numrows($sql);
         ///////////////////////////////////////////////////////////////////////

         if($login_check > 0){
                 $row = $db->sql_fetchrow($sql);

                 $userid = $row['userid'];
                 $username = $row['username'];
                 $password = $row['password'];
                 $ipaddress = $row['ipaddress'];
                 $isactive = $row['isactive'];
                 $user_permission = $row['user_permission'];
                 $user_options = $row['user_site_option'];

                 //USER NON ANCORA ATTIVATO
                 if($isactive == 0){
                    return 4;
                    exit();
                 }


                 //Utente Sospeso
                 if($isactive == 2){
                    $id_user = $row['userid'];
                    return 5;
                    exit();
                 }

                 //Utente Cancellato
                 if($isactive == 99){
                    return 6;
                    exit();
                 }



                 $lastlogin = explode(" ", $row['lastlogin']);
                 $lastlogin_date =  $lastlogin[0];
                 $lastlogin_time = $lastlogin[1];

                 $info = base64_encode("$userid|$username|$password|$ipaddress|$lastlogin_date|$lastlogin_time|$user_permission|$user_options");
                 if (isset($remember)){
                     //setcookie("user","$info",time()+1209600);
                     setcookie("user","$info",time()+1209600,"/");
                     //setcookie("user","$info",time()+1209600,"/gas2/");
                     //setcookie("user","$info",time()+1209600,"/gas2/ptp/");
                     //setcookie("user","$info",time()+1209600,"/gas2/mobile/");
                 }else{
                     //setcookie("user","$info",0);
                     setcookie("user","$info",0,"/");
                     //setcookie("user","$info",0,"/gas2/");
                     //setcookie("user","$info",0,"/gas2/ptp/");
                     //setcookie("user","$info",0,"/gas2/mobile/");
                 }



                 $db->sql_query("UPDATE maaking_users SET ipaddress='$RA', lastlogin=NOW() WHERE userid='$userid'");


                 //LOGIN OK
                 Return 1;
                 exit;


         }else{


                // LOGIN ERRATO
                return 3;
                exit();
         }
}

function Logout($user) {

         unset($user);
         //setcookie("user","",time()-3600);
         //setcookie("user","",time()-3600,"/gas2/");
         //setcookie("user","",time()-3600,"/gas2");
         setcookie('user','',time()-3600,'/');
         //setcookie("user","",time()-3600,"/gas2/mobile");
         //setcookie("user","",time()-3600,"/gas2/ptp");
         //setcookie("user","",time()-3600,"/gas2/mobile/");
         //setcookie("user","",time()-3600,"/gas2/ptp/");
         go("start");

}

function Logout_new() {
         global $user;
         unset($user);
         setcookie("user","",time()-3600,"/");
}


function is_logged_in($user) {
    global $db;

    $read_cookie = explode("|", base64_decode($user));
    $userid = addslashes($read_cookie[0]);
    $user_id = ($read_cookie[0]);
    $passwd = $read_cookie[2];
    $userid = intval($userid);

    if ($userid != "" AND $passwd != "") {
        $result = $db->sql_query("SELECT * FROM maaking_users WHERE userid='$userid'");
    $row = $db->sql_fetchrow($result);
        $pass = $row['password'];
    if($pass == $passwd && $pass != "") {

           define("_USER_PERMISSIONS",$row["user_permission"]);
           define("_USER_OPTIONS",$row["user_site_option"]);
           define("_USER_LOGGED_IN",true);
           define("_USER_ID",$user_id);
           define("_USER_ID_GAS",$row["id_gas"]);
           define("_USER_FULLNAME",$row["fullname"]);
           define("_USER_USERNAME",$row["username"]);
           define("_USER_PASSWORD",$row["password"]);
           return 1;
    }
    }



    define("_USER_LOGGED_IN",false);
    return 0;
}


function gas_n_user_data($id_gas,$data){
  //ID ditta --> Quanti listini associati
  //echo "$data<br>";

  global $db;
  $sql = "SELECT * FROM maaking_users WHERE id_gas='$id_gas' AND regdate<'$data' AND isactive='1';";
  $ret = $db->sql_query($sql);
  $row = $db->sql_numrows($ret);
  //echo "ROW : $row<br>$sql<br>";
  return $row;
}


//VECCHIA FUNZIONE ACTIVATE
function Activate(){
         global $user, $db,  $code,$site_email,$site_name;

         $code = intval($code);

         if(isset($code) != 0){

             $result = $db->sql_query("SELECT userid FROM maaking_users WHERE code='$code'");

             if($db->sql_numrows($result) == 1){

                   $row = $db->sql_fetchrow($result);

                   $sql = $db->sql_query("UPDATE maaking_users SET isactive='1',code='0' WHERE userid='$row[userid]'");

                    $message = message_mail_nuovo_utente();
                    #set email headers  to aviod spam filters
                    $email = id_user_mail($row["userid"]);
                    $headers = message_headers($site_name,$site_email);
                    $subject = _SITE_NAME.", attivazione account.";
                    $go1 =  mail($email,$subject,$message, $headers);


                   c1_go_away("?q=1");

             }else{
                   echo _VALIDATE_ERROR;
             }

         }
}

//PERMESSI
function checkPermission($user, $permission) {
        if($user & $permission) {
            return true;
        } else {
            return false;
        }
    }

function user_non_attivi(){
  //QUANTI USER NON ATTIVI
  $sql = "SELECT * FROM `maaking_users` WHERE (`maaking_users`.`isactive` <> '1')";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;

}
function user_non_attivati(){
  //QUANTI USER NON ATTIVI
  $sql = "SELECT * FROM `maaking_users` WHERE (`maaking_users`.`isactive` = '0')";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;

}
function gas_user($idu){
  //ID USER --> Nome gas

  $sql = "SELECT retegas_gas.descrizione_gas
        FROM maaking_users INNER JOIN retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
        WHERE (((maaking_users.userid)=$idu));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function gas_n_user($idu){
  //ID ditta --> Quanti listini associati
  $sql = "SELECT * FROM maaking_users WHERE (id_gas='$idu');";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function gas_n_user_Act($idu){
  //ID ditta --> Quanti listini associati
  $sql = "SELECT * FROM maaking_users WHERE (id_gas='$idu') AND isactive=1;";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function gas_n_user_comunica($idu){
  //ID ditta --> Quanti listini associati
  $sql = "SELECT * FROM maaking_users WHERE (id_gas='$idu') AND (user_site_option AND ".opti::acconsento_comunica_tutti." );";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function user_level($idu){
  //ID gas --> Mail_Gas_Owner

  $sql = "SELECT maaking_users.user_level
            FROM maaking_users
            WHERE (((maaking_users.userid)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function user_status($idu){
  //ID gas --> Mail_Gas_Owner

  $sql = "SELECT maaking_users.isactive
            FROM maaking_users
            WHERE (((maaking_users.userid)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function id_proprio_referente_retegas($gas){
  $sql = "SELECT retegas_gas.id_referente_gas
        FROM retegas_gas
        WHERE (((retegas_gas.id_gas)='$gas'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function fullname_from_id($idu){
  //ID user --> fullname

  $sql = "SELECT maaking_users.fullname
        FROM maaking_users
        WHERE (((maaking_users.userid)='$idu'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function indirizzo_user_from_id($idu){
  //ID user --> fullname

  $sql = "SELECT *
        FROM maaking_users
        WHERE (((maaking_users.userid)='$idu'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_array($ret);
  return $row["country"].", ".$row["city"];
}
function via_user_from_id($idu){
  //ID user --> fullname

  $sql = "SELECT *
        FROM maaking_users
        WHERE (((maaking_users.userid)='$idu'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_array($ret);
  return $row["country"];
}
function citta_user_from_id($idu){
  //ID user --> fullname

  $sql = "SELECT *
        FROM maaking_users
        WHERE (((maaking_users.userid)='$idu'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_array($ret);
  return $row["city"];
}
function lat_lon_from_id($idu){
  //ID user --> fullname

  $sql = "SELECT maaking_users.user_gc_lat
        FROM maaking_users
        WHERE (((maaking_users.userid)='$idu'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function lon_lat_from_id($idu){
  //ID user --> fullname

  $sql = "SELECT maaking_users.user_gc_lng
        FROM maaking_users
        WHERE (((maaking_users.userid)='$idu'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function email_from_id($idu){
  //ID user --> fullname

  $sql = "SELECT maaking_users.email
        FROM maaking_users
        WHERE (((maaking_users.userid)='$idu'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function telefono_from_id($idu){
  //ID user --> fullname

  $sql = "SELECT maaking_users.tel
        FROM maaking_users
        WHERE (((maaking_users.userid)='$idu'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function leggi_opzioni_sito_utente($id_user){
 global $db;
  $sql = "SELECT maaking_users.user_site_option
        FROM maaking_users
        WHERE (((maaking_users.userid)='$id_user'));";
        $ret = $db->sql_query($sql);
        $row = $db->sql_fetchrow($ret);
  return $row[0];

}
function leggi_permessi_utente($id_user){
 global $db;
  $sql = "SELECT maaking_users.user_permission
        FROM maaking_users
        WHERE (((maaking_users.userid)='$id_user'));";
        $ret = $db->sql_query($sql);
        $row = $db->sql_fetchrow($ret);
  return $row[0];

}

function rg_score($id_utente){
    $ordini_gestiti = ordini_user($id_utente) * 3;
    $ordini_partecipati = ordini_user_partecipato($id_utente);


    $score = $ordini_gestiti + $ordini_partecipati;


    return round($score);
}

function utenti_attesa_attivazione($id_gas){
 global $db;
 $res = $db->sql_query("SELECT * FROM maaking_users WHERE isactive='0' AND id_gas='$id_gas'");
 return $db->sql_numrows($res);

}

function leggi_permessi_default($id_gas){
 global $db;
  $sql = "SELECT retegas_gas.default_permission
        FROM retegas_gas
        WHERE (((retegas_gas.id_gas)='$id_gas'));";
        $ret = $db->sql_query($sql);
        $row = $db->sql_fetchrow($ret);
  return $row[0];

}

// USER ACTIVITY
function update_activity($user){
    global $db;
    if (is_logged_in($user)) {
        $cookie_read = explode("|", base64_decode($user));
        $id_user = $cookie_read[0];
        $db->sql_query("UPDATE maaking_users SET maaking_users.last_activity = NOW() WHERE maaking_users.userid ='$id_user';");

    }


}
function crea_lista_user_attivi($minuti){
global $db;

$query = " select maaking_users.fullname
            FROM maaking_users
            WHERE
            (time_to_sec(timediff(now(),maaking_users.last_activity))/60)<$minuti;";

$result = $db->sql_query($query);
while ($row = $db->sql_fetchrow($result)){

$lista .=$row[0].", ";

}

$lista = rtrim($lista,", ");

return $lista;
}
function crea_numero_user_attivi_totali($minuti){
global $db;

$query = " select maaking_users.fullname
            FROM maaking_users
            WHERE
            (time_to_sec(timediff(now(),maaking_users.last_activity))/60)<$minuti;";

$result = $db->sql_query($query);
$lista = $db->sql_numrows($result);



return $lista;
}

function crea_lista_user_attivi_pubblica($minuti){
global $db;

$query = " select maaking_users.fullname
            FROM maaking_users
            WHERE
            (time_to_sec(timediff(now(),maaking_users.last_activity))/60)<$minuti;";

            //Parte con il controllo delle options
            //AND
            //(maaking_users.user_site_option & ".opti::visibile_a_tutti.")

$result = $db->sql_query($query);
while ($row = $db->sql_fetchrow($result)){

$lista .=$row[0].", ";

}

$lista = rtrim($lista,", ");

return $lista;
}
function crea_lista_user_attivi_pubblica_gas($minuti,$gas){
global $db;

$query = " select maaking_users.fullname
            FROM maaking_users
            WHERE
            (time_to_sec(timediff(now(),maaking_users.last_activity))/60)<$minuti
            AND maaking_users.id_gas = '$gas';";

            //Parte vecchia
            //AND
            //(maaking_users.user_site_option & ".opti::visibile_al_proprio_gas.")

$result = $db->sql_query($query);
while ($row = $db->sql_fetchrow($result)){
$lista .=$row[0].", ";
}

$lista = rtrim($lista,", ");

return $lista;
}
function crea_lista_gas_attivi($minuti){
global $db;

$query = " SELECT
retegas_gas.descrizione_gas,
Count(maaking_users.userid)
FROM
maaking_users
Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
WHERE
            (time_to_sec(timediff(now(),maaking_users.last_activity))/60)<'$minuti'
GROUP BY
retegas_gas.descrizione_gas";

$result = $db->sql_query($query);
while ($row = mysql_fetch_row($result)){

$lista .=$row[0].": <b>".$row[1]."</b><br> ";

}

//$lista = rtrim($lista,", ");

return $lista;
}

function is_chat_popolated($minuti){
global $db;

$query =    "SELECT *
            FROM retegas_messaggi
            WHERE
            (time_to_sec(timediff(now(),timbro))/60)<$minuti
            AND
            tipo='CHT';";

$result = $db->sql_query($query);
$lista = $db->sql_numrows($result);



return $lista;
}

// LISTA PERMESSI
function utenti_scheda_permessi($id_user){

$user_permission = leggi_permessi_utente($id_user);
// GESTIONE DEI PERMESSI ----------------------------------------------------
if($user_permission & perm::puo_creare_ordini){$checked_1="Gestire ordini";}
if($user_permission & perm::puo_partecipare_ordini){$checked_2="Partecipare agli ordini";}
if($user_permission & perm::puo_creare_gas){$checked_3="Gestire il proprio GAS";}
if($user_permission & perm::puo_creare_ditte){$checked_4="Inserire nuove ditte";}
if($user_permission & perm::puo_creare_listini){$checked_5="Inserire nuovi listini e relativi articoli";}
if($user_permission & perm::puo_mod_perm_user_gas){$checked_6="Modificare i permessi di altri utenti del proprio GAS";}
if($user_permission & perm::puo_avere_amici){$checked_7="Inserire una propria rubrica di amici";}
if($user_permission & perm::puo_postare_messaggi){$checked_8="Inserire messaggi in bacheca";}
if($user_permission & perm::puo_eliminare_messaggi){$checked_9="Gestire la bacheca del proprio GAS";}
if($user_permission & perm::puo_gestire_utenti){$checked_10="Gestire gli utenti del proprio GAS";}
if($user_permission & perm::puo_vedere_tutti_ordini){$checked_11="Supervisionare gli ordini";}
if($user_permission & perm::puo_gestire_la_cassa){$checked_12="<b>Gestire la cassa</b>";}
if($user_permission & perm::puo_operare_con_crediti){$checked_13="Operare con crediti di altri utenti in caso sia il referente ordine";}
if($user_permission & perm::puo_vedere_retegas){$checked_14="Gestire il proprio DES";}

return '
<div>
    <h4>'.fullname_from_id($id_user).' può :
    </h4>
    <div>
    '.$checked_1.'
    </div>
    <div>
    '.$checked_2.'
    </div>
    <div>
    '.$checked_4.'
    </div>
    <div>
    '.$checked_5.'
    </div>
    <div>
    '.$checked_7.'
    </div>
    <div>
    '.$checked_8.'
    </div>
    <div>
    '.$checked_3.'
    </div>
    <div>
    '.$checked_6.'
    </div>
    <div>
    '.$checked_9.'
    </div>
    <div>
    '.$checked_10.'
    </div>
    <div>
    '.$checked_11.'
    </div>
    <div>
    '.$checked_12.'
    </div>
    <div>
    '.$checked_13.'
    </div>
    <div>
    '.$checked_14.'
    </div>
</div><br>';


}

function crea_lista_user_mio_gas($gas,$limit=20){



$my_query = "SELECT * FROM maaking_users WHERE id_gas='$gas' AND isactive='1';";

      //echo $my_query;

      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;
      global $RG_addr;

      $result = $db->sql_query($my_query);



        $riga=0;

        $h_table='<ul style="list-style-type: none; padding: 0; margin: 0;" id="list_filter_12">';

         while ($row = mysql_fetch_array($result)){
         $riga++;

        if(opti::acconsento_comunica_tutti && leggi_opzioni_sito_utente($row["userid"])){
          //  $pal = '<a><IMG SRC="'.$RG_addr["img_pallino_verde"].'"  style="height:10px; width:10px;vertical_align:middle;border=0;" TITLE=""></a>';
        }else{
          //   $pal = '<a><IMG SRC="'.$RG_addr["img_pallino_rosso"].'"  style="height:10px; width:10px;vertical_align:middle;border=0;" TITLE=""></a>';

        }

        $h_table.=  '<li>
        '.$pal.'
        <a href="'.$RG_addr["pag_users_form"].'?id_utente='.mimmo_encode($row["userid"]).'"><b>'.$row["fullname"].'</b></a>, '.$row["tel"].', '.$row["country"].' '.$row["city"].'
                     </li>
                    ';
         }//end while


       $h_table .='</ul>';

return $h_table;

}

function numero_tipo_utenti_gas($id_gas,$stato){
    Global $db;
    $sql = "SELECT *
            FROM maaking_users
            WHERE
            id_gas = '$id_gas'
            AND
            isactive='$stato';";
    $res = $db->sql_query($sql);
    $row = $db->sql_numrows($res);

    return $row;
}