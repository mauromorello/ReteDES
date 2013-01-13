<?php
function gas_nome($idu){
  //ID gas--> Nome gas
  
  $sql = "SELECT retegas_gas.descrizione_gas
		FROM retegas_gas
		WHERE (((retegas_gas.id_gas)='$idu'));";
		$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function gas_estremi($idu){
  //ID gas--> Nome gas
  
  $sql = "SELECT retegas_gas.nome_gas
        FROM retegas_gas
        WHERE (((retegas_gas.id_gas)='$idu'));";
        $ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}

function gas_address_from_id($gas){
  $sql = "SELECT retegas_gas.sede_gas
		FROM retegas_gas
		WHERE (((retegas_gas.id_gas)='$gas'));";
		$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];    
};

function id_gas_user($idu){
  //ID USER --> ID_Gas
  
  $sql = "SELECT retegas_gas.id_gas
		FROM maaking_users INNER JOIN retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
		WHERE (((maaking_users.userid)='$idu'));";
		$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
}
function utenti_gruppo_user($idu){
  //ID Gruppo --> Quanti utenti nel gruppo
  $sql = "SELECT * FROM `maaking_users` WHERE (`maaking_users`.`id_gas`= '$idu');";
  $ret = mysql_query($sql);
  $row = mysql_numrows($ret);
  return $row;
}
function utenti_abilitazione_gestione_utenti($gas){
    global $db;
    $query = "SELECT * FROM maaking_users WHERE ((maaking_users.id_gas ='$gas') AND (maaking_users.user_permission & 2048));";    
    $res = $db->sql_query($query);
    $h = "<ul>";
    while ($row = mysql_fetch_array($res)){
    
        $h .= "<li>".$row["fullname"]."</li>";
        
    }
    $h .= "</ul>";
    
   return $h; 
}
function utenti_abilitazione_visione_ordini($gas){
    global $db;
    $query = "SELECT * FROM maaking_users WHERE ((maaking_users.id_gas ='$gas') AND (maaking_users.user_permission & 4096));";    
    $res = $db->sql_query($query);
    $h = "<ul>";
    while ($row = mysql_fetch_array($res)){
    
        $h .= "<li>".$row["fullname"]."</li>";
        
    }
    $h .= "</ul>";
    
   return $h; 
}
function utenti_abilitazione_gestione_gas($gas){
    global $db;
    $query = "SELECT * FROM maaking_users WHERE ((maaking_users.id_gas ='$gas') AND (maaking_users.user_permission & 4));";    
    $res = $db->sql_query($query);
    $h = "<ul>";
    while ($row = mysql_fetch_array($res)){
    
        $h .= "<li>".$row["fullname"]."</li>";
        
    }
    $h .= "</ul>";
    
   return $h; 
}
function utenti_abilitazione_gestione_bacheca($gas){
    global $db;
    $query = "SELECT * FROM maaking_users WHERE ((maaking_users.id_gas ='$gas') AND (maaking_users.user_permission & 1024));";    
    $res = $db->sql_query($query);
    $h = "<ul>";
    while ($row = mysql_fetch_array($res)){
    
        $h .= "<li>".$row["fullname"]."</li>";
        
    }
    $h .= "</ul>";
    
   return $h; 
}
function utenti_abilitazione_gestione_cassa($gas){
    global $db;
    $query = "SELECT * FROM maaking_users WHERE ((maaking_users.id_gas ='$gas') AND (maaking_users.user_permission & 8192));";    
    $res = $db->sql_query($query);
    $h = "<ul>";
    while ($row = mysql_fetch_array($res)){
    
        $h .= "<li>".$row["fullname"]."</li>";
        
    }
    $h .= "</ul>";
    
   return $h; 
}
function utenti_gestori_des($gas){
    global $db,$RG_addr;
    $query = "SELECT * FROM maaking_users WHERE ((maaking_users.id_gas ='$gas') AND (maaking_users.user_permission & 32768));";    
    $res = $db->sql_query($query);
    $h = "<ul>";
    while ($row = mysql_fetch_array($res)){
    
        $h .= "<li><a href=\"".$RG_addr["user_form_public"]."?id_utente=".mimmo_encode($row["userid"])."\">".$row["fullname"]."</a></li>";
        
    }
    $h .= "</ul>";
    
   return $h; 
}


function leggi_permessi_gas($id_gas){
 global $db; 
  $sql = "SELECT retegas_gas.gas_permission
        FROM retegas_gas 
        WHERE (((retegas_gas.id_gas)='$id_gas'));";
        $ret = $db->sql_query($sql);
        $row = $db->sql_fetchrow($ret);
  return $row[0];    
    
}