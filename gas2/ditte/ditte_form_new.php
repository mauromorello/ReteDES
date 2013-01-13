<?php


   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("ditte_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!isset($id_ditta)){
     pussa_via();
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Scheda Ditta";



$r->javascripts[] =  java_list_filter();
    
$ditta_gc_lat = db_val_q("id_ditte",$id_ditta,"ditte_gc_lat","retegas_ditte");
$ditta_gc_lng = db_val_q("id_ditte",$id_ditta,"ditte_gc_lng","retegas_ditte");
$gas_gc_lat = db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lat","retegas_gas");
$gas_gc_lng = db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lng","retegas_gas");
      
$r->javascripts[] = java_head_rateit();      
$r->javascripts[] = '
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">

    var location1 = new google.maps.LatLng('.$ditta_gc_lat.', '.$ditta_gc_lng.');
    var location2 = new google.maps.LatLng('.$gas_gc_lat.', '.$gas_gc_lng.');


    var latlng;
    var map;
    var myPano;
        
    // creates and shows the map
    function showMap()
    {
        latlng = new google.maps.LatLng((location1.lat()+location2.lat())/2,(location1.lng()+location2.lng())/2);

        var mapOptions = 
        {
            zoom: 1,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        
        // show route between the points
        directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer(
        {
            suppressMarkers: true,
            suppressInfoWindows: true
        });
        directionsDisplay.setMap(map);
        var request = {
            origin:location1, 
            destination:location2,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) 
        {
            if (status == google.maps.DirectionsStatus.OK) 
            {
                directionsDisplay.setDirections(response);
                distance = " la distanza dal tuo GAS è di "+response.routes[0].legs[0].distance.text;
                distance += "<br/>con un tempo di percorrenza di  "+response.routes[0].legs[0].duration.text;
                document.getElementById("distance_road").innerHTML = distance;
            }
        });
     
        var marker1 = new google.maps.Marker({
            map: map, 
            position: location1,
            title: "Ditta"
        });
        var marker2 = new google.maps.Marker({
            map: map, 
            position: location2,
            title: "Tuo GAS"
        });
        
        // create the text to be shown in the infowindows
        var text1 = "'.ditta_nome($id_ditta).'";
                
        var text2 = "'.gas_nome(_USER_ID_GAS).'";
        
        // create info boxes for the two markers
        var infowindow1 = new google.maps.InfoWindow({
            content: text1
        });
        var infowindow2 = new google.maps.InfoWindow({
            content: text2
        });

        // add action events so the info windows will be shown when the marker is clicked
        google.maps.event.addListener(marker1, \'click\', function() {
            infowindow1.open(map,marker1);
        });
        google.maps.event.addListener(marker2, \'click\', function() {
            infowindow2.open(map,marker2);
        });
        
        }
    
    function toRad(deg) 
    {
        return deg * Math.PI/180;
    }
     
    //google.maps.event.addDomListener(window, \'load\', showMap);
    //google.maps.event.trigger(map, \'resize\');
    
</script>';
$r->javascripts[]= '
<script>
function initialize_streetview()
    {
        var ditta = new google.maps.LatLng('.geo_ditta_center($id_ditta).');
        var panoramaOptions = {
          position:ditta,
          pov: {
            heading: 165,
            pitch:0,
            zoom:1
          }
        };
        myPano = new google.maps.StreetViewPanorama(document.getElementById(\'streetview_canvas\'), panoramaOptions);
        myPano.setVisible(true);
      }
      
    
</script>'; 

$r->javascripts[] = '
    <script>
    $(document).ready(function() {
            $( "#tabs" ).tabs();
    $(\'#tabs\').bind(\'tabsshow\', function(event, ui) {
    if (ui.panel.id == "mappa_ditta") {
        initialize_streetview();
        showMap();
    }
});
    
    });
    
    </script>';    

    
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ditte_menu_completo($id_ditta);


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto



$h ='
<div class="rg_widget rg_widget_helper" style="overflow: auto;">
    <h3>'.ditta_nome($id_ditta).'</h3>
    <div id="tabs">
        <ul>
            <li><a href="#scheda_ditta" class="awesome green">Scheda</a></li>
            <li><a href="#listini_ditta" class="awesome yellow ">Listini</a></li>
            <li><a href="#mappa_ditta" class="awesome blue ">Mappa</a></li>
            <li><a href="ajax_certificazioni.php?id_ditta='.$id_ditta.'" class="awesome magenta">DES</a></li>
            <li><a href="ajax_relazioni.php?id_ditta='.$id_ditta.'" class="awesome magenta">GAS</a></li>
            <li><a href="ajax_valutazioni.php?id_ditta='.$id_ditta.'" class="awesome magenta">GESTORI</a></li>
            <li><a href="ajax_commenti.php?id_ditta='.$id_ditta.'" class="awesome magenta">PARTECIPANTI</a></li>
        </ul>
        <div id="scheda_ditta">'.ditte_render_form_2($id_ditta).'</div>
        <div id="listini_ditta">'.listini_render_table_3("listini_table",$id_ditta).'</div>
        <div id="mappa_ditta">
                <p style="font-size:1.2em">La ditta si trova a '.db_val_q("id_ditte",$id_ditta,"indirizzo","retegas_ditte").', <span id="distance_road"></span> </p>
                <table>                
                    <tr>
                        <td style="width:66%">
                            <div id="map_canvas" style="width:99%;height:400px;"></div>
                        </td>
                        <td style=""width:33%">
                            <div id="streetview_canvas" style="width:99%;height:400px;"></div>
                        </td>
                    </tr>
                </table>
        </div>
    </div>
</div>';

$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>