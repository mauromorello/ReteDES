<?php
function proprietario_messaggio_bacheca($idu){
  //ID ditta --> IdUser
  $sql = "SELECT retegas_bacheca.id_utente
			FROM retegas_bacheca 
			WHERE (((retegas_bacheca.id_bacheca)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function bacheca_array_quantita_messaggi(){
global $db;
 
$sql = "SELECT
Count(retegas_bacheca.code_uno),
retegas_bacheca.code_uno
FROM
retegas_bacheca
GROUP BY
retegas_bacheca.code_uno"; 
$result = $db->sql_query($sql);
while ($row = mysql_fetch_row($result)){
//echo "row 0 = $row[0]-- row 1 = $row[1] <br>";    
$lista["$row[1]"] = $row[0];    
	
}

return $lista;    
}

function bacheca_write_commento_ordine_partecipante($id_utente,$id_ordine,$titolo,$commento){
    global $db;
    
    $id_gas = id_gas_user($id_utente);
    $id_listino = id_listino_from_id_ordine($id_ordine);
    $id_ditta = ditta_id_from_listino($id_listino);
    $tipo_messaggio = argomenti::commento;
    $ruolo = ruoli::partecipante;
    $id_des = des_id_des_from_id_gas($id_gas);
    //$commento = sanitize($commento);
    $titolo = sanitize($titolo);
    
    //Cancella la (le) opinioni dell'utente sull'ordine
    $sql_delete = "DELETE from retegas_bacheca WHERE id_utente='$id_utente' AND id_ordine='$id_ordine' AND code_uno='$tipo_messaggio' and code_due='$ruolo';";
    $db->sql_query($sql_delete);
    
    if($titolo<>""){
    
    $sql_insert = 'INSERT INTO `retegas_bacheca` 
                        (`id_bacheca`, 
                         `id_utente`, 
                         `visibility`, 
                         `code_uno`, 
                         `code_due`, 
                         `timbro_bacheca`, 
                         `titolo_messaggio`, 
                         `messaggio`, 
                         `scadenza`, 
                         `id_utente_destinatario`, 
                         `id_ditta`, 
                         `id_listino`, 
                         `id_articolo`, 
                         `id_ordine`, 
                         `tags`, 
                         `id_des`, 
                         `id_gas`, 
                         `lat_bacheca`, 
                         `lng_bacheca`, 
                         `bacheca_address`) 
                         VALUES 
                         (NULL, 
                         \''.$id_utente.'\', 
                         \'1\', 
                         \''.$tipo_messaggio.'\', 
                         \''.$ruolo.'\', 
                         NOW(), 
                         \''.$titolo.'\', 
                         \''.$commento.'\', 
                         \'0000-00-00 00:00:00\', 
                         \'0\', 
                         \''.$id_ditta.'\', 
                         \''.$id_listino.'\', 
                         \'0\', 
                         \''.$id_ordine.'\', 
                         \'\', 
                         \''.$id_des.'\', 
                         \''.$id_gas.'\', 
                         \'0\', 
                         \'0\', 
                         \'\');';
       $db->sql_query($sql_insert);
    } 
    
    
}
function bacheca_write_commento_ordine_referente($id_utente,$id_ordine,$titolo,$commento){
    global $db;
    
    $id_gas = id_gas_user($id_utente);
    $id_listino = id_listino_from_id_ordine($id_ordine);
    $id_ditta = ditta_id_from_listino($id_listino);
    $tipo_messaggio = argomenti::valutazione;
    $ruolo = ruoli::referente;
    $id_des = des_id_des_from_id_gas($id_gas);
    //$commento = sanitize($commento);
    $titolo = sanitize($titolo);
    
    //Cancella la (le) opinioni dell'utente sull'ordine
    $sql_delete = "DELETE from retegas_bacheca WHERE id_utente='$id_utente' AND id_ordine='$id_ordine' AND code_uno='$tipo_messaggio' AND code_due='$ruolo';";
    $db->sql_query($sql_delete);
    
    if($titolo<>""){
    
    $sql_insert = 'INSERT INTO `retegas_bacheca` 
                        (`id_bacheca`, 
                         `id_utente`, 
                         `visibility`, 
                         `code_uno`, 
                         `code_due`, 
                         `timbro_bacheca`, 
                         `titolo_messaggio`, 
                         `messaggio`, 
                         `scadenza`, 
                         `id_utente_destinatario`, 
                         `id_ditta`, 
                         `id_listino`, 
                         `id_articolo`, 
                         `id_ordine`, 
                         `tags`, 
                         `id_des`, 
                         `id_gas`, 
                         `lat_bacheca`, 
                         `lng_bacheca`, 
                         `bacheca_address`) 
                         VALUES 
                         (NULL, 
                         \''.$id_utente.'\', 
                         \'1\', 
                         \''.$tipo_messaggio.'\', 
                         \''.$ruolo.'\', 
                         NOW(), 
                         \''.$titolo.'\', 
                         \''.$commento.'\', 
                         \'0000-00-00 00:00:00\', 
                         \'0\', 
                         \''.$id_ditta.'\', 
                         \''.$id_listino.'\', 
                         \'0\', 
                         \''.$id_ordine.'\', 
                         \'\', 
                         \''.$id_des.'\', 
                         \''.$id_gas.'\', 
                         \'0\', 
                         \'0\', 
                         \'\');';
       $db->sql_query($sql_insert);
    } 
    
    
}
function bacheca_write_commento_ditta_certificante($id_utente,$id_ditta,$titolo,$commento){
    global $db;
    
    $id_gas = id_gas_user($id_utente);
    $id_listino = 0;
    
    $tipo_messaggio = argomenti::certificazione;
    $id_des = des_id_des_from_id_gas($id_gas);
    //$commento = sanitize($commento);
    $titolo = sanitize($titolo);
    
    //Cancella la (le) opinioni dell'utente sull'ordine
    
    if($titolo<>""){
    
    $sql_insert = 'INSERT INTO `retegas_bacheca` 
                        (`id_bacheca`, 
                         `id_utente`, 
                         `visibility`, 
                         `code_uno`, 
                         `code_due`, 
                         `timbro_bacheca`, 
                         `titolo_messaggio`, 
                         `messaggio`, 
                         `scadenza`, 
                         `id_utente_destinatario`, 
                         `id_ditta`, 
                         `id_listino`, 
                         `id_articolo`, 
                         `id_ordine`, 
                         `tags`, 
                         `id_des`, 
                         `id_gas`, 
                         `lat_bacheca`, 
                         `lng_bacheca`, 
                         `bacheca_address`) 
                         VALUES 
                         (NULL, 
                         \''.$id_utente.'\', 
                         \'1\', 
                         \''.$tipo_messaggio.'\', 
                         \''.ruoli::certificante.'\', 
                         NOW(), 
                         \''.$titolo.'\', 
                         \''.$commento.'\', 
                         \'0000-00-00 00:00:00\', 
                         \'0\', 
                         \''.$id_ditta.'\', 
                         \''.$id_listino.'\', 
                         \'0\', 
                         \''.$id_ordine.'\', 
                         \'\', 
                         \''.$id_des.'\', 
                         \''.$id_gas.'\', 
                         \'0\', 
                         \'0\', 
                         \'\');';
       $db->sql_query($sql_insert);
    } 
    
    
}
function bacheca_write_commento_ditta_relazionante($id_utente,$id_ditta,$titolo,$commento){
    global $db;
    
    $id_gas = id_gas_user($id_utente);
    $id_listino = 0;
    $ruolo=ruoli::relazionante;
    $tipo_messaggio = argomenti::relazione;
    $id_des = des_id_des_from_id_gas($id_gas);
    //$commento = sanitize($commento);
    $titolo = sanitize($titolo);
    
    //Cancella la (le) opinioni dell'utente sull'ordine
    
    if($titolo<>""){
    
    $sql_insert = 'INSERT INTO `retegas_bacheca` 
                        (`id_bacheca`, 
                         `id_utente`, 
                         `visibility`, 
                         `code_uno`, 
                         `code_due`, 
                         `timbro_bacheca`, 
                         `titolo_messaggio`, 
                         `messaggio`, 
                         `scadenza`, 
                         `id_utente_destinatario`, 
                         `id_ditta`, 
                         `id_listino`, 
                         `id_articolo`, 
                         `id_ordine`, 
                         `tags`, 
                         `id_des`, 
                         `id_gas`, 
                         `lat_bacheca`, 
                         `lng_bacheca`, 
                         `bacheca_address`) 
                         VALUES 
                         (NULL, 
                         \''.$id_utente.'\', 
                         \'1\', 
                         \''.$tipo_messaggio.'\', 
                         \''.$ruolo.'\', 
                         NOW(), 
                         \''.$titolo.'\', 
                         \''.$commento.'\', 
                         \'0000-00-00 00:00:00\', 
                         \'0\', 
                         \''.$id_ditta.'\', 
                         \''.$id_listino.'\', 
                         \'0\', 
                         \''.$id_ordine.'\', 
                         \'\', 
                         \''.$id_des.'\', 
                         \''.$id_gas.'\', 
                         \'0\', 
                         \'0\', 
                         \'\');';
       $db->sql_query($sql_insert);
    } 
    
    
}
function bacheca_n_messaggi_ditta($id_ditta,$tipo_ruolo){
    global $db;
    
    $sql = "SELECT COUNT(id_bacheca) as num_msg FROM retegas_bacheca WHERE id_ditta='$id_ditta' AND code_due='$tipo_ruolo';";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return $row["num_msg"];
    
}

function bacheca_read_commento_ordine_partecipante($id_utente,$id_ordine){
    global $db;
    
    $ruolo = ruoli::partecipante;
    $tipo = argomenti::commento;
    
    //Cancella la (le) opinioni dell'utente sull'ordine
    $sql_read = "SELECT * from retegas_bacheca WHERE id_utente='$id_utente' AND id_ordine='$id_ordine' AND code_uno='$tipo' AND code_due='$ruolo' LIMIT 1;";
    $res = $db->sql_query($sql_read);
    $row = $db->sql_fetchrow($res);
    return $row["messaggio"];
    
    
}	
function bacheca_read_titolo_commento_ordine_partecipante($id_utente,$id_ordine){
    global $db;
    
    $ruolo = ruoli::partecipante;
    $tipo = argomenti::commento;
    //Cancella la (le) opinioni dell'utente sull'ordine
    $sql_read = "SELECT * from retegas_bacheca WHERE id_utente='$id_utente' AND id_ordine='$id_ordine' AND code_uno='$tipo' AND code_due='$ruolo' LIMIT 1;";
    $res = $db->sql_query($sql_read);
    $row = $db->sql_fetchrow($res);
    return $row["titolo_messaggio"];
    
    
}

function bacheca_read_commento_ordine_referente($id_utente,$id_ordine){
    global $db;
    $ruolo = ruoli::referente;
    $tipo = argomenti::valutazione;
    
    //Cancella la (le) opinioni dell'utente sull'ordine
    $sql_read = "SELECT * from retegas_bacheca WHERE id_utente='$id_utente' AND id_ordine='$id_ordine' AND code_uno='$tipo' AND code_due='$ruolo' LIMIT 1;";
    $res = $db->sql_query($sql_read);
    $row = $db->sql_fetchrow($res);
    return $row["messaggio"];
    
    
}
function bacheca_read_titolo_commento_ordine_referente($id_utente,$id_ordine){
    global $db;
    $ruolo = ruoli::referente;
    $tipo = argomenti::valutazione;
    //Cancella la (le) opinioni dell'utente sull'ordine
    $sql_read = "SELECT * from retegas_bacheca WHERE id_utente='$id_utente' AND id_ordine='$id_ordine' AND code_uno='$tipo' AND code_due='$ruolo' LIMIT 1;";
    $res = $db->sql_query($sql_read);
    $row = $db->sql_fetchrow($res);
    return $row["titolo_messaggio"];
    
    
}

function bacheca_render_fullwidth_messaggio($id_messaggio){
    
    global $db;
    global $RG_lista_argomenti_messaggi;
    
    $sql = "SELECT * FROM retegas_bacheca WHERE id_bacheca='$id_messaggio' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    
    $id_ordine = CAST_TO_INT($row["id_ordine"],0);
    if($id_ordine>0){
          $ordine =" (Ordine #".$row["id_ordine"]." del ".conv_date_from_db(db_val_q("id_ordini",$id_ordine,"data_chiusura","retegas_ordini")).") ";
    }

    if($row["bacheca_address"]<>""){$indirizzo=", locato in <cite>".$row["bacheca_address"]."</cite>";}
    
    
    $info_div="<br>
                <div id=\"msg_".$row["id_bacheca"]."\" style=\"display:none;\">
                <span class=\"small_link\"><strong>".$RG_lista_argomenti_messaggi[$row["code_uno"]]."</strong> #".$row["id_bacheca"].", ruolo : <strong>".$row["code_due"]."</strong>, del ".conv_date_from_db($row["timbro_bacheca"])."$ordine $indirizzo </span><br>
                (".gas_nome($row["id_gas"])." - ".db_val_q("id_des",_USER_ID_DES,"des_descrizione","retegas_des").") 
                </div>";
    
    if($row["id_utente"]==_USER_ID){
        $show_commands ="[<a>EDIT</a>] 
                         [<a>DELETE</a>] 
                         [<a>GEOMAP</a>]";
    }else{
        $show_commands ="";
    }
    
    $h  =  "<div class=\"rg_widget rg_widget_helper\">";
    $h .=  "<h4 style=\"margin-bottom:.4em;\">".$row["titolo_messaggio"].", di ".fullname_from_id($row["id_utente"])."</h4>";
    $h .=  "<span class=\"small_link\">[<a href=\"#\" onclick=\"$('#msg_".$row["id_bacheca"]."').toggle();return false;\">INFO</a>] $show_commands</span>";
    $h .=  $info_div;
    $h .=  "<br>
            <div style=\"height:6em;overflow:auto; border-left:2px solid #ccc; margin-left:2em; padding:.5em;\">".$row["messaggio"]."</div>";
    $h .=  "</div>";
    
    return $h;    
    
}

function bacheca_update_messaggio($id_bacheca,$titolo,$messaggio){
 
    global $db;
    $qry = "UPDATE  `retegas_bacheca` 
                SET  `titolo_messaggio` =  '$titolo',
                     `messaggio` =  '$messaggio'
                WHERE  
                id_bacheca = '$id_bacheca'
                LIMIT 1 ;";
    $res = $db->sql_query($qry);
    
    return;
    
}
function bacheca_delete_messaggio($id_bacheca){
 
    global $db;
    $qry = "DELETE  FROM `retegas_bacheca` 
                WHERE  
                id_bacheca = '$id_bacheca'
                LIMIT 1 ;";
    $res = $db->sql_query($qry);
    
    return;
    
}