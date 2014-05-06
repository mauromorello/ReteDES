<?php
function ditte_user($idu){
  //ID USER --> Quante ditte a lui associate
  $sql = "SELECT * FROM `retegas_ditte` WHERE (`retegas_ditte`.`id_proponente`= $idu)";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function ditta_user($idu){
  //ID ditta --> IdUser
  global $db;
  $sql = "SELECT retegas_ditte.id_proponente
			FROM retegas_ditte 
			WHERE (((retegas_ditte.id_ditte)='$idu'));";
  $ret = $db->sql_query($sql);
  $row = $db->sql_fetchrow($ret);
  return $row["id_proponente"];
}
function ditta_nome_from_listino($lis){
	//ID Ditta --> Nome ditta
  $sql = "SELECT retegas_ditte.descrizione_ditte
FROM retegas_ditte INNER JOIN retegas_listini ON retegas_ditte.id_ditte = retegas_listini.id_ditte
WHERE (((retegas_listini.id_listini)=$lis));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0]; 
 }
function ditta_mail_from_listino($lis){
	//ID listino --> mail ditta
  $sql = "SELECT retegas_ditte.mail_ditte
FROM retegas_ditte INNER JOIN retegas_listini ON retegas_ditte.id_ditte = retegas_listini.id_ditte
WHERE (((retegas_listini.id_listini)=$lis));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0]; 
 }
function ditta_indirizzo_from_listino($lis){
	//ID listino --> mail ditta
  $sql = "SELECT retegas_ditte.indirizzo
FROM retegas_ditte INNER JOIN retegas_listini ON retegas_ditte.id_ditte = retegas_listini.id_ditte
WHERE (((retegas_listini.id_listini)=$lis));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0]; 
 }
function ditta_telefono_from_listino($lis){
    //ID listino --> mail ditta
  $sql = "SELECT retegas_ditte.telefono
FROM retegas_ditte INNER JOIN retegas_listini ON retegas_ditte.id_ditte = retegas_listini.id_ditte
WHERE (((retegas_listini.id_listini)=$lis));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0]; 
 }  
   
function ditta_id_from_listino($lis){
   //ID listino --> ID ditta
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_listini`= $lis)";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[4]; 
 }
function ditta_nome($ditta){
	//ID Ditta --> Nome ditta
  global $db;
  $sql = "SELECT * FROM `retegas_ditte` WHERE (`retegas_ditte`.`id_ditte`= $ditta)";
  $ret = $db->sql_query($sql);
  $row = $db->sql_fetchrow($ret);
  return $row["descrizione_ditte"]; 
 }
function ditta_nome_from_id_ordine($id_ordine){
    //ID Ditta --> Nome ditta
  global $db;
  $sql = "SELECT * FROM `retegas_ditte` WHERE (`retegas_ditte`.`id_ditte`= '".ditta_id_from_listino(listino_ordine_from_id_ordine($id_ordine))."')";
  $ret = $db->sql_query($sql);
  $row = $db->sql_fetchrow($ret);
  return $row["descrizione_ditte"]; 
 } 
  
function ditta_data_creazione($ditta){
   
  global $db;
  $sql = "SELECT * FROM `retegas_ditte` WHERE (`retegas_ditte`.`id_ditte`= $ditta)";
  $ret = $db->sql_query($sql);
  $row = $db->sql_fetchrow($ret);
  return $row["data_creazione"]; 
 }