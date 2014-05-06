<?php   $_FUNCTION_LOADER=array("mobile",
                                "ordini",
                                "ordini_valori",
                                "gas",
                                "listini",
                                "ditte",
                                "tipologie",
                                "geocoding");

include_once ("../rend.php");



//Controllo su login
if(!_USER_LOGGED_IN){   
    go("sommario_mobile");       
}  
$id_articolo = CAST_TO_INT($id_articolo);

if($id_articolo>0){
    echo "<html><body>
        <div data-role=\"page\" id=\"dialogPage\">".
          db_val_q("id_articoli",$id_articolo,"articoli_note","retegas_articoli").
         "
         </div>
         </body>
         </html>";
}