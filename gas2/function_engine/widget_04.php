<?php
  
function rgw_bacheca($site,$gas){
global $db, $RG_addr;
    
    
// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Nome id del widget
$w_name = "rgw_4";


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';
 
// istanzio un nuovo oggetto widget
$w = new rg_widget();

// Imposto le proprietà del widget
$w->name = $w_name;
$w->title="Feedback fornitori";
$w->toggle_state ="hide";

$my_query="SELECT retegas_bacheca.*
                FROM retegas_bacheca
                ORDER BY
                timbro_bacheca DESC
                LIMIT 10;";
$result = $db->sql_query($my_query);
    
while ($row = $db->sql_fetchrow($result)){
    $h .= bacheca_render_fullwidth_messaggio($row["id_bacheca"]);
}
                




$w->content = $h;
$w->footer = "Scheda con feedback ai fornitori";       
$w->use_handler =false;
// Eseguo il rendering        
$h = $w->rgw_render();

// Distruggo il widget
unset($w);    
    
//Ritorno l'HTML    
return $h;


}