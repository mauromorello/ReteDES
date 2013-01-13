<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    
if (!isset($id_ordine)){
    pussa_via();
}

$_fs    =_USER_CSV_SEPARATOR;
$_eol   =_USER_CSV_EOL;
$_fd    =_USER_CSV_DELIMITER;
$_fz    =_USER_CSV_ZERO;


//PRIMA RIGA
$A1 = ($id_ordine);
$A2 = (_USER_ID);



$h = $_fd.$A1.$_fd.$_fs.$_fd.$A2.$_fd.$_fs.$_fd.$A3.$_fd.$_eol;

//SECONDA RIGA
// INTESTAZIONI

// 0 = Cod art GAs, 1 = Articolo, 2 = Descrizione, 3 = mestesso, 4... n amici, n+1 = totale riga 

$h .=$_fd."ID UT.".$_fd.$_fs.$_fd."NOME".$_fd.$_fs.$_fd."GAS".$_fd.$_fs.$_fd."COD ARTICOLO".$_fd.$_fs.$_fd."RIGA".$_fd.$_fs.$_fd."DESCRIZIONE".$_fd.$_fs.$_fd."CHECKSUM".$_fd.$_fs.$_fd."Q_ORD".$_fd.$_fs.$_fd."Q_ARR".$_fd.$_eol;

//QUERY ORDINE
$res = $db->sql_query("SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine';");
//$h .= "Query = SELECT * FROM retegas_articoli WHERE id_listini='".id_listino."';";
while ($row=$db->sql_fetchrow($res)){
   
   $id_utente = $row["id_utenti"];
   $fullname = fullname_from_id($row["id_utenti"]);
   $nomegas = gas_nome(id_gas_user($row["id_utenti"]));
   $codice_articolo = articolo_suo_codice($row["id_articoli"]);
   $riga = $row["id_dettaglio_ordini"];
   $desc_art = articolo_sua_descrizione($row["id_articoli"]);
   $qta_ord = $row["qta_ord"];
   $qta_arr = $row["qta_arr"];
   
   $checksum = crc32(   $id_utente.
                        $fullname.
                        $nomegas.
                        $codice_articolo.
                        $riga.
                        $desc_art.
                        $qta_ord);
    
    
   $h .=    $_fd.   $id_utente.         $_fd.      $_fs.
            $_fd.   $fullname.          $_fd.      $_fs.
            $_fd.   $nomegas.           $_fd.      $_fs.
            $_fd.   $codice_articolo.   $_fd.      $_fs.
            $_fd.   $riga.              $_fd.      $_fs.
            $_fd.   $desc_art.          $_fd.      $_fs.
            $_fd.   $checksum.          $_fd.      $_fs.
            $_fd.   $qta_ord.           $_fd.      $_fs.
            $_fd.   $qta_arr.           $_fd.
            $_eol;

  }




  
  //A questo punto creo il file CSV prima associano l'hader e poi stampando il tutto
  header("Content-Type: application/text");
  header("Content-Disposition: attachment; filename=rettifica_qarr_ordine_".$id_ordine.".csv");
  print $h;