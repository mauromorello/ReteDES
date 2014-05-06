<?php
function tipologia_nome_from_listino($lis){
	//ID Ditta --> Nome ditta
  $sql = "SELECT retegas_tipologia.descrizione_tipologia
FROM retegas_listini INNER JOIN retegas_tipologia ON retegas_listini.id_tipologie = retegas_tipologia.id_tipologia
WHERE (((retegas_listini.id_listini)='$lis'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
 }
 function tipologia_id_from_listino($lis){
    //ID Ditta --> Nome ditta
  $sql = "SELECT retegas_tipologia.id_tipologia
FROM retegas_listini INNER JOIN retegas_tipologia ON retegas_listini.id_tipologie = retegas_tipologia.id_tipologia
WHERE (((retegas_listini.id_listini)='$lis'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
 }
?>