<?php

if (eregi("utenti_render.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}


function utenti_render_register_form(){
        global $messaggio_referente, $tel, $username, $password, $password2, $email, $gasappartenenza, $fullname, $user_taken_err, $consenso, $email_taken_err;

        $disclaimer = strip_tags(read_option_note(0,"DISCLAIMER"));



        $help_username='Il tuo nome utente.<br>Un nome utente � un nome semplice che identifica il tuo accesso. La sua lunghezza massima � di 15 caratteri. Il sito distingue tra lettere maiuscole e minuscole.';
        $help_password='La tua password.<br>Pu� contenere lettere e  numeri, il sito distingue tra lettere minuscole e maiuscole.';
        $help_ripeti_password ='Ripetendo la password siamo sicuri che sia quella voluta, e che non contenga errori di battitura.';
        $help_gasappartenenza ='Scegli il gas con il quale ti sei messo in contatto e del quale vuoi fare parte.';

        $help_messaggio_referente ='Scrivi qua una tua (non obbligatoria) comunicazione al referente.';

        $help_email ='Inserisci una email valida. Al termine della registrazione ti arriver� una mail di conferma.';
        $help_tel ='Inserisci un tuo valido recapito telefonico.';
        $help_consenso='Leggi attentamente, anche se a prima vista pu� risultare noioso. In questo disclaimer vi � anche la parte sul trattamento dei dati personali.';
        $help_fullname = 'Il tuo vero Nome e cognome.<br>ReteDES.it si basa sulla fiducia e sulla collaborazione reciproca, � quindi fondamentale conoscere personalmente tutti i partecipanti';

        $content_disclaimer = (str_replace("\\r\\n","",read_option_note(0,"DISCLAIMER")));


        $h = '<div class="retegas_form ui-corner-all">

        <p>Tutti i campi sono obbligatori</p>

        <form id="register" name="scheda_registrazione" method="POST" action="index_start.php#3" >


        <div>
        <h4>1</h4>
        <label for="username">Scegli il tuo nome utente, max 15 caratteri.</label>
        <input type="text" name="username" value="'.$username.'" size="20" maxlength="15"></input>
        <h5 title="'.$help_username.'">Inf.</h5>
        </div>

        <div>
        <h4 title="'.$help_password.'">2</h4>
        <label for="password">...scegli una tua password segreta:</label>
        <input id="password" type="password" name="password" value="'.$password.'" size="20"></input>
        <span id="result"></span>
        <h5 title="'.$help_password.'">Inf.</h5>
        </div>

        <div>
        <h4>3</h4>
        <label for="password2">ripeti la password scelta</label>
        <input  type="password" name="password2" value="'.$password2.'" size="20"></input>
        <h5 title="'.$help_password2.'">Inf.</h5>
        </div>

        <div>
        <h4>4</h4>
        <label for="email">inserisci la tua email:</label>
        <input type="email" name="email" value="'.$email.'" size="20"></input>
        <h5 title="'.$help_email.'">Inf.</h5>
        </div>

        <div>
        <h4>5</h4>
        <label for="fullname">inserisci il tuo nome e cognome</label>
        <input type="text" name="fullname" value="'.$fullname.'" size="20"></input>
        <h5 title="'.$help_fullname.'">Inf.</h5>
        </div>

        <div>
        <h4>6</h4>
        <label for="tel">..ed un tuo recapito telefonico</label>
        <input type="tel" name="tel" value="'.$tel.'" size="20"></input>
        <h5 title="'.$help_tel.'">Inf.</h5>
        </div>

        <div>
        <h4>7</h4>

        <label for="consenso">...dichiaro di aver letto, compreso ed accettato il qui seguente disclaimer</label>
        <input type="checkbox" name="consenso" value="1"></input>
        <span><textarea name="Disclaimer" wrap="virtual" align="justify" >'.$content_disclaimer.'</textarea></span>
        <h5 title="'.$help_consenso.'">Inf.</h5>
        </div>

        <div>
        <h4>8</h4>
        <label for="gasappartenenza">..faccio parte del seguente GAS:</label>
        <select name= "gasappartenenza" id="lista_gas" >';

        $result = mysql_query("SELECT * FROM retegas_gas ORDER BY id_gas ASC");
        $totalrows = mysql_num_rows($result);
        $h .= "<option value=\"-1\">Selezionare GAS</option>";

        while ($row = mysql_fetch_array($result)){
                $idgas = $row['id_gas'];
                $descrizionegas = $row['descrizione_gas'];
                if ($idgas==$gasappartenenza){$agg=" selected ";}else{$agg=null;}
        $h .= "<option value=\"".$idgas ."\" $agg>".$descrizionegas ."  </option>";
         }//end while
        $h .= "<option value=\"0\">Voglio creare un nuovo GAS !</option>";
        $h .='</select>
        <h5 title="'.$help_gasappartenenza.'">Inf.</h5>
        </div>

        <div>
        <h4>9</h4>

        <label for="messaggio_referente">Scrivi un tuo messaggio di presentazione.</label>
        <span><textarea id="messaggio_referente" class ="" name="messaggio_referente" cols="40" style="display:inline-block;">'.$messaggio_referente.'</textarea></span>
        <h5 title="'.$help_messaggio_referente.'">Inf.</h5>
        </div>

        <div>
        <h4>10</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Invia la richiesta" align="center" >
        <input type="hidden" name="do" value="do_register">
        </div>


        </form>
        </div>';








return $h;

}


function utenti_option_sito($id_user){
//echo "USER OPT = $user_options";
//$opz = leggi_opzioni_sito_utente($id_user);
$user_options = leggi_opzioni_sito_utente($id_user);

if($user_options<>$opz){$msg="ATTENZIONE : la nuova configurazione di opzioni si attiver� dal prossimo LOGIN";}
//$user_options=$opz;

// GESTIONE DEI PERMESSI ----------------------------------------------------
if($user_options & opti::visibile_al_proprio_gas){$checked_1=" CHECKED ";}
if($user_options & opti::visibile_a_tutti){$checked_2=" CHECKED ";}
if($user_options & opti::aggiornami_nuovi_ordini){$checked_3=" CHECKED ";}
if($user_options & opti::avvisami_scadenza_3gg){$checked_4=" CHECKED ";}
if($user_options & opti::acconsento_comunica_tutti){$checked_5=" CHECKED ";}
if($user_options & opti::stampe_senza_intestazioni){$checked_6=" CHECKED ";}
if($user_options & opti::sito_senza_header){$checked_7=" CHECKED ";}

$h_table .='
<div class="rg_widget rg_widget_helper">
<h3>Opzioni sito</h3>
<form  name="Modifica permessi" method="POST" action="utenti_option_sito.php">
<table>
    <tr class="odd">
        <th>Divento visibile quando sono online (solo mio GAS)</th>
        <td><input '.$checked_1.'type="checkbox" name="v_m_g" value="'.opti::visibile_al_proprio_gas.'"</td>
    </tr>
    <tr class="odd">
        <th >Divento visibile quando sono online (tutta la RETEGAS.AP)</th>
        <td><input '.$checked_2.'type="checkbox" name="v_t_r" value="'.opti::visibile_a_tutti.'"</td>
    </tr>
    <tr class="odd">
        <th>Aggiornami per mail sui nuovi ordini</th>
        <td><input '.$checked_3.'type="checkbox" name="a_n_o" value="'.opti::aggiornami_nuovi_ordini.'"</td>
    </tr>
    <tr class="odd">
        <th >Avvisami 3 giorni prima che si chiuda un ordine al quale io posso partecipare o sto gi� partecipando
        <br>
        <cite>
        (Opzione attualmente non funzionante)
        </cite>
        </th>
        <td><input '.$checked_4.'type="checkbox" name="a_s_3" value="'.opti::avvisami_scadenza_3gg.'"</td>
    </tr>
    <tr class="odd">
        <th>Acconsento a ricevere mail dal sito da parte di altri utenti iscritti<br>
        <cite>(Attenzione : se si toglie questa opzione, si verr� esclusi dalla mailing-list del sito.)
        </cite>
        </th>
        <td><input '.$checked_5.'type="checkbox" name="a_c_t" value="'.opti::acconsento_comunica_tutti.'"</td>
    </tr>
    <tr class="odd">
        <th>Stampe senza intestazioni</th>
        <td><input '.$checked_6.'type="checkbox" name="s_s_i" value="'.opti::stampe_senza_intestazioni.'"</td>
    </tr>
    <tr class="odd">
        <th>Pagine sito senza intestazione (pi� spazio per i dati)</th>
        <td><input '.$checked_7.'type="checkbox" name="s_s_h" value="'.opti::sito_senza_header.'"</td>
    </tr>
<table>


<input type="hidden" name="do"  value="change_user_options">
<input type="hidden" name="id_utente_opzioni"  value="'.$id_user.'">
<center>
<input class="large green awesome" style="margin:20px;" type="submit" value="Salva le modifiche">
</center>
</form>
</div>

';
return $h_table;

}
function utenti_option_sito_v2($id_user){
    global $RG_addr;

    if(_USER_OPT_SEND_MAIL=="SI"){
        $vis_opt_mail = "<span style=\"color:green;\"><strong>OPZIONE ATTIVATA</strong></span>";
    }else{
        $vis_opt_mail = "<span style=\"color:red;\">OPZIONE NON ATTIVA</span>";
    }

    if(_USER_OPT_NO_HEADER=="SI"){
        $vis_opt_snh = "<span style=\"color:green;\"><strong>OPZIONE ATTIVATA</strong></span>";
    }else{
        $vis_opt_snh = "<span style=\"color:red;\">OPZIONE NON ATTIVA</span>";
    }

    $h .= '<table style="font-size:16px;">
            <thead>
            <tr>
            <th style="width:50%">OPZIONE</th>
            <th style="width:10%">&nbsp</th>
            <th style="width:50%">STATO</th>
            </tr>
            </thead>
            <tbody>';

    $h .= '<tr>';
    $h .= '<td>NON VOGLIO ricevere mail dal sito</td>';
    $h .= '<td><div id="opt_mail"></div></td>';
    $h .= '<td><div id="ajax_opt_mail">'.$vis_opt_mail.'</div></td>';
    $h .= '</tr>';

    $h .= '<tr class="odd">';
    $h .= '<td>Pagine del sito senza intestazione (per computer con schermo piccolo)</td>';
    $h .= '<td><div id="opt_snh"></div></td>';
    $h .= '<td><div id="ajax_opt_snh">'.$vis_opt_snh.'</div></td>';
    $h .= '</tr>';

    $h .= '<tr>';
    $h .= '<td>Stampe senza intestazione (per fare stare pi� dati sui fogli)</td>';
    $h .= '<td><div id="opt_print"></div></td>';
    $h .= '<td><div id="ajax_opt_print"></div></td>';
    $h .= '</tr>';


    $h .= '</tbody>';
    $h .= '<tfoot>';
    $h .= '</tfoot>';
    $h .= '</table>';

    return $h;
}

function utenti_permessi_sito($c1){

$user_permission = leggi_permessi_utente($c1);

// GESTIONE DEI PERMESSI ----------------------------------------------------
if($user_permission & perm::puo_creare_ordini){$checked_1=" CHECKED ";}
if($user_permission & perm::puo_partecipare_ordini){$checked_2=" CHECKED ";}
if($user_permission & perm::puo_creare_gas){$hidden_3=perm::puo_creare_gas;}
if($user_permission & perm::puo_creare_ditte){$checked_4=" CHECKED ";}
if($user_permission & perm::puo_creare_listini){$checked_5=" CHECKED ";}
if($user_permission & perm::puo_mod_perm_user_gas){$hidden_6=perm::puo_mod_perm_user_gas;}
if($user_permission & perm::puo_avere_amici){$checked_7=" CHECKED ";}
if($user_permission & perm::puo_postare_messaggi){$checked_8=" CHECKED ";}
if($user_permission & perm::puo_eliminare_messaggi){$hidden_9=perm::puo_eliminare_messaggi;}
if($user_permission & perm::puo_gestire_utenti){$hidden_10=perm::puo_gestire_utenti;}
if($user_permission & perm::puo_vedere_tutti_ordini){$hidden_11=perm::puo_vedere_tutti_ordini;}
if($user_permission & perm::puo_gestire_la_cassa){$hidden_12=perm::puo_gestire_la_cassa;}
if($user_permission & perm::puo_operare_con_crediti){$checked_13=" CHECKED ";}
if($user_permission & perm::puo_vedere_retegas){$hidden_14=perm::puo_vedere_retegas;}
if($user_permission & perm::puo_gestire_retegas){$hidden_15=perm::puo_gestire_retegas;}

$h_table .='
<div class="rg_widget rg_widget_helper">
<h3>Permessi utente</h3>
<form  name="Modifica permessi" method="POST" action="">
<table>
    <tr class="odd">
        <th>Pu� creare ordini</th>
        <td><input '.$checked_1.'type="checkbox" name="p_c_o" value="'.perm::puo_creare_ordini.'"</td>
    </tr>
    <tr class="odd">
        <th >Pu� partecipare agli ordini</th>
        <td><input '.$checked_2.'type="checkbox" name="p_p_o" value="'.perm::puo_partecipare_ordini.'"</td>
    </tr>
        <tr class="odd">
        <th >Pu� creare nuove ditte</th>
        <td><input '.$checked_4.'type="checkbox" name="p_c_d" value="'.perm::puo_creare_ditte.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� creare nuovi listini</th>
        <td><input '.$checked_5.'type="checkbox" name="p_c_l" value="'.perm::puo_creare_listini.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� avere amici (...)</th>
        <td><input '.$checked_7.'type="checkbox" name="p_a_a" value="'.perm::puo_avere_amici.'"</td>
    </tr>
        </tr>
        <tr class="odd">
        <th >Pu� scrivere in bacheca</th>
        <td><input '.$checked_8.'type="checkbox" name="p_s_b" value="'.perm::puo_postare_messaggi.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� operare con crediti da referente ordine</th>
        <td><input '.$checked_13.'type="checkbox" name="p_o_c" value="'.perm::puo_operare_con_crediti.'"</td>
    </tr>
<table>

<input type="hidden" name="p_c_g"  value="'.$hidden_3.'">
<input type="hidden" name="p_m_p"  value="'.$hidden_6.'">
<input type="hidden" name="p_e_m"  value="'.$hidden_9.'">
<input type="hidden" name="p_g_u"  value="'.$hidden_10.'">
<input type="hidden" name="p_v_t_o"  value="'.$hidden_11.'">
<input type="hidden" name="p_g_c"  value="'.$hidden_12.'">
<input type="hidden" name="p_v_rg"  value="'.$hidden_14.'">
<input type="hidden" name="p_g_rg"  value="'.$hidden_15.'">

<input type="hidden" name="do"  value="change_user_permissions">
<input type="hidden" name="id_utente_permessi"  value="'.$c1.'">
<center>
<input class="large green awesome" style="margin:20px;" type="submit" value="Salva le modifiche">
</center>
</form>
</div>

';

return $h_table;

}
function utenti_permessi_sito_zeus($c1){
    global $RG_addr;

    $user_permission = leggi_permessi_utente($c1);

        // GESTIONE DEI PERMESSI ----------------------------------------------------
if($user_permission & perm::puo_creare_ordini){$checked_1=" CHECKED ";}
if($user_permission & perm::puo_partecipare_ordini){$checked_2=" CHECKED ";}
if($user_permission & perm::puo_creare_gas){$checked_3=" CHECKED ";}
if($user_permission & perm::puo_creare_ditte){$checked_4=" CHECKED ";}
if($user_permission & perm::puo_creare_listini){$checked_5=" CHECKED ";}
if($user_permission & perm::puo_mod_perm_user_gas){$checked_6=" CHECKED ";}
if($user_permission & perm::puo_avere_amici){$checked_7=" CHECKED ";}
if($user_permission & perm::puo_postare_messaggi){$checked_8=" CHECKED ";}
if($user_permission & perm::puo_eliminare_messaggi){$checked_9=" CHECKED ";}
if($user_permission & perm::puo_gestire_utenti){$checked_10=" CHECKED ";}
if($user_permission & perm::puo_vedere_tutti_ordini){$checked_11=" CHECKED ";}
if($user_permission & perm::puo_gestire_la_cassa){$checked_12=" CHECKED ";}
if($user_permission & perm::puo_operare_con_crediti){$checked_13=" CHECKED ";}
if($user_permission & perm::puo_vedere_retegas){$checked_14=" CHECKED ";}
if($user_permission & perm::puo_gestire_retegas){$checked_15=" CHECKED ";}

$h_table .='
 <div class="rg_widget rg_widget_helper">
<h3>Permessi utente</h3>
<form  name="Modifica permessi avanzati" method="POST" action="">
<table>

<tr>
        <th style="text-align:left;">PERMESSI SEMPLICI</th>
        <td>&nbsp</td>
    </tr>
        <tr class="odd">
        <th>Pu� creare ordini</th>
        <td><input '.$checked_1.'type="checkbox" name="p_c_o" value="'.perm::puo_creare_ordini.'"</td>
    </tr>
    <tr class="odd">
        <th >Pu� partecipare agli ordini</th>
        <td><input '.$checked_2.'type="checkbox" name="p_p_o" value="'.perm::puo_partecipare_ordini.'"</td>
    </tr>
        <tr class="odd">
        <th >Pu� creare nuove ditte</th>
        <td><input '.$checked_4.'type="checkbox" name="p_c_d" value="'.perm::puo_creare_ditte.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� creare nuovi listini</th>
        <td><input '.$checked_5.'type="checkbox" name="p_c_l" value="'.perm::puo_creare_listini.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� avere amici</th>
        <td><input '.$checked_7.'type="checkbox" name="p_a_a" value="'.perm::puo_avere_amici.'"</td>
    </tr>
    </tr>
    <tr class="odd">
        <th >Pu� scrivere in bacheca</th>
        <td><input '.$checked_8.'type="checkbox" name="p_s_b" value="'.perm::puo_postare_messaggi.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� operare con crediti da referente ordine</th>
        <td><input '.$checked_13.'type="checkbox" name="p_o_c" value="'.perm::puo_operare_con_crediti.'"</td>
    </tr>


    </tr>
    <tr>
        <th style="text-align:left;">PERMESSI AVANZATI</th>
        <td>&nbsp</td>
    </tr>
    <tr class="odd">
        <th >Pu� gestire il proprio GAS</th>
        <td><input '.$checked_3.'type="checkbox" name="p_c_g" value="'.perm::puo_creare_gas.'"</td>
    </tr>
        <tr class="odd">
        <th >Pu� modificare permessi utenti proprio GAS</th>
        <td><input '.$checked_6.'type="checkbox" name="p_m_p" value="'.perm::puo_mod_perm_user_gas.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� eliminare messaggi visibili dal proprio GAS</th>
        <td><input '.$checked_9.'type="checkbox" name="p_e_m" value="'.perm::puo_eliminare_messaggi.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� gestire utenti</th>
        <td><input '.$checked_10.'type="checkbox" name="i_n_u" value="'.perm::puo_gestire_utenti.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� vedere tutti gli ordini</th>
        <td><input '.$checked_11.'type="checkbox" name="p_v_t_o" value="'.perm::puo_vedere_tutti_ordini.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� gestire la cassa</th>
        <td><input '.$checked_12.'type="checkbox" name="p_g_c" value="'.perm::puo_gestire_la_cassa.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� vedere statistiche di ReteGAS</th>
        <td><input '.$checked_14.'type="checkbox" name="p_v_rg" value="'.perm::puo_vedere_retegas.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Pu� GESTIRE Tutta Retegas.AP (Se ti togli questa opzione non potrai pi� rimetterla)</th>
        <td><input '.$checked_15.'type="checkbox" name="p_g_rg" value="'.perm::puo_gestire_retegas.'"</td>
    </tr>

<table>
<input type="hidden" name="do"  value="change_user_permissions_zeus">
<input type="hidden" name="id_utente_permessi"  value="'.$c1.'">
<input class="large black awesome" style="margin:20px;text-align:right" type="submit" value="Salva">
</form>
</div>
';

return $h_table;
}

function utenti_form_public($id,$gas){

     global $db,$RG_addr,$id_user;
  // QUERY




      $my_query="SELECT * FROM maaking_users WHERE  (userid='$id') LIMIT 1";

      // SQL NOMI DEI CAMPI
      $d1="userid";
      $d2="fullname";
      $d3="indirizzo";
      $d4="country";
      $d5="city";
      $d6="email";
      $d7="tel";
      $d8="profile";
      $d9="id_gas";

      // TITOLO TABELLA
      $titolo_tabella="Scheda pubblica utente";

      // INTESTAZIONI CAMPI
      $h1="ID";
      $h2="Nome";
      $h3="";
      $h4="Indirizzo";
      $h5="Citt�";
      $h6="mail";
      $h7="telefono";
      $h8="note";
      $h9="GAS";


      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------


      $result = mysql_query($my_query);
      if(mysql_numrows($result)==0){
          $h_table .= "<div class=\"rg_widget rg_widget_helper\">
            <div style=\"margin-bottom:16px;\">Nessun utente con questo codice</div>
             ";
          return $h_table;
          exit;
      }

      $row = mysql_fetch_array($result);

      //$h_table .= ditte_menu_2($id,$id_user);



         // VALORI DELLE CELLE da DB---------------------
              $c1 = $row["$d1"];
              $c2 = $row["$d2"];
              $c3 = $row["$d3"];
              $c4 = $row["$d4"];
              $c5 = $row["$d5"];
              $c6 = $row["$d6"];
              $c7 = $row["$d7"];
              $c8 = $row["$d8"];
              $c9 = $row["$d9"];

              $user_permission = $row["user_permission"];

       if (($c9<>$gas) & (user_level($id_user)<>5)){
          $c3 =  $c4 = $c5 = $c6 = $c7 = $c8 = "Non disponibile";

       }
       $c9 = gas_nome($c9);

if(read_option_text($id,"_USER_OPT_SEND_MAIL")<>"NO"){
    $submit="Scrivi un messaggio e premi <input class=\"large magenta awesome\" style=\"margin-top:1em;\" type=\"submit\" value=\"Invia\">";
    $textarea ="<textarea rows=\"5\" name=\"msg_mail\" cols=\"30\" class=\"ckeditor\"></textarea>";
}else{
    $submit = "&nbsp;</h4>";
    $textarea = "<h3>Questo utente non vuole ricevere mail dal sito.</h3>";
}

$h_table .= "<div class=\"rg_widget rg_widget_helper\">
            <h3>$titolo_tabella</h3>
             ";
$h_table .=  "<table>
        <tr  class=\"odd sinistra\">
            <th>$h2</th>
            <td>$c2</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>$h4</th>
            <td>$c4</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>$h5</th>
            <td>$c5</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>$h6</th>
            <td>$c6</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>$h7</th>
            <td>$c7</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>$h9</th>
            <td>$c9</td>
        </tr>
        <tr class=\"odd sinistra\">
            <form method=\"POST\" action=\"\">
            <th $col_1>$submit</th>
            <td $col_2>$textarea
            <input type=\"hidden\" name=\"do\" value=\"send_mail\">
            <input type=\"hidden\" name=\"id\" value=\"".mimmo_encode($id)."\">
            </td>
            </form>
        </tr>
        </table>
        </div>";
      // END TABELLA ----------------------------------------------------------------------------

return $h_table;

}
function utenti_form_private($id){

     global $db,$RG_addr;
  // QUERY





      $my_query="SELECT * FROM maaking_users WHERE  (userid='$id') LIMIT 1";

      // SQL NOMI DEI CAMPI
      $d1="userid";
      $d2="fullname";
      $d3="indirizzo";
      $d4="country";
      $d5="city";
      $d6="email";
      $d7="tel";
      $d8="profile";
      $d9="id_gas";

      // TITOLO TABELLA
      $titolo_tabella="Scheda personale";

      // INTESTAZIONI CAMPI
      $h1="ID";
      $h2="Nome";
      $h3="";
      $h4="Indirizzo";
      $h5="Citt�";
      $h6="mail";
      $h7="telefono";
      $h8="Note";
      $h9="GAS";

      //  LARGHEZZA E CLASSI COLONNE
      $col_1="";
      $col_2="";





      // OPZIONI

      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;

      $result = mysql_query($my_query);
      if(mysql_numrows($result)==0){
          $h_table .= "<div class=\"rg_widget rg_widget_helper\">
            <div style=\"margin-bottom:16px;\">Nessun utente con questo codice</div>
             ";
          return $h_table;
          exit;
      }

      $row = mysql_fetch_array($result);

      //$h_table .= ditte_menu_2($id,$id_user);



         // VALORI DELLE CELLE da DB---------------------
              $c1 = $row["$d1"];
              $c2 = $row["$d2"];
              $c3 = $row["$d3"];
              $c4 = $row["$d4"];
              $c5 = $row["$d5"];
              $c6 = $row["$d6"];
              $c7 = $row["$d7"];
              $c8 = $row["$d8"];
              $c9 = $row["$d9"];
              $c10= conv_date_from_db($row["regdate"]);
              $c11= conv_datetime_from_db($row["lastlogin"]);
              if((int)($row["user_gc_lat"])>0){
                  $c12 = "Indirizzo correttamente riconosciuto";
              }else{
                  $c12 = "Indirizzo NON riconosciuto";
              }

              $user_permission = $row["user_permission"];


       $c9 = gas_nome($c9);


$h_table .= "<div class=\"rg_widget rg_widget_helper\">
            <h3>$titolo_tabella</h3>
             ";
$h_table .=  "<table>

        <tr  class=\"odd\">
            <th $col_1>$h2</th>
            <td $col_2>$c2</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h4</th>
            <td $col_2>$c4</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h5</th>
            <td $col_2>$c5</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Geolocalizzazione</th>
            <td $col_2>$c12</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h6</th>
            <td $col_2>$c6</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h7</th>
            <td $col_2>$c7</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h9</th>
            <td $col_2>$c9</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Data di registrazione</th>
            <td $col_2>$c10</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Ultimo accesso eseguito</th>
            <td $col_2>$c11</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Lingua selezionata</th>
            <td $col_2>"._USER_LANGUAGE."</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>Abilitazioni</th>
            <td $col_2>".utenti_scheda_permessi($id)."</td>
        </tr>
        </table>
";
      // END TABELLA ----------------------------------------------------------------------------

return $h_table;

}

function utenti_form_public_small($id,$gas){

     global $db,$RG_addr,$id_user;
  // QUERY




      $my_query="SELECT * FROM maaking_users WHERE  (userid='$id') LIMIT 1";

      // SQL NOMI DEI CAMPI
      $d1="userid";
      $d2="fullname";
      $d3="indirizzo";
      $d4="country";
      $d5="city";
      $d6="mail";
      $d7="tel";
      $d8="profile";
      $d9="id_gas";

      // TITOLO TABELLA
      $titolo_tabella="Scheda pubblica utente";

      // INTESTAZIONI CAMPI
      $h1="ID";
      $h2="Nome";
      $h3="";
      $h4="Indirizzo";
      $h5="Citt�";
      $h6="mail";
      $h7="telefono";
      $h8="note";
      $h9="GAS";

      //  LARGHEZZA E CLASSI COLONNE
      $col_1="";
      $col_2="";





      // OPZIONI

      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;

      $result = mysql_query($my_query);
      if(mysql_numrows($result)==0){
          $h_table .= "<div class=\"rg_widget rg_widget_helper\">
            <div style=\"margin-bottom:16px;\">Nessun utente con questo codice</div>
             ";
          return $h_table;
          exit;
      }

      $row = mysql_fetch_array($result);

      //$h_table .= ditte_menu_2($id,$id_user);



         // VALORI DELLE CELLE da DB---------------------
              $c1 = $row["$d1"];
              $c2 = $row["$d2"];
              $c3 = $row["$d3"];
              $c4 = $row["$d4"];
              $c5 = $row["$d5"];
              $c6 = $row["$d6"];
              $c7 = $row["$d7"];
              $c8 = $row["$d8"];
              $c9 = $row["$d9"];

              $user_permission = $row["user_permission"];

       if (($c9<>$gas) & (user_level($id_user)<>5)){
          $c3 =  $c4 = $c5 = $c6 = $c7 = $c8 = "Non disponibile";

       }
       $c9 = gas_nome($c9);


$h_table .= "<div class=\"rg_widget rg_widget_helper\">
            <h3>$titolo_tabella</h3>
             ";
$h_table .=  "<table>
        <tr  class=\"odd\">
            <th $col_1>$h2</th>
            <td $col_2>$c2</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h4</th>
            <td $col_2>$c4</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h5</th>
            <td $col_2>$c5</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h6</th>
            <td $col_2>$c6</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h7</th>
            <td $col_2>$c7</td>
        </tr>
        <tr class=\"odd\">
            <th $col_1>$h9</th>
            <td $col_2>$c9</td>
        </tr>
        </table>
        </div>

        ";
      // END TABELLA ----------------------------------------------------------------------------

return $h_table;

}

function utenti_render_form_edit($id_user){

        global $db;

        global $fullname,
               $indirizzo,
               $citta,
               $mail,
               $telefono;

        $query = "SELECT * FROM maaking_users WHERE userid='$id_user' LIMIT 1;";
        $res = $db->sql_query($query);
        $row = $db->sql_fetchrow($res);

        if(!isset($fullname)){
            $fullname   = $row["fullname"];
        }
        if(!isset($indirizzo)){
            $indirizzo  = $row["country"];
        }
        if(!isset($citta)){
            $citta      = $row["city"];
        }
        if(!isset($mail)){
            $mail       = $row["email"];
        }
        if(!isset($telefono)){
            $telefono   = $row["tel"];
        }

        $help_fullname='Ricorda che ReteDES richiede un nome reale e completo.
        Se da verifiche risultasse non essere coerente con la realt�, ReteDES o chi da essa delegato potr�
        eliminare l\'account senza alcun preavviso.<br>
        In caso di dubbi consultare il Disclaimer (disclaimer.retegas.info)';
        $help_indirizzo='Dopo aver inserito la via e la citt�, clicca su \'CERCA\',<br/> se l\'indirizzo viene accettato compare un messaggio';
        $help_citta='La tua citt�.';
        $help_mail ='La tua mail. Verr� usata per tutte le comunicazioni da parte di ReteDES';
        $help_telefono ='Il tuo Recapito telefonico. Vale lo stesso discorso come descritto nel \'nome utente\'';


        $h = '
        <div class="rg_widget rg_widget_helper">
        <div class="ui-state-error ui-corner-all padding_6px ui-widget-content">
        Per cambiare username, gas di appartenenza contattare il proprio responsabile reteDES.<br>
        Per cambiare password usare la pagina apposita.<br>
        Inserendo un indirizzo valido (basta anche solo la citt�), verr� posizionato un pallino rosso approssimativo sulla mappa del proprio GAS.
        </div>
        <br>

        <h3>Modifica i tuoi dati personali</h3>

        <form name="modifica ditta" method="POST" action="" class="retegas_form">


        <div>
        <h4>1</h4>
        <label for="fullname">Puoi modificare il tuo nome...</label>
        <input type="text" name="fullname" value="'.$fullname.'" size="50"></input>
        <h5 title="'.$help_fullname.'">Inf.</h5>
        </div>

        <div>
        <h4>2</h4>
        <label for="indirizzo">..o il tuo indirizzo..<br>LEGGI le INF qua a destra !!</label>

        <input id="address1" type="text" name="indirizzo" value="'.$indirizzo.'" size="50"></input>
        <h5 title="'.$help_indirizzo.'">Inf.</h5>

        </div>


        <div>
        <h4>3</h4>
        <label for="citta">...o la tua citt�..</label>
        <div id="panel" style="display:inline;">
            <input id="address2" type="text" name="citta" value="'.$citta.'" size="50"></input>
            <input type="button" value="Cerca" onclick="codeAddress()">
            <input id="lat" type="hidden" name="lat" value="">
            <input id="lng" type="hidden" name="lng" value="">
        </div>
        <h5 title="'.$help_citta.'">Inf.</h5>
        <div id="ir" style="display:block;">'.$indirizzo_OK.'</div>
        </div>
       <div id="map-canvas" style="width:200px;height:200px;display:inline-block;"></div>

        <div>
        <h4>4</h4>
        <label for="mail">..oppure puoi cambiare la tua mail</label>
        <input type="email" name="mail" value="'.$mail.'" size="50"></input>
        <h5 title="'.$help_mail.'">Inf.</h5>
        </div>


        <div>
        <h4>6</h4>
        <label for="telefono">.. qui modifichi il tuo telefono</label>
        <input type="text" name="telefono" value="'.$telefono.'" size="50"></input>
        <h5 title="'.$help_telefono.'">Inf.</h5>
        </div>

        <div>
        <h4>7</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Salva le modifiche !" align="center" >
        <input type="hidden" name="do" value="mod">
        </div>


        </form>
        </div>';


        return $h;

    }
function utenti_render_form_password($id_user){

        global $db;



        $h = '
        <div class="ui-state-error ui-corner-all padding_6px ui-widget-content">
        Una volta cambiata la password, effettuare nuovamente il login.
        </div>
        <br>
        <div class="retegas_form ui-corner-all">
        <h3>Modifica la mia password</h3>

        <form name="modifica ditta" method="POST" action="">

        '.render_form_element_text(1,"old_pwd",$old_pwd,"Inserisci la tua password attuale.","La tua password abituale (quella che usi per il login.)").'
        '.render_form_element_password(2,"new_pwd1",$new_pwd1,"Qui la nuova password","La password che userai da ora in poi").'
        '.render_form_element_password(3,"new_pwd2",$new_pwd2,"E qui ripeti la nuova password","Ripeti la password che userai da qui in poi").'

        <div>
        <h4>7</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Salva la nuova password !" align="center" >
        <input type="hidden" name="do" value="pwd">
        </div>


        </form>
        </div>';


        return $h;

    }

function utenti_gestione_widgets($id_user){
//echo "USER OPT = $user_options";
//$opz = leggi_opzioni_sito_utente($id_user);

//widgets
$wi = array (1 => "Ordini Chiusi",
             2 => "Ordini Aperti",
             3 => "Ordini Futuri",
             4 => "Feedback fornitori",
             5 => "Grafico distribuzione utilizzo sito",
             6 => "Utenti OnLine in tempo reale",
             7 => "Messaggi di servizio da ReteGas.AP",
             8 => "NON DISPONIBILE",
             9 => "Alerts Utente",
             10 => "Tutti gli ordini (Aperti, chiusi, futuri)",
             11 => "Ordini ai quali partecipo o gestisco qualcosa",
             12 => "Utenti del mio GAS",
             13 => "Movimenti di cassa personali",
             14 => "Ordine sott'occhio",
             15 => "IDS - Indice di solidariet�");

$numero_widgets = count($wi);

$user_widgets = unserialize(base64_decode(read_option_text($id_user,"WGO")));

//print_r($user_widgets);

if(!$user_widgets){$user_widgets=array(7,9);};

$checked = "CHECKED";
foreach ($user_widgets as $position => $item) :

$en = "";
$n = $wi[$item];

switch ($item){
           case 7:
           $h[] ='<tr class="odd sinistra">
                            <th>'.$n.'</th>
                            <td><input '.$checked.' '.$en.' type="hidden" name="wdg[]" value="'.$item.'">Non eliminabile</td>
                        </tr>';
            break;

            case 9:
           $h[] ='<tr class="odd sinistra">
                            <th>'.$n.'</th>
                            <td><input '.$checked.' '.$en.' type="hidden" name="wdg[]" value="'.$item.'">Non eliminabile</td>
                        </tr>';
            break;
            default:


            $h[] ='<tr class="odd sinistra">
                            <th>'.$n.'</th>
                            <td><input '.$checked.' '.$en.' type="checkbox" name="wdg[]" value="'.$item.'"></td>
                        </tr>';
            break;
        }





endforeach;


$checked = "";
for($i=1;$i<=$numero_widgets;$i++){

if(!in_array($i,$user_widgets)){
    $checked = "";
    switch ($i){
           case 7:
           $n = $wi[$i];
           $checked = "CHECKED";
           $h[] ='<tr class="odd sinistra">
                            <th>'.$n.'</th>
                            <td><input '.$checked.' '.$en.' type="hidden" name="wdg[]" value="'.$i.'">Non eliminabile</td>
                        </tr>';
            break;
            case 9:
           $n = $wi[$i];
           $checked = "CHECKED";
           $h[] ='<tr class="odd sinistra">
                            <th>'.$n.'</th>
                            <td><input '.$checked.' '.$en.' type="hidden" name="wdg[]" value="'.$i.'">Non eliminabile</td>
                        </tr>';
            break;
            default:
                $n = $wi[$i];


                $h[] ='<tr class="odd sinistra">
                    <th>'.$n.'</th>
                    <td><input '.$checked.' '.$en.' type="checkbox" name="wdg[]" value="'.$i.'"></td>
                    </tr>';
            break;
        }

}

}



$h_table .='
<div class="rg_widget rg_widget_helper">
<h3>Gestione elementi HomePage</h3>
<div style=""><h5>L\'aggiunta di molti elementi alla propria homepage pu� influire sul suo tempo di caricamento. Ogni elemento ha delle caratteristiche preimpostate,
tra le quali il fatto di comparire aperto oppure chiuso. Alcuni elementi non possono essere rimossi (ad esempio i messaggi di servizio ReteGas.AP).<br>
<strong>Il servizio � sperimentale, in caso di malfunzionamenti contattarmi a retegas.ap@gmail.com</strong></h5></div>
<form  name="Modifica widgets" method="POST" action="">
<table>';


foreach ($h as $position => $item) : $h_table .= $item; endforeach;

$h_table .='</table>


<input type="hidden" name="do"  value="change_widgets">
<center>
<input class="large green awesome" style="margin:20px;" type="submit" value="Salva le modifiche">
</center>
</form>
</div>

';
return $h_table;

}
