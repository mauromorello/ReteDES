<?php
function amici_referente_di_amico($idu){
  //ID USER --> Nome gas
  
  $sql = "SELECT retegas_amici.id_referente
		FROM retegas_amici
		WHERE (((retegas_amici.id_amici)=$idu));";
		$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function amici_nome_di_amico($idu){
  //ID USER --> Nome gas
  
  if(is_empty($idu) or $idu==0){
      return "Me stesso";
      die();
  }
  
  $sql = "SELECT *
        FROM retegas_amici
        WHERE (((retegas_amici.id_amici)=$idu));";
        $ret = mysql_query($sql);
        $row = mysql_fetch_row($ret);

    return $row[2];
 
}
function amici_n_amici_user($idu){
  //ID User --> Quanti amici ha User
  $sql = "SELECT retegas_amici.id_amici FROM retegas_amici WHERE (((retegas_amici.id_referente)='$idu') AND status='1');";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function amici_quanti_per_gas($id_gas){
    Global $db;
    $sql = "SELECT
                retegas_amici.id_referente
                FROM
                retegas_amici
                Inner Join maaking_users ON retegas_amici.id_referente = maaking_users.userid
                WHERE
                maaking_users.id_gas =  '$id_gas' AND
                retegas_amici.is_visible = 1;";
    $res = $db->sql_query($sql);
    $row = $db->sql_numrows($res);
    
    return $row;            
}
function amici_lista_attivi($id_user){
 global $db;  
 global $RG_addr;
      
      $query="SELECT * FROM  retegas_amici WHERE id_referente = '$id_user' 
                AND retegas_amici.is_visible = '1' AND status='1';";   
      $result=$db->sql_query($query);
                
 while ($row = mysql_fetch_array($result)){
      $h .= $row["nome"].", ";
 }   
 
 $h = rtrim($h,", ");
 
 return $h;
 
}
	
?>