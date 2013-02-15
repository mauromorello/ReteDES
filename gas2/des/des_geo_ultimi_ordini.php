<?php


  
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    


//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     go("sommario",_USER_ID,"Non hai i permessi necessari (Rilasciati dal tuo DES) per vedere questa pagina");
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Geo ultimi n ordini";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = des_menu_completo(_USER_ID);

$r->javascripts_header[] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?key='.GOOGLE_KEY.'&sensor=false"></script>';
$r->javascripts_header[] = '<script type="text/javascript" src="'.$RG_addr["js_gmap3"].'"></script>';


//PREPARAZIONE DEI DATI

//COLORI GAS
for($s=0;$s<100;$s++){
        $colo[$s]=random_color();          
}

if (!empty($days)){
    $days = CAST_TO_INT($days,1,10);
}else{
    $days = 1;
}

$sql_ordini = " SELECT id_utenti,
                maaking_users.user_gc_lat,
                maaking_users.user_gc_lng,
                maaking_users.id_gas,   
                sum(qta_ord) as SQO
                FROM retegas_dettaglio_ordini 
                INNER JOIN maaking_users ON id_utenti = maaking_users.userid
                WHERE data_inserimento > DATE_SUB(CURDATE(), INTERVAL $days DAY)
                AND maaking_users.user_gc_lat >0
                GROUP BY id_utenti
                ORDER BY id_dettaglio_ordini DESC";




$res_o = $db->sql_query($sql_ordini);

while ($row = $db->sql_fetchrow($res_o)){                

$g_data .= gmap3_addmarker($row["user_gc_lat"],$row["user_gc_lng"],"Utente <strong>".gas_nome($row["id_gas"])."</strong><br><br>".round($row["SQO"],0)." ARTICOLI").",\n
         ".gmap3_addcircle($row["user_gc_lat"],$row["user_gc_lng"],$row["SQO"]*250,$colo[$row["id_gas"]]).",\n\n";
 
}                

$g_data = rtrim($g_data,",\n\n");
                
$r->javascripts[] = ' <script type="text/javascript">
                    $(function(){ // or $(document).ready(function(){ 
                        $("#map_canvas").gmap3(
                            
                            '.gmap3_init(geo_des_center(_USER_ID_DES),10).',
                            '.$g_data.'      
                        );                         
                    });
                    </script>';

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "  <div class=\"rg_widget rg_widget_helper\">
        <h3>Quantità di articoli ordinati raggruppati per utente</h3>
        <p><form method=\"POST\" action=\"\">Numero di giorni passati da considerare :<input type=\"text\" name=\"days\" size=\"3\" value=\"$days\"><input type=\"submit\" value=\"aggiorna\"></form></p>
        <div id=\"map_canvas\" style=\"width: 100%; height: 42em\"></div>
        </div>";;

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>