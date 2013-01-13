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


$res = $db->sql_query("SELECT * FROM retegas_amici WHERE (((retegas_amici.id_referente)='"._USER_ID."') AND status='1') ORDER BY id_amici ASC;");
$totale_amici = $db->sql_numrows($res);

//PRIMA RIGA
$A1 = ($id_ordine);
$A2 = (_USER_ID);
$A3 = ($totale_amici);
$totale_colonne = $totale_amici + 4; 

$h = $_fd.$A1.$_fd.$_fs.$_fd.$A2.$_fd.$_fs.$_fd.$A3.$_fd.$_fs.$_fd."0".$_fd;

$amici = array();
while ($row=$db->sql_fetchrow($res)){
   $h .= $_fs.$_fd.($row["id_amici"]).$_fd;   
}



// Id ordine,  Id user,  Totale amici, 0, amico_1, amico_n ,0



$h .= $_fs.$_fd."0".$_fd.$_eol;

//SECONDA RIGA
// INTESTAZIONI

// 0 = Cod art GAs, 1 = Articolo, 2 = Descrizione, 3 = mestesso, 4... n amici, n+1 = totale riga 

$h .=$_fd."CODICE".$_fd.$_fs.$_fd."ARTICOLO".$_fd.$_fs.$_fd."DESCRIZIONE".$_fd.$_fs.$_fd."ME STESSO".$_fd;
$res = $db->sql_query("SELECT * FROM retegas_amici WHERE (((retegas_amici.id_referente)='"._USER_ID."') AND status='1') ORDER BY id_amici ASC;");

while ($row=$db->sql_fetchrow($res)){
   $h .= $_fs.$_fd.$row["nome"].$_fd;   
}
$h .=$_fs.$_fd."TOTALE RIGA".$_fd.$_eol;

//LISTINO
$id_listino = id_listino_from_id_ordine($id_ordine);
$res = $db->sql_query("SELECT * FROM retegas_articoli WHERE id_listini='".$id_listino."' AND articoli_unico <> '1';");
//$h .= "Query = SELECT * FROM retegas_articoli WHERE id_listini='".id_listino."';";
while ($row=mysql_fetch_array($res)){
   $h .= $_fd.$row["id_articoli"].$_fd.$_fs.$_fd.$row["codice"].$_fd.$_fs.$_fd.sanitize($row["descrizione_articoli"]).$_fd.$_fs.$_fd.$_fz.$_fd.$_fs;
   for($i=0;$i<($totale_amici);$i++){
       $h .=$_fd.$_fz.$_fd.$_fs;
   }
   $h.=$_fd.$_fz.$_fd.$_eol;   
}




  
  //A questo punto creo il file CSV prima associano l'hader e poi stampando il tutto
  header("Content-Type: application/text");
  header("Content-Disposition: attachment; filename=amici_ordine_".$id_ordine.".csv");
  print $h;


   
?>