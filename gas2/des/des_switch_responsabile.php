<?php


// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     pussa_via();
}

if($do=="do_switch"){

$id_gas = CAST_TO_INT($id_gas,0);
$id_responsabile_gas = CAST_TO_INT($id_responsabile_gas,0);

//Nuovo utente al gas
$sql = "UPDATE retegas_gas SET id_referente_gas='$id_responsabile_gas' WHERE id_gas='$id_gas' LIMIT 1";
$db->sql_query($sql);

//NUOVO GAS ALL'UTENTE
$sql = "UPDATE maaking_users SET id_gas='$id_gas' WHERE userid='$id_responsabile_gas' LIMIT 1";
$db->sql_query($sql);

$fn = fullname_from_id($id_responsabile_gas);
$ng = gas_nome($id_gas);

$msg="Al GAS : $ng L'utente : $fn";

log_me(0,_USER_ID,"DES","MOD","Cambio referente GAS",0,$msg);

}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Gestione Responsabile GAS";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = des_menu_completo(_USER_ID);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");
$r->javascripts_header[]=java_head_select2();
$r->javascripts[]="<script>
        $(document).ready(function() { $('#id_responsabile_gas').select2(); });
    </script>";





if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else{
    if(!empty($msg)){$r->messaggio = $msg;}
}
//Contenuto

$t.='   <div>
        <h4>1</h4>
        <label for="gasappartenenza">Nuovo responsabile GAS</label>
        <select name= "id_responsabile_gas" id="id_responsabile_gas" >';

        $result = mysql_query("SELECT fullname, userid, maaking_users.id_gas
                                FROM maaking_users
                                INNER JOIN retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
                                WHERE
                                (
                                retegas_gas.id_des =  '"._USER_ID_DES."'
                                OR
                                retegas_gas.id_des = '0'
                                )
                                AND isactive<2
                                AND maaking_users.userid<>0
                                AND maaking_users.userid<>retegas_gas.id_referente_gas
                                ORDER BY userid ASC
                                ");
        $totalrows = mysql_num_rows($result);
        $t .= "<option value=\"-1\">Selezionare un Utente</option>";

        while ($row = mysql_fetch_array($result)){
                $userid = $row['userid'];
                $descrizionegas = $row['fullname']." ".gas_nome($row["id_gas"]);
                if ($userid==$gasappartenenza){$agg=" selected ";}else{$agg=null;}
        $t .= "<option value=\"".$userid ."\" $agg>".$descrizionegas ."  </option>";
         }//end while
        $t .='</select>

        </div>';


$h .= " <div class=\"rg_widget rg_widget_helper\">
                <h2>Nuovo responsabile gas, in "._USER_DES_NAME."</h2>
                <p>Il nuovo responsabile di un gas si può segliere tra gli utenti appartenenti al proprio DES, ma che non sono già gravati da questo incarico in un gas.</p>
                <p>Questa operazione non tiene conto di tutti i dati presenti in retedes riguardanti l'utente e il gas selezionato. Tutti i movimenti rimarrano riferiti all'utente (Cassa, ordini), e di conseguenza compariranno come movimenti effettivi (anche se passati) del gas di destinazione.</p>
                <p>NON viene inviata nessuna mail, l'operazione viene comunque loggata.</p>
                <form class=\"retegas_form\" method=\"POST\" action=\"\">
                $t

                <fieldset>
                <h4>2</h4>
                <p>Seleziona il gas</p>
                <table id=\"output_1\">

                <thead>

                <tr>
                <th>Descrizione</th>


                </tr>

                 <thead>

                 <tbody>";

       //$o1 =   $db->sql_query("SELECT id_gas FROM maaking_users WHERE userid = ". $id_user );

       $result = $db->sql_query("SELECT * FROM retegas_gas WHERE id_des = "._USER_ID_DES.";");



       //$outp = mysql_fetch_row($o1);

       $riga=0;

         while ($row = $db->sql_fetchrow($result)){

         $riga++;


              $idgas = $row["id_gas"];
              $descrizionegas = $row['descrizione_gas'];
              $sedegas = $row['sede_gas'];
              $responsabile = fullname_from_id($row["id_referente_gas"]);
              $nomegas = $row['nome_gas'];
              $websitegas = $row['website_gas'];
              $mailgas = $row['mail_gas'];
              $n_ute= gas_n_user($idgas);
              $utenti_des = utenti_gestori_des($idgas);


              $max_cambia_plus = "<a class=\"awesome green option\" href=\"?do=max_plus&id_gas=$idgas\">+</a>";
              $max_cambia_minus = "<a class=\"awesome red option\" href=\"?do=max_minus&id_gas=$idgas\">-</a>";
              $max = read_option_max_giorni_gas($idgas);

              if($max==0){$max="0 <span class=\"small_link\">(Illimitato)</span>";}

              $h.= "<tr>";
              $h.= "<td $col_2><input type=\"radio\" name=\"id_gas\" value=\"$idgas\"/> $descrizionegas - $responsabile</td>";
              $h.= "</tr>";

            }//end while



         $h.= "</tbody>
                </table>
                </fieldset>
                <input type=\"hidden\", name=\"do\" value=\"do_switch\">
                <input type=\"submit\", name=\"submit\" value=\"SALVA LA SELEZIONE\">
                </form>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r)
?>