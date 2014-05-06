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
     go("sommario",_USER_ID,"Non hai i permessi necessari o questa pagina non è disponibile per il tuo DES");
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Geo Soldi a ditte";


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
    $days = CAST_TO_INT($days,1,1000);
}else{
    $days = 365;
}

if (!empty($multiplier)){
    $multiplier = CAST_TO_FLOAT($multiplier,0.0001,100);
}else{
    $multiplier = 1;
}

$sql_ditte ="SELECT
                Sum(retegas_dettaglio_ordini.qta_arr * retegas_dettaglio_ordini.prz_dett_arr) AS SQO,
                retegas_ditte.descrizione_ditte,
                retegas_ditte.id_ditte,
                retegas_ditte.ditte_gc_lat,
                retegas_ditte.ditte_gc_lng
                FROM
                retegas_listini
                Inner Join retegas_ordini ON retegas_ordini.id_listini = retegas_listini.id_listini
                Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
                Inner Join retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte
                
                WHERE
                retegas_dettaglio_ordini.data_inserimento > DATE_SUB(CURDATE(), INTERVAL $days DAY)
                AND
                retegas_ditte.ditte_gc_lat <>  '0'
                GROUP BY
                retegas_listini.id_ditte";



$res_o = $db->sql_query($sql_ditte);

while ($row = $db->sql_fetchrow($res_o)){                

$g_data .= gmap3_addmarker($row["ditte_gc_lat"],$row["ditte_gc_lng"],"<strong>".sanitize($row["descrizione_ditte"])."</strong><br><br>".round($row["SQO"],0)." Euri").",\n
         ".gmap3_addcircle($row["ditte_gc_lat"],$row["ditte_gc_lng"],$row["SQO"] * $multiplier ,$colo[$row["id_ditte"]]).",\n\n";
 
}                

$g_data = rtrim($g_data,",\n\n");
                
$r->javascripts[] = ' <script type="text/javascript">
                    
                    var roadAtlasStyles2 =  [
  {
    "featureType": "road",
    "stylers": [
      { "visibility": "off" }
    ]
  },{
    "featureType": "administrative.province",
    "stylers": [
      { "visibility": "off" }
    ]
  },{
    "featureType": "administrative.locality",
    "elementType": "labels",
    "stylers": [
      { "visibility": "on" }
    ]
  },{
    "featureType": "poi",
    "stylers": [
      { "visibility": "off" }
    ]
  },{
    "featureType": "landscape",
    "stylers": [
      { "visibility": "simplified" },
      { "saturation": -69 },
      { "lightness": 50 }
    ]
  }
];

                    $(function(){ // or $(document).ready(function(){ 
                        $("#map_canvas").gmap3(
                            
                            '.gmap3_init("44.7, 10.400" ,7).',
                            '.$g_data.',
                            { action: "setStyledMap",
                                        id: "style1",
                                        style : roadAtlasStyles2
                            }      
                            );                         
                    });
                    </script>';

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "  <div class=\"rg_widget rg_widget_helper\">
        <h3>Spesa verso le ditte</h3>
        <p>
        <form method=\"POST\" action=\"\">
        Numero di giorni passati da considerare :
        <input type=\"text\" name=\"days\" size=\"3\" value=\"$days\">
        Dimensione (1=normale)
        <input type=\"text\" name=\"multiplier\" size=\"5\" value=\"$multiplier\">
        <input type=\"submit\" value=\"aggiorna\">
        </form>
        </p>
        <div id=\"map_canvas\" style=\"width: 100%; height: 40em\"></div>
        </div>";;

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>