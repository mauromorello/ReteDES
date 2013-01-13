<?php
function id_listino_user($idu){
  //ID listino --> ID_user
  
  $sql = "SELECT retegas_listini.id_utenti FROM retegas_listini WHERE (((retegas_listini.id_listini)=$idu));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function id_ordine_user($idu){
  //ID ordine --> ID_user
  
  $sql = "SELECT retegas_ordini.id_utente FROM retegas_ordini WHERE (((retegas_ordini.id_ordini)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function listino_proprietario($idu){
  //ID articolo --> IdUser
  $sql = "SELECT retegas_listini.id_utenti
  FROM retegas_listini
	WHERE (((retegas_listini.id_listini)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function listini_user($idu){
  //ID USER --> Quanti listini a lui associati
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_utenti`= $idu)";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function listini_ditte($idu){
  //ID ditta --> Quanti listini associati ATTIVI
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_ditte`= '$idu') AND data_valido > now() AND tipo_listino=0;";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function listini_ditte_totali($idu){
  //ID ditta --> Quanti listini associati
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_ditte`= '$idu');";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function listino_nome($lis){
	//ID listino --> Nome listino
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_listini`= $lis)";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[1]; 
 }
function listino_tipo($lis){
	//ID listino --> Nome listino
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_listini`= '$lis')";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  
  return $row[6]; 
 }
function listino_tempo_valido($lis){
    //ID listino --> Nome listino
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_listini`= '$lis')";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  //echo $row[5];
  if(gas_mktime(conv_date_from_db($row[5]))>gas_mktime(date("d/m/Y"))){
    return true;
  }else{
    return false;   
  } 
 } 
Function id_listino_from_id_ordine($idu){
 // ID ordine ----> ID listino

$sql = "SELECT retegas_ordini.id_listini FROM retegas_ordini
WHERE (((retegas_ordini.id_ordini)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function listino_is_privato($lis){
    //ID listino --> Nome listino
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_listini`= '$lis')";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  if($row["is_privato"]>0){
       return true;
       die();
  }else{
       return false;
       die();
  }
      
} 	