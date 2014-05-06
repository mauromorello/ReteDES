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
function articolo_suo_udm($id_articolo){
$qry="SELECT CONCAT(u_misura, ' ', misura) as udm FROM retegas_articoli WHERE retegas_articoli.id_articoli='$id_articolo' LIMIT 1;";
$ret = mysql_query($qry);
$row = mysql_fetch_assoc($ret);

return $row["udm"];

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
function qta_arr_ordine_articolo($id_ordine,$id_articolo){
    global $db;
    $sql = "SELECT
                Sum(retegas_dettaglio_ordini.qta_arr)
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

function qta_ord_ordine_articolo_user($id_ordine,$id_articolo,$id_utente){
    global $db;
    $sql = "SELECT
                Sum(retegas_dettaglio_ordini.qta_ord)
                FROM
                retegas_dettaglio_ordini
                WHERE
                retegas_dettaglio_ordini.id_articoli =  '$id_articolo'
                AND
                id_ordine = '$id_ordine'
                AND id_utenti ='$id_utente';";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return $row[0];
}
function qta_arr_ordine_articolo_user($id_ordine,$id_articolo,$id_utente){
    global $db;
    $sql = "SELECT
                Sum(retegas_dettaglio_ordini.qta_arr)
                FROM
                retegas_dettaglio_ordini
                WHERE
                retegas_dettaglio_ordini.id_articoli =  '$id_articolo'
                AND
                id_ordine = '$id_ordine'
                AND id_utenti ='$id_utente';";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return $row[0];
}
function qta_ord_ordine_user($id_ordine,$id_utente){
    global $db;
    $sql = "SELECT
                Sum(retegas_dettaglio_ordini.qta_ord)
                FROM
                retegas_dettaglio_ordini
                WHERE
                id_ordine = '$id_ordine'
                AND id_utenti ='$id_utente';";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    return $row[0];
}


function articoli_crea_tags($id_ordine){

global $db,$RG_addr;


$sql = "select * from retegas_articoli where id_listini='".id_listino_from_id_ordine($id_ordine)."';";
$sql_t = "select * from retegas_tags;";
$res = $db->sql_query($sql);
$res_t = $db->sql_query($sql_t);

while ($row = $db->sql_fetchrow($res)){

    $descrizione = $row["descrizione_articoli"];
    $lemmi = preg_split('/\s+/', trim($descrizione));
    //echo "<hr>";
    foreach ($lemmi as $lemma) {
        //echo $lemma;
        //echo "<br>";
        //Se ? almeno di 4 lettere
        if(strlen($lemma)>3){

        $db->sql_rowseek(0,$res_t);

        while ($row_t = $db->sql_fetchrow($res_t)){

            if (levenshtein($lemma,$row_t["tag"])<2){
                //echo "Trovato : $lemma -> ".$row_t["tag"]."<br>";
                $tags[$row_t["id_tag"]]++;
            }

        }

        }
    }


}
$a=0;
foreach ($tags as $tag) {
    $a++;
    //echo "$a tag : $tag<br>";

}



write_option_tags_ordini($id_ordine,json_encode($tags));

return;

}

function articoli_disegna_tags($id_ordine){
    if(!check_option_tags_ordini($id_ordine)){
        articoli_crea_tags($id_ordine);
    }

    $tags = json_decode(read_option_tags_ordini($id_ordine),true);
    //var_dump($tags);



    $i = array();
    foreach ($tags as $key => $value) {
        //echo "key $key value $value<br>";
        if($key>0){

            switch($key){
            case 1: $c="Pasta"; break;
            case 2: $c="Condimenti"; break;
            case 3: $c="Delizie"; break;
            case 4: $c="Dolciumi"; break;
            case 5: $c="Frutta"; break;
            case 6: $c="Verdura"; break;
            case 7: $c="Frutti di bosco"; break;
            case 8: $c="Frutta secca"; break;
            case 9: $c="Legumi"; break;
            case 10: $c="Pesce"; break;
            case 11: $c="Bevande"; break;
            case 12: $c="Formaggio"; break;
            }



            //$i[]= '<a class="btn"><span class="glyphicon glyphicon-star" data-id_categoria="'.$key.'"></span><span> : '.$value.'</span></a>';
            $i[]= '<a class="btn btn-sm"><small>'.$c.'</small><br><span>'.$value.'</span></a>';

        }
    }

    $h='<div class="btn-group  btn-group-justified">
          '.$i[0].'
          '.$i[1].'
          '.$i[2].'
       </div>';
    $h.='<div class="btn-group  btn-group-justified">
          '.$i[3].'
          '.$i[4].'
          '.$i[5].'
       </div>';
    $h.='<div class="btn-group  btn-group-justified">
          '.$i[6].'
          '.$i[7].'
          '.$i[8].'
       </div>';





    return $h;
}


function articoli_crea_tags_2($id_ordine){

global $db,$RG_addr;


$sql = "select * from retegas_articoli where id_listini='".id_listino_from_id_ordine($id_ordine)."';";
$sql_t = "select * from retegas_tags;";
$res = $db->sql_query($sql);
$res_t = $db->sql_query($sql_t);

$tags=array();
while ($row = $db->sql_fetchrow($res)){

    $descrizione = $row["descrizione_articoli"];
    $lemmi = preg_split('/\s+/', trim($descrizione));
    //echo "<hr>";
    foreach ($lemmi as $lemma) {
        //echo $lemma;
        //echo "<br>";
        //Se ? almeno di 4 lettere
        if(strlen($lemma)>3){

            if(!in_array($lemma,$tags)){
                $tags[$lemma]=1;
            }else{
                $tags[$lemma]++;
            }

        }
    }


}
$a=0;

var_dump($tags);

//write_option_tags_ordini($id_ordine,json_encode($tags));

return;

}
