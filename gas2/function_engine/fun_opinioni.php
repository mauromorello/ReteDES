<?php
//OPINIONI FORNITORI
function check_option_opinione_ordine($id_ordine,$id_utente,$chiave_opinione){
    global $db;
    $qry = "SELECT * FROM retegas_opinioni 
            WHERE
            id_ordine = '$id_ordine'
            AND
            id_utente = '$id_utente'
            AND
            chiave = '$chiave_opinione';";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;    
}
function check_option_opinione_ditta($id_ditta,$id_utente,$chiave_opinione){
    global $db;
    $qry = "SELECT * FROM retegas_opinioni 
            WHERE
            id_ditta = '$id_ditta'
            AND
            id_utente = '$id_utente'
            AND
            chiave = '$chiave_opinione';";
   $res = $db->sql_query($qry);
   $row = $db->sql_numrows($res);
   
   return $row;    
}

function write_option_opinione_ordine($id_ordine,$id_utente,$chiave_opinione,$valore_opinione){
    global $db;
    
    $id_utente = CAST_TO_INT($id_utente);
    $id_ordine = CAST_TO_INT($id_ordine);
    $valore_opinione = CAST_TO_INT($valore_opinione,0,5);
    
    $esiste_opinione = check_option_opinione_ordine($id_ordine,$id_utente,$chiave_opinione);
    
    if($esiste_opinione==0){
            $id_ditta = ditta_id_from_listino(listino_ordine_from_id_ordine($id_ordine));
            $id_gas = id_gas_user($id_utente);
            $id_des = des_id_des_from_id_user($id_utente);
            $qry = "INSERT INTO  
                    `retegas_opinioni` (
                        `id_opinione` ,
                        `id_utente` ,
                        `id_ordine` ,
                        `chiave` ,
                        `timbro` ,
                        `valore_int` ,
                        `id_ditta`,
                        `id_gas`,
                        `id_des`
                        )
                    VALUES (
                             NULL ,  
                             '$id_utente',
                             '$id_ordine',  
                             '$chiave_opinione',  
                             CURRENT_TIMESTAMP,  
                             '$valore_opinione', 
                             '$id_ditta',
                             '$id_gas',
                             '$id_des'
                    );";    
    }else{
        $qry = "UPDATE  `retegas_opinioni` 
                SET  `valore_int` =  '$valore_opinione' 
                WHERE  
                id_utente = '$id_utente'
                AND
                chiave = '$chiave_opinione'
                AND
                id_ordine = '$id_ordine' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;     
}
function write_option_opinione_ditta($id_ditta,$id_utente,$chiave_opinione,$valore_opinione){
    global $db;
    
    $id_utente = CAST_TO_INT($id_utente);
    $id_ordine = CAST_TO_INT($id_ditta);
    $valore_opinione = CAST_TO_INT($valore_opinione,0,5);
    
    
    
    $esiste_opinione = check_option_opinione_ditta($id_ditta,$id_utente,$chiave_opinione);
    
    if($esiste_opinione==0){
            $id_gas = id_gas_user($id_utente);
            $id_des = des_id_des_from_id_user($id_utente);
            $qry = "INSERT INTO  
                    `retegas_opinioni` (
                        `id_opinione` ,
                        `id_utente` ,
                        `id_ordine` ,
                        `chiave` ,
                        `timbro` ,
                        `valore_int` ,
                        `id_ditta`,
                        `id_gas`,
                        `id_des`
                        )
                    VALUES (
                             NULL ,  
                             '$id_utente',
                             '0',  
                             '$chiave_opinione',  
                             CURRENT_TIMESTAMP,  
                             '$valore_opinione', 
                             '$id_ditta',
                             '$id_gas',
                             '$id_des'
                    );";    
    }else{
        $qry = "UPDATE  `retegas_options` 
                SET  `valore_int` =  '$valore_opinione' 
                WHERE  
                id_user = '$id_utente'
                AND
                chiave = '$chiave_opinione'
                AND
                id_ditta = '$id_ditta' 
                LIMIT 1 ;";
        
    }
    
    
   $res = $db->sql_query($qry);
   $rows = $db->sql_affectedrows($res);
   
   return $rows;     
}

function delete_option_opinione_ordine($id_ordine,$id_utente,$chiave_opinione){
    global $db;
    $qry = "DELETE FROM retegas_opinioni 
            WHERE
            id_utente = '$id_utente'
            AND
            chiave = '$chiave_opinione'
            AND
            id_ordine = '$id_ordine'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return ;
}
function delete_option_opinione_ditta($id_ditta,$id_utente,$chiave_opinione){
    global $db;
    $qry = "DELETE FROM retegas_opinioni 
            WHERE
            id_utente = '$id_utente'
            AND
            chiave = '$chiave_opinione'
            AND
            id_ditta = '$id_ditta'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return ;
}

function read_option_opinione_ordine($id_ordine,$id_utente,$chiave_opinione){
    global $db;
    $qry = "SELECT * FROM retegas_opinioni 
            WHERE
            id_utente = '$id_utente'
            AND
            chiave = '$chiave_opinione'
            AND
            id_ordine = '$id_ordine'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_INT($row["valore_int"]);
}
function read_option_opinione_ditta($id_ditta,$id_utente,$chiave_opinione){
    global $db;
    $qry = "SELECT * FROM retegas_opinioni 
            WHERE
            id_utente = '$id_utente'
            AND
            chiave = '$chiave_opinione'
            AND
            id_ditta = '$id_ditta'
            LIMIT 1;";
   $res = $db->sql_query($qry);
   $row = $db->sql_fetchrow($res);
   
   return CAST_TO_INT($row["valore_int"]);
}

//MEDIE
function media_opinione_ordine($id_ordine){
    global $db;
    
    $sql = "SELECT avg(valore_int) as media_opinioni FROM retegas_opinioni WHERE id_ordine='$id_ordine' AND chiave LIKE '".opinioni::tutte."' GROUP BY id_ordine;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return round($row["media_opinioni"],1);
    
}
function media_opinione_ditta($id_ditta){
    global $db;
    
    $sql = "SELECT avg(valore_int) as media_opinioni FROM retegas_opinioni WHERE id_ditta='$id_ditta' AND chiave LIKE '".opinioni::tutte."' GROUP BY id_ditta;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return round($row["media_opinioni"],1);
    
}
function media_opinione_singola_ditta($id_ditta,$chiave){
    global $db;
    
    $sql = "SELECT avg(valore_int) as media_opinioni FROM retegas_opinioni WHERE id_ditta='$id_ditta' AND chiave = '$chiave' GROUP BY id_ditta;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return round($row["media_opinioni"],1);
    
}

function conteggio_opinione_ordine($id_ordine){
    global $db;
    
    $sql = "SELECT count(valore_int) as conteggio_opinioni FROM retegas_opinioni WHERE id_ordine='$id_ordine' AND chiave LIKE '".opinioni::tutte."' GROUP BY id_ordine;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return $row["conteggio_opinioni"];
    
}
function conteggio_opinione_ditta($id_ditta){
    global $db;
    
    $sql = "SELECT count(valore_int) as conteggio_opinioni FROM retegas_opinioni WHERE id_ditta='$id_ditta' AND chiave LIKE '".opinioni::tutte."' GROUP BY id_ditta;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return $row["conteggio_opinioni"];
    
}
function conteggio_opinione_singola_ditta($id_ditta,$chiave){
    global $db;
    
    $sql = "SELECT count(valore_int) as conteggio_opinioni FROM retegas_opinioni WHERE id_ditta='$id_ditta' AND chiave = '$chiave' GROUP BY id_ditta;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return $row["conteggio_opinioni"];
    
}


