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
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_ditte`= '$idu') AND data_valido > now() AND tipo_listino<>1;";
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
function listino_opz_usage($lis){
    //ID listino --> Nome listino
  $sql = "SELECT * FROM `retegas_listini` WHERE (`retegas_listini`.`id_listini`= $lis)";
  $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[8]; 
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

function listini_compatibili($old_listino,$id_listino){
        global $db;
        $log .="Listino VECCHIO =".$old_listino." Listino NUOVO = $id_listino, ID Ordine = $id_ordine<br>"; 
         //echo "CONTROLLO  $id_listino --> $old_listino<br>";
         $check=false;
         
         if ($old_listino<>$id_listino){
         
         
         $riga=0;
         $qry = "SELECT retegas_articoli.codice,retegas_articoli.id_articoli FROM retegas_articoli WHERE retegas_articoli.id_listini = '$old_listino' ORDER BY retegas_articoli.id_articoli DESC;";
         $ret = $db->sql_query($qry);
         while ($row = mysql_fetch_array($ret)){
            $riga++;
            $corrispondenze["old"][$riga] = $row["id_articoli"];
            $corrispondenze["cod"][$riga] = $row["codice"];
            $pacco_old = $pacco_old . $row["codice"];
               
         }
         $crc_old=crc16($pacco_old);   
         $log .= "CRC OLD: -".$crc_old."-<br>"; 
         $riga=0;         
         $qry = "SELECT retegas_articoli.codice,retegas_articoli.id_articoli FROM retegas_articoli WHERE retegas_articoli.id_listini = '$id_listino'  ORDER BY retegas_articoli.id_articoli DESC;";
         $ret = $db->sql_query($qry);
         while ($row = mysql_fetch_array($ret)){
            $riga++;
            $corrispondenze["new"][$riga] = $row["id_articoli"];
            $corrispondenze["cod_old"][$riga] = $row["codice"];
            $pacco_new = $pacco_new . $row["codice"];
            //echo "corrispondenze[\"new\"][$riga] : ".$row["id_articoli"]."<br>";     
         }
         $crc_new=crc16($pacco_new);
         $log .= "CRC new: -".$crc_new."-<br>";
      
        if ($crc_new==$crc_old){
            $log .= "CRC CHECK : OK <br>";
            //echo "CRC CHECK : OK $id_listino --> $old_listino<br>";
            $check=true;
        }else{
            $check=false;      
        }// se i listini sono uguali
     }// se il listino e' cambiato 
     else{
            $check=true;    
            $msg .= "Il listino rimarrà lo stesso di prima<br>";
     }
    return $check;
    
} 	