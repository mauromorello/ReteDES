<?php

include_once ("../rend.php");

if (is_logged_in($user)){
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  = $cookie_read[0];
    $res = $db->sql_query("SELECT * FROM retegas_messaggi WHERE tipo='CHT' ORDER BY timbro DESC LIMIT 100");
    $riga=0;
    while ($row = $db->sql_fetchrow($result)){
        $riga ++;
        if(is_integer($riga / 2)){
            $cl = " ui-state-highlight ";
        }else{
            $cl="";
        }
        $text = utf8_encode($row["messaggio"]);
        $lista .="<div class='msgln $cl' ><span class='small_link'>(".date("d/m g:i A",gas_mktime(conv_datetime_from_db($row["timbro"])))." da ".gas_nome(id_gas_user($row["id_user"])).")</span><br> <b>".fullname_from_id($row["id_user"])."</b>: ".$text."</div>";    
    
    }
  echo $lista;
}   

  
?>