<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_postare_messaggi)){
     pussa_via();
}

if($do=="save_coord"){
    
    
    $url = 'http://maps.google.com/maps/geo?q='.$lat.','.$lon.'&output=json&sensor=false';
    // make the HTTP request
    $data = @file_get_contents($url);
    // parse the json response
    $jsondata = json_decode($data,true);
    // if we get a placemark array and the status was good, get the addres
    if(is_array($jsondata )&& $jsondata ['Status']['code']==200)
    {
        $addr = $jsondata ['Placemark'][0]['address'];
        $ok="OK";
    }else{
        $addr="";
        $ok="FAIL";
    }
    
    $sql = "UPDATE retegas_bacheca SET 
            lat_bacheca=$lat,
            lng_bacheca=$lon,
            bacheca_address ='$addr'
            WHERE id_bacheca=$id_bacheca LIMIT 1;";
    $res = $db->sql_query($sql);
    if($ok=="OK"){        
        echo "SALVATO come :$addr";
    }else{
        echo "Indirizzo non riconosciuto, salvato come LAT : $lat, LON : $lon";
    }
    
    die(); 
}


if(!isset($id_bacheca)){
    go("sommario",_USER_ID,"Nessun messaggio scelto");
}






//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::bacheca;
//Assegno il titolo che compare nella barra delle info
$r->title = "Georeferenzia messaggio";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;





if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
if($msg)$r->messaggio=$msg;

$user_latlng = lat_lon_from_id(_USER_ID).",".lon_lat_from_id(_USER_ID);


$r->javascripts[] = <<<JJJ
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">

function showMap()
    {

            var map = new google.maps.Map(document.getElementById('map_canvas'), {
                zoom: 8,
                center: new google.maps.LatLng($user_latlng),               
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var myMarker = new google.maps.Marker({
                position: new google.maps.LatLng($user_latlng),
                draggable: true
            });

            google.maps.event.addListener(myMarker, 'dragend', function(evt){
                document.getElementById('current').innerHTML = '<p>Posizione Lat: ' + evt.latLng.lat().toFixed(3) + ' Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
                $.ajax({
                  url: '',
                  type: 'POST',
                  data :{do:'save_coord',
                         id_bacheca:'$id_bacheca',
                         lat:evt.latLng.lat(),
                         lon:evt.latLng.lng()},
                  success: function(data) {
                    $('#current').html(data);
                  }
                });
            });

            google.maps.event.addListener(myMarker, 'dragstart', function(evt){
                document.getElementById('current').innerHTML = '<p>Cercando....</p>';
            });

            map.setCenter(myMarker.position);
            myMarker.setMap(map);
    };
    
    google.maps.event.addDomListener(window, 'load', showMap);

</script>
JJJ;

$a = bacheca_render_fullwidth_messaggio($id_bacheca);
$b = "<div style=\"width:100%;height:20em;\" id=\"map_canvas\"></div>
      <div id=\"current\"></div>"; 

//Contenuto
$h = "<div class=\"rg_widget rg_widget_helper\">
        <h3>Localizza questo messaggio</h3>".
        render_container_table_2($a,$b,40,60)."</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);   
