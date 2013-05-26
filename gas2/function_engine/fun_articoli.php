<?php
function articoli_n_in_listino($idu){
  //ID listino --> Quanti articoli a lui associate
  $sql = "SELECT * FROM `retegas_articoli` WHERE (`retegas_articoli`.`id_listini`='$idu')";
  $ret = mysql_query($sql);
  $row = mysql_num_rows($ret);
  if(!$row){$row=0;}
  return $row;
}
function articoli_in_ordine($idu){
  //Quanti articoli sono presenti in tutti gli ordini
  $sql = "SELECT
Count(retegas_dettaglio_ordini.id_dettaglio_ordini)
FROM
retegas_dettaglio_ordini
WHERE
retegas_dettaglio_ordini.id_articoli =  '$idu'";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function user_n_articoli($idu){
  //ID user --> quanti articoli ha immesso
  $sql = "SELECT retegas_articoli.*
FROM retegas_articoli INNER JOIN retegas_listini ON retegas_articoli.id_listini = retegas_listini.id_listini
WHERE (((retegas_listini.id_utenti)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function articoli_user($idu){
  //ID articolo --> IdUser
  $sql = "SELECT retegas_listini.id_utenti
			FROM retegas_articoli INNER JOIN retegas_listini ON retegas_articoli.id_listini = retegas_listini.id_listini
			WHERE (((retegas_articoli.id_articoli)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function articolo_id_listino($idu){
  //ID articolo --> Id listino
  $sql = "SELECT retegas_articoli.id_listini
			FROM retegas_articoli 
			WHERE (((retegas_articoli.id_articoli)='$idu'));";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function articolo_codice_quanti_in_listino($codice_articolo,$id_listino){
	global $db;
	$query = "SELECT * FROM retegas_articoli WHERE codice='$codice_articolo' AND id_listini='$id_listino';";
	$result = $db->sql_query($query);
	return $db->sql_numrows($result);
}
function info_line_articolo($art){
$qry="SELECT * FROM retegas_articoli WHERE retegas_articoli.id_articoli='$art' LIMIT 1;";
$ret = mysql_query($qry);
$row = mysql_fetch_row($ret);

$ret = "Art. $art, Codice <b>-$row[2]- : $row[5]</b>, $row[3] $row[4] per Euro $row[7], in scatole da $row[6], acquistabile in multipli di $row[9]";    
return $ret;    
	
}
function articolo_suo_prezzo($id_articolo){
$qry="SELECT * FROM retegas_articoli WHERE retegas_articoli.id_articoli='$id_articolo' LIMIT 1;";
$ret = mysql_query($qry);
$row = mysql_fetch_assoc($ret);

return round($row["prezzo"],4);    

}
function articolo_sua_qmin($id_articolo){
$qry="SELECT * FROM retegas_articoli WHERE retegas_articoli.id_articoli='$id_articolo' LIMIT 1;";
$ret = mysql_query($qry);
$row = mysql_fetch_assoc($ret);

return round($row["qta_minima"],4);    

}
function articolo_univoco($id_articolo){
$qry="SELECT * FROM retegas_articoli WHERE retegas_articoli.id_articoli='$id_articolo' LIMIT 1;";
$ret = mysql_query($qry);
$row = mysql_fetch_assoc($ret);

if($row["articoli_unico"]<>1){
   return false; 
}else{
   return true; 
}
   

}
function articolo_sua_descrizione($id_articolo){
$qry="SELECT * FROM retegas_articoli WHERE retegas_articoli.id_articoli='$id_articolo' LIMIT 1;";
$ret = mysql_query($qry);
$row = mysql_fetch_assoc($ret);

return $row["descrizione_articoli"];    

}
function articolo_suo_codice($id_articolo){
$qry="SELECT * FROM retegas_articoli WHERE retegas_articoli.id_articoli='$id_articolo' LIMIT 1;";
$ret = mysql_query($qry);
$row = mysql_fetch_assoc($ret);

return $row["codice"];    

}
function qta_ord_ordine_articolo($id_ordine,$id_articolo){
    global $db;
    $sql = "SELECT
                Sum(retegas_dettaglio_ordini.qta_ord)
                FROM
                retegas_dettaglio_ordini
                WHERE
                retegas_dettaglio_ordini.id_articoli =  '$id_articolo'
                AND
                id_ordine = '$id_ordine'; ";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return $row[0];
}
	        