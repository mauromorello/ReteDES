<?php
function db_dettagli_ordine_senza_ordine(){
global $db;
	
$sql ='SELECT
    retegas_dettaglio_ordini.id_dettaglio_ordini,
    retegas_dettaglio_ordini.id_utenti,
    retegas_dettaglio_ordini.id_articoli,
    retegas_dettaglio_ordini.id_stati,
    retegas_dettaglio_ordini.data_inserimento,
    retegas_dettaglio_ordini.data_convalida,
    retegas_dettaglio_ordini.qta_ord,
    retegas_dettaglio_ordini.id_amico,
    retegas_dettaglio_ordini.id_ordine,
    retegas_dettaglio_ordini.qta_conf,
    retegas_dettaglio_ordini.qta_arr,
    retegas_dettaglio_ordini.timestamp_ord
    FROM
    retegas_ordini
    Right Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
    WHERE
    retegas_ordini.id_ordini IS NULL;';
 
 return $db->sql_numrows($db->sql_query($sql));    
	
}
function db_distribuzione_spesa_senza_dettagli_ordine(){
$sql ='SELECT
retegas_distribuzione_spesa.id_distribuzione,
retegas_distribuzione_spesa.id_riga_dettaglio_ordine,
retegas_distribuzione_spesa.id_amico,
retegas_distribuzione_spesa.qta_ord,
retegas_distribuzione_spesa.qta_arr,
retegas_distribuzione_spesa.data_ins,
retegas_distribuzione_spesa.id_articoli,
retegas_distribuzione_spesa.id_user,
retegas_distribuzione_spesa.id_ordine,
retegas_distribuzione_spesa.id_gas
FROM
retegas_distribuzione_spesa
Left Join retegas_dettaglio_ordini ON retegas_distribuzione_spesa.id_riga_dettaglio_ordine = retegas_dettaglio_ordini.id_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_dettaglio_ordini IS NULL';
  $ret = mysql_query($sql);
 $row = mysql_numrows($ret);
return $row;    
}
function db_referenze_senza_ordine(){
$sql ='SELECT
 *
FROM
retegas_referenze
Left Join retegas_ordini ON retegas_referenze.id_ordine_referenze = retegas_ordini.id_ordini
WHERE
retegas_ordini.id_ordini IS NULL';
  $ret = mysql_query($sql);
 $row = mysql_numrows($ret);
return $row;    
}
function db_amici_senza_referente(){
$sql ='SELECT
retegas_amici.id_amici,
retegas_amici.id_referente,
retegas_amici.nome,
retegas_amici.indirizzo,
retegas_amici.telefono,
retegas_amici.note,
retegas_amici.is_visible
FROM
retegas_amici
Left Join maaking_users ON maaking_users.userid = retegas_amici.id_referente
WHERE
maaking_users.userid IS NULL ';
  $ret = mysql_query($sql);
 $row = mysql_numrows($ret);
return $row;    
}
function db_articoli_senza_listino(){
$sql ='SELECT
retegas_articoli.id_articoli,
retegas_articoli.id_listini,
retegas_articoli.codice,
retegas_articoli.u_misura,
retegas_articoli.misura,
retegas_articoli.descrizione_articoli,
retegas_articoli.qta_scatola,
retegas_articoli.prezzo,
retegas_articoli.ingombro,
retegas_articoli.qta_minima,
retegas_articoli.qta_multiplo,
retegas_articoli.articoli_note
FROM
retegas_articoli
Left Join retegas_listini ON retegas_listini.id_listini = retegas_articoli.id_listini
WHERE
retegas_listini.id_listini IS NULL';
  $ret = mysql_query($sql);
 $row = mysql_numrows($ret);
return $row;    
}
function db_listini_senza_ditte(){
$sql ='SELECT
retegas_listini.id_listini,
retegas_listini.descrizione_listini,
retegas_listini.id_utenti,
retegas_listini.id_tipologie,
retegas_listini.id_ditte,
retegas_listini.data_valido
FROM
retegas_listini
Left Join retegas_ditte ON retegas_ditte.id_ditte = retegas_listini.id_ditte
WHERE
retegas_ditte.id_ditte IS NULL ';
  $ret = mysql_query($sql);
 $row = mysql_numrows($ret);
return $row;    
}
function db_dettagli_senza_articoli(){
$sql ='SELECT
retegas_dettaglio_ordini.id_dettaglio_ordini
FROM
retegas_dettaglio_ordini
Left Join retegas_articoli ON retegas_articoli.id_articoli = retegas_dettaglio_ordini.id_articoli
WHERE
retegas_articoli.id_articoli IS NULL ';
  $ret = mysql_query($sql);
 $row = mysql_numrows($ret);
return $row;    
}

function db_ordini_senza_listino(){
global $db;
    
$sql ='SELECT
        retegas_ordini.id_ordini,
        retegas_ordini.id_listini,
        retegas_ordini.id_utente,
        retegas_ordini.descrizione_ordini,
        retegas_ordini.data_scadenza1,
        retegas_ordini.data_scadenza2,
        retegas_ordini.data_apertura,
        retegas_ordini.data_chiusura
        FROM
        retegas_ordini
        Left Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
        WHERE
            retegas_listini.id_listini IS NULL';
 
 return $db->sql_numrows($db->sql_query($sql));    
    
}

function messaggi_ordine_singolo($ref_table,$id_ordine){
    global $RG_addr, $db;
    
    
      $query="select * from retegas_messaggi WHERE  id_ordine='$id_ordine' ORDER BY id_messaggio DESC";
      $result= $db->sql_query($query);
      $numfields = $db->sql_numfields($result);

      $h_table .= "<table id=\"$ref_table\"><thead><tr>";
      
      for ($i=0; $i < $numfields; $i++) 
      { 
      $h_table .= '<th>'.mysql_field_name($result, $i).'</th>'; 
      }
      $h_table .= "</tr>
                    </thead>
                    <tbody>";
   
      while ($row = mysql_fetch_row($result)) 
      { 
      $h_table .= '<tr><td>'.implode($row,'</td><td>')."</td></tr>"; 
      }
      $h_table .= " </tbody>
                    </table>";
    
      return $h_table;
    
    
}
?>