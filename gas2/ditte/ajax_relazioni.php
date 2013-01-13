<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     echo "NOT ALLOWED"; die();
}    

$id_ditta=CAST_TO_INT($id_ditta);

if($id_ditta==0){
    echo "NOT ALLOWED"; die();
}
                                                                
$sql = "SELECT * FROM retegas_bacheca WHERE id_ditta='$id_ditta' AND code_uno='".argomenti::relazione."' ORDER BY id_bacheca DESC; ";
$res = $db->sql_query($sql);

if($db->sql_numrows($res)>0){
$h .= "<h3></h3>";    
}else{
$h .= "<h3></h3>"; 
$h .= "<h4>Nessuna relazione per questa ditta</h4>";
}

 while ($row = $db->sql_fetchrow($res)){

 $h .= bacheca_render_fullwidth_messaggio($row["id_bacheca"]);
     
}

echo $h; 